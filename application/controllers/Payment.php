<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . 'third_party/vendor/autoload.php';

class Payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function payment_success()
    {
        header('Content-type: text/json');
        echo json_encode(array("success" => true, "message" => "Payment successfully"));
    }

    public function payment_failed()
    {
        header('Content-type: text/json');
        echo json_encode(array("success" => false, "message" => "Payment failed please retry"));
    }

    public function make($order_id)
    {
        $total = $this->business_model->get_business_appointment_total_temp($order_id);
        $total_amount = $total->total_amount;
        $appointment = $this->business_model->get_business_appointment_temp_by_id($order_id);


        /* Request Hash
	----------------
	For hash calculation, you need to generate a string using certain parameters 
	and apply the sha512 algorithm on this string. Please note that you have to 
	use pipe (|) character as delimeter. 
	The parameter order is mentioned below:
	
	sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||SALT)
	
	Description of each parameter available on html page as well as in PDF.
	
	Case 1: If all the udf parameters (udf1-udf5) are posted by the merchant. Then,
	hash=sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||SALT)
	
	Case 2: If only some of the udf parameters are posted and others are not. For example, if udf2 and udf4 are posted and udf1, udf3, udf5 are not. Then,
	hash=sha512(key|txnid|amount|productinfo|firstname|email||udf2||udf4|||||||SALT)

	Case 3: If NONE of the udf parameters (udf1-udf5) are posted. Then,
	hash=sha512(key|txnid|amount|productinfo|firstname|email|||||||||||SALT)
	
	In present kit and available PayU plugins UDF5 is used. So the order is -	
	hash=sha512(key|txnid|amount|productinfo|firstname|email|||||udf5||||||SALT)
	
	*/
        $action = ($this->config->item("PAYU_MODE") == "sandbox") ? 'https://test.payu.in/_payment' : 'https://secure.payu.in/_payment';
        $key = $this->config->item("PAYU_KEY");
        $salt = $this->config->item("PAYU_SALT");

        $_POST = array(
            "txnid" => time(),
            "amount" => $total_amount,
            "productinfo" => "Appointment Booking",
            "firstname" => $appointment->app_name,
            "email" => $appointment->app_email,
            "udf5" => $order_id,
            "Lastname" => "",
            "Zipcode" => "",
            "phone" => $appointment->app_phone,
            "address1" => "",
            "city" => "",
            "state" => "",
            "country" => "",
            "Pg" => ""
        );

        //print_r( $_POST);
        //generate hash with mandatory parameters and udf5
        $hash = hash('sha512', $key . '|' . $_POST['txnid'] . '|' . $_POST['amount'] . '|' . $_POST['productinfo'] . '|' . $_POST['firstname'] . '|' . $_POST['email'] . '|||||' . $_POST['udf5'] . '||||||' . $salt);

        $_SESSION['salt'] = $salt; //save salt in session to use during Hash validation in response

        $html = '<form action="' . $action . '" id="payment_form_submit" method="post">
			<input type="hidden" id="udf5" name="udf5" value="' . $_POST['udf5'] . '" />
            <input type="hidden" id="surl" name="surl" value="' . site_url("payment/payment_success") . '" />
			<input type="hidden" id="furl" name="furl" value="' . site_url("payment/payment_failed") . '" />
			<input type="hidden" id="curl" name="curl" value="' . site_url("payment/payment_failed") . '" />
	        <input type="hidden" id="key" name="key" value="' . $key . '" />
			<input type="hidden" id="txnid" name="txnid" value="' . $_POST['txnid'] . '" />
			<input type="hidden" id="amount" name="amount" value="' . $_POST['amount'] . '" />
			<input type="hidden" id="productinfo" name="productinfo" value="' . $_POST['productinfo'] . '" />
			<input type="hidden" id="firstname" name="firstname" value="' . $_POST['firstname'] . '" />
			<input type="hidden" id="Lastname" name="Lastname" value="' . $_POST['Lastname'] . '" />
			<input type="hidden" id="Zipcode" name="Zipcode" value="' . $_POST['Zipcode'] . '" />
			<input type="hidden" id="email" name="email" value="' . $_POST['email'] . '" />
			<input type="hidden" id="phone" name="phone" value="' . $_POST['phone'] . '" />
			<input type="hidden" id="address1" name="address1" value="' . $_POST['address1'] . '" />
			<input type="hidden" id="address2" name="address2" value="' . (isset($_POST['address2']) ? $_POST['address2'] : '') . '" />
			<input type="hidden" id="city" name="city" value="' . $_POST['city'] . '" />
			<input type="hidden" id="state" name="state" value="' . $_POST['state'] . '" />
			<input type="hidden" id="country" name="country" value="' . $_POST['country'] . '" />
			<input type="hidden" id="Pg" name="Pg" value="' . $_POST['Pg'] . '" />
			<input type="hidden" id="hash" name="hash" value="' . $hash . '" />
			</form>
			<script type="text/javascript"><!--
				document.getElementById("payment_form_submit").submit();	
			//-->
			</script>';
        echo $html;
    }
    function finishOrder($order_id, $txnid, $paymentAmount)
    {


        $appointment = $this->business_model->get_business_appointment_temp_by_id($order_id);
        if (!empty($appointment)) {
            $this->db->insert("business_appointment", array(
                "user_id" => $appointment->user_id,
                "appointment_date" => $appointment->appointment_date,
                "start_time" => $appointment->start_time,
                "time_token" => $appointment->time_token,
                "app_name" => $appointment->app_name,
                "app_email" => $appointment->app_email,
                "app_phone" => $appointment->app_phone,
                "payment_type" => "paypal",
                "payment_ref" => $txnid,
                "payment_mode" => "instant",
                "payment_amount" => $paymentAmount
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

            $appointment = $this->db->query("Select * from business_appointment where id = '" . $app_id . "' limit 1");

            if ($this->config->item("ALLOW_EMAIL")) {
                $email_data["appointment"] = $appointment;
                $email_data["business"] = $this->business_model->get_businesses_details();

                $message = $this->load->view('common/emails/appointment-book', $email_data, TRUE);

                $this->load->library('email');
                $this->email->from($appointment->app_email, $appointment->app_name);
                $list = array($email_data["business"]->business_email, $appointment->app_email);
                $this->email->to($list);
                $this->email->reply_to($email_data["business"]->business_email, $this->config->item("app_name"));
                $this->email->subject('Appointment Booked');
                $this->email->message($message);
                if (!$this->email->send()) {
                }
            }
        }
    }
    function payuResponse()
    {
        $postdata = $_POST;
        $msg = '';
        $salt = $_SESSION['salt']; //Salt already saved in session during initial request.

        /* Response received from Payment Gateway at this page.

        It is absolutely mandatory that the hash (or checksum) is computed again after you receive response from PayU and compare it with request and post back parameters. This will protect you from any tampering by the user and help in ensuring a safe and secure transaction experience. It is mandate that you secure your integration with PayU by implementing Verify webservice and Webhook/callback as a secondary confirmation of transaction response.

        Process response parameters to generate Hash signature and compare with Hash sent by payment gateway 
        to verify response content. Response may contain additional charges parameter so depending on that 
        two order of strings are used in this kit.

        Hash string without Additional Charges -
        hash = sha512(SALT|status||||||udf5|||||email|firstname|productinfo|amount|txnid|key)

        With additional charges - 
        hash = sha512(additionalCharges|SALT|status||||||udf5|||||email|firstname|productinfo|amount|txnid|key)

        */
        if (isset($postdata['key'])) {
            $key                =   $postdata['key'];
            $txnid                 =     $postdata['txnid'];
            $amount              =     $postdata['amount'];
            $productInfo          =     $postdata['productinfo'];
            $firstname            =     $postdata['firstname'];
            $email                =    $postdata['email'];
            $udf5                =   $postdata['udf5'];
            $status                =     $postdata['status'];
            $resphash            =     $postdata['hash'];
            //Calculate response hash to verify	
            $keyString               =      $key . '|' . $txnid . '|' . $amount . '|' . $productInfo . '|' . $firstname . '|' . $email . '|||||' . $udf5 . '|||||';
            $keyArray               =     explode("|", $keyString);
            $reverseKeyArray     =     array_reverse($keyArray);
            $reverseKeyString    =    implode("|", $reverseKeyArray);
            $CalcHashString     =     strtolower(hash('sha512', $salt . '|' . $status . '|' . $reverseKeyString)); //hash without additionalcharges

            //check for presence of additionalcharges parameter in response.
            $additionalCharges  =     "";

            if (isset($postdata["additionalCharges"])) {
                $additionalCharges = $postdata["additionalCharges"];
                //hash with additionalcharges
                $CalcHashString     =     strtolower(hash('sha512', $additionalCharges . '|' . $salt . '|' . $status . '|' . $reverseKeyString));
            }
            //Comapre status and hash. Hash verification is mandatory.
            if ($status == 'success'  && $resphash == $CalcHashString) {
                $msg = "Transaction Successful, Hash Verified...<br />";
                //Do success order processing here...
                //Additional step - Use verify payment api to double check payment.
                if (verifyPayment($key, $salt, $txnid, $status)) {
                    redirect("payment/payment_success");
                } else {
                    $msg = "Transaction Successful, Hash Verified...Payment Verification failed...";
                    redirect("payment/payment_failed");
                }
            } else {
                //tampered or failed
                $msg = "Payment failed for Hash not verified...";
                redirect("payment/payment_failed");
            }
        }
    }

    //This function is used to double check payment
    function verifyPayment($key, $salt, $txnid, $status)
    {
        $command = "verify_payment"; //mandatory parameter

        $hash_str = $key  . '|' . $command . '|' . $txnid . '|' . $salt;
        $hash = strtolower(hash('sha512', $hash_str)); //generate hash for verify payment request

        $r = array('key' => $key, 'hash' => $hash, 'var1' => $txnid, 'command' => $command);

        $qs = http_build_query($r);
        //for production
        //$wsUrl = "https://info.payu.in/merchant/postservice.php?form=2";

        //for test
        $wsUrl = ($this->config->item("PAYU_MODE") == "sandbox") ? "https://test.payu.in/merchant/postservice.php?form=2" : "https://info.payu.in/merchant/postservice.php?form=2";

        try {
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $wsUrl);
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_SSLVERSION, 6); //TLS 1.2 mandatory
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            $o = curl_exec($c);
            if (curl_errno($c)) {
                $sad = curl_error($c);
                throw new Exception($sad);
            }
            curl_close($c);

            $response = json_decode($o, true);

            if (isset($response['status'])) {
                // response is in Json format. Use the transaction_detailspart for status
                $response = $response['transaction_details'];
                $response = $response[$txnid];

                if ($response['status'] == $status) //payment response status and verify status matched
                {
                    $this->finishOrder($response['udf5'], $response['txnid'], $response["amt"]);
                    return true;
                } else
                    return false;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
