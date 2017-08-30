<?php

try {

    //GET INFORMATION FROM THE CLIENT'S SYSTEM TO BUILD THE XML
    $secretWord = $_POST['secretWord'];

    $profileID = $_POST['profileID']; //The ID of the profile used to identify the merchant using the service (REQUIRED)
    $amount = $_POST['amount']; //the amount of the transaction (REQUIRED)
    $currency = $_POST['currency']; // the currency code of the transaction - 3 digit ISO code (REQUIRED)
    $language = $_POST['language']; //The language displayed in the checkout page. - 2 char code(REQUIRED)
    $orderReference = $_POST['orderReference']; //the order reference for the transaction - depends on the merchants order table (REQUIRED)
 

    $udf1 = $_POST['udf1']; //Use for passing extra data (REQUIRED)
    $udf2 = $_POST['udf2']; //Use for passing extra data (REQUIRED)
    $udf3 = $_POST['udf3']; //Use for passing extra data (REQUIRED)
    $redirectionURL = $_POST['redirectionURL']; //The URL of the successful page (message page)(REQUIRED)
    $failedRedirectionURL = 
    $actionType = $_POST['actionType']; // The transaction Type ID (ex: 1 = Purchase)(REQUIRED)


   if (isset($_POST['hasCSSTemplate'])) {
        $hasCSSTemplate = $_POST['hasCSSTemplate'];
    } //flag - if TRUE will add the CSSTemplate node to the xml
    if (isset($_POST['CSSTemplate'])) {
        $CSSTemplate = $_POST['CSSTemplate'];
    } // CSSTemaplte filename on APCO system for formating of payment form


    if (isset($_POST['hasMobile'])) {
        $hasMobile = $_POST['hasMobile'];
    } //flag - if TRUE will add the MOBILE node to the xml
    if (isset($_POST['mobile'])) {
        $mobile = $_POST['mobile'];
    } // to store the mobile number of the user processing the transaction

    if (isset($_POST['hasEmail'])) {
        $hasEmail = $_POST['hasEmail'];
    }//flag - if TRUE will add the EMAIL node to the xml
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } // to store the email address of the user processing the transaction

    if (isset($_POST['hasAddress'])) {
        $hasAddress = $_POST['hasAddress'];
    }//flag - if TRUE will add the ADDRESS node to the xml
    if (isset($_POST['address'])) {
        $address = $_POST['address'];
    }//to store the postal address of the user processing the transaction

    if (isset($_POST['hasCountry'])) {
        $hasCountry = $_POST['hasCountry'];
    }//flag - if TRUE will add the COUNTRY node to the xml
    if (isset($_POST['country'])) {
        $country = $_POST['country'];
    }//the client's 3 character country ISO code

    if (isset($_POST['hasPassport'])) {
        $hasPassport = $_POST['hasPassport'];
    }//flag - if TRUE will add the PASSPORTNO node to the xml
    if (isset($_POST['passport'])) {
        $passport = $_POST['passport'];
    }//The client`s passport number

    if (isset($_POST['hasDrivingLicence'])) {
        $hasDrivingLicence = $_POST['hasDrivingLicence'];
    }//flag - if TRUE will add the DRIVINGLIC node to the xml
    if (isset($_POST['drivingLicence'])) {
        $drivingLicence = $_POST['drivingLicence'];
    }//The client`s driving license number

    if (isset($_POST['hasPspID'])) {
        $hasPspID = $_POST['hasPspID'];
    }//flag - if TRUE will add the PSPID node to the xml
    if (isset($_POST['pspID'])) {
        $pspID = $_POST['pspID'];
    }//The Transaction ID to perform an action upon. This is required in the Case were follow up transactions will/may occur

    if (isset($_POST['hasClientAccount'])) {
        $hasClientAccount = $_POST['hasClientAccount'];
    }//flag - if TRUE will add the CLIENTACC node to the xml
    if (isset($_POST['clientAccount'])) {
        $clientAccount = $_POST['clientAccount'];
    }//The client account reference. This tag is required if ‘CardRestrict’ is present

    if (isset($_POST['hasStatusURL'])) {
        $hasStatusURL = $_POST['hasStatusURL'];
    }//flag - if TRUE will add the STATUS_URL node to the xml
    if (isset($_POST['statusURL'])) {
        $statusURL = $_POST['statusURL'];
    }//URL to which the transaction details will be posted after the payment process is completed, even for transactions not processed in real time. This is posted before the redirect_url. This post does not redirect the user. This is simply a listener.

    if (isset($_POST['hasExtendedError'])) {
        $hasExtendedError = $_POST['hasExtendedError'];
    }//flag - if TRUE will add the EXTENDEDERR node to the xml
    if (isset($_POST['extendedError'])) {
        $extendedError = $_POST['extendedError'];
    }//

    if (isset($_POST['hasForcePayment'])) {
        $hasForcePayment = $_POST['hasForcePayment'];
    }//flag - if TRUE will add the FORCEPAYMENT node to the xml
    if (isset($_POST['forcePayment'])) {
        $forcePayment = $_POST['forcePayment'];
    }//Redirect the user automatically to the specified payment method 

    if (isset($_POST['hasStatementTicketNumber'])) {
        $hasStatementTicketNumber = $_POST['hasStatementTicketNumber'];
    }//flag - if TRUE will add the StatementTicketNo node to the xml
    if (isset($_POST['statementTicketNumber'])) {
        $statementTicketNumber = $_POST['statementTicketNumber'];
    }//A transaction ticket number would be added to the cardholder’s bank statement in the narrative

    if (isset($_POST['hasUniqueID'])) {
        $hasUniqueID = $_POST['hasUniqueID'];
    }//flag - if TRUE will add the UID node to the xml
    if (isset($_POST['uniqueID'])) {
        $uniqueID = $_POST['uniqueID'];
    }//The user will not be asked to retry the transaction after the first time it was unsuccessful

    if (isset($_POST['hasRpin'])) {
        $hasRpin = $_POST['hasRpin'];
    }//flag - if TRUE will add the RPIN node to the xml
    if (isset($_POST['rpin'])) {
        $rpin = $_POST['rpin'];
    }//The user will not be asked to retry the transaction after the first time it was unsuccessful

    if (isset($_POST['hasRegName'])) {
        $hasRegName = $_POST['hasRegName'];
    }//flag - if TRUE will add the UID node to the xml
    if (isset($_POST['regName'])) {
        $regName = $_POST['regName'];
    }//The user will not be asked to retry the transaction after the first time it was unsuccessful

    if (isset($_POST['hasValCardLength'])) {
        $hasValCardLength = $_POST['hasValCardLength'];
    }//flag - if TRUE will add the VALCARDLEN node to the xml. Specifies if the card length entered by the customer should be validated
    if (isset($_POST['hasValCardMod10'])) {
        $hasValCardMod10 = $_POST['hasValCardMod10'];
    }//flag - if TRUE will add the VALCARDMOD10 node to the xml. Specifies if the card should be validated by Mod10
    if (isset($_POST['hasReturnPspID'])) {
        $hasReturnPspID = $_POST['hasReturnPspID'];
    }//flag - if TRUE will add the RETURN_PSPID node to the xml. Will return the pspID in the transaction result
    if (isset($_POST['hasHideSslLogo'])) {
        $hasHideSslLogo = $_POST['hasHideSslLogo'];
    }//flag - if TRUE will add the HIDESSLLOGO node to the xml.  Hide the SSL Site security seal (will not be displayed)
    if (isset($_POST['hasNoRetry'])) {
        $hasNoRetry = $_POST['hasNoRetry'];
    }//flag - if TRUE will add the NORETRY node to the xml.  The user will not be asked to retry the transaction after the first time it was unsuccessful
    if (isset($_POST['hasCurencyAmount'])) {
        $hasCurencyAmount = $_POST['hasCurencyAmount'];
    }//flag - if TRUE will add the CA node to the xml.  Transaction result received will include the currency and amount processed by the bank. (Feature only available for noncredit or debit card transactions in version prior to FP4F – FP4F return the value+currency for credit ordebit card transactions too)
    if (isset($_POST['hasTestCard'])) {
        $hasTestCard = $_POST['hasTestCard'];
    }//flag - if TRUE will add the TESTCARD node to the xml.  The user will not be asked to retry the transaction after the first time it was unsuccessful
    if (isset($_POST['hasExtendedData'])) {
        $hasExtendedData = $_POST['hasExtendedData'];
    }//flag - if TRUE will add the EXTENDEDDATA node to the xml.  Return extra transaction data.
    if (isset($_POST['hasExtendedData2'])) {
        $hasExtendedData2 = $_POST['hasExtendedData2'];
    }//flag - if TRUE will add the EXTENDEDDATA2 node to the xml.  Return extra transaction data.
    if (isset($_POST['hasPostDeclined'])) {
        $hasPostDeclined = $_POST['hasPostDeclined'];
    }//flag - if TRUE will add the POSTDECLINED node to the xml.  Post transaction result even if the transaction is declined by the bank




    if (isset($_POST['hasFastPay'])) {
        $hasFastPay = $_POST['hasFastPay'];
    }//flag - if TRUE will add the FASTPAY node to the xml
    if (isset($_POST['hasListAllCards'])) {
        $hasListAllCards = $_POST['hasListAllCards'];
    }//flag - if TRUE will add the LISTALLCARDS node to the xml
    if (isset($_POST['listAllCards'])) {
        $listAllCards = $_POST['listAllCards'];
    }//If the client already has more than X ALL one credit card linked to his account, he will either be displayed with the last successful processed credit card (Last) or with a list of all successful cards (All)
    if (isset($_POST['hasCardRestrict'])) {
        $hasCardRestrict = $_POST['hasCardRestrict'];
    }//flag - if TRUE will add the CARDRESTRICT node to the xml.  This node is used if a new credit card entered by the client will be restricted only to be used by the same client
    if (isset($_POST['hasOriginURL'])) {
        $hasOriginURL = $_POST['hasOriginURL'];
    }//flag - if TRUE will add the ORIGINURL node to the xml.          
    //$originURL = $_POST['originURL'];}//This node is used for logging the URL from where FastPay was called.
    $originURL = (isset($_SERVER['HTTPS']) == 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //automatically gets the full url of this page

    if (isset($_POST['hasNewCardTry'])) {
        $hasNewCardTry = $_POST['hasNewCardTry'];
    }//flag - if TRUE will add the NEWCARD1TRY node to the xml. This option is used to enable the client to enter a new credit card  together with a list of available cards (if any) on his first attempt to process a transaction
    if (isset($_POST['hasNewCardOnFail'])) {
        $hasNewCardOnFail = $_POST['hasNewCardOnFail'];
    }//flag - if TRUE will add the NEW CARD ON FAIL node to the xml. This option is used if the client can have the option to enter a new credit card when his first attempt to process a transaction fails
    if (isset($_POST['hasPromptCVV'])) {
        $hasPromptCVV = $_POST['hasPromptCVV'];
    }//flag - if TRUE will add the PROMPT CVV node to the xml
    if (isset($_POST['hasPromptExpiry'])) {
        $hasPromptExpiry = $_POST['hasPromptExpiry'];
    }//flag - if TRUE will add the PROMT EXPIRY node to the xml

    if (isset($_POST['hasAntiFraud'])) {
        $hasAntiFraud = $_POST['hasAntiFraud'];
    }//flag - if TRUE will add the ANTIFRAUD node to the xml
    if (isset($_POST['hasProvider'])) {
        $hasProvider = $_POST['hasProvider'];
    }//flag - if TRUE will add the PROVIDER node to the xml
    if (isset($_POST['provider'])) {
        $provider = $_POST['provider'];
    }// 
    if (isset($_POST['hasPreCheck'])) {
        $hasPreCheck = $_POST['hasPreCheck'];
    }//flag - if TRUE will add the PRECHECK node to the xml
    if (isset($_POST['hasPostCheck'])) {
        $hasPostCheck = $_POST['hasPostCheck'];
    }//flag - if TRUE will add the POSTCHECK node to the xml

    if (isset($_POST['has3dSecure'])) {
        $has3dSecure = $_POST['has3dSecure'];
    }//flag - if TRUE will add the SECURE3D node to the xml
    if (isset($_POST['hasBypass'])) {
        $hasBypass3dS = $_POST['hasBypass'];
    }//flag - if TRUE will add the Bypass3DS node to the xml
    if (isset($_POST['hasOnly3ds'])) {
        $hasOnly3ds = $_POST['hasOnly3ds'];
    }//flag - if TRUE will add the Only3DS node to the xml

    if (isset($_POST['hasFailedTransaction'])) {
        $hasFailedTransaction = $_POST['hasFailedTransaction'];
    }//flag - if TRUE will add the FailedTrans node to the xml
    if (isset($_POST['hasFailedRedirectionURL'])) {
        $hasFailedRedirectionURL = $_POST['hasFailedRedirectionURL'];
    }//flag - if TRUE will add the FailedRedirectionURL node to the xml
    if (isset($_POST['failedRedirectionURL'])) {
        $failedRedirectionURL = $_POST['failedRedirectionURL'];
    }// Redirect to a specified URL when transaction fails.
    if (isset($_POST['hasPopup'])) {
        $hasPopup = $_POST['hasPopup'];
    }//flag - if TRUE will add the Popup node to the xml. This tab should be used only for redirection URL to open up in a  window

    if (isset($_POST['hasShowInPopup'])) {
        $hasShowInPopup = $_POST['hasShowInPopup'];
    }//flag - if TRUE will add the showInPopUp node to the xml
    if (isset($_POST['hasTopBannerUrl'])) {
        $hasTopBannerUrl = $_POST['hasTopBannerUrl'];
    }//flag - if TRUE will add the topBannerURL node to the xml
    if (isset($_POST['topBannerUrl'])) {
        $topBannerUrl = $_POST['topBannerUrl'];
    }// 
    if (isset($_POST['hasBottomBannerUrl'])) {
        $hasBottomBannerUrl = $_POST['hasBottomBannerUrl'];
    }//flag - if TRUE will add the bottomBannerURL node to the xml
    if (isset($_POST['bottomBannerUrl'])) {
        $bottomBannerUrl = $_POST['bottomBannerUrl'];
    }// 
    // build the XML for the transaction
    $transactionXmlString = "<Transaction hash=\"" . $secretWord . "\">";
    $transactionXmlString .= "<ProfileID>" . $profileID . "</ProfileID>";
    $transactionXmlString .= "<Value>" . $amount . "</Value>";
    $transactionXmlString .= "<Curr>" . $currency . "</Curr>";
    $transactionXmlString .= "<Lang>" . $language . "</Lang>";
    $transactionXmlString .= "<ORef>" . $orderReference . "</ORef>";
    $transactionXmlString .= "<UDF1>" . $udf1 . "</UDF1>";
    $transactionXmlString .= "<UDF2>" . $udf2 . "</UDF2>";
    $transactionXmlString .= "<UDF3>" . $udf3 . "</UDF3>";
    $transactionXmlString .= "<RedirectionURL>" . $redirectionURL . "</RedirectionURL>";
    $transactionXmlString .= "<FailedRedirectionURL>" . $failedRedirectionURL . "</FailedRedirectionURL>";
    $transactionXmlString .= "<ActionType>" . $actionType . "</ActionType>";
    $transactionXmlString .= "<Enc>UTF8</Enc>";

    
     
   

    if (isset($hasCSSTemplate) && $hasCSSTemplate == true) {
        $transactionXmlString .= "<CSSTemplate>" . $CSSTemplate . "</CSSTemplate>";
    }

    if (isset($hasMobile) && $hasMobile == true) {
        $transactionXmlString .= "<MobileNo>" . $mobile . "</MobileNo>";
    }
    if (isset($hasEmail) && $hasEmail == true) {
        $transactionXmlString .= "<Email>" . $email . "</Email>";
    }
    if (isset($hasAddress) && $hasAddress == true) {
        $transactionXmlString .= "<Address>" . $address . "</Address>";
    }
    if (isset($hasCountry) && $hasCountry == true) {
        $transactionXmlString .= "<Country>" . $country . "</Country>";
    }
    if (isset($hasPassport) && $hasPassport == true) {
        $transactionXmlString .= "<PassportNo>" . $passport . "</PassportNo>";
    }
    if (isset($hasDrivingLicence) && $hasDrivingLicence == true) {
        $transactionXmlString .= "<DrivingLic>" . $drivingLicence . "</DrivingLic>";
    }
    if (isset($hasPspID) && $hasPspID == true) {
        $transactionXmlString .= "<PspID>" . $pspID . "</PspID>";
    }
    if (isset($hasClientAccount) && $hasClientAccount == true) {
        $transactionXmlString .= "<ClientAcc>" . $clientAccount . "</ClientAcc>";
    }
    if (isset($hasStatusURL) && $hasStatusURL == true) {
        $transactionXmlString .= "<status_url>" . $statusURL . "</status_url>";
    }
    if (isset($hasExtendedError) && $hasExtendedError == true) {
        $transactionXmlString .= "<ExtendedErr>" . $extendedError . "</ExtendedErr>";
    }
    if (isset($hasForcePayment) && $hasForcePayment == true) {
        $transactionXmlString .= "<ForcePayment>" . $forcePayment . "</ForcePayment>";
    }
    if (isset($hasStatementTicketNumber) && $hasStatementTicketNumber == true) {
        $transactionXmlString .= "<StatementTicketNo>" . $statementTicketNumber . "</StatementTicketNo>";
    }
    if (isset($hasUniqueID) && $hasUniqueID == true) {
        $transactionXmlString .= "<UID>" . $uniqueID . "</UID>";
    }
    if (isset($hasRpin) && $hasRpin == true) {
        $transactionXmlString .= "<RPIN>" . $rpin . "</RPIN>";
    }
    if (isset($hasRegName) && $hasRegName == true) {
        $transactionXmlString .= "<RegName>" . $regName . "</RegName>";
    }
    if (isset($hasValCardLength) && $hasValCardLength == true) {
        $transactionXmlString .= "<ValCardLen />";
    }
    if (isset($hasValCardMod10) && $hasValCardMod10 == true) {
        $transactionXmlString .= "<ValCardMod10 />";
    }
    if (isset($hasReturnPspID) && $hasReturnPspID == true) {
        $transactionXmlString .= "<return_pspid />";
    }
    if (isset($hasHideSslLogo) && $hasHideSslLogo == true) {
        $transactionXmlString .= "<HideSSLLogo />";
    }
    if (isset($hasNoRetry) && $hasNoRetry == true) {
        $transactionXmlString .= "<noRetry />";
    }
    if (isset($hasCurencyAmount) && $hasCurencyAmount == true) {
        $transactionXmlString .= "<CA />";
    }
    if (isset($hasTestCard) && $hasTestCard == true) {
        $transactionXmlString .= "<TESTCARD />";
    }
    if (isset($hasExtendedData) && $hasExtendedData == true) {
        $transactionXmlString .= "<ExtendedData />";
    }
    if (isset($hasExtendedData2) && $hasExtendedData2 == true) {
        $transactionXmlString .= "<ExtendedData2 />";
    }
    if (isset($hasPostDeclined) && $hasPostDeclined == true) {
        $transactionXmlString .= "<PostDeclined />";
    }


    if (isset($hasFastPay) && $hasFastPay == true) {
        $transactionXmlString .= "<FastPay>";
        if (isset($hasListAllCards) && $hasListAllCards == true) {
            $transactionXmlString .= "<ListAllCards>" . $listAllCards . "</ListAllCards>";
        }
        if (isset($hasCardRestrict) && $hasCardRestrict == true) {
            $transactionXmlString .= "<CardRestrict />";
        }
        if (isset($hasOriginURL) && $hasOriginURL == true) {
            $transactionXmlString .= "<OriginURL>" . $originURL . "</OriginURL>";
        }
        if (isset($hasNewCardTry) && $hasNewCardTry == true) {
            $transactionXmlString .= "<NewCard1Try />";
        }
        if (isset($hasNewCardOnFail) && $hasNewCardOnFail == true) {
            $transactionXmlString .= "<NewCardOnFail />";
        }
        if (isset($hasPromptCVV) && $hasPromptCVV == true) {
            $transactionXmlString .= "<PromptCVV />";
        }
        if (isset($hasPromptExpiry) && $hasPromptExpiry == true) {
            $transactionXmlString .= "<PromptExpiry />";
        }
        $transactionXmlString .= "</FastPay>";
    }

    if (isset($hasAntiFraud) && $hasAntiFraud == true) {
        $transactionXmlString .= "<AntiFraud>";
        if (isset($hasProvider) && $hasProvider == true) {
            $transactionXmlString .= "<Provider>" . $provider . "</Provider>";
        }
        if (isset($hasPreCheck) && $hasPreCheck == true) {
            $transactionXmlString .= "<PreCheck />";
        }
        if (isset($hasPostCheck) && $hasPostCheck == true) {
            $transactionXmlString .= "<PostCheck />";
        }
        $transactionXmlString .= "</AntiFraud>";
    }

    if (isset($has3dSecure) && $has3dSecure == true) {
        $transactionXmlString .= "<Secure3D>";
        if (isset($hasBypass3dS) && $hasBypass3dS == true) {
            $transactionXmlString .= "<Bypass3DS />";
        }
        if (isset($hasOnly3ds) && $hasOnly3ds == true) {
            $transactionXmlString .= "<Only3DS />";
        }
        $transactionXmlString .= "</Secure3D>";
    }

    if (isset($hasFailedTransaction) && $hasFailedTransaction == true) {
        $transactionXmlString .= "<FailedTrans>";
        if (isset($hasFailedRedirectionURL) && $hasFailedRedirectionURL == true) {
            $transactionXmlString .= "<FailedRedirectionURL>" . $failedRedirectionURL . "</FailedRedirectionURL>";
        }
        if (isset($hasPopup) && $hasPopup == true) {
            $transactionXmlString .= "<Popup />";
        }
        $transactionXmlString .= "</FailedTrans>";
    }


    if (isset($hasShowInPopup) && $hasShowInPopup == true) {
        $transactionXmlString .= "<showInPopUp>";
        if (isset($hasTopBannerUrl) && $hasTopBannerUrl == true) {
            $transactionXmlString .= "<topBannerURL>" . $topBannerUrl . "</topBannerURL>";
        }
        if (isset($hasBottomBannerUrl) && $hasBottomBannerUrl == true) {
            $transactionXmlString .= "<bottomBannerURL>" . $bottomBannerUrl . "</bottomBannerURL>";
        }+
        $transactionXmlString .= "</showInPopUp>";
    }

    $transactionXmlString .= "</Transaction>";
  /* Call Ws to update the hash value */
  
  
  	//Output XML
      	//echo "<pre>" . htmlentities($transactionXmlString) . "</pre>"; 
     	//die;


  
  $transactionXmlString = updateXmlHash($transactionXmlString);




} catch (Exception $ex) {
    echo "<br/><strong>Message: </strong>" . $ex->getMessage();
    echo "<br/><strong>Trace: </strong>" . $ex->getTraceAsString();
}

/**
* Recieves the Transaction XML and calls to ToolKit to set the Hash Encryption
* @param type $transactionXmlString Original XML
* @return \DOMDocument Final XML with hash updated
* @throws Exception
*/
function updateXmlHash($transactionXmlString) {
  try {
    /* CONNECT WITH THE TOOL AND RETRIEVE THE LAST TRANSACTION */
    $client = new SoapClient($GLOBALS['wsdl'], array("trace" => 0, "exception" => 0));
    $soapResult = $client->ComputeHash(array(
      "MerchID" => $GLOBALS['merchantCode'], "MerchPass" => $GLOBALS['merchantPassword'], "XML" => $transactionXmlString));
    /* Retrieve the update XML (String) - hash updated */
    $xmlToolResponse = $soapResult->ComputeHashResult;
    /* Return Result */
    return $xmlToolResponse;
  } catch (Exception $ex) {
    throw $ex;
  }
}
?>