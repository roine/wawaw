<div class="grid_6" style='margin-left:25%'>
	<div class="box effect1">
		<div class="header">
			<img width="16" height="16" alt="" src="/assets/img/icons/packs/fugue/16x16/ui-text-field-format.png">
			<h3>To <?php echo $user->username; ?></h3>
			<span></span>
		</div>
		<?php echo Form::open(array('class' => 'form-stacked validate message', 'data-to' => $user->id)); ?>

		<div class="content with-actions">

			<div class="alert warning no-margin top">
				<span class="icon"></span><span class='restaured'></span>
			</div>

			<div class="_100">
				<?php echo Form::input('subject', Input::post('subject', isset($message) ? $message->subject : ''), array('class' => '_100', 'placeholder' => 'subject')); ?>
			</div>

			<div class="_100">
				<?php echo Form::textarea('content', Input::post('content', isset($message) ? $message->content : ''), array('class' => '_100', 'placeholder' => 'Message...', 'maxlength' => '255', 'rows' => '10')); ?>
			</div>

		</div>
		<!-- end content -->

		<div class="actions">
			<div class="actions-left">
				<?php echo Form::button('save', 'Save', array('class' => 'save')); ?>
			</div>
			<div class="actions-right">
				<?php echo Form::submit('submit', 'Send', array('class' => 'btn primary')); ?>
			</div>
		</div> 

		<?php echo Form::close(); ?>
	</div>
</div>