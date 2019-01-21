<?php
namespace SoundConcepts\User;

class Model extends \SoundConcepts\Model\API
{
	public	static	function distIdExists($id)
	{
		$query	=	self::getModelInstance()->getData('users/check',['distributor_id'=>$id]);
		return (!empty($query['success']['exists']));
	}
	
	public	static	function distInfo($id)
	{
		return self::getModelInstance()->getData('users/check',['distributor_id'=>$id]);
	}
	
	public	static	function addUser($settings)
	{
		if(empty($settings['distributor_id']))
			return false;
		
		$query	=	self::getModelInstance()->getData('users/add',$settings);
	}
	
	public	static	function deleteUser($id)
	{
		if(empty($id))
			return false;
		
		$query	=	self::getModelInstance()->getData('users/delete',['distributor_id'=>$id]);
		
		return ($query['success']['message'] == 'deleted');
	}
	
	protected	static	function getModelInstance()
	{
		return new Model();
	}
	
	public	static	function modifyUser($settings)
	{
		if(empty($settings['distributor_id']))
			return false;
		
		$id	=	$settings['distributor_id'];
		unset($settings['distributor_id']);
		
		return self::getModelInstance()->getData('users/edit/'.$id,$settings);
	}
	
	public	static	function search($settings=false)
	{
		$query	=	self::getModelInstance()->getData('users/index',$settings);
		
		if(!empty($query['users'])) {
			$query['users']	=	array_values($query['users']);
		}
		
		return $query;
	}
}