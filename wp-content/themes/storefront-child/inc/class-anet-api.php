<?php
/**
 * -----------------------
 * Authorize.net Functions
 * -----------------------
 * Authorize.net API functions
 */
require  $_SERVER['DOCUMENT_ROOT'] . '/fxprotools/vendor/autoload.php';
	
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('AuthAPI')){

	class AuthAPI {
	
		const AUTHORIZENET_API_LOGIN_ID = '6XP44snrs6q';
		const AUTHORIZENET_TRANSACTION_KEY = '9r94EZzZq588bn4L';

		// public function get_auth_users()
		// {
		//     $merchantAuthentication = anet_authentication(AUTHORIZENET_API_LOGIN_ID, AUTHORIZENET_TRANSACTION_KEY);
		    
		//     // Set the transaction's refId
		//     $refId = 'ref' . time();

		//     // Get all existing customer profile ID's
		//     $request = new AnetAPI\GetCustomerProfileIdsRequest();
		//     $request->setMerchantAuthentication($merchantAuthentication);
		//     $controller = new AnetController\GetCustomerProfileIdsController($request);
		//     $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

		//     if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
		//         $res['profile_ids'] = $response->getIds();
		        
		//         $profiles = array();
		//         foreach ($res['profile_ids'] as $key => $id) {
		//             // $request = new AnetAPI\GetCustomerProfileRequest();
		//             // $request->setMerchantAuthentication($merchantAuthentication);
		//             // $request->setCustomerProfileId($id);
		//             // $controller = new AnetController\GetCustomerProfileController($request);
		//             // $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
		            
		//             // if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ){
		//             //     $profiles[$id] = $response->getProfile();
		//             // }
		//         }

		//         $res['status'] = $response->getMessages()->getResultCode();
		//     } else {
		//         $res['status'] = $response->getMessages()->getResultCode();
		//     }


		//     dd($profiles);

		// }

		public function get_auth_user_profile($profile_id)
		{
			$merchantAuthentication = $this->anet_authentication(self::AUTHORIZENET_API_LOGIN_ID, self::AUTHORIZENET_TRANSACTION_KEY);

			$request = new AnetAPI\GetCustomerProfileRequest();
			$request->setMerchantAuthentication($merchantAuthentication);
			$request->setCustomerProfileId($profile_id);
			$controller = new AnetController\GetCustomerProfileController($request);
			$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
			
			if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ){
				$selectedProfile        = $response->getProfile();
				$res['profile']         = $selectedProfile;
				$res['payment_profile'] = $selectedProfile->getPaymentProfiles();
				$res['status']          = $response->getMessages()->getResultCode();
			} else {
				$res['status'] = $response->getMessages()->getResultCode();
			}

			return $res;
		}

		// Authentication
		public function anet_authentication($login_id, $transaction_key)
		{
			$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
			$merchantAuthentication->setName($login_id);
			$merchantAuthentication->setTransactionKey($transaction_key);
			return $merchantAuthentication;
		}

	}

}

return new AuthAPI();