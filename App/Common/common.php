<?php
function show_db_errorxx(){
	exit('系统访问量大，请稍等添加数据');
}

// 将html都替换为空
function replaceHtmltoNull($content)
{
    
}

/**
 * 搜索出的字体标红
 * @param type $string 原始字符串
 * @param type $pattern 搜索内容
 * @param type $replace 替换内容
 */
function searchWrodsDisplay($string, $content, $replace="")
{
    $patterns[0] = "/{$content}/";
    $replacements[0] = "<font color='red'><strong>{$content}</strong></font>";

    return  preg_replace($patterns, $replacements, $string);
}

/**
 * 发送邮件
 * @param type $title
 * @param type $content
 */
function sendEmail($toUser,$title,$content)
{
    require_once ('email.class.php');
    $smtpserver = "smtp.163.com";//SMTP服务器 
    $smtpserverport =25;//SMTP服务器端口 
    $smtpusermail = "liuhuibg@163.com";//SMTP服务器的用户邮箱 
    $smtpemailto = $toUser;//发送给谁 
    $smtpuser = "liuhuibg@163.com";//SMTP服务器的用户帐号 
    $smtppass = "liuhui32821163";//SMTP服务器的用户密码 
    $mailsubject = $title;//邮件主题 
    $mailbody = $content;//邮件内容 
    $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件 
    ########################################## 
    $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
    $smtp->debug = false;//是否显示发送的调试信息 
    $res = $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 
    
    return $res;
}
?>