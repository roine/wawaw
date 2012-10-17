<h2>Viewing #<?php echo $news->id; ?></h2>

<p>
	<strong>Title:</strong>
	<?php echo $news->title; ?></p>
<p>
	<strong>Body:</strong>
	<?php echo $news->body; ?></p>

<?php echo Html::anchor('news/edit/'.$news->id, 'Edit'); ?> |
<?php echo Html::anchor('news', 'Back'); ?>