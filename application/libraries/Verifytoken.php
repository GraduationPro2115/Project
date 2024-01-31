<?php   defined('BASEPATH') or exit('No direct script access allowed');
//include APPPATH . 'vendor/autoload.php';

class Verifytoken
{
    public $code;
    public $personalToken;
    public $userAgent;
    public function __construct()
    {
        // $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        // $dotenv->load();
        // $this->personalToken = $_ENV['SECRET_KEY'];
        // $this->userAgent = "https://dsinfoway.com";
    }
    function verify($code){
        $this->code = $code;
        $code = trim($this->code);

        // Make sure the code looks valid before sending it to Envato
        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $this->code)) {
            //throw new Exception("Invalid code");
            return (Object)array("response"=>false,"data"=>"Invalid code");
        }
       

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => "http://dsinfoway.com/evanto/validate_licence.php?code=$this->code&user=dsinfoway",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
        ));

        // Send the request with warnings supressed
        $response = @curl_exec($ch);
       
        // Handle connection errors (such as an API outage)
        // You should show users an appropriate message asking to try again later
        if (curl_errno($ch) > 0) { 
            //throw new Exception("Error connecting to API: " . curl_error($ch));
            return (Object)array("response"=>false,"data"=>"Error connecting to API");
        }

        // If we reach this point in the code, we have a proper response!
        // Let's get the response code to check if the purchase code was found
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // HTTP 404 indicates that the purchase code doesn't exist
        if ($responseCode === 404) {
            return (Object)array("response"=>false,"data"=>"Purchase code Invalid");
        }

        // Anything other than HTTP 200 indicates a request or API error
        // In this case, you should again ask the user to try again later
        if ($responseCode !== 200) {
            return (Object)array("response"=>false,"data"=>"Failed to validate code due to an error: HTTP {$responseCode}");
        }
       
        // Parse the response into an object with warnings supressed
        //return @json_decode($response);
        return json_decode($response);
        // // Check for errors while decoding the response (PHP 5.3+)
        // if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
        //     return array("response"=>false,"data"=>"Error parsing response");
        // }

        // // Now we can check the details of the purchase code
        // // At this point, you are guaranteed to have a code that belongs to you
        // // You can apply logic such as checking the item's name or ID
       
        // return  array("response"=>true,"data"=>$body->item->id); // (int) 17022701
        
    }
}