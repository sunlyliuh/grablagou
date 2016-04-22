<?php
/**
 * 飞信好友表
 */
class FetionUserModel extends Model
{
    protected $tableName        =   'fetion_user';
    protected $_validate         =         array(
        array('name','require','姓名必须！'),
        array('mobile','require','手机号必须！'),
        array('citycode','require','城市编码必须！'),
        array('cityname','require','城市名称必须！'),
    );

}
?>
