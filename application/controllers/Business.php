<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Business extends MY_Controller {
    public function __construct()
    {
                parent::__construct();
                // Your own constructor code
                $this->load->database();
                $this->load->helper('login_helper');
    }
    public function dashboard(){
        $usertype = _get_current_user_type_id($this);
        if(_is_user_login($this)){
            
            $this->load->model("notification_model");
            $this->notification_model->send_notification();
            
            $from_date = date("Y-m-d");
            $to_date = date("Y-m-d");
            if($this->input->post("date_range")){
                $date_range =  $this->input->post("date_range");
                $date_range = explode(',',$date_range);
                $from_date = trim($date_range[0]);
                $to_date = trim($date_range[1]);    
            }
            
            $data['date_range_lable'] = $this->input->post('date_range_lable');
            
            $data["app_count"] =  $this->business_model->get_business_appointment_count();
            
            
                $user_id = _get_current_user_id($this);
                $data["appointments"] = $this->business_model->get_business_appointment("",$user_id,date("Y-m-d"));
                $data["chart_appointment"] = $this->business_model->get_business_appointment_group($from_date,$to_date,$user_id);
                        $data["reviews_count"] = $this->business_model->get_reviews_counts("",$user_id);
    
            

            $data["user_count"] =  $this->users_model->get_users_counts("2");
            
            
            $this->load->view("admin/dashboard",$data);
            
        }
    }
    public function list_business()
	{
	   if(_is_user_login($this)){
	       $data["error"] = "";
	       $data["active"] = "business";
           
           if(_get_current_user_type_id($this)==3){

                $data["business"] = $this->business_model->get_businesses(3);
                $this->load->view('admin/business/list2',$data);
           }
           
        }
        else
        {
            redirect('admin');
        }
    }
/* business service area */
  public function business_service($id){
        if(_is_user_login($this)){
            $data = array();
            
            if($_POST){
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('bus_title', 'Busoness Service Title', 'trim|required');
                $this->form_validation->set_rules('bus_price', 'Business Service Price', 'trim|required|numeric');
                $this->form_validation->set_rules('bus_discount', 'Business Service Discount', 'trim|numeric');
                $this->form_validation->set_rules('bus_time', 'Busoness Service Time', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    
        		}else
                {
                         
                        $bus_title = $this->input->post("bus_title");
                        $bus_time = $this->input->post("bus_time");
                        $bus_price = $this->input->post("bus_price");
                        $bus_discount = $this->input->post("bus_discount");
                        

                            $this->common_model->data_insert("business_services",
                                array(
                                "service_title"=>$bus_title,
                                "business_approxtime"=>$bus_time,
                                "service_price"=>$bus_price,
                                "service_discount"=>$bus_discount,
                                "bus_id"=>$id
                               ));
                            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Business Service Added Successfully
                                </div>');
                                $curr_url = current_url();
                                redirect($curr_url);
                        
                }
            }
           
           if(!$this->business_model->is_user_business($id)){
               exit(); 
           }
           $data["business_service"] = $this->business_model->get_business_service($id);
           
           $this->load->view("business/business_service",$data);
        }
         else
        {
            redirect('admin');
        }
    }
       public function edit_service($id)
	{
	   if(_is_user_login($this))
       {

           $service = $this->business_model->get_business_service_by_id($id);
           if(!$this->business_model->is_user_business($service->bus_id)){
               exit(); 
           }
           if(!empty($service)){
            $data["error"] = "";
            if($_POST)
            {
                $this->load->library('form_validation');
               $this->form_validation->set_rules('bus_title', 'Busoness Service Title', 'trim|required');
                $this->form_validation->set_rules('bus_price', 'Business Service Price', 'trim|required|numeric');
                $this->form_validation->set_rules('bus_discount', 'Business Service Discount', 'trim|numeric');
                
                if ($this->form_validation->run() == FALSE)
        		{
        			  $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>';
        		}
        		else
        		{
                        $bus_title = $this->input->post("bus_title");
                        $bus_price = $this->input->post("bus_price");
                        $bus_time = $this->input->post("bus_time");
                        $bus_discount = $this->input->post("bus_discount");
                        
                        $update_array = array(
                                "service_title"=>$bus_title,
                                "business_approxtime"=>$bus_time,
                                "service_price"=>$bus_price,
                                "service_discount"=>$bus_discount);
                    

                            $this->common_model->data_update("business_services",$update_array,array("id"=>$id)
                                );
                            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Service Update Successfully
                                </div>');
                                redirect("business/business_service/".$service->bus_id);
               	}
            }

           $data["business_service"] = $this->business_model->get_business_service_by_id($id);
	   	   $this->load->view('business/edit_service',$data);
        }
        
        }
        else
        {
            redirect('admin');
        }
	}
     function delete_business_service($service_id){
        if(_is_user_login($this)){
        $data = array();

            $service  = $this->business_model->get_business_service_by_id($service_id);
            if(!$this->business_model->is_user_business($service->bus_id)){
               exit(); 
            }

           if($service){
                $this->db->query("Delete from business_services where id = '".$service->id."'");
                $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Business Service Delete Successfully
                                </div>');
                redirect("business/business_service/".$service->bus_id);
           }
        }
    }
/* business review area */

     function business_review($business_id){
         if(_is_user_login($this))
       {
        $data = array();
            if(!$this->business_model->is_user_business($business_id)){
               exit(); 
            }

           $data["business_review"]  = $this->business_model->get_business_review($business_id);
           $this->load->view('business/business_review',$data);
           
       } else
        {
            redirect('admin');
        }
    }
     function delete_business_review($service_id){
          if(_is_user_login($this)){  
            $data = array();

            $service  = $this->business_model->get_business_review_by_id($service_id);
            if(!$this->business_model->is_user_business($service->bus_id)){
               exit(); 
            }

            if($service){
                $this->db->query("Delete from business_reviews where id = '".$service->id."'");
                $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Business Service Delete Successfully
                                </div>');
                redirect("business/business_review/".$service->bus_id);
            }
           
          } 
    }
  
  /* business Photo */
  public function business_photo($id){
        if(_is_user_login($this)){
            $data = array();
            
            if($_POST){
                $this->load->library('form_validation');
                
                 $this->form_validation->set_rules('photo_title', 'Business Photo Name', 'trim|required');
                    if (empty($_FILES['bus_img']['name']))
                {
                    $this->form_validation->set_rules('bus_img', 'Business Image', 'required');
                } 
                
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    
        		}else
                {
                         
                        $photo_title = $this->input->post("photo_title");
                        if($_FILES["bus_img"]["size"] > 0){
                    $config['upload_path']          = './uploads/business/businessphoto/';
                    $config['allowed_types']        = 'gif|jpg|png|jpeg';
                    $this->load->library('upload', $config);
                    if(!file_exists($config['upload_path'])){
                        mkdir($config['upload_path']);
                    }
                    if ( ! $this->upload->do_upload('bus_img'))
                    {
                            $error = array('error' => $this->upload->display_errors());
                    }
                    else
                    {
                        $img_data = $this->upload->data();
                        $savebus["photo_image"]=$img_data['file_name'];
                         $savebus["photo_title"]=$photo_title;
                         $savebus["bus_id"]=$id;
                    }
                    
               }

                $this->common_model->data_insert("business_photo",$savebus);
                            
                            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Business Photo Added Successfully
                                </div>');
                                $curr_url = current_url();
                                redirect($curr_url);
                        
                }
            }
           if(!$this->business_model->is_user_business($id)){
               exit(); 
           }
  
           $data["business_photo"] = $this->business_model->get_business_photo($id);
           $this->load->view("business/business_photo",$data);
           
        }
         else
        {
            redirect('admin');
        }
    } 
 
   public function edit_photo($id){
        if(_is_user_login($this)){
            $data = array("error"=>"");  

             
            $photo  = $this->business_model->get_business_photo_by_id($id);
            if(!$this->business_model->is_user_business($photo->bus_id)){
               exit(); 
            }

            if(!empty($photo)){
            $data["setbuss"] = $photo; 
                $this->load->library('form_validation');
                 $this->form_validation->set_rules('photo_title', 'Business Photo Name', 'trim|required');
                   
              if ($this->form_validation->run() == FALSE)
        		{
  		            if($this->form_validation->error_string()!=""){
        			     $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                    }
        		}
        		else
        		{
  		            
                $savebus = array(
                "photo_title"=>$this->input->post("photo_title")
                            );
            
                if($_FILES["bus_img"]["size"] > 0){
                    $config['upload_path']          = './uploads/business/businessphoto/';
                    $config['allowed_types']        = 'gif|jpg|png|jpeg';
                    $this->load->library('upload', $config);
                    if(!file_exists($config['upload_path'])){
                        mkdir($config['upload_path']);
                    }
                    if ( ! $this->upload->do_upload('bus_img'))
                    {
                            $error = array('error' => $this->upload->display_errors());
                    }
                    else
                    {
                        $img_data = $this->upload->data();
                        $savebus["photo_image"]=$img_data['file_name'];
                    }
                    
               }
                      
                    $this->db->update("business_photo",$savebus,array("id"=>$id)); 
                    
                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Your Business Photo Upadete successfully...
                                    </div>');
                                     redirect("business/business_photo/".$photo->bus_id);
                    //redirect('admin/business/');
                    //redirect('admin/business/');
               	}
            
	       $data["error"] = "";
	       
           $this->load->view('business/edit_photo',$data);
           }
        }
        else
        {
            redirect('admin');
        }
        
    }
 
      function delete_business_photo($service_id){
          if(_is_user_login($this)){  
            $data = array();

            $service  = $this->business_model->get_business_photo_by_id($service_id);
            if(!$this->business_model->is_user_business($service->bus_id)){
               exit(); 
            }
            if($service){
                $this->db->query("Delete from business_photo where id = '".$service->id."'");
                unlink("uploads/business/businessphoto/".$service->photo_image);
                $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Business Photo Delete Successfully
                                </div>');
                redirect("business/business_photo/".$service->bus_id);
            }
           
          }
    }
 
 /* business appointment area */

        function business_appointment($business_id="",$doct_id=""){
            if(_is_user_login($this))
            {
                $data = array();
                        $from_date = "";
                $to_date = "";
                if($this->input->post("date_range")){
                    $date_range =  $this->input->post("date_range");
                    $date_range = explode(',',$date_range);
                    $from_date = trim($date_range[0]);
                    $to_date = trim($date_range[1]);    
                }
                $data['date_range_lable'] = $this->input->post('date_range_lable');
                
                $user_id = "";
                $usertype = _get_current_user_type_id($this);
                
                if($usertype == 3 || $usertype == "3" ){
                    $user_id = _get_current_user_id($this);
                }

                if($doct_id == ""){
                    $doct_id = $this->input->post("filter_doct");
                    $data["doctors"] = $this->business_model->get_businesses_doctor();
                }
                $data["business"]  = $this->business_model->get_business_appointment($business_id,$user_id,$from_date,$to_date,$doct_id);
                $this->load->view('business/business_appointment',$data);
            } else
            {
                redirect('admin');
            }
    }
       function delete_business_appointment($service_id){
           if(_is_user_login($this)){ 
                $data = array();

                $service  = $this->business_model->get_business_appointment_by_id($service_id);
                if(!$this->business_model->is_user_business($service->bus_id)){
                   exit(); 
                }
                if($service){
                    $doctor = $this->doctor_model->get_doctor_by_id($service->doct_id);
            
                    $user = $this->users_model->get_user_by_id($service->user_id);
                    if(!empty($user)){
                                                        $text = "Your appointment for Dr. ".$doctor->doct_name." is rejected";
                        $message = array("title"=>"Appointment Cancel",
                        "message"=>$text,"image"=>"","created_at"=>date("Y-m-d h:i:s"));

                                $this->load->helper("gcm_helper");
                                $gcm = new GCM();
                                if($user->user_gcm_code != "")
                                    $result = $gcm->send_notification(array($user->user_gcm_code),$message,"android");
                                if($user->user_ios_token != "")
                                    $result = $gcm->send_notification(array($user->user_ios_token),$message,"ios");
        
                    } 
            
                    $this->db->query("Delete from business_appointment where id = '".$service->id."'");
                    $this->db->query("Delete from business_appointment_services where busness_appointment_id = '".$service->id."'");
                    $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Business Appointment Delete Successfully
                                </div>');
                    redirect("business/business_appointment/".$service->bus_id);
                }
            }
    }   
   
    /* business appointment area */

     function appointment_service($business_id){
        if(_is_user_login($this))
       {
            $data = array();

            
            $data["appointment"] = $this->business_model->get_business_appointment_by_id($business_id);
            $data["doctor"] = $this->business_model->get_businesses_doctor_by_id($data["appointment"]->doct_id);
            $data["business_appo"]  = $this->business_model->get_business_appointment_service($business_id);
            $this->load->view('business/appointment_service',$data);
       } else
       {
            redirect('admin');
       }
    }
    function delete_business_appointment_service($service_id){
           if(_is_user_login($this)){ 
                $data = array();

                $service  = $this->business_model->get_business_appointment_service($service_id);
                if(!$this->business_model->is_user_business($service->bus_id)){
                   exit(); 
                }
                if($service){
                    $this->db->query("Delete from business_appointment where id = '".$service->id."'");
                    $this->db->query("Delete from business_appointment_services where busness_appointment_id = '".$service->id."'");
                    $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Business Appointment Delete Successfully
                                    </div>');
                    redirect("business/business_appointment/".$service->bus_id);
               }
           }
    }   
           
    function business_schedule($bus_id){
        if(_is_user_login($this)){
            $data = array();
            if($_POST){
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('morning_from', 'Morning From', 'trim|required');
                    $this->form_validation->set_rules('morning_to', 'Morning To', 'trim|required');
                    $this->form_validation->set_rules('afternoon_from', 'Afternoon From', 'trim|required');
                    $this->form_validation->set_rules('afternoon_to', 'Afternoon To', 'trim|required');
                    $this->form_validation->set_rules('evening_from', 'Evening From', 'trim|required');
                    $this->form_validation->set_rules('evening_to', 'Evening To', 'trim|required');
                    $this->form_validation->set_rules('morning_interval', 'Morning Interval', 'trim|required');
                    $this->form_validation->set_rules('evening_interval', 'Evening Interval', 'trim|required');
                    $this->form_validation->set_rules('afternoon_interval', 'Afternoon Interval', 'trim|required');   
                    if ($this->form_validation->run() == FALSE)
            		{
      		            if($this->form_validation->error_string()!=""){
            			     $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                            <i class="fa fa-warning"></i>
                                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                          <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                        </div>');
                        }
            		}
            		else
            		{
          		         $morning_from = date("H:i:s", strtotime($this->input->post("morning_from")));
                           $morning_to = date("H:i:s", strtotime($this->input->post("morning_to")));
                           $evening_from = date("H:i:s", strtotime($this->input->post("evening_from")));
                           $evening_to = date("H:i:s", strtotime($this->input->post("evening_to")));
                           $afternoon_from = date("H:i:s", strtotime($this->input->post("afternoon_from")));
                           $afternoon_to = date("H:i:s", strtotime($this->input->post("afternoon_to")));
                           $morning_interval = $this->input->post("morning_interval");
                           $afternoon_interval = $this->input->post("afternoon_interval");
                           $evening_interval = $this->input->post("evening_interval");
                           $book_type = "slot";//$this->input->post("book_type");
                           $days = implode(',',$_REQUEST['day']);
                           
                            $sql = $this->db->insert_string("business_appointment_schedule",
                           array("bus_id"=>$bus_id,
                           "working_days"=>$days,
                           "morning_time_start"=>$morning_from,
                           "morning_time_end"=>$morning_to,
                           "morning_tokens"=>$morning_interval,
                           "afternoon_time_start"=>$afternoon_from,
                           "afternoon_time_end"=>$afternoon_to,
                           "afternoon_tokens"=>$afternoon_interval,
                           "evening_time_start"=>$evening_from,
                           "evening_time_end"=>$evening_to,
                           "evening_tokens"=>$evening_interval,"book_type"=>$book_type)) . " ON DUPLICATE KEY UPDATE  working_days= '".$days."', ".
                           "morning_time_start = '".$morning_from."', ".
                           "morning_time_end = '".$morning_to."', ".
                           "morning_tokens = '".$morning_interval."', ".
                           "afternoon_time_start = '".$afternoon_from."', ".
                           "afternoon_time_end = '".$afternoon_to."', ".
                           "afternoon_tokens = '".$afternoon_interval."', ".
                           "evening_time_start = '".$evening_from."', ".
                           "evening_time_end = '".$evening_to."', ".
                           "evening_tokens = '".$evening_interval."', ".
                           "book_type = '".$book_type."'";
    $this->db->query($sql);
    $id = $this->db->insert_id();
                            
                             $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                            <i class="fa fa-success"></i>
                                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                          <strong>Success!</strong> Time slot added successfully
                                        </div>');
                              
                    }
                
        }

        if(!$this->business_model->is_user_business($bus_id)){
               exit(); 
        }
        $schedule = $this->business_model->get_business_schedule($bus_id);
            $data["schedule"] = $schedule;
            $this->load->view("business/business_schedule",$data);
        
        
        }
    }  
    
    public function books($bus_id=""){
        if(_is_user_login($this)){
             $user_id = _get_current_user_id($this);   
             $business = $this->business_model->get_business_details_by_id($bus_id,$user_id);
             if(!empty($business)){
             $doct_id = $this->input->post("doct_filter");
             $appointments  = $this->business_model->get_business_appointment($bus_id,"","","",$doct_id);
             $array = array();
             foreach($appointments as $app){
                 $total_expand_time =  explode(':',$app->taken_time);
                $total_expand_time_add = '+'.$total_expand_time[0].' hour +'.$total_expand_time[1].' minutes';
                                
                 $endTime = strtotime($total_expand_time_add, strtotime($app->start_time));
                $time_slot = array("title"=>$app->app_name,
                "start"=>$app->appointment_date."T".$app->start_time,
                "end"=>$app->appointment_date."T".date('H:i:s', $endTime),
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
         
         $data["appointments"] = $array;
         $data["bus_id"] = $bus_id;
         $data["services"] = $this->business_model->get_business_service($bus_id); 
         $data["doctors"] = $this->business_model->get_businesses_doctor($bus_id);   
         $this->load->view("business/books",$data);
            
            }
        }
    }
    
    public function get_schedule_slot(){
        //header('Content-type: text/json');
        
        $date =  date("Y-m-d",strtotime($this->input->post("start_date")));
        
        $time_slots_date_array = array();
                

        $time_slots_date_array = $this->business_model->get_time_slot($date,$this->input->post("bus_id"),$this->input->post("doct_id"));
        if(!empty($time_slots_date_array)){
            $this->load->view("business/timeslot",array("timeslot"=>$time_slots_date_array,"date"=>$date));
        }else{
            echo "No time schedule setup";
        }
        //echo json_encode($time_slots_date_array);
                
    }
    public function get_avialable_slot(){
            
    }
    public function get_time_slot(){
                $data = array();
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('bus_id', 'Business ID', 'trim|required');
                $this->form_validation->set_rules('times_slot', 'Total Time', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
                        $data["responce"] = false;       
  			           $data["error"] = $this->form_validation->error_string();
                    
        		}else
                {
                        $bus_id = $this->input->post("bus_id");
                        $time_slots = explode(',', $this->input->post("times_slot") );
                        $date = $this->input->post("date");
                        $total_time = trim($time_slots[0]);
                        for($i = 1; $i < count($time_slots); $i++){
                            $time_slots[$i];
                            $total_expand_time =  explode(':',trim($time_slots[$i]));
                            $total_expand_time_add = '+'.$total_expand_time[0].' hour +'.$total_expand_time[1].' minutes';
                            $total_time = date("H:i:s",strtotime($total_expand_time_add, strtotime($total_time)));
                        }

                        $data = $this->business_model->get_time_slot($bus_id,$total_time, $date);
                        $data["date"] = $date;
                        $this->load->view("business/get_time_slot",$data);
                }

    }
    public function add_appointment(){
        //if(_is_user_login($this)){
                header('Content-type: text/json');
                $data = array();
                $this->load->library('form_validation');
                $this->form_validation->set_rules('doct_id', 'Doct ID', 'trim|required');
                $this->form_validation->set_rules('bus_id', 'Business ID', 'trim|required');
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                $this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');
                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
                $this->form_validation->set_rules('appointment_date', 'App date', 'trim|required');
                $this->form_validation->set_rules('start_time', 'Start Time', 'trim|required');
                $this->form_validation->set_rules('time_token', 'Time Token', 'trim|required');
                $this->form_validation->set_rules('services', 'Services', 'trim|required');
                
                if ($this->form_validation->run() == FALSE) 
        		{
                        $data["responce"] = false;       
  			           $data["error"] = $this->form_validation->error_string();
                    
        		}else
                {
                    $doct_id = $this->input->post("doct_id");
                        $bus_id = $this->input->post("bus_id");
                        $user_id = $this->input->post("user_id");
                        $appointment_date = $this->input->post("appointment_date");
                        $start_time = $this->input->post("start_time");
                        $time_token = $this->input->post("time_token");
                        
                        
                        
                         $this->db->insert("business_appointment",array("bus_id"=>$bus_id,
                         "doct_id"=>$doct_id,
                        "user_id"=>$user_id,
                        "appointment_date"=>date("Y-m-d",strtotime($appointment_date)),
                        "start_time"=>date("H:i:s",strtotime($start_time)),
                        "time_token"=>$time_token,
                        "app_name"=>$this->input->post("fullname"),
                        "app_email"=>$this->input->post("email"),
                        "app_phone"=>$this->input->post("phone")));
                        $app_id = $this->db->insert_id();
                        

                        //$business = $this->business_model->get_businesses_by_id($bus_id);
                        
                        $service_array = explode(',',$this->input->post("services"));
                        
                        foreach($service_array as $service){
                            $this->db->insert("business_appointment_services",array("busness_appointment_id"=>$app_id,
                            "busness_service_id"=>trim($service),
                            "service_qty"=>1));
                        }
                        
                        $data["responce"] = true;
                        $appointment = $this->db->query("Select * from business_appointment where id = '".$app_id."' limit 1");
                        $data["data"] = $appointment->row();
                        
                        
                }
                echo json_encode($data); 
        //}
    }
    public function app_details(){
        $this->load->library('form_validation');
                $this->form_validation->set_rules('appid', 'Appointment ID Required', 'trim|required');
                if ($this->form_validation->run() == FALSE)
        		{
  		            if($this->form_validation->error_string()!=""){
        			     $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                    }
        		}
        		else
        		{
      		            $appid = $this->input->post("appid");

                        $appointment = $this->business_model->get_business_appointment_by_id($appid);
                        $doctor = $this->business_model->get_businesses_doctor_by_id($appointment->doct_id);
                        $services = $this->business_model->get_business_appointment_service($appid);
                        

                        $user = $this->users_model->get_user_by_id($appointment->user_id);
                        
                        $data["appointment"] = $appointment;
                        $data["services"] = $services;
                        $data["user"] = $user;
                        $data["doctor"] = $doctor;
                        $this->load->view("business/app_details",$data);
                        
                }
    }
    
    
    public function doctor($bus_id){
        if(_is_user_login($this)){
            $data = array("error"=>"");  
            if(!$this->business_model->is_user_business($bus_id)){
               exit(); 
            }

                $this->load->library('form_validation');
               
                    $this->form_validation->set_rules('doct_name', 'Doctor Name', 'trim|required');
                    $this->form_validation->set_rules('doct_phone', 'Doctor Phone', 'trim|required');
                    $this->form_validation->set_rules('doct_degree', 'Doctor Degree', 'trim|required');
                    $this->form_validation->set_rules('doct_email', 'email', 'trim|required|valid_email|is_unique[users.user_email]');
                    $this->form_validation->set_message('is_unique', 'Email address is already register');
                    
              if ($this->form_validation->run() == FALSE)
        		{
  		            if($this->form_validation->error_string()!=""){
        			     $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                    }
        		}
        		else
        		{
  		            

                          $doct_id =  $this->common_model->data_insert("users",
                                array(
                                "user_fullname"=>$this->input->post("doct_name"),
                                "user_email"=>$this->input->post("doct_email"),
                                "user_password"=>_encrypt_val($this->input->post("doct_password")),
                                "user_type_id"=>"1",
                                "user_status"=>"1"));     
               if($doct_id){  
        
                $savebus = array(
                            "doct_id"=>$doct_id,
                            "bus_id"=>$bus_id,
                            "doct_name"=>$this->input->post("doct_name"),
                            "doct_degree"=>$this->input->post("doct_degree"),
                            "doct_phone"=>$this->input->post("doct_phone"),
                            "doct_speciality"=>$this->input->post("doct_speciality"),
                            "doct_about"=>$this->input->post('doct_about')
                            );
                
               if($_FILES["doct_logo"]["size"] > 0){
                        $config['upload_path']          = './uploads/business/';
                        $config['allowed_types']        = 'gif|jpg|png|jpeg';
                        $this->load->library('upload', $config);
        
                        if ( ! $this->upload->do_upload('doct_logo'))
                        {
                                $error = array('error' => $this->upload->display_errors());
                        }
                        else
                        {
                            $img_data = $this->upload->data();
                            $savebus["doct_photo"]=$img_data['file_name'];
                        }
                        
                }

                $this->db->insert("business_doctinfo",$savebus);
                    
                    
                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Doctor details added successfully...
                                    </div>');
                    //redirect('admin/business/');
                    //redirect('admin/business/');
               	}
           } 
	       $data["error"] = "";
	       $data["doctors"] = $this->business_model->get_businesses_doctor($bus_id);
            
           $this->load->view('business/doctors',$data);
           
           }
        
        
    } 
    public function doctor_delete($doct_id){
        if(_is_user_login($this)){
            $bus_id = _get_current_user_id($this);
            $user_type_id = _get_current_user_type_id($this);
            $row = array();
            if($user_type_id == 0){
                $q = $this->db->query("select * from business_doctinfo where doct_id = '".$doct_id."'"); 
                $row = $q->row();   
            }else{
                $q = $this->db->query("select business_doctinfo.* from business_doctinfo
                inner join business on business.bus_id = business_doctinfo.bus_id
                 where business.user_id = '".$bus_id."' and business_doctinfo.doct_id = '".$doct_id."'");
                 $row = $q->row();
            }
            
            
            if(!empty($row)){
                $doct = $row;
                $this->db->delete("business_doctinfo",array("doct_id"=>$doct->doct_id));
                $this->db->delete("users",array("user_id"=>$doct->doct_id));
                redirect("business/doctor_list/".$doct->bus_id);
            }
        }
    }
    public function doctor_edit($doct_id){
        if(_is_user_login($this)){
            $data = array("error"=>""); 

            $user = $this->users_model->get_user_by_id($doct_id); 
            if(!empty($user)){
            $data["user"] = $user;

            
                $this->load->library('form_validation');
               
                    $this->form_validation->set_rules('doct_name', 'Doctor Name', 'trim|required');
                    $this->form_validation->set_rules('doct_phone', 'Doctor Phone', 'trim|required');
                    $this->form_validation->set_rules('doct_degree', 'Doctor Degree', 'trim|required');
                
            
              if ($this->form_validation->run() == FALSE)
        		{
  		            if($this->form_validation->error_string()!=""){
        			     $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                    }
        		}
        		else
        		{
  		            
                        
                        $update_array = array(
                                "user_fullname"=>$this->input->post("doct_name"));
                        $user_password = $this->input->post("user_password");
                        if(_decrypt_val($user->user_password) != $user_password && $user_password != ""){
                            $update_array["user_password"]= _encrypt_val($user_password);
                        }

                        $this->common_model->data_update("users",$update_array,array("user_id"=>$user->user_id));


                $savebus = array(
                            "doct_name"=>$this->input->post("doct_name"),
                            "doct_degree"=>$this->input->post("doct_degree"),
                            "doct_phone"=>$this->input->post("doct_phone"),
                            "doct_speciality"=>$this->input->post("doct_speciality"),
                            "doct_about"=>$this->input->post('doct_about')
                            );
            
               if($_FILES["doct_logo"]["size"] > 0){
                        $config['upload_path']          = './uploads/business/';
                        $config['allowed_types']        = 'gif|jpg|png|jpeg';
                        $this->load->library('upload', $config);
                        if(!is_dir($config['upload_path'])){
                            mkdir($config['upload_path']);
                        }
                        if ( ! $this->upload->do_upload('doct_logo'))
                        {
                                $error = array('error' => $this->upload->display_errors());
                        }
                        else
                        {
                            $img_data = $this->upload->data();
                            $savebus["doct_photo"]=$img_data['file_name'];
                        }
                        
                }

                $this->db->update("business_doctinfo",$savebus,array("doct_id"=>$doct_id));
                    
                    
                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Doctor details added successfully...
                                    </div>');
                    
               	}
           $user_id = _get_current_user_id($this); 
	       
	       $data["doctor"] = $this->business_model->get_businesses_doctor_by_id($doct_id,$user_id);
           if(!empty($data["doctor"])){ 
            $this->load->view('business/doctors_edit',$data);
            }  
            }
            }
       
        
    } 
    function doctor_list($bus_id){
         if(_is_user_login($this)){

             $data["doctors"] = $this->business_model->get_businesses_doctor();
             //if(!empty($data["doctors"])){   
             $this->load->view('business/doctors_list',$data);
             //}
         }
    }
    
    
    
    
        public function business_edit($id){
        if(_is_user_login($this)){
            $data = array("error"=>"");  
            $data["categor"] = $this->category_model->sel_categories();
            
            $data["buscat"] = $this->category_model->bus_category($id);
            
                $this->load->library('form_validation');
                $this->form_validation->set_rules('bus_title', 'Business Title', 'trim|required');
                $this->form_validation->set_rules('bus_email', 'email Currectr', 'trim|required|valid_email');
                $this->form_validation->set_rules('bus_phone', 'phone Number', 'trim|required|is_natural');
                $this->form_validation->set_rules('buscat[]', 'Category Name', 'trim|required');
                //$this->form_validation->set_rules('lat_log', 'Latitude Longitude', 'trim|required');
                //$this->form_validation->set_rules('location', 'Business Location ', 'trim|required');
                
                
                
            
              if ($this->form_validation->run() == FALSE)
        		{
  		            if($this->form_validation->error_string()!=""){
        			     $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                    }
        		}
        		else
        		{
  		            
                     
                    $busslug = url_title($this->input->post('bus_title'), 'dash', TRUE);
            
            $lat = $this->input->post("lat");
            $lon = $this->input->post("lon");
            
           
                $savebus = array(
                
                            "bus_title"=>$this->input->post("bus_title"),
                            "bus_slug"=>$busslug,
                            "bus_email"=>$this->input->post("bus_email"),
                            "bus_contact"=>$this->input->post("bus_phone"),
                            "bus_description"=>$this->input->post("busdesc"),
                            "bus_google_street"=>$this->input->post("address"),
                            "bus_latitude"=>$lat,
                            "bus_longitude"=>$lon,
                            "bus_fee"=>$this->input->post('bus_fee'),
                            "bus_con_time"=>$this->input->post('bus_con_time'),
                            "city_id"=>$this->input->post('city_id'),
                            "country_id"=>$this->input->post('country_id'),
                            "locality_id"=>$this->input->post('locality_id')
                
                );
            
                        if($_FILES["bus_logo"]["size"] > 0){
                            $config['upload_path']          = './uploads/business/';
                            $config['allowed_types']        = 'gif|jpg|png|jpeg|webp';
                            $this->load->library('upload', $config);
                            if(!is_dir($config['upload_path'])){
                                mkdir($config['upload_path']);
                            }
                            if ( ! $this->upload->do_upload('bus_logo'))
                            {
                                    $error = array('error' => $this->upload->display_errors());
                                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> '.$this->upload->display_errors().'
                                    </div>');
                            }
                            else
                            {
                                $img_data = $this->upload->data();
                                $savebus["bus_logo"]=$img_data['file_name'];
                            }
                            
                    }
                      
                    $this->db->update("business",$savebus,array("bus_id"=>$id)); 
                    
                    $this->db->query("Delete from business_category where bus_id = '".$id."'");

                    foreach($_REQUEST["buscat"] as $cat){
                            $this->common_model->data_insert("business_category",array("bus_id"=>$id,"category_id"=>$cat));
                    }
                    
                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Your Business Details Upadete successfully...
                                    </div>');
                    
               	}
            $data["setbuss"] = $this->business_model->set_listing($id);
            if(!empty($data["setbuss"])){ 
                $data["error"] = "";
            
                $data["countries"] = $this->area_model->get_countries("1");
                $data["localities"] = $this->area_model->get_locality("1",$data["setbuss"]->country_id,$data["setbuss"]->city_id);
                $data["cities"] = $this->area_model->get_cities("1",$data["setbuss"]->country_id);
            

                $data["users"] = $this->users_model->get_users(array("user_type"=>3));
                $this->load->view('admin/business/edit',$data);
           }
        }
        else
        {
            redirect('admin');
        }
        
    }

    public function business_delete($id){
        if(_is_user_login($this)){
	       
              $data = array();
            $business  = $this->business_model->get_businesses_by_id($id);
            if(!$this->business_model->is_user_business($id)){
               exit(); 
            }

           if($business){
                $this->db->query("Delete from business where bus_id = '".$business->bus_id."'");
                $this->db->query("Delete from business_category where bus_id = '".$business->bus_id."'");
                redirect("admin/list_business");
           }
        }
        else
        {
            redirect('admin');
        }
    }
    
}
