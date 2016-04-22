<?php
require '../lib/PHPFetion.php';

$fetion = new PHPFetion('13500001111', '123123');	// 手机号、飞信密码
$fetion->send('13500001111', 'Hello!');	// 接收人手机号、飞信内容
