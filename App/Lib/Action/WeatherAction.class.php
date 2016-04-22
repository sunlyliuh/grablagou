<?php
/**
 * 发送天气预报到手机
 * @authror liuhui
 * @time 2014-03-27
 */
class WeatherAction extends CommonAction
{
    
    public function test()
    {
        $toUser = '252299567@qq.com';
        $title = '你好呀刘辉';
        $content = 'hello,world。';
        $res = $this->_sendEmail($toUser, $title, $content);
        var_dump($res);
    }
    
    /**
     * 预警
     * @每2分钟一次，每天只发送一次
     */
    public function warnprice()
    {
        $stockModel = new StockModel();
        $stockWarningModel = new StockWarningModel();
        $stockArr = $stockWarningModel->select();
        foreach($stockArr as $stock){
            $code = $stock['code'];
            $stockInfo = $stockModel->getStockData($code);
            $msg = '';
            // 将大盘信息也一起打印出来
            if($stockInfo[1] <= $stock['price_min']){
                $msg = "当前price已经低于{$stock['price_min']},当天跌了{$stockInfo[3]}%,请马上进行处理。";
            }else if($stockInfo[1] >= $stock['price_max']){
                $msg = "当前price已经高于{$stock['price_max']},当天涨了{$stockInfo[3]}%，可以卖了。";
            }
            
            if($msg){
                // 发送邮件
                $this->_sendWarning($code.'-预警', $msg);
            }
        }
        
        echo 'success';
    }
    
    /**
     * 天气的cron
     */
    public function index()
    {
        $fetionUserModel = new FetionUserModel();
        $fetionUser = $fetionUserModel->limit(50)->select();
//        $quickBrain = $this->_getQuickBrain();
        foreach($fetionUser as $user)
        {
//            $msg = $this->_getMsg($user['citycode']);
            $msg = $this->getBaiduApiWeather($user['cityname']);
            if($msg){
                $res = $this->_sendWeather($user['email'], $msg);
                echo $res.'<br />';
            }
            sleep(200);
            if($user['send_news'] == 1){
                $this->_sendNews($user['email']);
            }
//            if($user['send_jizhuanwan']){
//                if($quickBrain['content'])
//                    $this->_sendQuickBrain($user['email'], $quickBrain['content']);
//            }
        }
        
//        $this->_setQuickBrainSend($quickBrain['id']);
        echo 'success';
    }
    
    /**
     * 新闻的job
     */
    public function news()
    {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        $newsUrl = 'http://news.163.com';
        $content = $this->_curlGetData($newsUrl);
        $news = '【今日头条新闻】';
        if($content['succ'] == 'T'){
//            preg_match_all('/<h2 class="bigsize">(.*)?<\/h2>/iU', $content['data'],$match);
            preg_match_all('/<h3><a class="ac01" .*>(.*)?<\/a><\/h3>/iU', $content['data'], $match);
//            print_r($match[1]);
            if(!empty($match[1])){
                foreach($match[1] as $key=>$new)
                {
                    $newKey = $key+1;
                    $new = iconv('gbk', 'UTF-8', $new);
                    $news .= $newKey.'.'.$new.'&nbsp;&nbsp;';
                }
            }else{
                $news .= '获取新闻失败';
            }
            
        }else{
            $news .= '获取新闻失败';
        }
        $newsContent = strip_tags($news);
        $newsModel = new NewsModel();
        $newsModel->add(array('content'=>$newsContent, 'today'=>date('Y-m-d')));
        echo 'success!';
    }
    
    public function _getMsg($cityCode)
    {
        // http://www.weather.com.cn/data/cityinfo/101240408.html
        // 数据格式 {"weatherinfo":{"city":"南城","cityid":"101240408","temp1":"27℃","temp2":"18℃","weather":"中雨","img1":"d8.gif","img2":"n8.gif","ptime":"11:00"}}
        // http://www.weather.com.cn/data/sk/101240408.html
        // 数据格式 {"weatherinfo":{"city":"南城","cityid":"101240408","temp":"26","WD":"南风","WS":"3级","SD":"62%","WSE":"3","time":"13:45","isRadar":"0","Radar":""}}
//        $cityCode =  include_once './Public/data/citycode.php';
        $msg = '';
        $today = date('Y-m-d');
        $cityWeatherModel = new CityWeatherModel();
        $weatherArr = array(
            'today'=>$today,
            'citycode' => $cityCode,
        );
        $todayWeather = $cityWeatherModel->where($weatherArr)->find();
        if(empty($todayWeather)) {
            $infoUrl = "http://www.weather.com.cn/data/cityinfo/".$cityCode.".html";
            $weather1 = $this->_curlGetData($infoUrl);
            if($weather1['succ'] == 'T'){
                $arr = json_decode($weather1['data'], true);
                // 比较温度
                $maxTemp = $minTemp = 0;
                if($arr['weatherinfo']['temp1'] > $arr['weatherinfo']['temp2']){
                    $maxTemp = $arr['weatherinfo']['temp1'];
                    $minTemp = $arr['weatherinfo']['temp2'];
                }else{
                    $maxTemp = $arr['weatherinfo']['temp2'];
                    $minTemp = $arr['weatherinfo']['temp1'];
                }
                $msg = '今天【'.$arr['weatherinfo']['city'].'】天气:'.$arr['weatherinfo']['weather'].',最高温度'.$maxTemp.',最低温度'.$minTemp;
                $dataUrl = "http://www.weather.com.cn/data/sk/".$cityCode.".html";
                $weather2 = $this->_curlGetData($dataUrl);
                if($weather2['succ'] == 'T'){
                    $fengli = json_decode($weather2['data'], true);
                    $msg .= '，有'.$fengli['weatherinfo']['WS'].$fengli['weatherinfo']['WD'];
                }
                
            }else{
                $msg .= '获取天气失败';
            }
            $msg .= ' 发送人:刘辉';
            $cityWeatherModel->add(array('citycode'=>$cityCode,'today'=>$today,'msg'=>$msg));
        }else{
            $msg = $todayWeather['msg'];
        }

        return $msg;
    }
    
    public function getSinaWeather($city)
    {
        $msg = '';
        // 接口 http://php.weather.sina.com.cn/xml.php?city=%B1%B1%BE%A9&password=DJOYnieT8234jlsK&day=0
        //http://m.weather.com.cn/data/101110101.html
        $city = iconv("UTF-8", "GB2312", $city);
        $city = urlencode($city); 
        $infoUrl = "http://php.weather.sina.com.cn/xml.php?city=".$city."&password=DJOYnieT8234jlsK&day=1";

        $weather1 = file_get_contents($infoUrl);
        if($weather1){
            
            $parseXml = simplexml_load_string($weather1);
            $json = json_encode($parseXml);
            $array = json_decode($json,TRUE);
            print_r($array);
            $msg .= "【{$array['Weather']['city']}】天气:{$array['Weather']['status1']},温度：{$array['Weather']['temperature2']}-{$array['Weather']['temperature1']},风力：{$array['Weather']['direction1']}";
        }else{
            $msg .= '获取天气失败';
        }
        return $msg;         
    }
    
    public function getBaiduApiWeather($city)
    {
        $msg = '';
//        $city = iconv("UTF-8", "GB2312", $city);
        $url = "http://apistore.baidu.com/microservice/cityinfo?cityname=$city";
        $weather = file_get_contents($url);
        $weather = json_decode($weather, true); 
        
        if($weather['retMsg'] == 'success') {
            $url2 = "http://apistore.baidu.com/microservice/weather?cityid={$weather['retData']['cityCode']}";
            $tianqi = file_get_contents($url2);
            $tianqiArr = json_decode($tianqi, true);
//            print_r($tianqiArr);
            if($tianqiArr['errMsg'] == 'success'){
                $msg .= "【{$city}】天气:{$tianqiArr['retData']['weather']},温度:{$tianqiArr['retData']['l_tmp']}-{$tianqiArr['retData']['h_tmp']},风力:{$tianqiArr['retData']['WD']},{$tianqiArr['retData']['WS']}";
                }else{
                $msg .= '获取天气失败';
            }
        }else{
            $msg .= '获取天气失败';
        }
        
        return $msg;
    }
    
    private function _iconvArr($arr){
        foreach ($arr as $key=>$val){
            $arr[$key] = iconv("UTF-8", "GB2312", $val);
        }
        
        return $arr;
    }
    
    /**
     * 发送天气
     * @param type $mobile
     * @param type $content
     */
    private function _sendWeather($email, $content)
    {
        // 判断当天是否已经发送过
        $todayTime = date('Y-m-d');
        $todayUnixTime = strtotime($todayTime);
        $fetionLogModel = new FetionLogModel();
        $whereArr = array(
            'mobile' => $email,
            'type' => '0',
            'add_time' => array('gt',$todayUnixTime),
        );
        $logInfo = $fetionLogModel->order('id desc')->where($whereArr)->find();
        
        $res = '';
        if(empty($logInfo)) { // 不存在，说明没有发送过，可以发送
//            $res = $this->_send($mobile, $content); // 接收人手机号、飞信内容
            $res = $this->_sendEmail($email, '今日天气'.date('m-d H:i'), $content);
            // 记录日志
            $fetionLogModel->add(array('mobile'=>$email,'msg'=>$content,'return_val'=>$res,'add_time'=>time()));
        }  else {
            $res = '已经发送过了，不需要发送';
        }
        
        return $res;
        
    }
    
    /**
     * 发送新闻
     * @param type $mobile
     * @param type $content
     */
    private function _sendNews($mobile)
    {
        $newsModel = new NewsModel();
        $todayTime = date('Y-m-d');
        $whereArr = array(
            'today' => $todayTime,
        );
        $newsInfo = $newsModel->where($whereArr)->find();
        if($newsInfo) {
            $content = $this->_filterContent($newsInfo['content']);
//            $res = $this->_send($mobile, $content);
            $res = $this->_sendEmail($mobile, '今日新闻', $content);
            $fetionLogModel = new FetionLogModel();
            $fetionLogModel->add(array('mobile'=>$mobile,'msg'=>$newsInfo['content'],'return_val'=>$res,'type'=>1, 'add_time'=>time()));
        }else{
            // 
        }
    }
    
    /**
     * 发送脑经急转弯
     * @param type $mobile
     * @param type $content
     * @return boolean
     */
    private function _sendQuickBrain($mobile, $content)
    {
//        $res = $this->_send($mobile, $content);
        $res = $this->_sendEmail($mobile, '脑筋急转弯', $content);
        $fetionLogModel = new FetionLogModel();
        $fetionLogModel->add(array('mobile'=>$mobile,'msg'=>$content,'return_val'=>$res,'type'=>2, 'add_time'=>time()));
        return true;
    }
    
    /**
     * 发送预警
     * @param type $title
     * @param type $content
     * @return boolean
     */
    private function _sendWarning($title,$content)
    {
        $mobile = '13581578309@139.com';
        $fetionLogModel = new FetionLogModel();
        // 判断当天是否已经发送了，如果已经发送就不再发送
        $date = date('Y-m-d');
        $startTime = strtotime($date.' 00:00:00');
        $endTime = strtotime($date.' 23:59:59');
        $logExist = $fetionLogModel->where("`type`='3' AND add_time>= '{$startTime}' and add_time <= '{$endTime}' and title='{$title}'")->find();
        
        if(empty($logExist)){
//            $res = $this->_sendEmail($mobile, $title, $content);
            $fetionLogModel->add(array('mobile'=>$mobile,'title'=>$title, 'msg'=>$content,'return_val'=>$res,'type'=>3, 'add_time'=>time()));
        }
        
        return true;
    }
    
    /**
     * 获取脑经急转弯
     */
    private function _getQuickBrain()
    {
        $quickBrainM = new QuickBrainModel();
        $quickBrainInfo = $quickBrainM->where(array('is_send'=>0))->find();
        $quickBrainInfo['content'] = '';
        if($quickBrainInfo){
            $content = '【脑筋急转弯】';
            $content .= $quickBrainInfo['title'].'&nbsp;';
            $content .= $quickBrainInfo['answer'];
            $quickBrainInfo['content'] = $content;
        }
        
        return $quickBrainInfo;
    }
    
    /**
     * 设置该条信息已读
     * @param type $id
     */
    private function _setQuickBrainSend($id)
    {
        $quickBrainM = new QuickBrainModel();
        if($id){
            $quickBrainM->where(array('id'=>$id))->save(array('is_send'=>1));
        }
        
        return true;
    }


    /**
     * 发送内容
     * @param type $mobile
     * @param type $content
     */
    private function _send($mobile, $content)
    {
        $fetionClass = new PHPFetion('13581578309', 'liuhui32821');// 手机号、飞信密码
        $res = $fetionClass->send($mobile, $content); // 接收人手机号、飞信内容
      
        return $res;
    }
    
    /**
     * 发送email
     * @param type $toUser
     * @param type $title
     * @param type $content
     * @return type
     */
    private function _sendEmail($toUser, $title, $content)
    {
        $res = sendEmail($toUser, $title, $content);
        return $res;
    }
    
    /**
     * curl获取数据
     * @param type $url
     */
    private function _curlGetData($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   //返回数据，而不是直接输出
        curl_setopt($ch, CURLOPT_HEADER, 0);   // 设置是否显示header信息 0是不显示，1是显示  默认为0
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $output = curl_exec($ch);    //发送HTTP请求
        
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);
        $arr = array(
            'succ' => 'F',
            'data' => '',
        );
        if($http_status != 200){
            $arr['data'] = '获取数据失败';
        }else{
            $arr['succ'] = 'T';
            $arr['data'] = $output;
        }
        return $arr;
    }
    
    private function _filterContent($content)
    {
        $arr = array('习近平','李克强');
        $reArr = array('大大','克克');
        $str = str_replace($arr, $reArr, $content);
        
        return $str;
    }
        
}
