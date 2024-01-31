<?php

defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . 'third_party/vendor/autoload.php';

class Payorder extends CI_Controller
{
    public $apiContext;
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $this->config->item("PAYPAL_CLIENT_ID"),     // ClientID
                $this->config->item("PAYPAL_CLIENT_SECRET")      // ClientSecret
            )
        );
        $this->apiContext->setConfig(
      array(
        'log.LogEnabled' => true,
        'log.FileName' => 'PayPal.log',
        'log.LogLevel' => 'DEBUG',
        'mode' => $this->config->item("PAYPAL_MODE"),
        
      )
);
    }
    public function paypalcancel(){
        header('Content-type: text/json');
        $order_id = $this->input->get("order_id");
        $this->db->delete("business_appointment_temp",array("id"=>$order_id));
        $this->db->delete("business_appointment_services_temp",array("busness_appointment_id"=>$order_id));
        echo json_encode(array("id"=>$order_id,"Error"=>"User cancel payment"));
    }
    public function paypal($order_id){
        // 3. Lets try to create a Payment
        // https://developer.paypal.com/docs/api/payments/#payment_create
        
        $total = $this->business_model->get_business_appointment_total_temp($order_id);
                        
                        $total_amount = $total->total_amount;
                        
                        $appointment = $this->business_model->get_business_appointment_temp_by_id($order_id);
                        
                        
                        //$total_amount = $total_amount + $business->bus_fee;
                        $invoice_id = uniqid();
                        
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');
        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($total_amount);
        $amount->setCurrency($this->config->item("CURENCY"));
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);
        $transaction->setInvoiceNumber($invoice_id);
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(site_url("payorder/paypalsuccess")."?invoice_id=".$invoice_id."&order_id=".$order_id."&total=".$total_amount)
            ->setCancelUrl(site_url("payorder/paypalcancel")."?invoice_id=".$invoice_id."&order_id=".$order_id."&total=".$total_amount);
        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);
        // 4. Make a Create Call and print the values
        try {
            $payment->create($this->apiContext);
            $approvalUrl = $payment->getApprovalLink();
            if($approvalUrl != ""){
                echo "<script>window.location = '".$approvalUrl."'</script>";
            }
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            echo $ex->getData();
        }

    }
    
    public function paypalsuccess(){
        
    header('Content-type: text/json');

    //$paymentToBeUpdated = Payment::where('payment_id', $_GET['paymentId'])->first();
    //$paymentToBeUpdated->update(['payer_id' => $_GET['PayerID']]);

        // Get the payment Object by passing paymentId
    // payment id was previously stored in session in
    // CreatePaymentUsingPayPal.php
    $paymentId = $_GET['paymentId'];
    $payment = \PayPal\Api\Payment::get($paymentId, $this->apiContext);
    // ### Payment Execute
    // PaymentExecution object includes information necessary
    // to execute a PayPal account payment.
    // The payer_id is added to the request query parameters
    // when the user is redirected from paypal back to your site
    $execution = new \PayPal\Api\PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);
    
    // ### Optional Changes to Amount
    // If you wish to update the amount that you wish to charge the customer,
    // based on the shipping address or any other reason, you could
    // do that by passing the transaction object with just `amount` field in it.
    // Here is the example on how we changed the shipping to $1 more than before.
    
    //$transaction = new \PayPal\Api\Transaction();
    //$amount = new \PayPal\Api\Amount();
    //    $amount->setTotal($this->input->get("total"));
    //    $amount->setCurrency($this->config->item("CURRENCY"));
    //$transaction->setAmount($amount);
    // Add the above transaction object inside our Execution object.
    //$execution->addTransaction($transaction);
    
    try {
        // Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        $result = $payment->execute($execution, $this->apiContext);
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printResult("Executed Payment", "Payment", $payment->getId(), $execution, $result);
        
            if ($result->getState() == 'approved') {
                //if($paymentToBeUpdated->status == 'Pending')
                //    $paymentToBeUpdated->update(['status' => 'Success']);
            }else{
                //if($paymentToBeUpdated->status == 'Pending')
                //    $paymentToBeUpdated->update(['status' => 'Failure']);
                
                echo json_encode(array("id"=>null,"Error"=>"Failed to payment please try again ","exception"=>""));
                exit(1);
            }
            
        try {
            
            $payment = \PayPal\Api\Payment::get($paymentId, $this->apiContext);
        } catch (Exception $ex) {
            echo json_encode(array("id"=>null,"Error"=>"Failed to payment please try again ".$ex->getCode().$ex->getData(),"exception"=>$ex));
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            //ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
            exit(1);
        }
    } catch (Exception $ex) {
        $error_object = json_decode($ex->getData());
        if($error_object->name == 'PAYMENT_ALREADY_DONE'){
            
        }else{
            echo json_encode(array("id"=>null,"Error"=>"Failed to payment please try again ".$ex->getCode().$ex->getData(),"exception"=>$ex));
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            //ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
            exit(1);    
        }

    }
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    //ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);
    
    
    $order_id = $this->input->get("order_id");
            if($payment->getId() != NULL && $payment->getId() != ""){
            
            $appointment = $this->business_model->get_business_appointment_temp_by_id($order_id);
            if(!empty($appointment)){            
                        $this->db->insert("business_appointment",array(
                        "user_id"=>$appointment->user_id,
                        "appointment_date"=>$appointment->appointment_date,
                        "start_time"=>$appointment->start_time,
                        "time_token"=>$appointment->time_token,
                        "app_name"=>$appointment->app_name,
                        "app_email"=>$appointment->app_email,
                        "app_phone"=>$appointment->app_phone,
                        "payment_type"=>"paypal",
                        "payment_ref"=>$payment->getId(),
                        "payment_mode"=>"instant",
                        "payment_amount"=>$this->input->get("total")));
                        $app_id = $this->db->insert_id();
                        
                            $services = $this->business_model->get_business_appointment_service_temp($appointment->id);
                            if(!empty($services)){
                                foreach($services as $serv){
                                    $this->db->insert("business_appointment_services",array("busness_appointment_id"=>$app_id,
                                            "busness_service_id"=>$serv->busness_service_id,
                                            "service_qty"=>$serv->service_qty));
                                }
                            }
                            $this->db->delete("business_appointment_temp",array("id"=>$appointment->id));
                            $this->db->delete("business_appointment_services_temp",array("busness_appointment_id"=>$appointment->id));
                        
                        $appointment = $this->db->query("Select * from business_appointment where id = '".$app_id."' limit 1");
                        
                        if($this->config->item("ALLOW_EMAIL")){
                            $email_data["appointment"] = $appointment;
                            $email_data["business"] = $this->business_model->get_businesses_details();
                    
                            $message = $this->load->view('common/emails/appointment-book',$email_data,TRUE);
                        
                                $this->load->library('email');
                                $this->email->from($appointment->app_email, $appointment->app_name);
                                $list = array($email_data["business"]->business_email, $appointment->app_email);
                                $this->email->to($list);
                                $this->email->reply_to($email_data["business"]->business_email, $this->config->item("app_name"));
                                $this->email->subject('Appointment Booked');
                                $this->email->message($message);
                                if ( ! $this->email->send()){
                                
                                }
                        }
                        
                            print($payment);
                            exit(1);    
                        
                }
            
            }
    print($payment);
    }
}