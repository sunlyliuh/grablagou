<?php
/**
 * 所有类的父类，都需要继承这个进行操作
 */
class CommonAction extends Action
{
    protected $_seoName;
    public function __construct() {
        // 获取访客的ip及时间
        $ip = $_SERVER['REMOTE_ADDR'];
        if(preg_match("/188\.143\.232\.*/i", $ip)){
            echo '禁止访问';exit;
        }
        $time = date('Y-m-d H:i:s');
        $visitModel = new VisitModel();
        $visitModel->ip = $ip;
        $visitModel->visiturl = $this->getActionName().'/'.ACTION_NAME;
        $visitModel->created = $time;
        if(in_array($this->getActionName(), array('Web','Weather'))){
            // 
        }else{
            $visitModel->add();
        }
           
        $this->_insertNapsBot(); // 插入蜘蛛访问记录
        
        // 获取controller和acton
        $url = $this->getActionName().'/'.ACTION_NAME;
        parent::__construct();
        $userId = session('user_id');
        $this->assign('userId', $userId);
        $this->assign('url',$url);
        
        $this->assignSeoName();
    }
    
    // 加密文章id
    public function encodeArticleId($id)
    {
        $newId = 100000 + $id;
        return $newId;
    }
    
    // 解密文章id
    public function decodeAritcleId($id)
    {
        $newId = $id-100000;
        return $newId;
    }
    
    // 插入各种蜘蛛的访问记录
    protected function _insertNapsBot()
    {
        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);                                   
        $spider = '';
        if(strpos($useragent, 'googlebot') !== false){                   
            $spider =  'Googlebot';           
        }                                

        if(strpos($useragent, 'slurp') !== false){                  
           $spider =  'Yahoobot';          
        }                     

        if(strpos($useragent, 'baiduspider') !== false){                  
            $spider =  'Baiduspider';          
        }                   

        if(strpos($useragent, 'sosospider') !== false){                  
            $spider = 'Soso';          
        }                   

        if(strpos($useragent, 'sogou spider') !== false){                  
            $spider =  'Sogou';          
        } 

        if(strpos($useragent, 'yodaobot') !== false){                  
            $spider =  'Yodao';         
        }      
        
        if(strpos($useragent, 'msnbot') !== false){                  
            $spider =  'Bing';         
        }    

        if(strpos($useragent,"msie 67.0")!==false){//我测试用的（我的浏览器是ie6.0）         
            $spider =  'MSIE 7.0';        
        }          
        
        $nowTime = date('Y-m-d H:i:s');
        $fromUrl= $_SERVER['HTTP_REFERER'];
        if(!empty($spider)) {
            $spiderModel = new SpiderModel();
            $spiderModel->add(array(
                'spider' => $spider,
                'source_url' => $fromUrl,
                'visit_time' => $nowTime,
            ));
        }
    }
    
    /**
     * 设置seo的名字
     * @param type $title
     */
    public function assignSeoName( $title='', $keyWrods='', $description='')
    {
        $seoName = '刘辉的博客';
        
        if($title){
            $seoName = $title.'_'.$seoName;
        }
        
        if(empty($keyWrods)) {
            $keyWrods = 'php mysql linux yii thinkphp';
        }
        if(empty($description)) {
            $description = 'php mysql linux phper';
        }

        $this->assign('title', $seoName);
        $this->assign('keywords', $keyWrods);
        $this->assign('description', $description);
    }
    
    /**
     * 获取seo的名字
     * @param type $url
     * @param type $id
     */
    public function getSeoName($url, $id)
    {
        // 根据不同的url返回不同的seo名称
    }
    
}
?>
