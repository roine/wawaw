<h2>Listing News</h2>
<br>
<?php if ($news): ?>
<table class="zebra-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th>Body</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($news as $new): ?>		
	<tr>

			<td><?php echo $new->title; ?></td>
			<td><?php echo $new->body; ?></td>
			<td>
			<?php if(Auth::has_access('news.read')): ?>
				<?php echo Html::anchor('news/view/'.$new->id, 'View', array("class"=>"btn primary")); ?>
			<?php endif; ?>
			<?php if(Auth::has_access('news.update')): ?>
				 <?php echo Html::anchor('news/edit/'.$new->id, 'Edit', array("class"=>"btn success")); ?>
			<?php endif; ?>
			<?php if(Auth::has_access('news.delete')): ?>
				<?php echo Html::anchor('news/delete/'.$new->id, 'Delete', array('onclick' => "return confirm('Are you sure?')", 'class' => 'btn danger')); ?>
			<?php endif; ?>

			</td>

		</tr>
<?php endforeach; ?>	
</tbody>
</table>

<?php else: ?>
<p>No News.</p>

<?php endif; ?><p>
	
	<?php echo Html::anchor('news/create', 'Add new News', array('class' => 'btn success')); ?>

</p>
