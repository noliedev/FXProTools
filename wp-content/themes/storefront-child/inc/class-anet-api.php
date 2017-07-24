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

		// Get user information - profile and payment information
		public function get_user_info($profile_id)
		{
			$merchantAuthentication = $this->anet_authentication(self::AUTHORIZENET_API_LOGIN_ID, self::AUTHORIZENET_TRANSACTION_KEY);

			$request = new AnetAPI\GetCustomerProfileRequest();
			$request->setMerchantAuthentication($merchantAuthentication);
			$request->setCustomerProfileId($profile_id);
			$controller = new AnetController\GetCustomerProfileController($request);
			$anetResponse = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
			
			if ( ($anetResponse != null) && ($anetResponse->getMessages()->getResultCode() == "Ok") ) {
				$basicInfo   = $anetResponse->getProfile();
				$paymentInfo = $basicInfo->getPaymentProfiles();
				
				$info['profile'] = array(
					'customer_id'  => $basicInfo->getCustomerProfileId(),
					'merchant_id'  => $basicInfo->getmerchantCustomerId(),
					'description'  => $basicInfo->getDescription(),
					'email'        => $basicInfo->getEmail(),
				);

				$info['payment_profile'] = array(
					'customer_profile_id'         => $paymentInfo[0]->getCustomerProfileId(),
					'customer_payment_profile_id' => $paymentInfo[0]->getCustomerPaymentProfileId(),
					'defaul_payment_profile'      => $paymentInfo[0]->getDefaultPaymentProfile(),
					'payment' => array(
						'credit_card' => array(
							'card_number'     => $paymentInfo[0]->getPayment()->getCreditCard()->getCardNumber(),
							'expiration_date' => $paymentInfo[0]->getPayment()->getCreditCard()->getExpirationDate(),
							'card_type'       => $paymentInfo[0]->getPayment()->getCreditCard()->getCardType(),
							'card_art'        => $paymentInfo[0]->getPayment()->getCreditCard()->getCardArt()
						),
					),
					'drivers_license'  => $paymentInfo[0]->getDriversLicense(),
					'tax_id'           => $paymentInfo[0]->getTaxId(),
					'subscription_ids' => '', // Leave it blank for a while
					'customer_type'    => $paymentInfo[0]->getCustomerType(),
					'billing_info'     => array(
						'phone_number' => $paymentInfo[0]->getBillTo()->getPhoneNumber(),
						'fax_number'   => $paymentInfo[0]->getBillTo()->getFaxNumber(),
						'email'        => $paymentInfo[0]->getBillTo()->getEmail(),
						'first_name'   => $paymentInfo[0]->getBillTo()->getFirstName(),
						'last_name'    => $paymentInfo[0]->getBillTo()->getLastName(),
						'company'      => $paymentInfo[0]->getBillTo()->getCompany(),
						'address'      => $paymentInfo[0]->getBillTo()->getAddress(),
						'city'         => $paymentInfo[0]->getBillTo()->getCity(),
						'state'        => $paymentInfo[0]->getBillTo()->getState(),
						'zip'          => $paymentInfo[0]->getBillTo()->getZip(),
						'country'      => $paymentInfo[0]->getBillTo()->getCountry(),
					),
				);
	
				array_push($info['profile'], $info['payment_profile']);
				
				$response['info']   = $info['profile'];
				$response['status'] = $anetResponse->getMessages()->getResultCode();
			} else {
				$response['status'] = $anetResponse->getMessages()->getResultCode();
			}

			return $response;
		}

		// Get shipping information
		public function get_shipping_info($profile_id)
		{
			$merchantAuthentication = $this->anet_authentication(self::AUTHORIZENET_API_LOGIN_ID, self::AUTHORIZENET_TRANSACTION_KEY);
			$request = new AnetAPI\GetCustomerShippingAddressRequest();
		  	$request->setMerchantAuthentication($merchantAuthentication);
		  	$request->setCustomerProfileId($profile_id);
		  	
		  	$controller   = new AnetController\GetCustomerShippingAddressController($request);
		  	$anetResponse = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

		  	if ( ($anetResponse != null) && ($anetResponse->getMessages()->getResultCode() == "Ok") ) {
		  		$response['shipping_info'] = array(
					'first_name' 	      => $anetResponse->getAddress()->getFirstName(),
					'last_name' 	      => $anetResponse->getAddress()->getLastName(),
					'company' 	          => $anetResponse->getAddress()->getCompany(),
					'address' 	          => $anetResponse->getAddress()->getAddress(),
					'city' 		          => $anetResponse->getAddress()->getCity(),
					'state' 		      => $anetResponse->getAddress()->getState(),
					'zip' 		          => $anetResponse->getAddress()->getZip(),
					'country' 	          => $anetResponse->getAddress()->getCountry(),
					'phone_number' 	      => $anetResponse->getAddress()->getPhoneNumber(),
					'fax_number' 	      => $anetResponse->getAddress()->getFaxNumber(),
					'customer_address_id' => $anetResponse->getAddress()->getCustomerAddressId(),
		  		);
		  		$response['status'] = $anetResponse->getMessages()->getResultCode();
		  	} else {
				$response['status'] = $anetResponse->getMessages()->getResultCode();
			}

			return $response;
		}

		// Get payment information
		public function get_payment_info($profile_id)
		{

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