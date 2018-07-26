<?php
/* @var $this StockWarningController */
/* @var $model StockWarning */

$this->breadcrumbs=array(
	'Stock Warnings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List StockWarning', 'url'=>array('index')),
	array('label'=>'Manage StockWarning', 'url'=>array('admin')),
);
?>

<h1>Create StockWarning</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>