<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'job-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'position_id'); ?>
		<?php echo $form->textField($model,'position_id'); ?>
		<?php echo $form->error($model,'position_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'job_type'); ?>
		<?php echo $form->textField($model,'job_type',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'job_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'salary'); ?>
		<?php echo $form->textField($model,'salary',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'salary'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'salary_min'); ?>
		<?php echo $form->textField($model,'salary_min'); ?>
		<?php echo $form->error($model,'salary_min'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'salary_max'); ?>
		<?php echo $form->textField($model,'salary_max'); ?>
		<?php echo $form->error($model,'salary_max'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'company_name'); ?>
		<?php echo $form->textField($model,'company_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'company_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'job_nature'); ?>
		<?php echo $form->textField($model,'job_nature',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'job_nature'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'education'); ?>
		<?php echo $form->textField($model,'education',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'education'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'finance_stage'); ?>
		<?php echo $form->textField($model,'finance_stage',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'finance_stage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'company_size'); ?>
		<?php echo $form->textField($model,'company_size',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'company_size'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createTime'); ?>
		<?php echo $form->textField($model,'createTime'); ?>
		<?php echo $form->error($model,'createTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'position_name'); ?>
		<?php echo $form->textField($model,'position_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'position_name'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->