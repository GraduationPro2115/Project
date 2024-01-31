<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/api
	 *	- or -
	 * 		http://example.com/index.php/api/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/api/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
     public function __construct()
     {
                parent::__construct();
                // Your own constructor code
                $this->load->database();
                header('Content-type: text/json');
                
                /**
                 * Put your country timezone here.. also set
                 * **/
                date_default_timezone_set('Europe/London');
                $this->db->query("SET time_zone='+00:00'");
                $this->db->trans_strict(FALSE);
                //if($this->input->post("user_id")){
                //    $this->session->set_userdata(array("user_id"=>$this->input->post("user_id")));
                //}

                //header('Content-type: text/json');
                header("Access-Control-Allow-Origin: *");
                header("Content-Type: application/json; charset=UTF-8");
                header("Access-Control-Allow-Methods: POST, GET");
                header("Access-Control-Max-Age: 3600");
                header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

              
                $headers = apache_request_headers();
                $authorized = false;

                $q = $this->db->get("keys");
                $keys = $q->row();
                if (!empty($keys)) {
                    foreach ($headers as $key => $value) {
                        if (strtolower($key) == 'authorization' && $value == $keys->key) {
                            $authorized = true;
                        }
                    }
                }
                if (!$authorized) {
                    echo json_encode(array("responce" => false, "error" => "authentication problem"));
                    exit();
                }
     }
     function getallheaders()
    {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
     public function get_categories(){

        $categories = $this->category_model->get_categories_short(0,0,$this) ;
        $data["responce"] = true;
        $data["data"] = $categories;
        echo json_encode($data);
        
    }
    /**
     * BASE_URL + "/index.php/api/get_business"
     * Get Business Listing with this services
     * Pass POST request with following parameter : 
     * "cat_id"  filter record belong to this category
     * "lat, lon, rad"  if you pass all this three parameters then all record will display nearby regarding this latitude and longitude
     * "search" this will search kewords from database and display listing 
     * "locality_id"  in application search screen allow user to choose "locality" arey to display listing there
     * */   
    public function get_business(){
    
    	$cat_id = $this->input->post("cat_id");
    	$lat= $this->input->post("lat");
    	$lon= $this->input->post("lon");
    	$rad= $this->input->post("rad");
    	$search= $this->input->post("search");
        
        $params = array();
        if($this->input->post("locality_id")){
            $params["locality_id"] = $this->input->post("locality_id");     
        }
        	

        $business = $this->business_model->get_business_by_category($cat_id,$lat,$lon,$rad,$search,"","",$params) ;
        $data["responce"] = true;
        $data["data"] = $business;
        echo json_encode($data);
    }
    /**
     * BASE_URL + "/index.php/api/get_recommonded"
     * Get Recommonded Business Listing with this services
     * Pass POST request with following parameter : 
     * */   
    public function get_recommonded(){
        $params = array("is_recommonded"=>"1");
        $business = $this->business_model->get_business_by_category("","","","","","","",$params,"") ;
        $data["responce"] = true;
        $data["data"] = $business;
        echo json_encode($data);
    }
    /**
     * BASE_URL + "/index.php/api/get_services"
     * Display services of "Business"
     * */
    public function get_services(){
                $data = array();
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('bus_id', 'Business ID', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
                        $data["responce"] = false;       
  			           $data["error"] = $this->form_validation->error_string();
                    
        		}else
                {

                        $servoces = $this->business_model->get_business_service($this->input->post("bus_id"));
                        $data["responce"] = true;
                        $data["data"] = $servoces;
                }
          echo json_encode($data);
    }
    /**
     * BASE_URL + "/index.php/api/get_photos"
     * Display Photos of business. 
     * */
    
    public function get_photos(){
                $data = array();
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('bus_id', 'Business ID', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
                        $data["responce"] = false;       
  			           $data["error"] = $this->form_validation->error_string();
                    
        		}else
                {

                        $photos = $this->business_model->get_business_photo($this->input->post("bus_id"));
                        $data["responce"] = true;
                        $data["data"] = $photos;
                }
          echo json_encode($data);
    }
    
    /**
     * BASE_URL + "/index.php/api/add_appointment"
     * Add Appointment : Through this api user can set his appointmer for pertucular business.
     * "bus_id"  appointment book for given business id
     * "user_id"  pass id of login user
     * "appointment_date"  date for appointment booking  yyyy-mm-dd
     * "time_token"  - 0,1,2 value of time token
     * "start_time"  time for appoitnment booking  H:i:s
     * "services"  json script of services choosen by user
     * */
    public function add_appointment_temp(){
                $data = array();
                $this->load->library('form_validation');
                $this->form_validation->set_rules('doct_id', 'Doctor ID', 'trim|required');
                $this->form_validation->set_rules('bus_id', 'Business ID', 'trim|required');
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                $this->form_validation->set_rules('appointment_date', 'App date', 'trim|required');
                $this->form_validation->set_rules('start_time', 'Start Time', 'trim|required');
                $this->form_validation->set_rules('time_token', 'Time Token', 'trim|required');
                //$this->form_validation->set_rules('services', 'Services', 'trim|required');
                
                if ($this->form_validation->run() == FALSE) 
        		{
                        $data["responce"] = false;       
                        $data["error"] = $this->form_validation->error_string();
                    
        		}else
                {
                        $bus_id = $this->input->post("bus_id");
                        $doct_id = $this->input->post("doct_id");
                        $user_id = $this->input->post("user_id");
                        $appointment_date = $this->input->post("appointment_date");
                        $start_time = $this->input->post("start_time");
                        $time_token = $this->input->post("time_token");
                        
                        $q = $this->db->query("Select * from business_appointment where bus_id='".$bus_id."' and doct_id='".$doct_id."' and appointment_date = '".date("Y-m-d",strtotime($appointment_date))."' and start_time = '".date("H:i:s",strtotime($start_time))."'");
                        if($q->row()){
                            $data["responce"] = false;       
                            $data["error"] = "This time slot is already booked or recently booked";
                    
                        }else{ 
                         $this->db->insert("business_appointment_temp",array("bus_id"=>$bus_id,
                         "doct_id"=>$doct_id,
                        "user_id"=>$user_id,
                        "appointment_date"=>date("Y-m-d",strtotime($appointment_date)),
                        "start_time"=>date("H:i:s",strtotime($start_time)),
                        "time_token"=>$time_token,
                        "app_name"=>$this->input->post("user_fullname"),
                        "app_email"=>$this->input->post("user_email"),
                        "app_phone"=>$this->input->post("user_phone")));
                        $app_id = $this->db->insert_id();
                        

                        //$business = $this->business_model->get_businesses_by_id($bus_id);
                        $servs = $this->input->post("services");
                        if(!empty($servs))
                        {
                        $service_array = explode(',',$this->input->post("services"));
                            foreach($service_array as $service){
                                $this->db->insert("business_appointment_services_temp",array("busness_appointment_id"=>$app_id,
                                "busness_service_id"=>trim($service),
                                "service_qty"=>1));
                                
                            }
                        }
                        
                        $data["responce"] = true;
                        //$appointment = $this->db->query("Select * from business_appointment_temp where id = '".$app_id."' limit 1");
                        $appointment = $this->business_model->get_business_appointment_temp_by_id($app_id);
                        $data["data"] = $appointment;
                        $data["payment_url"] = site_url("payment/make/".$appointment->id);
                        }
                        
                }
          echo json_encode($data);
    }
    public function add_appointment(){
                $data = array();
                $this->load->library('form_validation');
                $this->form_validation->set_rules('doct_id', 'Doctor ID', 'trim|required');
                $this->form_validation->set_rules('bus_id', 'Business ID', 'trim|required');
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                $this->form_validation->set_rules('appointment_date', 'App date', 'trim|required');
                $this->form_validation->set_rules('start_time', 'Start Time', 'trim|required');
                $this->form_validation->set_rules('time_token', 'Time Token', 'trim|required');
                //$this->form_validation->set_rules('services', 'Services', 'trim|required');
                
                if ($this->form_validation->run() == FALSE) 
        		{
                        $data["responce"] = false;       
                        $data["error"] = $this->form_validation->error_string();
                    
        		}else
                {
                        $bus_id = $this->input->post("bus_id");
                        $doct_id = $this->input->post("doct_id");
                        $user_id = $this->input->post("user_id");
                        $appointment_date = $this->input->post("appointment_date");
                        $start_time = $this->input->post("start_time");
                        $time_token = $this->input->post("time_token");
                        
                        $q = $this->db->query("Select * from business_appointment where bus_id='".$bus_id."' and doct_id='".$doct_id."' and appointment_date = '".date("Y-m-d",strtotime($appointment_date))."' and start_time = '".date("H:i:s",strtotime($start_time))."'");
                        if($q->row()){
                            $data["responce"] = false;       
                            $data["error"] = "This time slot is already booked or recently booked";
                    
                        }else{ 
                         $this->db->insert("business_appointment",array("bus_id"=>$bus_id,
                         "doct_id"=>$doct_id,
                        "user_id"=>$user_id,
                        "appointment_date"=>date("Y-m-d",strtotime($appointment_date)),
                        "start_time"=>date("H:i:s",strtotime($start_time)),
                        "time_token"=>$time_token,
                        "app_name"=>$this->input->post("user_fullname"),
                        "app_email"=>$this->input->post("user_email"),
                        "app_phone"=>$this->input->post("user_phone")));
                        $app_id = $this->db->insert_id();
                        

                        //$business = $this->business_model->get_businesses_by_id($bus_id);
                        $servs = $this->input->post("services");
                        if(!empty($servs))
                        {
                        $service_array = explode(',',$this->input->post("services"));
                            foreach($service_array as $service){
                                $this->db->insert("business_appointment_services",array("busness_appointment_id"=>$app_id,
                                "busness_service_id"=>trim($service),
                                "service_qty"=>1));
                                
                            }
                        }
                        
                        $data["responce"] = true;
                        //$app_result = $this->db->query("Select * from business_appointment where id = '".$app_id."' limit 1");
                        //$appointment = $app_result->row();
                        $appointment =  $this->business_model->get_business_appointment_by_id($app_id);
                        $data["data"] = $appointment;
                        
                        if($this->config->item("ALLOW_EMAIL")){
                        $email_data["appointment"] = $appointment;
                        $email_data["doctor"] = $this->doctor_model->get_doctor_by_id($appointment->doct_id);
                        $email_data["business"] = $this->business_model->get_business_details_by_id($appointment->bus_id);
                
                        $message = $this->load->view('common/emails/appointment-confirm',$email_data,TRUE);
                    
                            $this->load->library('email');
                            $this->email->from($appointment->app_email, $appointment->app_name);
                            $list = array($email_data["business"]->bus_email,$email_data["doctor"]->user_email, $appointment->app_email);
                            $this->email->to($list);
                            $this->email->reply_to($email_data["business"]->bus_email, $this->config->item("app_name"));
                            $this->email->subject('Appointment for '.$email_data["doctor"]->doct_name);
                            $this->email->message($message);
                            if ( ! $this->email->send()){
                            
                            }
                        }
                        
                        }
                        
                        
                }
          echo json_encode($data);
    }
    /**
     * BASE_URL + "/index.php/api/get_time_slot"
     * Get available time slot for business for choosen date
     * "bus_id"  business id
     * "date" appointment booking date
     * */
    public function get_time_slot(){
        $data = array();
        $this->load->library('form_validation');
                $this->form_validation->set_rules('doct_id', 'Business ID', 'trim|required');
                $this->form_validation->set_rules('bus_id', 'Business ID', 'trim|required');
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
                        $data["responce"] = false;       
  			           $data["error"] = $this->form_validation->error_string();
                    
        		}else
                {
                                $date =  date("Y-m-d",strtotime($this->input->post("date")));

                                $data["responce"] = true;
                                $data["data"] = $this->business_model->get_time_slot($date,$this->input->post("bus_id"),$this->input->post("doct_id"));

                }
          echo json_encode($data);    
    }
    /**
     * BASE_URL + "/index.php/api/cancel_appointment"
     * Cancel Appointment Booked By user and user can cancel it
     * */
    public function cancel_appointment(){
        $data = array();
        $this->load->library('form_validation');
                
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('app_id', 'Appointment ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
  		{
            if($this->form_validation->error_string()!=""){
                        $data["responce"] = false;
                        $data["error"] = strip_tags($this->form_validation->error_string());
            
  		    }
        }else
            {
                $app_id = $this->input->post("app_id");
                $user_id = $this->input->post("user_id");
                

                $app  = $this->business_model->get_business_appointment_by_user_id($user_id,$app_id);
                if($app){
                    $data["responce"] = true;
                    $this->db->query("Delete from business_appointment where id = '".$app->id."'");
                    $this->db->query("Delete from business_appointment_services where busness_appointment_id = '".$app->id."'");
                    $data["data"] = "Deleted successfully";
                    
                        $appointment = $app;
                        
                        $email_data["appointment"] = $appointment;
                        $email_data["doctor"] = $this->doctor_model->get_doctor_by_id($appointment->doct_id);
                        $email_data["business"] = $this->business_model->get_business_details_by_id($appointment->bus_id);
                
                        $message = $this->load->view('common/emails/appointment-cancel',$email_data,TRUE);
                    
                            $this->load->library('email');
                            $this->email->from($appointment->app_email, $appointment->app_name);
                            $list = array($email_data["business"]->bus_email,$email_data["doctor"]->user_email, $appointment->app_email);
                            $this->email->to($list);
                            $this->email->reply_to($email_data["business"]->bus_email, $this->config->item("app_name"));
                            $this->email->subject('Appointment for '.$email_data["doctor"]->doct_name);
                            $this->email->message($message);
                            if ( ! $this->email->send()){
                            
                            }
                        
                        
                    
                }else{
                    $data["responce"] = false;
                    $data["error"] = "This is no belongs to you";
                }
           }
         echo json_encode($data);
    }
    /**
     * BASE_URL + "/index.php/api/my_appointments"
     * List of user appointments
     * */
    public function my_appointments(){
        $data = array();
        $this->load->library('form_validation');
                
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        		{
  		            if($this->form_validation->error_string()!=""){
                        $data["responce"] = false;
                        $data["message"] = strip_tags($this->form_validation->error_string());
                    }
        		}else
                {

                    $data["responce"] = true;
                    $data["data"] = $this->business_model->get_user_appointment($this->input->post("user_id"));
                }
                 echo json_encode($data); 
    }
    /**
     * BASE_URL + "/index.php/api/get_reviews"
     * Get reviews for perticular business... 
     * */
    public function get_reviews(){
        $data = array();
        $this->load->library('form_validation');
                
                $this->form_validation->set_rules('bus_id', 'Bus ID', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
  		            if($this->form_validation->error_string()!=""){
                        $data["responce"] = false;
                        $data["message"] = strip_tags($this->form_validation->error_string());
                    }
        		}else
                {

                    $reviews = $this->business_model->get_business_reviews($this->input->post("bus_id"));
                    $data["responce"] = true;
                    $data["data"] = $reviews;
                }
                 echo json_encode($data);
    }
    /**
     * BASE_URL + "/index.php/api/add_business_review"
     * Set review for perticular business... 
     * "user_id"  id of user who posting review
     * "bus_id" id of business for which posting review
     * "reviews"  comment text for business
     * "rating"  float value of rattings star. 
     * */
    public function add_business_review(){
        $data = array();
        $this->load->library('form_validation');
                
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                $this->form_validation->set_rules('bus_id', 'Bus ID', 'trim|required');
                $this->form_validation->set_rules('reviews', 'Review', 'trim|required');
                $this->form_validation->set_rules('rating', 'Rating', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
  		            if($this->form_validation->error_string()!=""){
                        $data["responce"] = false;
                        $data["message"] = strip_tags($this->form_validation->error_string());
                    }
        		}else
                {
                    $user_id = $this->input->post("user_id");
                    $bus_id = $this->input->post("bus_id");
                    $reviews = $this->input->post("reviews");
                    $ratings = $this->input->post("rating");
                    
                    $this->db->insert("business_reviews",array("user_id"=>$user_id,"bus_id"=>$bus_id,"reviews"=>$reviews,"ratings"=>$ratings));
                    $id = $this->db->insert_id();
                    //$this->db->query("Select * from review ")

                    
                    $data["responce"] = true;
                    $data["data"] = $this->business_model->get_business_review_by_id($id);
                }
                 echo json_encode($data);  
    }
    
    /**
     * Register User full process
     * */
    /**
     * BASE_URL + "/index.php/api/forgot_password"
      * User can recover password through this api. Modify password link will sent. though this link user can reset password
      * User must register with given email 
      * 
      * Configure from email : 
      * path  'application/config/config.php'  update in this file
      * */
     public function forgot_password(){
            $data = array();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('user_email', 'Email', 'trim|required');
            if ($this->form_validation->run() == FALSE) 
      		{
            		    $data["responce"] = false;  
            			$data["error"] = 'Warning! : '.strip_tags($this->form_validation->error_string());
                        
      		}else
            {
                   $request = $this->db->query("Select * from users where user_email = '".$this->input->post("user_email")."' limit 1");
                   if($request->num_rows() > 0){
                                
                                $user = $request->row();
                                $token = uniqid(uniqid());
                                $this->db->update("users",array("varified_token"=>$token),array("user_id"=>$user->user_id)); 
                                $this->load->library('email');
                                $this->email->from($this->config->item('default_email'), $this->config->item('email_host'));
                                $list = array($user->user_email);
                                $this->email->to($list);
                                $this->email->reply_to($this->config->item('default_email'), $this->config->item('email_host'));
                                $this->email->subject('Forgot password request');
                                $this->email->message("Hi ".$user->user_fullname." \n Your password forgot request is accepted plase visit following link to change your password. \n
                                ".site_url("users/modify_password/".$token)."
                                ");
                                if ( ! $this->email->send()){
                                            		    $data["responce"] = false;  
            			$data["error"] = 'Warning! : Something is wrong with system to send mail.';
    
                                }else{
                                            		    $data["responce"] = true;  
            			$data["error"] = 'Success! : Recovery mail sent to your email address please verify link.';
    
                                }
                   }else{
                                       		    $data["responce"] = false;  
            			$data["error"] = 'Warning! : No user found with this email.';
    
                   }
                }
                echo json_encode($data);
        }
        /* user registration */   
        /**
         * BASE_URL + "/index.php/api/signup"
         * User can signup through this api from application.. following are required fields : 
         * "user_fullname"
         * "user_phone"
         * "user_email"
         * "user_password"
         *
         * */            
        public function signup(){
    
            $data = array(); 
            $_POST = $_REQUEST;      
                $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required');
                $this->form_validation->set_rules('user_phone', 'Mobile Number', 'trim|required');
                $this->form_validation->set_rules('user_email', 'Email Id',  'trim|required|valid_email|is_unique[users.user_email]');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
                $this->form_validation->set_message('is_unique', 'Email address is already register');
                
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $data["responce"] = false;  
        			$data["error"] = 'Warning! : '.strip_tags($this->form_validation->error_string());
                    
        		}else
                {
                    $this->db->insert("users", array("user_fullname"=>$this->input->post("user_fullname"),
                                            "user_email"=>$this->input->post("user_email"),
                                            "user_phone"=>$this->input->post("user_phone"),
                                            "user_password"=>_encrypt_val($this->input->post("user_password")),
                                            "user_type_id"=>"2",
                                            "user_status"=>"1"
                                            ));
                 $user_id =  $this->db->insert_id();  
                 
                  
                    $data["responce"] = true;
                    $data["data"] = array("user_id"=>$user_id,"user_phone"=>$this->input->post("user_phone"),"user_fullname"=>$this->input->post("user_fullname"),"user_email"=>$this->input->post("user_email")) ;    
                    
                  }                  
           
                     echo json_encode($data);
        }
        /**
         * BASE_URL + "/index.php/api/update_profile"
         * User can update his profile data through this api... 
         * Pass "user_image" as multi part form data
         * */        
        public function update_profile(){
    
                $data = array(); 
                $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('user_phone', 'Phone', 'trim|required');
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required');
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $data["responce"] = false;  
        			$data["error"] = 'Warning! : '.strip_tags($this->form_validation->error_string());
                    
        		}else
                {
                    $update_array = array("user_fullname"=>$this->input->post("user_fullname"),
                    "user_phone"=>$this->input->post("user_phone"),
                                            "user_bdate" => date("Y-m-d",strtotime($this->input->post("user_bdate")))
                                            );
                                            
                    $file_name = "";
                    if(isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0){
                        $path = './uploads/profile';
                        
                        if(!file_exists($path)){
                            mkdir($path);
                        }
                        $this->load->library("imagecomponent");
                            
                            $file_name_temp = md5(uniqid())."_".$_FILES['user_image']['name'];
                            $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image',680,200,$path ,'crop',false,$file_name_temp);
                          $update_array["user_image"] = $file_name;
                    } 
                     
                     $this->db->update("users", $update_array, array("user_id"=>$this->input->post("user_id")));
                 
                 
                $data["responce"] = true;
                    
                  }                  
           
                     echo json_encode($data);
        }
        
        /**
         * BASE_URL + "/index.php/api/update_profile_image"
         * User can update his profile data through this api... 
         * Pass "user_image" as multi part form data
         * */        
        public function update_profile_image(){
    
            $data = array(); 
            $this->load->library('form_validation');
            /* add users table validation */
            $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
            if ($this->form_validation->run() == FALSE) 
            {
                $data["responce"] = false;  
                $data["error"] = 'Warning! : '.strip_tags($this->form_validation->error_string());
                
            }else
            {
                $update_array = array();
                                        
                $file_name = "";
                if(isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0){
                    $path = './uploads/profile';
                    
                    if(!file_exists($path)){
                        mkdir($path);
                    }
                    $this->load->library("imagecomponent");
                        
                    $file_name_temp = md5(uniqid())."_".$_FILES['user_image']['name'];
                    $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image',680,200,$path ,'crop',false,$file_name_temp);
                    $update_array["user_image"] = $file_name;

                    $this->db->update("users", $update_array, array("user_id"=>$this->input->post("user_id")));
             
                    $data["responce"] = true;
                    $data["data"] = "Profile updated successfully";
                }else{
                    $data["responce"] = false;  
                    $data["error"] = 'Warning! : Image file required';
                } 
                 
                 
                
              }                  
       
                 echo json_encode($data);
        }
        
        /**
         * BASE_URL + "/index.php/api/change_password"
         * User can change password with new password. through this api
         * */
        public function change_password(){
                $data = array(); 
                $this->load->library('form_validation');

                            $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                $this->form_validation->set_rules('c_password', 'Current Password', 'trim|required');
                $this->form_validation->set_rules('n_password', 'New Password', 'trim|required');
                $this->form_validation->set_rules('r_password', 'Re Password', 'trim|required');
                
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$data["responce"] = false;
		    		$data["error"] = $this->form_validation->error_string();
                    
        		}else
                {
	                $q = $this->db->query("Select * from users where user_id = '".$this->input->post('user_id')."'");
                    $user_data  = $q->row();
                    if(!empty($user_data)){
	                    if(_decrypt_val($user_data->user_password) == $this->input->post("c_password")){
	                        $n_password = $this->input->post("n_password");
	                        $r_password = $this->input->post("r_password");
	                        
	                        if($n_password == $r_password){
	                            $this->db->update("users",
	                                array("user_password"=>_encrypt_val($n_password)),array("user_id"=>$user_data->user_id));
	                           
	    				$data["responce"] = true;
	    				$data["data"] = "Password Change Successfully";
	                            
	                        }
	                        
	                    }else{
	                        $data["responce"] = false;
		    		        $data["error"] = "Current Password not matched";
	                    }
                     }else{
	                        $data["responce"] = false;
		    		        $data["error"] = "User not found";
                    }                                  
         }   
         echo json_encode($data);

        }        
        
        /* user login json */
        /**
         * BASE_URL + "/index.php/api/login"
         * Login api for user
         * User can login through email id and password
         * */
        public function login(){
            $data = array(); 
            $_POST = $_REQUEST;      
                $this->load->library('form_validation');
                 $this->form_validation->set_rules('user_email', 'Email Id',  'trim|required|valid_email');
                 $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
               
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $data["responce"] = false;  
        			$data["error"] = 'Warning! : '.strip_tags($this->form_validation->error_string());
                    
        		}else
                {
                   
                    $q = $this->db->query("Select * from `users` where users.user_email='".$this->input->post('user_email')."'  Limit 1");
                    //and users.user_password='".md5($this->input->post('user_password'))."'
                    
                    if ($q->num_rows() > 0)
                    {
                        $row = $q->row(); 
                        if($row->user_status == "0")
                        {
                                $data["responce"] = false;  
   			                  $data["error"] = 'Warning! : Your account currently inactive.Please Contact Admin';
                            
                        }
                        else if($this->input->post('user_password') != _decrypt_val($row->user_password)){
                            $data["responce"] = false;  
   			                  $data["error"] = 'Warning! : Incorrect Password';
                        }
                        else
                        {
                              $data["responce"] = true;  
   			                  $data["data"] = array("user_id"=>$row->user_id,"user_fullname"=>$row->user_fullname,"user_email"=>$row->user_email,"user_phone"=>$row->user_phone,"user_image"=>$row->user_image,"user_bdate"=>$row->user_bdate,"user_city"=>$row->user_city) ;
   			                   
                        }
                    }
                    else
                    {
                              $data["responce"] = false;  
   			                  $data["error"] = 'Warning! : Invalide Username or Passwords';
                    }
                   
                    
                }
           echo json_encode($data);
            
        }
        
        /**
         * BASE_URL + "/index.php/api/get_userdata"
         * Get user data from user_id
         * */
        public function get_userdata(){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('user_id', 'User Id',  'trim|required');
                
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $data["responce"] = false;  
        			$data["error"] = 'Warning! : '.strip_tags($this->form_validation->error_string());    
        		}else
                {
                    $q = $this->db->query("Select user_id, user_email,user_fullname,user_bdate,user_image,user_city,user_phone from users where user_id = '".$this->input->post("user_id")."' limit 1");
                    $data["responce"] = true;
                    $data["data"] = $q->row();
                }
            echo json_encode($data);
        }    
     
    /* user registration */               
    /**
     * BASE_URL + "/index.php/api/register_fcm"
     * register user for get firebase notification. 
     * "user_id"  id for login user
     * "token"   FCM generated token
     * "device"  android,ios type
     * */
    public function register_fcm(){
        $data = array();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        $this->form_validation->set_rules('device', 'Device', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
  		{
        		  $data["responce"] = false;
        			$data["error"] = $this->form_validation->error_string();
                            
  		}else
        {   
            $device = $this->input->post("device");
            $token = $this->input->post("token");
            $user_id = $this->input->post("user_id");
            
            $field = "";
            if($device=="android"){
                $field = "user_gcm_code";
            }else if($device=="ios"){
                $field = "user_ios_token";
            }
            if($field!=""){
                $this->db->query("update users set ".$field." = '".$token."' where user_id = '".$user_id."'");
                $data["responce"] = true;    
            }else{
                $data["responce"] = false;
                $data["error"] = "Device type is not set";
            }
            
            
        }
        echo json_encode($data);
    }
    /**
     * BASE_URL + "/index.php/api/get_locality"
     * get locality
     * */
    public function get_locality(){

                    $locality = $this->area_model->get_locality();
                    $data["responce"] = true;
                    $data["data"] = $locality;
        echo json_encode($data);
    }
    
    /**
     * BASE_URL + "/index.php/api/get_doctors"
     * */
    public function get_doctors(){

                    $locality = $this->business_model->get_businesses_doctor($this->input->post("bus_id"));
                    $data["responce"] = true;
                    $data["data"] = $locality;
                    echo json_encode($data);
    }
    
    /**
     * BASE_URL + "/index.php/api/get_appointments_business"
     * */
     public function get_appointments_business(){
        $data = array();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('bus_id', 'User ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
  		{
		      $data["responce"] = false;
            $data["error"] = $this->form_validation->error_string();
                            
  		}else
        {  
            $bus_id = $this->input->post("bus_id");
            $doct_id = $this->input->post("doct_id");

             $appointments  = $this->business_model->get_business_appointment($bus_id,"","","",$doct_id);
             $array = array();
             foreach($appointments as $app){
                 $total_expand_time =  explode(':',$app->taken_time);
                $total_expand_time_add = '+'.$total_expand_time[0].' hour +'.$total_expand_time[1].' minutes';
                                
                 $endTime = strtotime($total_expand_time_add, strtotime($app->start_time));
                $time_slot = array("title"=>$app->app_name,
                "start"=>$app->appointment_date."T".$app->start_time,
                "end"=>$app->appointment_date."T".date('h:i:s', $endTime),
                "allDay"=>false,
                "url"=>"javascript:onEvenClick('".$app->id."');");
                if($app->status == 0){
                    $time_slot["backgroundColor"] = "#ccc";
                    $time_slot["borderColor"] = "#ccc";
    
                }else if ($app->status == 1){
                    $time_slot["backgroundColor"] = "#00a65a";
                    $time_slot["borderColor"] = "#00a65a";
    
                }
                $array[] = $time_slot;
            }
            $data["responce"] = true;
            $data["data"] = $array;
         }
        echo json_encode($data);
     }
}
