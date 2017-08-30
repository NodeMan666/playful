<?php
ini_set("log_errors", "on");
ini_set("error_log", "errors.txt");
//php file containing the merchant settings and important xmls
include_once 'includes/configs.php';
//php file that assembles the XML to be given to the checkout page
include_once 'includes/generateXML.php';

?>
<pre><?php // print_r($_POST); ?></pre>


<html>
    <head><title>Checkout</title></head>

    <p>Loading checkout page!!</p>

    <!-- TEMPORARY FORM THAT SUBMITS THE XML (onLoad) TO FastPay -->
<body onload="document.form1.submit()" >

        <form name="form1" method="post" action="<?php echo $FastPayURL; ?>">
            <input name="params" type="hidden" value="<?php echo urlencode(trim($transactionXmlString)); ?>">
        </form>
    </body>
</html>
