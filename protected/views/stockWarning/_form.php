<?php
/* @var $this StockWarningController */
/* @var $model StockWarning */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stock-warning-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>
    
	<div class="row">
		<?php echo $form->labelEx($model,'miaoshu'); ?>
		<?php echo $form->textField($model,'miaoshu',array('size'=>20,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'miaoshu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'min_price'); ?>
		<?php echo $form->textField($model,'min_price',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'min_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_price'); ?>
		<?php echo $form->textField($model,'max_price',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'max_price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->