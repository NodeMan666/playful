 <?php $payinfo = doSQL("ms_payment_options", "*", "WHERE pay_option='square' "); ?>

  <script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>
  <script type="text/javascript">
    var sqPaymentForm = new SqPaymentForm({

      // Replace this value with your application's ID (available from the merchant dashboard).
      // If you're just testing things out, replace this with your _Sandbox_ application ID,
      // which is also available there.
      applicationId: '<?php print $payinfo['pay_num'];?>',
      inputClass: 'sq-input',
      cardNumber: {
        elementId: 'sq-card-number',
        placeholder: "0000 0000 0000 0000"
      },
      cvv: {
        elementId: 'sq-cvv',
        placeholder: 'CVV'
      },
      expirationDate: {
        elementId: 'sq-expiration-date',
        placeholder: 'MM/YY'
      },
      postalCode: {
        elementId: 'sq-postal-code',
        placeholder: 'Postal Code'
      },
      inputStyles: [

        // Because this object provides no value for mediaMaxWidth or mediaMinWidth,
        // these styles apply for screens of all sizes, unless overridden by another
        // input style below.
        {
          fontSize: '17px',
          padding: '8px',
			backgroundColor: '#<?php print $css['form_bg'];?>',
			  color: '#<?php print $css['form_color'];?>'
		  },

        // These styles are applied to inputs ONLY when the screen width is 400px
        // or smaller. Note that because it doesn't specify a value for padding,
        // the padding value in the previous object is preserved.
        {
          mediaMaxWidth: '400px',
          fontSize: '18px',
        }
      ],
      callbacks: {
        cardNonceResponseReceived: function(errors, nonce, cardData) {
          if (errors) {
            var errorDiv = document.getElementById('errors');
            errorDiv.innerHTML = "";
            errors.forEach(function(error) {
              var p = document.createElement('p');
              p.innerHTML = error.message;
              errorDiv.appendChild(p);
            });

          } else {
            // This alert is for debugging purposes only.
            // alert('Nonce received! ' + nonce + ' ' + JSON.stringify(cardData));

            // Assign the value of the nonce to a hidden form element
            var nonceField = document.getElementById('card-nonce');
            nonceField.value = nonce;
			$("#card-nonce-submit").hide().attr("disabled",true);
			$("#squaresubmit").hide();
			$("#cardSubmitLoading-square").show();

            // Submit the form
            document.getElementById('checkout').submit();

          }
        },
        unsupportedBrowserDetected: function() {
          // Alert the buyer that their browser is not supported
        },

		paymentFormLoaded: function() {
		 // sqPaymentForm.setPostalCode('94103');
		}

      }
    });
    function submitButtonClick(event) {
      event.preventDefault();
      sqPaymentForm.requestCardNonce();
    }
  </script>
  <style type="text/css">
    .sq-input {
      border: 1px solid #<?php print $css['form_border'];?>;
      margin-bottom: 10px;
      padding: 1px;
    }
    .sq-input--focus {
      outline-width: 5px;
      outline-color: #70ACE9;
      outline-offset: -1px;
      outline-style: auto;
    }
    .sq-input--error {
      outline-width: 5px;
      outline-color: #990000;
      outline-offset: 0px;
      outline-style: auto;
    }
	#square { max-width: 400px; margin-top: 16px; } 
	#square iframe { height: 34px; } 
  </style>
</head>
<body>
<script>
function addPostalCodeSquare() { 
	// sqPaymentForm.setPostalCode('94103');
}
$(document).ready(function(){
  });
</script>

<div id="square"  class="payoption" <?php if($default !== "square") { ?>style="display: none;"<?php } ?>>
<?php if(!empty($payopt['pay_description'])) { ?>
	<div class="pc" style="margin: 12px 0px;"><?php print nl2br($payopt['pay_description']);?></div>
	<?php } ?>
	<?php if($order['order_id'] > 0) { 
		include $setup['path']."/sy-inc/store/payment/payment.add.name.address.php"; 
	}
	?>
  <!-- <form id="form" novalidate action="<?php print $setup['temp_url_folder'];?>/sy-inc/store/payment/square/payment.php" method="post"> -->
    <div class="pc"><?php print _checkout_credit_cart_number_;?></div>
    <div id="sq-card-number" class="pc"></div>
    <div class="pc"><?php print _checkout_cvv_;?></div>
    <div id="sq-cvv" class="pc"></div>
    <div class="pc"><?php print _checkout_expiration_date_;?> (MM/YY)</div>
    <div id="sq-expiration-date" class="pc"></div>
    <div class="pc"><?php print _zip_;?></div>
    <div id="sq-postal-code" class="pc"></div>
    <input type="hidden" id="card-nonce" name="nonce">
  <div id="errors"></div>

	  <div id="squaresubmit">
    <input type="submit" onclick="submitButtonClick(event)" id="card-nonce-submit" value="<?php print $payopt['pay_text'];?>" class="checkout">
  <!-- </form> -->
	<?php if($paying_invoice == true) { ?>
	<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
	<?php } else { ?>
	<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
	<?php } ?>

	</div>


	<div class="pc" id="cardSubmitLoading-square" style="display: none; height: 50px; ">
		<div class="loadingspinnersmall"></div>
	</div>
	<div>&nbsp;</div>

	<?php
	if (strpos($payopt['pay_num'],'sandbox') !== false) {
	?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div>
	<?php } ?>

</div>