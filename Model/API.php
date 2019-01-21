<?php
namespace SoundConcepts\Model;

class API extends \Nubersoft\API\Model
{
	private	$settings	=	[
		'master_username'=>'wellbeyond-api',
		'master_password'=>'whynarrowtogethermiddle'
	];
	
	public	function getData()
	{
		$args		=	func_get_args();
		$service	=	$args[0];
		$query		=	(!empty($args[1]))? $args[1] : '';
		$type		=	(!empty($args[2]))? $args[2] : 'json';
		$params		=	(!empty($query))? array_merge($this->settings,$query) : $this->settings;
		return $this->setEndpoint('https://mywellbeyond.mysecureoffice.com/remote/'.ltrim($service,'/').'.'.$type)->sendAsPost($params)->getResponse($type);
	}
}