<?php echo Form::open(array('class' => 'form-stacked validate informations', 'autocomplete' => 'on')); ?>

		<div class="content no-padding with-actions">

			<div class="section _100">
				<?php echo Form::label('Username', 'username'); ?>
				<div>
				<?php echo Form::input('username', Input::post('username', isset($user) ? $user->username : ''), array('class' => '_100 required')); ?>
				</div>
			</div>

		<div class="section _100">
			<?php echo Form::label('Email', 'email'); ?>
			<div>
				<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => '_100 required')); ?>
			</div>
		</div>

		<div class="section _100">
			<?php echo Form::label('Password', 'password'); ?>
			<div>
				<?php echo Form::password('password', Input::post('password', isset($user) ? $user->password : ''), array('class' => '_100 required')); ?>
			</div>
		</div>		

		<div class="section _100">
			<?php echo Form::label('Repeat password', 'r_password'); ?>
			<div>
				<?php echo Form::password('r_password', Input::post('r_password', isset($user) ? $user->r_password : ''), array('class' => '_100 required')); ?>
			</div>
		</div>

		<div class="section _100">
			<?php echo Form::label('Group', 'group'); ?>
			<div>
			
				<select name='group'>
				<?php foreach($groups as $group): ?>
				<?php echo '<option value="'.$group['name'].'">'.$group['name'].'</option>'; ?>
				<?php endforeach; ?>
				</select>
			</div>
		</div>
		
		<div class="section _100">
			<?php echo Form::label('Department', 'department'); ?>
			<div>
				<?php echo Form::input('department', Input::post('department', isset($user) ? $user->department : ''), array('class' => '_100')); ?>
			</div>
		</div>

		<div class="section _100">
			<?php echo Form::label('First name', 'first_name'); ?>
			<div>
				<?php echo Form::input('first_name', Input::post('first_name', isset($user) ? $user->first_name : ''), array('class' => '_100')); ?>
			</div>
		</div>

		<div class="section _100">
			<?php echo Form::label('Last name', 'last_name'); ?>
			<div>
				<?php echo Form::input('last_name', Input::post('last_name', isset($user) ? $user->last_name : ''), array('class' => '_100')); ?>
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
	<?php echo Form::close(); ?>