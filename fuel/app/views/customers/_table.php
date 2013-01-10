<div id="modal"></div>
	<div class="grid_12">
<div id="Filter" class='grid_12'>
<?php if(Sentry::user()->has_access('filters_lang_use')): ?>
<div class='grid_2'>
	<select class='chosen_lang deselect' data-placeholder='Language Filter...' id='langFilter'>
		<option value=""></option>
		<?php foreach($language as $lang): ?>
		<option value='<?php echo $lang; ?>'>
		<?php
		switch($lang){
			case 'en':
			echo 'English';
			break;
			case 'ru':
			echo 'Russian';
			break;
			case 'tw':
			echo 'Taiwanese';
			break;
			case 'cn':
			echo 'Chinese';
			break;
			default:
			echo 'error';
		}
		?>
		</option>
		<?php endforeach; ?>
		<option value=''>All</option>
	</select>
</div>
<?php endif; ?>

<?php if(Sentry::user()->has_access('filters_date_use')): ?>
<div class='grid_10'>
	<select class='chosen_date grid_3 deselect' data-placeholder='Date Preset' id='datePreset'>
		<option></option>
		<optgroup label='Current'>
			<option value='today'>Today</option>
			<option value='week'>This Week</option>
			<option value='month'>This Month</option>
			<option value='year'>This Year</option>
		</optgroup>
		<optgroup label='Previous'>
			<option value='yesterday'>Yesterday</option>
			<option value='lastWeek'>Last Week</option>
			<option value='lastMonth'>Last Month</option>
			<option value='lastYear'>Last Year</option>
		</optgroup>
	</select>
	<input id="min" type="text"  name='min' class='datepicker grid_4' data-date-relative="now" placeholder="Min Date Range" value='' />
	<input  id="max" name='max' type="text" class='datepicker grid_4' placeholder="Max Date Range" value='' />
</div>
<?php endif; ?>
</div>
<div class='clear'></div>
		<div class="box effect2">
			<div class="header">
				<img src="/assets/img/icons/packs/fugue/16x16/shadeless/table-excel.png" width="16" height="16">
				<h3>Datatables :: <b><?php echo $current_table['cleanName']; ?></b></h3><span></span>
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
	<div class="clean"></div>
<script>
	var canEdit = '<?php echo Sentry::user()->has_access("customers_update"); ?>';
	var canDelete = '<?php echo Sentry::user()->has_access("customers_delete"); ?>'
	var table = '<?php echo $current_table["table"]; ?>';
	var languages = JSON.parse('<?php echo json_encode($language); ?>');
	

</script>