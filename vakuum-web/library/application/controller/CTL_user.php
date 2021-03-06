<?php
require_once('CTL_Abstract/Controller.php');

/**
 * user Controller
 *
 * @author BYVoid
 */
class CTL_user extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		$this->locator->redirect('user_list');
	}
	
	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;
		
		try
		{
			$rs = MDL_User_List::getList($page,array('register_time'));
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1] == 'page')
				$this->notFound(array('specifier' => 'user_list_page'));
			else
				throw $e;
		}
		
		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];
		
		$this->view->display('user_list.php');
	}

	public function ACT_detail()
	{
		$user_name = $this->path_option->getPathSection(2);
		try
		{
			$mud = new MDL_User_Detail();
			$rs = $mud->getUserByName($user_name);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1] == 'name')
				$this->notFound(array('specifier' => 'user'));
			else
				throw $e;
		}
		
		$this->view->user = $rs;
		$this->view->display('user_single.php');
	}
	
	public function ACT_space()
	{
		if (!$this->acl->check(array('general','unvalidated')))
			$this->deny();
		$this->view->user = BFL_Register :: getVar('personal');
		$this->view->display('user_space.php');
	}
	
	public function ACT_edit()
	{
		if (!$this->acl->check(array('general','unvalidated')))
			$this->deny();
		$this->view->user = BFL_Register :: getVar('personal');
		$this->view->display('user_edit.php');
	}
	
	public function ACT_doedit()
	{
		if (!$this->acl->check(array('general','unvalidated')))
			$this->deny();
		
		$user = BFL_Register :: getVar('personal');
		$user_id = BFL_ACL::getInstance()->getUserID();
		$user_name = $user['user_name'];
		
		$user_info = array
		(
			'user_id'=> $user_id,
			'user_name' => $user_name,
			'user_password_correct' => $user['user_password'],
			'user_password' => $_POST['user_password'],
			'user_password_original'=> $_POST['user_password_original'],
			'user_password_repeat' => $_POST['user_password_repeat'],
			'user_nickname' => $_POST['user_nickname'],
			'email'	=> $_POST['email'],
			'website' => $_POST['website'],
			'memo' => $_POST['memo'],
		);

		try
		{
			MDL_User_Edit::edit($user_info);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			$request['specifier'] = $desc[2];
			$this->locator->redirect('error_user_edit',$request);
		}
		
		//TODO success message
		$this->locator->redirect('user_space');
	}
}