<?php
/**
 * 文章表
 */
class ArticleModel extends Model
{
    protected $tableName        =   'article';
    protected $_validate         =         array(
        array('title','require','分类名称必须！'),
        array('content','require','文章内容必须！'),
        array('title','','分类名称已经存在！',0,'unique',1), // 在新增的时候验证字段是否唯一
    );


}
?>
