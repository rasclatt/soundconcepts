<?php
namespace SoundConcepts\User;

class Controller extends \SoundConcepts\User\Model
{
	public	function userExists($id)
	{
		return $this->distIdExists($id);
	}
	
	public	function createUser(array $settings)
	{
		foreach($settings as $key => $value)
			$settings[$key]	=	trim($value);
		
		$data['distributor_id']		=	(!empty($settings['distributor_id']))? $settings['distributor_id'] : false;
		$data['email']				=	(!empty($settings['email']))? $settings['email'] : false;
		$data['password']			=	(!empty($settings['password']))? $settings['password'] : false;
		$data['username']			=	(!empty($settings['username']))? $settings['username'] : false;
		#Allowed values: free, expired, or an empty string (which means "normal"). expired is sometimes also called "disabled" or "deactivated", but the actual value in the Sound Concepts database is "expired"
		$data['subscription_level']	=	(!empty($settings['subscription_level']))? $settings['subscription_level'] : 'normal';
		$data['first_name']			=	(!empty($settings['first_name']))? $settings['first_name'] : false;
		$data['last_name']			=	(!empty($settings['last_name']))? $settings['last_name'] : false;
		$data['country']			=	(!empty($settings['country']))? $settings['country'] : false;
		/*
		$settings['phone']
		$settings['address1']
		$settings['address2']
		$settings['city']
		$settings['state']
		$settings['zip']
		*/
		
		foreach($data as $key => $value) {
			if(empty($value))
				return false;
			
			$settings[$key]	=	$value;
		}
		if(!$this->distIdExists($data['distributor_id']))
			# Add user
			$this->addUser($settings);
		# Check exists
		return $this->distIdExists($data['distributor_id']);
	}
	
	public	function updateUser(array $settings)
	{
		foreach($settings as $key => $value)
			$settings[$key]	=	trim($value);
		
		$data['distributor_id']		=	(!empty($settings['distributor_id']))? $settings['distributor_id'] : false;
		foreach($data as $key => $value) {
			if(empty($value))
				return false;
			
			$settings[$key]	=	$value;
		}
		if(!$this->distIdExists($data['distributor_id']))
			# Add user
			$this->addUser($settings);
		else
			# Check exists
			return $this->modifyUser($settings);
	}
	
	public	function getUserInfo($id)
	{
		return ($this->userExists($id))? array_merge($this->distInfo($id)['success']['user'],$this->search(['distributor_id'=>$id])['users'][0]) : false;
			
	}
}