<div class="grid_12 " >
	<div class="box effect6">
		<div class="header">
			<img width="16" height="16" alt="" src="/assets/img/icons/packs/fugue/16x16/ui-text-field-format.png">
			<h3>List of users(<?php echo count($users)?>)</h3>
			<span></span>
		</div>

<div class='content'>
		<div role="grid" class="dataTables_wrapper" id="grid_wrapper" style="position: relative;">
	<table class='table' id='grid'>
		<thead>
		<?php if(Sentry::user()->has_access('users_unblock')): ?>
		<th>Unblock/Block</th>
		<?php endif; ?>
			<th>Username</th><th>E-Mail</th><th>Department</th><th>Type</th><th>Created</th><th>Last Connexion</th>
			<th>Actions</th>
		</thead>
		<tbody>
			<?php foreach($users as $user): ?>

				<?php $meta = Sentry::user(intval($user['id'])); ?>
				<?php $group = $meta->groups() ?>

				<?php $attempts = Sentry::attempts($user['username'])->get(); ?>
				<tr class='<?php echo $user["id"]; ?>'>
					<?php if(Sentry::user()->has_access('users_unblock')): ?>
					<td class='suspend'><input type='checkbox' <?php if(Sentry::attempts()->get_limit() <= $attempts) echo 'checked=checked'; ?>></td>
					<?php endif; ?>

					<?php if(Sentry::user()->has_access('users_view')): ?>
					<td class='username'><?php 
						echo (Sentry::attempts()->get_limit() <= $attempts) ?  "<span title='user blocked' class='blocked'>".Html::anchor('users/view/'.$user['id'], $user['username'])."</span>" :  Html::anchor('users/view/'.$user['id'], $user['username']); ?></td>
					<?php else: ?>
					<td class='username'><?php echo $user['username']; ?></td>
					<?php endif; ?>

					<td class='email'><?php echo $user['email']; ?></td>
					
					<td class='department'><?php echo $meta->get('metadata.department')  ?></td>

					<td class='group'><?php echo isset($group[0]['name']) ? $group[0]['name'] : '<b class="no_group">No group</b>' ?></td>

					<td class='created_at'>
						<abbr title='<?php echo !empty($user['created_at']) ? Date::forge($user['created_at'])->format("%A %d %B %Y") : ""; ?>'>
						<?php echo !empty($user['created_at']) ? Date::time_ago($user['created_at']) : "Never"; ?>
						</abbr>
					</td>

					<td class='last_login'>
						<abbr title='<?php echo !empty($user['last_login']) ? Date::forge($user['last_login'])->format("%A %d %B %Y") : ""; ?>'>
						<?php echo !empty($user['last_login']) ? Date::time_ago($user['last_login']) : "Never"; ?>
						</abbr>
					</td>

					<td class='control'> 
					<?php 
					if(Sentry::user(null, true)->has_access('users_edit') || $user['id'] == $current_user['id'])
						echo Html::anchor('users/edit/'.$user['id'], 'Edit');

					if(Sentry::user(null, true)->has_access('users_delete') || $user['id'] == $current_user['id']){
						if(Sentry::user(null, true)->has_access('users_edit') || $user['id'] == $current_user['id'])
							echo $separator;
						echo Html::anchor('users/delete/'.$user['id'], 'Delete', array('onclick' => "return confirm('Are you sure?')"));
					}
						 
					 ?>
					</td>
					
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
</div>

			<div class="clear"></div>
		</div>
		<!-- end box -->
	</div>
