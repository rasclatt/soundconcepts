<?php
namespace SoundConcepts\Credits;

use \Nubersoft\ArrayWorks;

class Controller extends \SoundConcepts\Credits
{
	/**
	 *	@description	Quick call to retrieve all the credit progams
	 */
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