<?php
namespace SoundConcepts;

use \Nubersoft\nReflect as Reflect;

class App
{
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