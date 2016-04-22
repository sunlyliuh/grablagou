<?php
/**
 * 飞信发送日志
 */
class FetionLogModel extends Model
{
    protected $tableName        =   'fetion_log';
    protected $_validate         =         array(
        array('mobile','require','手机号必须！'),
        array('msg','require','短信内容必须！'),
    );
}
?>
