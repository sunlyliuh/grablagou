<?php
/**
 * 返利网站页面
 * @author liuhui
 * @since 2014.2.13
 */
class WebAction extends CommonAction{

    /**
     * 获取股票的详情数据
     * @接口 http://hq.sinajs.cn/list=s_sz600839
     * @频率 5分钟一次
     */
    public function stockdetail()
    {
        
        $stockM = new StockModel();
        $stockAll = $stockM->select();
        
        $stockDetailM =  new StockDetailModel();
        $weekarray=array("日","一","二","三","四","五","六");
        $todayWeek = $weekarray[date("w")];
        
        $nowTime = date("H:i:s");
        echo $nowTime;
        if(($nowTime>= "09:00:00" && $nowTime<="11:30:00") || ($nowTime>= "13:00:00" && $nowTime<="15:00:00")){
            echo '可以抓取了<br />';
        }else{
            echo '不在股市时间内<br />';exit;
        }
        
        // 周一到周五才开始采集
        if(in_array($todayWeek, array("一","二","三","四","五"))){
            foreach($stockAll as $stock){
                 $stockDetail = $this->_getStockData($stock['code']);
//                 print_r($stockDetail);
                 $count = count($stockDetail);
                 if($count == 7){ // 抓取成功
                     if($stockDetail[1] != 0.00){
                        $detailArr = array(
                            'code' => $stockDetail[6],
                            'current_price' => $stockDetail[1],
                            'today_change' => $stockDetail[2],
                            'today_change_per' => $stockDetail[3],
                            'volume' => $stockDetail[4],
                            'deal_price' => $stockDetail[5],
                        );
                        $res = $stockDetailM->add($detailArr);
                        if($res){
                            echo $stock['code'].'insert success<br />';
                        }else{
                            echo $stock['code'].'insert error<br />';
                        }
                        usleep(100);
                     }else{
                         echo $stock['code'].'停牌中<br />';
                     }
                 }else{ //记录错误日志
                     echo '抓取错误<br />';
                 }
            }
        }else{
            echo 'not in deal date<br />';
        }
    }
    
    /**
     * 获取每天股票最后交易的金额
     * @频率 每天3点半 周一到周五运行
     */
    public function everystock()
    {
        $weekarray=array("日","一","二","三","四","五","六");
        $todayWeek = $weekarray[date("w")];
        $nowTime = mktime('14','00','00'); 
        $today = date("Y-m-d");
        // 周一到周五才开始采集
        if(in_array($todayWeek, array("一","二","三","四","五"))){
            $stockDetailM =  new StockDetailModel();
            $stockStatisM = new StockStatisModel();
            $stockM = new StockModel();
            
            $stockAll = $stockM->select();
            foreach($stockAll as $stock){
                // 获取这个股票当天最后一次的日志
                $stockDetail = $stockDetailM->where("code='{$stock['code']}' and created >='{$nowTime}'")->order('id desc')->find();
                if(!empty($stockDetail)){
                    $statisArr = array(
                            'code' => $stock['code'],
                            'current_price' => $stockDetail['current_price'],
                            'today_change' => $stockDetail['today_change'],
                            'today_change_per' => $stockDetail['today_change_per'],
                            'volume' => $stockDetail['volume'],
                            'deal_price' => $stockDetail['deal_price'],
                            'date' => $today
                        );
                }else{
                    $statisArr = array(
                            'code' => $stock['code'],
                            'current_price' => '0.00',
                            'today_change' => '0.00',
                            'today_change_per' => '0.00',
                            'volume' => 0,
                            'deal_price' => 0,
                            'date' => date("Y-m-d")
                        );
                }
                // 查看当天记录是否存在
                $statisToday = $stockStatisM->where("code='{$stock['code']}' and date='{$today}'")->find();
                if(empty($statisToday)) {
                    $res = $stockStatisM->add($statisArr);
                    if($res){
                        echo $stock['code'].'汇总成功<br>';
                    }else{
                         echo $stock['code'].'汇总失败<br>';
                    }
                }else{
                    echo $stock['code'].'当天记录已经存在<br>';
                }
            }
        }else{
            echo 'not in deal date<br />';
        }
    }
    
    /**
     * 获取笑话内容
     */
    public function getxiaohua()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $url = 'http://www.2345.com/jzw/'.$page.'.htm';
        $content = $this->_curlGetData($url);
//        print_r($content);
        preg_match_all('/<span class="table_left">(.*)?<\/span>/iU', $content['data'],$match);
        preg_match_all("/MM_popupMsg\(\'(.*)?\'\)/iU",$content['data'],$answon);
//        print_r($match); print_r($answon);
        $len = count($match[1]);
        // 添加数据
        $quickBrainM = new QuickBrainModel();
        for($i=0;$i<$len;$i++)
        {
            $title = iconv('gbk', 'UTF-8', $match[1][$i]);
            $brainInfo = $quickBrainM->where(array('title'=>$title))->find();
                        
            if(empty($brainInfo)) {
                $data = array(
                    'title' => $title,
                    'answer' => iconv('gbk', 'UTF-8', $answon[1][$i]),
                    'is_send' =>0,
                    'created' => time(),
                );
                echo $title.'<br>';
                $quickBrainM->add($data);
            }
        }
    }
    
    
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
    
    private function _getStockData($code){
        // 60开头是是上证sh  00开头是深证的 sz  ,30开头的是深证sz
        $city = 'sh';
       if( preg_match('/^60*/', $code)){
            $city = 'sh';
        }else{
            $city = 'sz';
        }
        $url = "http://hq.sinajs.cn/list=s_{$city}{$code}";
        // var hq_str_s_sz000005="世纪星源,11.45,-0.17,-1.46,1413069,164583";
        //数据含义分别为：指数名称，当前指数，今日变化值，今日变化百分比，成交量（手），成交额（万元）；
        $content = file_get_contents($url);

        $arr = explode(',', $content); 
        $name=explode('"',$arr[0]);

        $arr[0] = $name[1];

        $totalPrice = explode('"', $arr[5]);
        $arr[5] = $totalPrice[0];
        $arr[6] = $code;
        return  $arr;
    }
}
