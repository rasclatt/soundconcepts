<?php
namespace SoundConcepts;

use \SoundConcepts\App as SoundConcepts;

class Observer extends \Nubersoft\nApp implements \Nubersoft\nObserver
{
	public	function listen()
	{
		# If not admin
		if(!$this->isAdmin()) {
			# If distributor Id not set
			if(!$this->userGet('dist_id'))
				return false;
			# If id set but not distributor
			elseif($this->userGet('group_id') <= 2)
				return false;
		}
		# Extract data
		$sub	=	$this->getPost('subaction');
		$subact	=	strtolower($sub);
		$POST	=	$this->toArray($this->getPost());
		unset($POST['action']);
		
		if(!$this->isAdmin())
			$POST['distributor_id']	=	$this->userGet('dist_id');
		
		switch($subact) {
			case('add'):
				unset($POST['subaction']);
				if(SoundConcepts::User_createUser($POST)) {
					$this->toSuccess('User Added.');
					return true;
				}
				break;
			case('activate'):
				unset($POST['subaction']);
				if(SoundConcepts::User_createUser($POST)) {
					$this->toSuccess('You have successfully enrolled with the Beyond Mobile App.');
					return true;
				}
				break;
			case('update'):
				unset($POST['subaction']);
				$POST	=	array_filter($POST);
				if(SoundConcepts::User_updateUser($POST)) {
					$this->toSuccess('User Updated.');
					return true;
				}
				break;
			case('delete'):
				unset($POST['subaction']);
				if(SoundConcepts::User_deleteUser($POST['distributor_id'])) {
					$this->toSuccess('User Deleted.');
					return true;
				}
		}
		
		$this->toError('An error occurred and failed to '.$subact.' user.');
	}
}