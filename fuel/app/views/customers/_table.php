<div id="content-wrapper" class="container_12" role="main">
	<div id="modal"></div>
	<div id="main_content">
	<h2 class="grid_12"><?php echo $current_table['CleanName']; ?></h2>
		<div class="clean"></div>
		<div class="grid_12">
	<div id="Filter" class='grid_12'>
	<?php if(Sentry::user()->has_access('filters_lang_use')): ?>
	<div class='grid_2'>
		<select class='chosen_lang' data-placeholder='Language Filter...' id='langFilter'>
			<option></option>
			<option value='en'>English</option>
			<option value='cn'>Chinese</option>
			<option value='tw'>Taiwanese</option>
			<option value='ru'>Russian</option>
			<option value=''>All</option>
		</select>
	</div>
	<?php endif; ?>

	<?php if(Sentry::user()->has_access('filters_date_use')): ?>
	<div class='grid_10'>
		<input id="min" type="text"  name='min' class='datepicker grid_4' data-date-relative="now" placeholder="Min Date Range" value='' />
		<input  id="max" name='max' type="text" class='datepicker grid_4' placeholder="Max Date Range" value='' />
	</div>
	<?php endif; ?>
	</div>
	<div class='clear'></div>
			<div class="box">
				<div class="header">
					<img src="/assets/img/icons/packs/fugue/16x16/shadeless/table-excel.png" width="16" height="16">
					<h3>Datatables :: <b><?php echo $current_table['CleanName']; ?></b></h3><span></span>
				</div>
				<div class="content">
					<table id="grid" class="table <?php echo $current_table["table"]; ?>">
						<thead>
							<tr>
								<?php foreach($columns as $column): ?>
								<th><?php echo ucwords($column); ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<?php if(Sentry::user()->has_access('filters_multi_use')): ?>
						<tfoot>
							<tr>
								<?php foreach($columns as $k=>$column): ?>
								<th><input data-p='<?php echo $k; ?>' placeholder='<?php echo $column; ?> filter' type='text'></th>
								<?php endforeach; ?>
							</tr>
						</tfoot>
						<?php endif; ?>
					</table>
				</div>
				<!-- end content -->
				<div class="clear"></div>
			</div>
			<!-- end box -->
		</div>
		<!-- end grid_12 -->
	</div>
	<!-- end main_content -->
</div>
<!-- end content-wrapper -->

<script>
	var canEdit = '<?php echo Sentry::user()->has_access("customers_update"); ?>';
	var canDelete = '<?php echo Sentry::user()->has_access("customers_delete"); ?>'
	var table = '<?php echo $current_table["table"]; ?>';
</script>