<?php
/**
 * 股票价格预警
 * @author liuhui
 */
class StockCommand extends CConsoleCommand {
    
    /**
     * /home/wwwroot/default/talk/grablagou/trunk/protected/yiic stock index
     */
    public function actionIndex()
    {
        $jobmodel = new StockWarning();
        
        $stockInfo = $jobmodel->findAll();
        $msg = "";
        foreach($stockInfo as $stock){
            $stockData = $jobmodel->getStockData($stock->code);
            if(!empty($stockData)){
                $price = floatval($stockData[1]);
                $minPrice = floatval($stock->min_price);
                $maxPrice = floatval($stock->max_price);
                // 更新价格
                $jobmodel->updateByPk($stock->id, array('current_price'=>$price));
                if($price <= $minPrice){
                    $msg .= $stockData[0]."--当前价：{$price},跌倒了设置的最低价:{$minPrice}\r\n";
                }
                if($price >= $maxPrice){
                    $msg .= $stockData[0]."--当前价：{$price},超过了设置的最高价{$maxPrice}\r\n";
                }
            }
        }
        
        $this->_sendMail(date("Y-m-d H:i").'-价格预警', $msg);
        
        echo date("Y-m-d H:i:s")."--index success\r\n";
    }

	public function actionTest()
	{

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

		// 更多报头
		$headers .= 'From: <webmaster@51zhaoliwu.com>' . "\r\n";

		$to = '546743861@qq.com';
		$subject = '春天的气息';
		$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
";
		$res = mail($to,$subject,$message,$headers);
		var_dump($res);
	}
    
    private function _sendMail($title,$content)
    {
        if($content) {
            $headers = 'From: name<252299567@qq.com>';  
            $res = mail("546743861@qq.com", $title, $content,$headers);
            if($res){
                echo "1-mail {$content} send success\r\n";
            }else{
                echo "1-mail send error\r\n";
            }
        }else{
            echo "1-no stock to send warnging\r\n";
        }
    }
    
    private  function mail($data)
    {
        try{
            $message =new YiiMailMessage();
            $message->setTo( array(
                "{$data['email']}"=>"{$data['tousername']}",
            ));
            $message->setFrom(Yii::app()->params['adminEmail']);//发送人的邮箱
//            $message->view ='sendtemplate';//邮件内容模板
            $message->setSubject('ceshi xia kankan');
            $message->setBody( array(
                'email'=> $data['email'],
                'tousername'=> $data['tousername'],
            ),'text/html','gbk');            
            $numsent =Yii::app()->mail->send($message);
        }catch(Exception $e){        }
        return $numsent;
    }
    
    public function actionSendemail(){
        $r = $this->mail(array(
                 'touid'=>'0',
                 'email'=>'546743861@qq.com',
                'tousername'=>'sunlyliu',
            ));
    }
    
    

}
