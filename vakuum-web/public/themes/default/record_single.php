<?php $this->title='提交记录 #'.$this->record['record_id'] ?>
<?php $this->display('header.php') ?>

<?php $record_id = $this->record['record_id'] ?>
<?php $prob_id = $this->record['record_prob_id'] ?>
<?php $prob_name = $this->record['prob_name']?>
<?php $prob_title = $this->record['prob_title']?>
<?php $prob_path = $this->locator->getURL('problem_single').'/'.$prob_id ?>
<?php $user_id = $this->record['record_user_id'] ?>
<?php $user_name = $this->record['user_name'] ?>
<?php $user_nickname = $this->record['user_nickname'] ?>
<?php $user_path = $this->locator->getURL('user_detail').'/'.$user_name ?>
<?php $language = $this->record['detail']['lang'] ?>
<?php $status_text = showStatus($this->record['detail']['status'],$this->record['detail']['result_text'])?>
<?php $score = $this->record['detail']['score'] ?>
<?php $submit_time = $this->formatTime($this->record['detail']['submit_time']) ?>
<?php $time_used = $this->record['detail']['time'] ?>
<?php $memory_used = $this->record['detail']['memory'] ?>
<?php $source = $this->record['detail']['source'] ?>
<?php $source_length = strlen($source) ?>
<?php 
$compile_explaination = array
(
	0 => '编译指令错误',
	1 => '编译器被异常终止',
	2 => '源文件不存在',
	3 => '二进制文件不存在',
);
$result_explaination = array
(
	0 => '正常运行结束',
	1 => '运行时错误',
	2 => '超过时间限制',
	3 => '超过空间限制',
	4 => '超过输出限制',
	5 => '禁止系统调用',
	6 => '执行器发生错误',
);
?>
<?php if (isset($this->record['execute'])) $execute_result = $this->record['execute']; else $execute_result = array() ?>
<?php
$compile_result_message ='等待编译';
$compiled = isset($this->record['compile']);
if ($compiled)
{
	$compile_result = $this->record['compile'];
	if (isset($compile_result['result']))
	{
		if ($compile_result['result'] == 0)
			$compile_result_message = '编译成功';
		else
			$compile_result_message = '编译失败 '.$compile_explaination[$compile_result['option']];
		$compiler_message = $compile_result['compiler_message'];
	}
}
?>
<table border="1">
	<tr>
		<td>记录编号</td>
		<td><?php echo $record_id ?></td>
	</tr>
	<tr>
		<td>题目</td>
		<td><a href='<?php echo $prob_path ?>'><?php echo $prob_name ?></a></td>
	</tr>
	<tr>
		<td>用户</td>
		<td><a href='<?php echo $user_path ?>'><?php echo $user_nickname ?></a></td>
	</tr>
	<tr>
		<td>状态</td>
		<td><?php echo $status_text ?></td>
	</tr>
	<tr>
		<td>得分</td>
		<td><?php echo $score ?></td>
	</tr>
	<tr>
		<td>提交时间</td>
		<td><?php echo $submit_time ?></td>
	</tr>
	<tr>
		<td>语言</td>
		<td><?php echo $language ?></td>
	</tr>
	<tr>
		<td>时间使用</td>
		<td><?php echo $time_used ?> ms</td>
	</tr>
	<tr>
		<td>内存使用</td>
		<td><?php echo $memory_used ?> KB</td>
	</tr>
	<tr>
		<td>代码长度</td>
		<td><?php echo $source_length ?> Bytes</td>
	</tr>
	<tr>
		<td>编译结果</td>
		<td>
			<table border="0">
				<tr><td><?php echo $compile_result_message ?></td></tr>
				<?php if ($compiled) :?>
				<tr><td><textarea rows="6" cols="60"><?php echo $this->escape($compiler_message) ?></textarea></td></tr>
				<?php endif ?>
			</table>
		</td>
	</tr>
	<tr>
		<td>运行结果</td>
		<td>
			<table border="1">
				<tr>
					<td>ID</td>
					<td>结果</td>
					<td>备注</td>
					<td>时间(ms)</td>
					<td>空间(kB)</td>
					<td>得分</td>
					<td>信息</td>
				</tr>
<?php foreach ($execute_result as $item): ?>
				<tr>
					<td><?php echo $item['case_id'] ?></td>
					<td><?php echo $result_explaination[$item['result']] ?></td>
					<td><?php echo $item['option'] ?></td>
					<td><?php echo $item['time_used'] ?></td>
					<td><?php echo $item['memory_used'] ?></td>
					<td><?php echo $item['score'] ?></td>
					<td><?php echo $item['check_message'] ?></td>
				</tr>
<?php endforeach ?>
			</table>
		</td>
	</tr>
</table>

<?php $this->display('footer.php') ?>