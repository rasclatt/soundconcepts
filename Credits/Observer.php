<?php
namespace SoundConcepts\Credits;
/**
 *	@description	
 */
class Observer extends \SoundConcepts\Credits implements \Nubersoft\nObserver
{
	/**
	 *	@description	
	 */
	public	function listen()
	{
		$nApp	=	\Nubersoft\nApp::call();
		$POST	=	array_filter($nApp->getPost());
		
		switch($POST['action']) {
			case('sc_create_user_account'):
				if(!$nApp->isLoggedIn())
					return false;
				
				$password		=	(!empty($POST['password']))? $nApp->dec(trim($POST['password'])) : false;
				$SoundConcepts	=	new \SoundConcepts\User\Controller();
				$gsUser			=	(new \Greystar\Model())->doService('verifylogin', ['distid' => $nApp->userGet('distid'), 'pass' => $password ]);
				
				if(!$password) {
					$this->toError("Invalid Back Office password.");
					return false;
				}
				
				//Well123beyond#@!
				$data['distributor_id']		=	$nApp->userGet('distid');
				$data['email']				=	$nApp->userGet('email');
				$data['password']			=	$password;
				$data['username']			=	$nApp->userGet('username');
				$data['first_name']			=	$nApp->userGet('first_name');;
				$data['last_name']			=	$nApp->userGet('last_name');
				$data['country']			=	$nApp->userGet('country');
				
				if($SoundConcepts->createUser($data) == 1) {
					$nApp->toSuccess("You have successfully signed up to the Beyond App.");
				}
				else {
					$nApp->toError("An error occurred creating your user. Please contact customer support.");
				}
				return false;
		}
		
		if(!$nApp->isAdmin())
			return false;
		
		switch($POST['subaction']) {
			case('credit_manager'):
				
				if(empty($POST['distributor_id'])) {
					$this->toError('Distributor ID can not be empty.');
					return false;
				}
				
				$distid	=	$POST['distributor_id'];
				$asset	=	$POST['myship_asset_id'];
				
				$qty	=	(!empty($POST['quantity']) && is_numeric($POST['quantity']))? $POST['quantity'] : 1;
				
				if(!isset($POST['description']))
					$POST['description']	=	'iTrack Editor';
				
				$desc	=	$POST['description'];
				
				$response	=	($POST['action_type'] == 'add')? $this->addCredits($distid, $asset, $qty, $desc): $this->removeCredits($distid, $asset, $qty, $desc);
				
				$nApp->setNode('sc_api_response', ['response' => $response, 'distid' => $distid]);
		}
	}
}