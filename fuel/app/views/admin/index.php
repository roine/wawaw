
<?php if(!Auth::check()): ?>
<div class='span5'>
	<?php echo Form::open(); ?>
	<?php echo Form::input('username', $username, array('placeholder' => 'Username / Email', 'autofocus')); ?>
	<?php echo Form::password('password', $password, array('placeholder' => 'Password')); ?>
	<?php echo Form::submit('submit', 'Connect', array('class' => 'btn success')); ?>
	<?php echo Form::close(); ?>
</div>
<?php elseif(Auth::has_access('admin.read')): ?>
<?php echo 'admin panel'; ?><br />
<div id='news'>
	List of news
	<ul>
		<?php foreach($news as $new): ?>
		<?php echo '<li>'.Html::anchor('news/view/'.$new->id, $new->title).'</li>'; ?>
		<?php endforeach; ?>
	</ul>
	<?php echo Html::anchor('news/create', 'Create a News'); ?>
</div>
<?php echo Html::anchor('admin/logout', 'Logout'); ?>
<?php else: ?>
<?php Response::redirect(''); ?>
<?php endif; ?>
<?php
list($insert_id, $rows_affected) = \DB::insert('dbacl_role')
        ->columns(array('namespace', 'name'))
        ->values(array('Main\\', 'make_magic'))
        ->execute();
?>
