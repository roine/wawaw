<?php echo Form::open(array('class' => 'form-stacked informations validate forms', 'autocomplete' => 'on')); ?>

<div class="content no-padding with-actions">

	<div class="section _100">
		<?php echo Form::label('Clean Name', 'cleanName'); ?>
		<div>
			<?php echo Form::input('cleanName', Input::post('cleanName', isset($form) ? $form->cleanName : ''), array('class' => 'required')); ?>
		</div>
	</div>

	<div class="section _100">
		<?php echo Form::label('Url', 'url'); ?>
		<div>
			<?php echo Form::input('url', Input::post('url', isset($form) ? $form->url : ''), array('class' => 'required uri')); ?>
		</div>
	</div>

	<div class="section _100">
		<?php echo Form::label('Table', 'table'); ?>
		<div>
			<?php echo Form::input('table', Input::post('table', isset($form) ? $form->table : ''), array('class' => 'required tableExists')); ?>
		</div>
	</div>

</div>
	<!-- end content no-padding with-actions -->
	<div class="actions">
		<div class="actions-left">
			<?php echo Form::reset('reset', 'Reset', array('class' => 'over button color red small')); ?>
		</div>
		<div class="actions-right">
			<?php echo Form::submit('submit', 'Save', array('class' => 'over button color green')); ?>
		</div>
	</div> 
<?php Form::close(); ?>