<?php
/**
 * 用户model
 */
class UserModel extends Model
{
    protected $tableName        =   'user';
    
    protected $_validate         =         array(
        array('username','require','用户名必须！'), //默认情况下用正则进行验证
        array('password','require','密码必须！'),
    );


}
?>
