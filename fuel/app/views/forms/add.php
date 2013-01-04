<div class='grid_6 effect8' style='margin-left: 25%;font-size: 16px;padding: 20px;border-radius:3px;background:white;border:1px solid silver;'>
		Preambule: Before adding form, make sure the following requirements are reached:
		<ol>
			<li>The table must have a field <em class='labels label-info'>created_at</em></li>
			<li>The table must have a field <em class='labels label-info'>language</em></li>
			<li>The method <em class='labels label-info'>allTables</em> in the <em class='labels label-info'>Model_Ajax</em> class must have a variable named <em class='labels label-info'>important_data</em> containing the important data to display</li>
			<li>In <em class='labels label-info'>datatables.configuration.js</em> add a new line to <em class='labels label-info'>col</em>, follow the examples</li>
		</ol>
</div>
<div class="grid_6" style='margin-left:25%'>

	<div class="box effect4">
		<div class="header">
			<img width="16" height="16" alt="" src="/assets/img/icons/packs/fugue/16x16/ui-text-field-format.png">
			<h3>Add</h3>

		</div>
		<?php echo render('forms/_form'); ?>
	</div> <!-- End of .box -->
</div>
