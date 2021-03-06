<?php $this->title='评测机列表' ?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>名称</td>
		<td>可用</td>
		<td>计数</td>
	</tr>
<?php foreach($this->list as $item): ?>
	<tr>
		<td><?php echo $item['judger_id']?></td>
		<td><?php echo $this->escape($item['judger_name'])?></td>
		<td><?php echo $this->escape($item['judger_enabled'])?></td>
		<td><?php echo $this->escape($item['judger_count'])?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>