<?php
namespace SoundConcepts;

use \Nubersoft\ArrayWorks;

class Controller extends \SoundConcepts\Model\API
{
	public	function getCreditPrograms($type = false)
	{
		$new	=	[];
		$assets	=	$this->getData('myship_assets');
		ArrayWorks::getValuesByKeyName($assets, 'asset', $new, false);
		$assets	=	ArrayWorks::organizeByKey($new, 'type', ['multi'=>1]);
		
		if(!empty($type))
			return (isset($assets[$type]))? $assets[$type] : [];
		
		return $assets;
	}
}