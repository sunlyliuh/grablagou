<?php
/* @var $this StockWarningController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Stock Warnings',
);

$this->menu=array(
	array('label'=>'Create StockWarning', 'url'=>array('create')),
	array('label'=>'Manage StockWarning', 'url'=>array('admin')),
);
?>

<h1>Stock Warnings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
