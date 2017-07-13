<?php
/**
 * The AuthorizeNet PHP SDK. Include this file in your project.
 *
 * @package AuthorizeNet
 */
require dirname(__FILE__) . '/lib/shared/AuthorizeNetRequest.php';
require dirname(__FILE__) . '/lib/shared/AuthorizeNetTypes.php';
require dirname(__FILE__) . '/lib/shared/AuthorizeNetXMLResponse.php';
require dirname(__FILE__) . '/lib/shared/AuthorizeNetResponse.php';
require dirname(__FILE__) . '/lib/AuthorizeNetAIM.php';
require dirname(__FILE__) . '/lib/AuthorizeNetARB.php';
require dirname(__FILE__) . '/lib/AuthorizeNetCIM.php';
require dirname(__FILE__) . '/lib/AuthorizeNetSIM.php';
require dirname(__FILE__) . '/lib/AuthorizeNetDPM.php';
require dirname(__FILE__) . '/lib/AuthorizeNetTD.php';
require dirname(__FILE__) . '/lib/AuthorizeNetCP.php';
if (class_exists("SoapClient")) {
    require dirname(__FILE__) . '/lib/AuthorizeNetSOAP.php';
}
/**
 * Exception class for AuthorizeNet PHP SDK.
 *
 * @package AuthorizeNet
 */

use lib\net\authorize\api\contract\v1 as AnetAPI;
use lib\net\authorize\api\controller as AnetController;

class AuthorizeNetException extends Exception
{	
	const MERCHANT_LOGIN_ID = '6XP44snrs6q';
	const MERCHANT_TRANSACTION_KEY = '9r94EZzZq588bn4L';

	public function testAuthentication()
	{
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$merchantAuthentication->setName(self::MERCHANT_LOGIN_ID);
    	$merchantAuthentication->setTransactionKey(self::MERCHANT_TRANSACTION_KEY);
    	return $merchantAuthentication;
	}

}

// Test


// function testAuthentication()
// {
	


//     return $merchantAuthentication;
// }