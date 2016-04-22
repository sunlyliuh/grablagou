<?php
/**
 * stock model
 */
class StockModel extends Model
{
    protected $tableName        =   'stock';
    
    protected $_validate         =         array(
        array('name','require','股票名称必须！'), //默认情况下用正则进行验证
        array('code','require','股票代码必填！'),
        array('city','require','股票城市必填！'),
        array('code','','股票代码已经存在！',0,'unique',1),
    );
    
    /**
     * 获取详细信息
     * @param type $code
     * @return type
     */
    public function getStockData($code){
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

