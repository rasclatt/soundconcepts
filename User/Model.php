<?php
namespace SoundConcepts\User;

class Model extends \SoundConcepts\API\Model
{
	public	function distIdExists($id)
	{
		$query	=	$this->getData('users/check',['distributor_id'=>$id]);
		return (!empty($query['success']['exists']));
	}
	
	public	function distInfo($id)
	{
		return $this->getData('users/check',['distributor_id'=>$id]);
	}
	
	public	function addUser($settings)
	{
		if(empty($settings['distributor_id']))
			return false;
		
		$query	=	$this->getData('users/add',$settings);
	}
	
	public	function deleteUser($id)
	{
		if(empty($id))
			return false;
		
		$query	=	$this->getData('users/delete',['distributor_id'=>$id]);
		
		return ($query['success']['message'] == 'deleted');
	}
	
	public	function modifyUser($settings)
	{
		if(empty($settings['distributor_id']))
			return false;
		
		$id	=	$settings['distributor_id'];
		unset($settings['distributor_id']);
		
		return $this->getData('users/edit/'.$id,$settings);
	}
	
	public	function search($settings=false)
	{
		$query	=	$this->getData('users/index',$settings);
		
		if(!empty($query['users'])) {
			$query['users']	=	array_values($query['users']);
		}
		
		return $query;
	}
}