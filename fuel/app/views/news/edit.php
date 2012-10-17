<h2>Editing News</h2>
<br>

<?php echo render('news/_form'); ?>
<p>
	<?php echo Html::anchor('news/view/'.$news->id, 'View'); ?> |
	<?php echo Html::anchor('news', 'Back'); ?></p>
