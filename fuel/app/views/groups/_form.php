<?php echo Form::open(array('class' => 'validate', 'autocomplete' => 'on')); ?>
<div class="content no-padding with-actions">
	<div class="section _100">
		<div class='_50'>
			<?php echo Form::input('groupName', Input::post('groupName', isset($group['groupName']) ? $group['groupName'] : ''), array('class' => 'name required', 'placeholder' => 'Name of the group')); ?>
		</div>
	</div>
	<div class="_100 acl columns">
		<?php foreach($var as $v): ?>
		<?php if(isset($v['start']) && $v['start']['is_start'] == 1): ?>
		<div>
			<h2><?php echo $v['start']['text'] ?></h2>
			<?php endif; ?>
			
			<p>
				<label for="<?php echo $v['access']?>">
					<?php echo Form::checkbox($v['access'], 1, isset($group['permissions']['superuser']) || isset($group['permissions'][$v['access']]) || Input::post($v['access'])); ?>
					<?php echo $v['text'] ?>
				</label>
			</p>
			
			<?php if(isset($v['separate'])): ?>
			<hr>
			<?php endif; ?>
			
			<?php if(isset($v['start']) && $v['start']['is_start'] == 0): ?>
		</div>
		<?php endif; ?>
		
			<?php endforeach; ?>
			
	</div>
	<!-- end columns -->
</div>
<!-- end content -->
<div class="actions">

	<div class="actions-right">
		<input type="submit" class='over color blue button'>
	</div>
</div>

<?php echo Form::close(); ?>