<?php 

// DISCLAIMER:
//     This code is distributed in the hope that it will be useful, but without any warranty; 
//     without even the implied warranty of merchantability or fitness for a particular purpose.

// Main Interfaces:
//
// function InsertFP ($loginid, $x_tran_key, $amount, $sequence) - Insert HTML form elements required for SIM
// function CalculateFP ($loginid, $x_tran_key, $amount, $sequence, $tstamp) - Returns Fingerprint.


// compute HMAC-MD5
// Uses PHP mhash extension. Pl sure to enable the extension
function hmac ($key, $data){
	if(function_exists('mhash')) {
		return (bin2hex (mhash(MHASH_MD5, $data, $key)));
	} else {
		$algo = "md5";
		/* md5 and sha1 only */
		$algo=strtolower($algo);
		$p=array('md5'=>'H32','sha1'=>'H40');
		if(strlen($key)>64) $key=pack($p[$algo],$algo($key));
		if(strlen($key)<64) $key=str_pad($key,64,chr(0));

		$ipad=substr($key,0,64) ^ str_repeat(chr(0x36),64);
		$opad=substr($key,0,64) ^ str_repeat(chr(0x5C),64);

		return($algo($opad.pack($p[$algo],$algo($ipad.$data))));
	}
}

// Calculate and return fingerprint
// Use when you need control on the HTML output
function CalculateFP ($loginid, $x_tran_key, $amount, $sequence, $tstamp, $currency = "")
{
return (hmac ($x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
}


// Inserts the hidden variables in the HTML FORM required for SIM
// Invokes hmac function to calculate fingerprint.

function InsertFP ($loginid, $x_tran_key, $amount, $sequence, $currency = "")
{

$tstamp = time ();

$fingerprint = hmac ($x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

echo ('<input type="hidden" name="x_fp_sequence" value="' . $sequence . '">' );
echo ('<input type="hidden" name="x_fp_timestamp" value="' . $tstamp . '">' );
echo ('<input type="hidden" name="x_fp_hash" value="' . $fingerprint . '">' );


return (0);

}

?>

