
<table class='table' id='grid'>
	<thead>
		<th>Group Name</th><th>Number of users</th>
		<?php if(Sentry::user()->has_access('groups_edit') || Sentry::user()->has_access('groups_delete')): ?>
		<th>Actions</th>
		<?php endif; ?>
	</thead>
	<tbody>
	<?php foreach($groups as $group): ?>
		<tr>
			<td><?php echo Html::anchor('groups/view/'.$group['id'], $group['name']); ?></td>
			<td><?php echo count(Sentry::group($group['id'])->users()); ?></td>
			<?php if(Sentry::user()->has_access('groups_edit') || Sentry::user()->has_access('groups_delete')): ?>
			<td><?php 	
			if(Sentry::user()->has_access('groups_edit'))
				echo Html::anchor('groups/edit/'.$group['id'], 'Edit');
			if(Sentry::user()->has_access('groups_delete'))
				if(Sentry::user()->has_access('groups_edit'))
					echo $separator;
				echo Html::anchor('groups/delete/'.$group['id'], 'Delete', array('onclick' => "return confirm('Are you sure?')"));
			?></td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
