<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends MY_Controller {
    public function __construct()
    {
                parent::__construct();
                // Your own constructor code
                $this->load->database();
                $this->load->helper('login_helper');
    }
    public function dashboard(){
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
            $usertype = _get_current_user_type_id($this);
            
                $user_id = _get_current_user_id($this);
                $data["appointments"] = $this->business_model->get_business_appointment("","",date("Y-m-d"),"",$user_id);
                $data["chart_appointment"] = $this->business_model->get_business_appointment_group($from_date,$to_date,"",$user_id);
                $data["reviews_count"] = $this->business_model->get_reviews_counts("",$user_id);
    
            

            $data["user_count"] =  $this->users_model->get_users_counts("2");
            
            
            $this->load->view("doctor/dashboard",$data);
        }
    }
    public function doctor_appointment(){
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
                
                    $doct_id = _get_current_user_id($this);

                
                $data["business"]  = $this->business_model->get_doctor_appointment($from_date,$to_date,$doct_id);
                $this->load->view('business/business_appointment',$data);
            } else
            {
                redirect('admin');
            }
    }
    public function books(){
        if(_is_user_login($this)){
         

             $doct_id = _get_current_user_id($this);
             $q = $this->db->query("Select * from business_doctinfo where doct_id = '".$doct_id."'");
             $doctor = $q->row();
             
             $appointments  = $this->business_model->get_business_appointment("","","","",$doct_id);
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
         
         $data["appointments"] = $array;
         $data["bus_id"] = $doctor->bus_id;
         $data["services"] = $this->business_model->get_business_service($doctor->bus_id); 
         $data["doctors"] = $this->business_model->get_businesses_doctor($doctor->bus_id);   
         $this->load->view("business/books",$data);
        
        }
    }
}
?>