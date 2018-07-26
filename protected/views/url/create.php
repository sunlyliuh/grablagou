<?php
$this->breadcrumbs=array(
	'Urls'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Url', 'url'=>array('index')),
	array('label'=>'Manage Url', 'url'=>array('admin')),
);
?>

<h1>Create Url</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>