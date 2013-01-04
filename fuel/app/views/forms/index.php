
<?php echo Html::anchor('forms/add', 'Add a form') ?>
<div class="grid_12" >
	<div class="box effect2">
		<div class="header">
			<img width="16" height="16" alt="" src="/assets/img/icons/packs/fugue/16x16/ui-text-field-format.png">
			<h3>List of forms</h3>
			<span></span>
		</div>

<div class='content'>
		<div role="grid" class="dataTables_wrapper" id="grid_wrapper" style="position: relative;">
	<table class='table' id='grid'>
		<thead>
			<th>cleanName</th><th>url</th><th>table</th><th>Actions</th>
		</thead>
		<tbody>

			<?php foreach($all as $form): ?>

				<tr class='<?php echo $form->id; ?>'>
					<td class='forms_cleanName'><?php echo $form->cleanName; ?></td>
					<td class='forms_url'><?php echo $form->url; ?></td>
					<td class='forms_table'><?php echo $form->table; ?></td>

					<td class='forms_control'> 
					<?php 
					echo Html::anchor('forms/view/'.$form->id, 'View');
					if(Sentry::user(null, true)->has_access('forms_edit') && $form->table != 'all')
						echo $separator.Html::anchor('forms/edit/'.$form->id, 'Edit');

					if(Sentry::user(null, true)->has_access('forms_delete') && $form->table != 'all'){
						if(Sentry::user(null, true)->has_access('forms_edit'))
							echo $separator;
						echo Html::anchor('forms/delete/'.$form->id, 'Delete', array('onclick' => "return confirm('Are you sure?')"));
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
