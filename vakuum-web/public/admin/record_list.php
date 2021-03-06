<?php $this->title='提交记录列表' ?>
<?php $this->display('header.php') ?>
<?php $record_list = $this->list ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>状态</td>
		<td>题目</td>
		<td>用户</td>
		<td>评测机</td>
		<td>提交时间</td>
		<td>操作</td>
	</tr>
<?php foreach($record_list as $item): ?>
	<?php $record_id = $item['record_id']?>
	<?php $record_path = $this->locator->getURL('record_detail').'/'. $record_id ?>
	
	<?php $prob_id = $item['prob_id']?>
	<?php $prob_name = $item['prob_name'] ?>
	<?php $prob_title = $item['prob_title'] ?>
	<?php $prob_path = $this->locator->getURL('problem_single').'/'. $prob_name ?>
	
	<?php $user_id = $item['user_id']?>
	<?php $user_name = $item['user_name'] ?>
	<?php $user_nickname = $item['user_nickname'] ?>
	<?php $user_path = $this->locator->getURL('user_detail').'/'. $user_name ?>
	
	<?php $judger_id = $item['judger_id']?>
	<?php $submit_time = $item['other']['submit_time'] ?>
	<?php $submit_time_text = $this->formatTime($submit_time)?>
	<?php $status_text = showStatus($item['other']['status'],$item['other']['result_text'])?>
	
	<?php $record_source = $this->locator->getURL('record_source').'/'. $record_id ?>
	<?php $record_delete = $this->locator->getURL('admin_record_delete').'/'. $record_id ?>
	<?php $record_rejudge = $this->locator->getURL('admin_record_rejudge').'/record_id/'. $record_id ?>
	<?php $record_stopjudge = $this->locator->getURL('admin_record_stopjudge').'/'. $record_id ?>
	
	<tr>
		<td><?php echo $record_id?></td>
		<td><a href='<?php echo $record_path ?>' target="_blank"><?php echo $status_text ?></a></td>
		<td><a href='<?php echo $prob_path ?>' target="_blank"><?php echo $prob_title ?></a></td>
		<td><a href='<?php echo $user_path ?>' target="_blank"><?php echo $user_nickname ?></a></td>
		<td><?php echo $judger_id ?></td>
		<td><?php echo $submit_time_text ?></td>
		<td>
			<a href="<?php echo $record_source ?>" target="_blank">查看代码</a>
			<a href="<?php echo $record_delete ?>">永久删除</a> 
			<a href="<?php echo $record_rejudge ?>" target="_blank">重新评测</a>
			<a href="<?php echo $record_stopjudge ?>" >停止评测</a>
		</td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>