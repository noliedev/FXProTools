<?php
require 'anet_php_sdk/autoload.php'; 

define('AUTHORIZENET_API_LOGIN_ID', '6XP44snrs6q');
define('AUTHORIZENET_TRANSACTION_KEY', '9r94EZzZq588bn4L');

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

function testAuthentication()
{	

	$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(AUTHORIZENET_API_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(AUTHORIZENET_TRANSACTION_KEY);

     // Set the transaction's refId
    $refId = 'ref' . time();

    // Get all existing customer profile ID's
    $request = new AnetAPI\GetCustomerProfileIdsRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $controller = new AnetController\GetCustomerProfileIdsController($request);
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {
        echo "GetCustomerProfileId's SUCCESS: " . "\n";
        $profileIds[] = $response->getIds();
        echo "There are " . count($profileIds[0]) . " Customer Profile ID's for this Merchant Name and Transaction Key" . "\n";
     }
    else
    {
        echo "GetCustomerProfileId's ERROR :  Invalid response\n";
        $errorMessages = $response->getMessages()->getMessage();
        echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    }
    return $response;
   
}