<?php
$this->breadcrumbs=array(
	'Jobs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Job', 'url'=>array('index')),
	array('label'=>'Create Job', 'url'=>array('create')),
	array('label'=>'Update Job', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Job', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('zii','Are you sure you want to delete this item?'))),
	array('label'=>'Manage Job', 'url'=>array('admin')),
);
?>

<h1>View Job #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'position_id',
		'job_type',
		'city',
		'salary',
		'salary_min',
		'salary_max',
		'company_name',
		'job_nature',
		'education',
		'finance_stage',
		'company_size',
		'createTime',
		'position_name',
	),
)); ?>
