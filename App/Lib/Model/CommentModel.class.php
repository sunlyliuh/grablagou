<?php
/**
 * 回复表
 */
class CommentModel extends Model
{
    protected $tableName        =   'comment';
    protected $_validate         =         array(
        array('email','require','邮箱必须！'),
        array('comment','require','内容必须'),
    );


}
?>
