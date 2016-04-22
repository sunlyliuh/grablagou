<?php
/**
 * 文章分类
 */
class CatModel extends Model
{
    protected $tableName        =   'cat';
    protected $_validate         =         array(
        array('name','require','分类名称必须！'),
        array('name','','分类名称已经存在！',0,'unique',1), // 在新增的时候验证字段是否唯一
    );


}
?>
