<?php
namespace SoundConcepts;
/**
 *	@description	
 */
class Credits extends \SoundConcepts\Model\API
{
	protected	$errors	=	[];
	private		$data;
	/**
	 *	@description	
	 */
	public	function addCredits($distid, $assetid, $qty, $description = 'iTrack Added', $exp = false)
	{
		$args	=	[
			'distributor_id' => $distid,
			'expiration' => $exp,
			'description' => $description,
			'myship_asset_id' => $assetid,
			'quantity' => $qty
		];
		
		$query	=	$this->getData('myship_allowances/add', $args);
		
		if(!isset($query['success'])) {
			$this->errors[]	=	$query['error']['message'];
			return false;
		}
		
		return true;
	}
	/**
	 *	@description	
	 */
	public	function getCreditSummary($distid)
	{
		$args	=	[
			'distributor_id' => $distid
		];
		
		$this->data	=	$this->getData('myship_allowances/summary', $args);
		return $this->data;
		
		if(!isset($query['allowances'])) {
			$this->errors[]	=	(empty($query['error']['message']))? $query : $query['error']['message'];
			return false;
		}
		
		return $query['allowances'];
	}
	
	public	function getCredits($distid)
	{
		$this->getCreditSummary($distid);
		
		if(!empty($this->data['allowances']))
			$this->data	=	$this->data['allowances'];
		
		return $this;
	}
	
	public	function removeCredits($distid, $assetid, $qty, $description = 'iTrack Removed')
	{
		$summary	=	$this->getCreditSummary($distid);
		
		$points		=	(!empty($summary['allowances']['product_specific'][$assetid]['total']))? $summary['allowances']['product_specific'][$assetid]['total'] : 0;
		
		if($points == 0)
			return 0;
		
		$args	=	[
			'distributor_id' => $distid,
			'expiration' => date('Y-m-d H:i:s', strtotime('yesterday')),
			'description' => $description,
			'myship_asset_id' => $assetid,
		];
		
		$query	=	$this->getData('myship_allowances/edit', $args);
		
		if(!isset($query['success'])) {
			$this->errors[]	=	$query['error']['message'];
			return false;
		}
		else {
			$total	=	($points - $qty);
			if($total > 0)
				$this->addCredits($distid, $assetid, $total, $description);
			
			return $total;
		}
	}
	
	public	function getErrors()
	{
		return $this->errors;
	}
	
	public	function __call($method, $args=false)
	{
		$def	=	(isset($args[0]))? $args[0] : false; 
		$method	=	strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', preg_replace('/^get/', '', $method)));
		
		if($method == 'all')
			return $this->data;
		
		return (isset($this->data[$method]))? $this->data[$method] : $def;
	}
}