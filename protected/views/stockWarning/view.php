<?php
/* @var $this StockWarningController */
/* @var $model StockWarning */

$this->breadcrumbs=array(
	'Stock Warnings'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List StockWarning', 'url'=>array('index')),
	array('label'=>'Create StockWarning', 'url'=>array('create')),
	array('label'=>'Update StockWarning', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StockWarning', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StockWarning', 'url'=>array('admin')),
);
?>

<h1>View StockWarning #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'code',
        'miaoshu',
		'min_price',
		'max_price',
	),
)); ?>
