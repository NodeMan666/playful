<?php
//php file containing the merchant settings and important xmls
session_start();
header("Cache-control: private"); 
header('Content-Type: text/html; charset=utf-8');
include_once 'apco/includes/configs.php';

$url = "http://".$_SERVER['HTTP_HOST']."";


error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
* Collects the response from FastPay and converts to DomDocument(XML)
* @throws Exception
*/
function getParamsAndConvertToXmlDocument() {
  try {
    /* GET THE XML RESULT FROM THE FASTPAY */
    $paramValue = "";
    if (isset($_GET['params'])) {
      /* found in GET */
      $paramValue = urldecode($_GET['params']);
    } else {
      /* not found - throw exception */
      throw new Exception("Could not retrieve the result XML from both the GET!!!!");
    }
    /* convert the XML given from FastPay TO DomDocument XmlObject */
    $domXML = new DOMDocument();
    $domXML->loadXML($paramValue);
    /* return xml params object */
    return $domXML;
  } catch (Exception $ex) {
    /* error occured, throw exception */
    throw $ex;
  }
}

/**
* Compare the XmlResponse from FastPay with the Transaction Information to make sure that it matches
* @param type $xmlResponse the response given from the FastPay in DomDocument Format
* @throws Exception
*/
function validateResponseWithTool($FastPayXml) {
  try {
    /* get transaction information */
    $nodeORef = $FastPayXml->getElementsByTagName('ORef');
    $ORef = $nodeORef->item(0)->textContent;
    $nodeResult = $FastPayXml->getElementsByTagName('Result');
    $Result = $nodeResult->item(0)->textContent;
    /* connect with the tool and retrieve the last transaction */
    $client = new SoapClient($GLOBALS['wsdl'], array("trace" => 0, "exception" => 0));
    $soapResult = $client->getTransactionStatus(array(
      "MerchID" => $GLOBALS['merchantCode'], "MerchPass" => $GLOBALS['merchantPassword'], "ORef" => $ORef));
    /* it is intended to return a dataset (.net) but eventully it is an xml */
    $tempResult = $soapResult->getTransactionStatusResult->any;
    if (strlen($tempResult) > 0) {
      $fullWsResult = new DOMDocument();
      $fullWsResult->loadXML($tempResult);
      if ($fullWsResult->getElementsByTagName("Response")->length > 0) {
        /* convert WS response to XML document */
        $WsResponseXml = new DOMDocument();
        $WsResponseXml->loadXML(urldecode($fullWsResult->getElementsByTagName("Response")->item(0)->textContent));
        if (trim($ORef) !== trim($WsResponseXml->getElementsByTagName("ORef")->item(0)->textContent)) {
          //ORef does not match
          throw new Exception("ORef does not match.");
        }
      } else {
        /* No Response found in the WS result */
        throw new Exception("No Response found in the WS result.");
      }
    } else {
      /* No Results from ws */
      throw new Exception("No Results from ws.");
    }
    /* everything matches */
    return $Result;
  } catch (Exception $ex) {
    /* error occured, throw exception */
    throw $ex;
  }
}


try {
  /* retrieve the fastpay response DomDocument */
  $FastPayURL = getParamsAndConvertToXmlDocument();
  /* validate XML */
  $resultValue = validateResponseWithTool($FastPayURL);
  /* check if result returned */
  
  // Now get order session and timestamp
	$OrderDetails= "";
	$node = $FastPayURL ->getElementsByTagName('ORef');
	foreach ($node as $node) {
		$OrderDetails = $node->textContent;
	}
	
	$result = explode ( "-", $OrderDetails);
	$msos = $result[0]; // order session
	$msok = $result[1]; // order timestamp
	
  if (strtoupper(strlen($resultValue)) > 0) {
    /* result returned */
    switch (trim($resultValue)) {
      case "OK":
      //DO something: NOTOK -> The transaction was successful
      $redirectURL =  $url . "?view=order&frompp=1&msos=" . $msos . "&msok=" . $msok;
      header("Location: " . $redirectURL);
      // echo "OK messge - " . $redirectURL;
      break;
      case "NOTOK":
      //DO something: NOTOK -> The transaction was not successful
      echo("NOTOK messge");
      break;
      case "DECLINED":
      //DO something: DECLINED -> The transaction was not successful
      $redirectURL =  $url . "/?view=checkout&status=declined";
      
      
      /******************************************************
      
      - Need to set decline message - not sure if OK message is requried.
      
      - Need to handle other responses - PENDING, CANCEL, default, NOTOK - just in case.
      
      - Need to wrap APCO payment form into website
      
      ********************************************************/

      header("Location: " . $redirectURL);
      //echo("DECLINED messge");
      break;
      case "PENDING":
      //DO something: PENDING -> The transaction is still pending
      echo("PENDING messge");
      break;
      case "CANCEL":
      $redirectURL =  $url . "/?view=checkout";
      header("Location: " . $redirectURL);


      //DO something: CANCEL -> The transaction is cancelled
      echo "CANCEL messge";
      break;
      default:
      ECHO "other";
      $redirectURL =  $url . "/?view=checkout";
      header("Location: " . $redirectURL);

      //DO something: OTHER RESULT
      break;
    }
  }
} catch (Exception $ex) {
  echo "<br/><strong>Message: </strong>" . $ex->getMessage();
  echo "<br/><strong>Trace: </strong>" . $ex->getTraceAsString();
}


die();
?>