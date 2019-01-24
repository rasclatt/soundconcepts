<?php
namespace SoundConcepts;

use \Nubersoft\nReflect as Reflect;

class App
{
	/**
	*	@description	In order to use this exclusively for dynamic controllers,
	*					you need to provide the username, password, and subdomain
	*/
	public	function __construct()
	{
		$args	=	func_get_args();
		if(!empty($args)) {
			new API\Model(...$args);
		}
	}
	/**
	*	@description	Fetch controllers dynamically
	*/
	public function __call($class, $args)
	{
		$class			=	array_values(array_filter(explode('_',$class)));
		$className		=	'\\SoundConcepts\\'.$class[0].'\\Controller';
		$classMethod	=	$class[1];
		return (is_array($args))? Reflect::instantiate($className)->{$classMethod}(...$args) : Reflect::instantiate($className)->{$classMethod}($args);
	}
}