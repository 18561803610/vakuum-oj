<?php
/**
 * Transmit through FTP
 *
 * @author BYVoid
 */
class MDL_Judger_Transmit_FTP
{
	private static function connect($ftpinfo)
	{
		//Connect to the judger server
		if (!isset($ftpinfo['port']))
			$ftpinfo['port'] = 21;
		$pftp = ftp_connect($ftpinfo['address'],$ftpinfo['port'],10);
		if ($pftp === false)
		{
			throw new MDL_Exception_Judge_Send('ftp_connect');
		}
		$login_result = ftp_login($pftp, $ftpinfo['user'], $ftpinfo['password']);
		if ($login_result === false)
		{
			throw new MDL_Exception_Judge_Send('ftp_login');
		}
		return $pftp;
	}
	
	public static function uploadTask($ftpinfo,$task_name,$source,$src_name)
	{
		//Create a temp file to store the source file and get a file pointer $fp
		$temp_file = tempnam(sys_get_temp_dir(), 'Vakuum');
		file_put_contents($temp_file,$source);

		$pftp=self::connect($ftpinfo);

		//Create task dir and set permission
		if (ftp_chdir($pftp,$ftpinfo['path']['task']) === false)
			throw new MDL_Exception_Judge_Send('ftp_path_task');
		if (@ftp_chdir($pftp,$task_name) === false)
		{
			if (ftp_mkdir($pftp,$task_name) === false)
				throw new MDL_Exception_Judge_Send('ftp_mkdir');
			if (ftp_chmod($pftp,0777,$task_name) === false)
				throw new MDL_Exception_Judge_Send('ftp_chmod');
			ftp_chdir($pftp,$task_name);
		}
		
		//Upload the source file
		if (ftp_put($pftp, $src_name, $temp_file, FTP_BINARY) === false)
			throw new MDL_Exception_Judge_Send('ftp_upload');

		//Close the file and connection
		ftp_close($pftp);
	}
	
	public static function uploadTestdata($ftpinfo,$data_config)
	{
		$prob_name = $data_config['name'];
		$testdata_path = MDL_Config::getInstance()->getVar('judger_testdata').$prob_name.'/';
		
		$temp_file = tempnam(sys_get_temp_dir(), 'Vakuum');
		unset($data_config['id']);
		unset($data_config['title']);
		$xml = BFL_XML::Array2XML($data_config);
		file_put_contents($temp_file,$xml);
		
		$pftp=self::connect($ftpinfo);

		if (ftp_chdir($pftp,$ftpinfo['path']['testdata']) === false)
			throw new MDL_Exception_Judge_Send('ftp_path_testdata');
		if (@ftp_chdir($pftp,$prob_name) === false)
		{
			ftp_mkdir($pftp,$prob_name);
			ftp_chmod($pftp,0755,$prob_name);
			ftp_chdir($pftp,$prob_name);
		}
		
		//Upload config.xml
		if (ftp_put($pftp, 'config.xml', $temp_file, FTP_BINARY) === false)
			throw new MDL_Exception_Judge_Send('ftp_upload');
		
		if ($data_config['checker']['type']=='custom')
		{
			//Upload checker
			$checker_source = $data_config['checker']['custom']['source'];
			$checker_file = $testdata_path.$checker_source;
			if (ftp_put($pftp, $checker_source, $checker_file, FTP_BINARY) === false)
				throw new MDL_Exception_Judge_Send('ftp_upload');
		}
		
		//Upload testdatas
		foreach($data_config['case'] as $item)
		{
			foreach(array('input','output') as $key)
			{
				$testdata_file = $testdata_path. $item[$key];
				if (ftp_put($pftp, $item[$key], $testdata_file, FTP_BINARY) === false)
					throw new MDL_Exception_Judge_Send('ftp_upload');
			}
		}
		
		ftp_close($pftp);
	}
}