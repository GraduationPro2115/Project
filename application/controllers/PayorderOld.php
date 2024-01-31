<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

class Payorder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code

    }
    public function paypal($order_id)
    {
        include APPPATH . 'third_party/paypal/bootstrap.php';

        $total = $this->business_model->get_business_appointment_total_temp($order_id);

        $total_amount = $total->total_amount;

        $appointment = $this->business_model->get_business_appointment_temp_by_id($order_id);
        $business = $this->business_model->get_business_details_by_id($appointment->bus_id);
        $total_amount = $total_amount + $business->bus_fee;
        $doctor = $this->business_model->get_businesses_doctor_by_id($appointment->doct_id);



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
        $item1->setName("Book Appointment")
            ->setCurrency($this->config->item("PAYPAL_CURRENCY"))
            ->setQuantity(1)
            ->setSku($order_id) // Similar to `item_number` in Classic API
            ->setPrice($total_amount);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new Details();
        $details->setShipping(0.0)
            ->setTax(0.0)
            ->setSubtotal($total_amount);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency($this->config->item("PAYPAL_CURRENCY"))
            ->setTotal($total_amount)
            ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $invoice_id = uniqid();
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Book " . $doctor->doct_name . " on " . $appointment->appointment_date . " at " . $appointment->start_time)
            ->setInvoiceNumber($invoice_id);

        // ### Redirect urls
        // Set the urls that the buyer must be redirected to after 
        // payment approval/ cancellation.
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(site_url("payorder/paypalsuccess") . "?invoice_id=" . $invoice_id . "&order_id=" . $order_id . "&total=" . $total_amount)
            ->setCancelUrl(site_url("payorder/paypalcancel") . "?invoice_id=" . $invoice_id . "&order_id=" . $order_id . "&total=" . $total_amount);

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
        if ($approvalUrl != "") {
            echo "<script>window.location = '" . $approvalUrl . "'</script>";
        }
        //redirect($approvalUrl);
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //echo "Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>".  $request. $payment;

        //return $payment;
    }
    function paypalsuccess()
    {
        header('Content-type: text/json');
        include APPPATH . 'third_party/paypal/bootstrap.php';

        // Get the payment Object by passing paymentId
        // payment id was previously stored in session in
        // CreatePaymentUsingPayPal.php
        $paymentId = $_GET['paymentId'];
        $payment = Payment::get($paymentId, $apiContext);
        // ### Payment Execute
        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);
        // ### Optional Changes to Amount
        // If you wish to update the amount that you wish to charge the customer,
        // based on the shipping address or any other reason, you could
        // do that by passing the transaction object with just `amount` field in it.
        // Here is the example on how we changed the shipping to $1 more than before.
        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();
        $details->setShipping(0.0)
            ->setTax(0.0)
            ->setSubtotal($this->input->get("total"));
        $amount->setCurrency($this->config->item("PAYPAL_CURRENCY"));
        $amount->setTotal($this->input->get("total"));
        $amount->setDetails($details);
        $transaction->setAmount($amount);
        // Add the above transaction object inside our Execution object.
        $execution->addTransaction($transaction);
        //try {
        // Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        $result = $payment->execute($execution, $apiContext);
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printResult("Executed Payment", "Payment", $payment->getId(), $execution, $result);
        $payment = Payment::get($paymentId, $apiContext);
        //} catch (Exception $ex) {
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
        //    echo json_encode(array("id"=>null,"Error"=>"Failed to payment please try again","exception"=>$ex));
        //    exit(1);
        //}
        $order_id = $this->input->get("order_id");
        if ($payment->getId() != NULL && $payment->getId() != "") {

            $appointment = $this->business_model->get_business_appointment_temp_by_id($order_id);
            if (!empty($appointment)) {
                $this->db->insert("business_appointment", array(
                    "bus_id" => $appointment->bus_id,
                    "doct_id" => $appointment->doct_id,
                    "user_id" => $appointment->user_id,
                    "appointment_date" => $appointment->appointment_date,
                    "start_time" => $appointment->start_time,
                    "time_token" => $appointment->time_token,
                    "app_name" => $appointment->app_name,
                    "app_email" => $appointment->app_email,
                    "app_phone" => $appointment->app_phone,
                    "payment_type" => "paypal",
                    "payment_ref" => $payment->getId(),
                    "payment_mode" => "instant",
                    "payment_amount" => $this->input->get("total")
                ));
                $app_id = $this->db->insert_id();

                $services = $this->business_model->get_business_appointment_service_temp($appointment->id);
                if (!empty($services)) {
                    foreach ($services as $serv) {
                        $this->db->insert("business_appointment_services", array(
                            "busness_appointment_id" => $app_id,
                            "busness_service_id" => $serv->busness_service_id,
                            "service_qty" => $serv->service_qty
                        ));
                    }
                }
                $this->db->delete("business_appointment_temp", array("id" => $appointment->id));
                $this->db->delete("business_appointment_services_temp", array("busness_appointment_id" => $appointment->id));

                print($payment);
                exit(1);
            }
        }





        print($payment);

        /* if(isset($app_id) && isset($appointment)){
    $email_data["appointment"] = $this->business_model->get_business_appointment_by_id($app_id);
                $email_data["doctor"] = $this->doctor_model->get_doctor_by_id($appointment->doct_id);
                $email_data["business"] = $this->business_model->get_business_details_by_id($appointment->bus_id);
                
                $message = $this->load->view('common/emails/appointment-confirm',$email_data,TRUE);
                    
                    $this->load->library('email');
                            $this->email->from($appointment->app_email, $appointment->app_name);
                            $list = array($email_data["business"]->business_email,$email_data["doctor"]->user_email);
                            $this->email->to($list);
                            $this->email->reply_to($email_data["business"]->business_email, $this->config->item("app_name"));
                            $this->email->subject('Appointment for '.$email_data["doctor"]->doct_name);
                            $this->email->message($message);
                            if ( ! $this->email->send()){
                            
                            }
    }
    */
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);
    }

    function paypalcancel()
    {
        header('Content-type: text/json');
        include APPPATH . 'third_party/paypal/bootstrap.php';

        echo json_encode(array("id" => null, "Error" => "User cancel payment"));
    }
}
