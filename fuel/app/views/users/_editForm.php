<?php echo Form::open(array('class' => 'form-stacked informations', 'autocomplete' => 'on')); ?>

<div class="content no-padding with-actions">
	<div class="section _100">
		<?php echo Form::label('Username', 'username'); ?>
		<div>
			<?php echo Form::input('username', Input::post('username', isset($user) ? $user->username : ''), array('class' => '_100', 'disabled')); ?>
		</div>
	</div>
	<div class="section _100">
		<?php echo Form::label('Email', 'email'); ?>
		<div>
			<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => '_100')); ?>
		</div>
	</div>
	<?php if(Sentry::user()->has_access('change_acl')): ?>
	<div class="section _100">
		<?php echo Form::label('Group', 'group'); ?>
		<div>
			<select name='group'>
			<?php 
			foreach($groups as $group){
				if(($user_group[0]['name'] == $group['name']) || (Input::post('group') ==  $group['name']))
				 	echo '<option value="'.$group['name'].'" selected>'.$group['name'].'</option>';
				else
					echo '<option value="'.$group['name'].'">'.$group['name'].'</option>';
			 }
			 ?>
			</select>
		</div>
	</div>
	<?php endif; ?>
	<div class="section _100">
		<?php echo Form::label('Department', 'department'); ?>
		<div>
			<?php echo Form::input('department', Input::post('department', isset($user) ? $user->get('metadata.department') : ''), array('class' => '_100')); ?>
		</div>
	</div>

		<div class="section _100">
		<?php echo Form::label('Last Name', 'last_name'); ?>
		<div>
			<?php echo Form::input('last_name', Input::post('last_name', isset($user) ? $user->get('metadata.last_name') : ''), array('class' => '_100')); ?>
		</div>
	</div>

		<div class="section _100">
		<?php echo Form::label('First Name', 'first_name'); ?>
		<div>
			<?php echo Form::input('first_name', Input::post('first_name', isset($user) ? $user->get('metadata.first_name') : ''), array('class' => '_100')); ?>
		</div>
	</div>
	<div class="actions">
		<div class="actions-left">
			<?php echo Form::reset('reset', 'Reset', array('class' => 'over button color red small')); ?>
		</div>
		<div class="actions-right">
			<?php echo Form::submit('submit', 'Save', array('class' => 'over button color green')); ?>
		</div>
	</div> 

	<?php echo Form::close(); ?>

	<?php if(Sentry::user()->has_access('users_password_change')): ?>
	
	<?php echo Form::open(array('class' => 'form-stacked password', 'autocomplete' => 'on')); ?>

	<div class="alert warning no-margin top" style='margin:0'>
		<span class="icon"></span>In order to change your password you must type your old and new password
	</div>

	<div class="section _100">
		<?php echo Form::label('Old password', 'old_password'); ?>
		<div>
			<?php echo Form::password('old_password', '', array('class' => '_100')); ?>
		</div>
	</div>

	<div class="section _100">
		<?php echo Form::label('New Password', 'new_password'); ?>
		<div>
			<?php echo Form::password('new_password', '', array('class' => '_100')); ?>
		</div>
	</div>

	<div class="section _100">
		<?php echo Form::label('Confirm New Password', 'c_new_password'); ?>
		<div>
			<?php echo Form::password('c_new_password', '', array('class' => '_100')); ?>
		</div>
	</div>

</div>
<div class="actions">
		<div class="actions-right">
			<?php echo Form::submit('submit', 'Save', array('class' => 'over button color blue')); ?>
		</div>
	</div> 

<?php echo Form::close(); ?>
<?php endif; ?>