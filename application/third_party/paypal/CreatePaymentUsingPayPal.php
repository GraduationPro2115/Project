<?php

// # Create Payment using PayPal as payment method
// This sample code demonstrates how you can process a 
// PayPal Account based Payment.
// API used: /v1/payments/payment

require __DIR__ . '/bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

// ### Payer
// A resource representing a Payer that funds a payment
// For paypal account payments, set payment method
// to 'paypal'.
$payer = new Payer();
$payer->setPaymentMethod("paypal");

// ### Itemized information
// (Optional) Lets you specify item wise
// information
//$q = $this->db->query("Select * from bus_packages where id = ".$package_id." limit 1");

$item1 = new Item();
$item1->setName("Test")
    ->setCurrency("USD")
    ->setQuantity(1)
    ->setSku("0012") // Similar to `item_number` in Classic API
    ->setPrice("1");

$itemList = new ItemList();
$itemList->setItems(array($item1));

// ### Additional payment details
// Use this optional field to set additional
// payment information such as tax, shipping
// charges etc.
$details = new Details();
$details->setShipping(0.0)
    ->setTax(0.0)
    ->setSubtotal("1");

// ### Amount
// Lets you specify a payment amount.
// You can also specify additional details
// such as shipping, tax.
$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal("1")
    ->setDetails($details);

// ### Transaction
// A transaction defines the contract of a
// payment - what is the payment for and who
// is fulfilling it. 
$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription("Payment description")
    ->setInvoiceNumber(uniqid());

// ### Redirect urls
// Set the urls that the buyer must be redirected to after 
// payment approval/ cancellation.
$baseUrl = "http://localhost/paypal/tes/";
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("$baseUrl/account/execurepayment?success=true")
    ->setCancelUrl("$baseUrl/account/execurepayment?success=false");

// ### Payment
// A Payment Resource; create one using
// the above types and intent set to 'sale'
$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));


// For Sample Purposes Only.
$request = clone $payment;

// ### Create Payment
// Create a payment by calling the 'create' method
// passing it a valid apiContext.
// (See bootstrap.php for more on `ApiContext`)
// The return object contains the state and the
// url to which the buyer must be redirected to
// for payment approval
try {
    $payment->create($apiContext);
} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 	//ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
    exit(1);
}

// ### Get redirect url
// The API response provides the url that you must redirect
// the buyer to. Retrieve the url from the $payment->getApprovalLink()
// method
$approvalUrl = $payment->getApprovalLink();
if($approvalUrl != ""){
    echo "<script>window.location = '".$approvalUrl."'</script>";
}
//redirect($approvalUrl);
// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//echo "Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>".  $request. $payment;

//return $payment;
?>

