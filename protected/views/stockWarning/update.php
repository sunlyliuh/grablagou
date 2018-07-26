<?php
/* @var $this StockWarningController */
/* @var $model StockWarning */

$this->breadcrumbs=array(
	'Stock Warnings'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List StockWarning', 'url'=>array('index')),
	array('label'=>'Create StockWarning', 'url'=>array('create')),
	array('label'=>'View StockWarning', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StockWarning', 'url'=>array('admin')),
);
?>

<h1>Update StockWarning <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>