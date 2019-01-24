<?php
namespace SoundConcepts\API;
/**
*	@description	Main requester for the call
*/
class Model extends \Nubersoft\API\Model
{
	private	$settings	=	[];
	private	static	$username;
	private	static	$password;
	private	static	$subdomain;
	/**
	 *	@description	Sets the connection settings
	 *	@param	$username	[string]	Your username provided by SoundConcepts
	 *	@param	$password	[string]	Your password provided by SoundConcepts
	 *	@param	$domain		[string]	Your subdomain for the API provided by SoundConcepts
	 */
	public	function __construct()
	{
		# Remove spaces
		$args	=	array_filter(array_map('trim', func_get_args()));
		# Set credentials if input, otherwise ignore
		if(!empty($args) && count($args) == 3) {
			self::$username		=	$args[0];
			self::$password		=	$args[1];
			self::$subdomain	=	$args[2];
		}
		# Assign username (required from SoundConcepts)
		$this->settings['master_username']	=	(!empty($args[0]))? $args[0] : self::$username;
		# Assign password (required from SoundConcepts)
		$this->settings['master_password']	=	(!empty($args[1]))? $args[1] : self::$password;
		# Assign subdomain (required from SoundConcepts)
		$this->settings['subdomain']		=	(!empty($args[2]))? $args[2] : self::$subdomain;
		# Stop if the none of the credentials are filled
		if(count(array_filter($this->settings)) < 3) {
			throw new \Exception("API credentials can not be left empty. Use any instance of \\Nubersoft\\API\\Model with username, password, and subdomain in the construct.");
		}
		
		return parent::__construct();
	}
	/**
	 *	@description	Creates the query request to SoundConcepts
	 *	@param	$service	[string]		Name of the service from the SoundConcept API Docs
	 *	@param	$query		[array|null]	All the key/value pairs the requesting service needs
	 *	@param	$type		[string|null]	The type of request
	 */
	public	function getData()
	{
		$args		=	func_get_args();
		$service	=	$args[0];
		$query		=	(!empty($args[1]))? $args[1] : '';
		$type		=	(!empty($args[2]))? $args[2] : 'json';
		$params		=	(!empty($query))? array_merge($this->settings,$query) : $this->settings;
		# Compiled endpoint url
		$endpoint	=	'https://'.$this->settings['subdomain'].'.mysecureoffice.com/remote/'.ltrim($service,'/').'.'.$type;
		# Send request, return array (converted from json)
		return $this->setEndpoint($endpoint)
			->sendAsPost($params)
			->getResponse($type);
	}
}