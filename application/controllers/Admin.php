<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
    public function __construct()
    {
                parent::__construct();
                // Your own constructor code
                $this->load->database();
                $this->load->helper('login_helper');
    }
        function signout(){
        $this->session->sess_destroy();
        redirect("admin");
    }
    public function change_status(){
        $table = $this->input->post("table");
        $id = $this->input->post("id");
        $on_off = $this->input->post("on_off");
        $id_field = $this->input->post("id_field");
        $status = $this->input->post("status");
        
        $this->db->update($table,array("$status"=>$on_off),array("$id_field"=>$id));
        if($table == "business_appointment"){
            $appointment = $this->business_model->get_business_appointment_by_id($id);
            $doctor = $this->doctor_model->get_doctor_by_id($appointment->doct_id);
            
            $user = $this->users_model->get_user_by_id($appointment->user_id);
            if(!empty($user)){
                        $text = "Your appointment for Dr. ".$doctor->doct_name." at ".$appointment->appointment_date." time ".$appointment->start_time." is completed";
                        $message = array("title"=>"Appointment Complete",
                        "message"=>$text,"image"=>"","created_at"=>date("Y-m-d h:i:s"));
                        
                        $this->load->helper("gcm_helper");
                        $gcm = new GCM();
                        if($user->user_gcm_code != "")
                            $result = $gcm->send_notification(array($user->user_gcm_code),$message,"android");
                        if($user->user_ios_token != "")
                            $result = $gcm->send_notification(array($user->user_ios_token),$message,"ios");

            }   
        }
    }
	public function index()
	{   
        if(_is_user_login($this)){
            redirect(_get_user_redirect($this));
        }else{
            
            $data = array("error"=>"");       
            if(isset($_POST))
            {
                
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		  if($this->form_validation->error_string()!=""){
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    }
                    
        		}else
                {
                   
                    $q = $this->db->query("Select * from `users` where (`user_email`='".$this->input->post("email")."')   Limit 1");
                    //and user_password='".md5($this->input->post("password"))."'
                    
                   // print_r($q) ; 
                    if ($q->num_rows() > 0)
                    {
                        $row = $q->row(); 
                        if($row->user_status == "0")
                        {
                            $data["error"] = '<div class="alert alert-danger alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> Your account currently inactive.</div>';
                        }
                        else if(_decrypt_val($row->user_password) != $this->input->post("password")){
                            $data["error"] = '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Warning!</strong> Incorrect password.</div>';
                        }
                        else
                        {
                            $newdata = array(
                                                   'user_name'  => $row->user_fullname,
                                                   'user_email'     => $row->user_email,
                                                   'logged_in' => TRUE,
                                                   'user_id'=>$row->user_id,
                                                   'user_image'=>$row->user_image,
                                                   'user_type_id'=>$row->user_type_id
                                                  );
                            $this->session->set_userdata($newdata);
                            redirect(_get_user_redirect($this));
                         
                        }
                    }
                    else
                    {
                        $data["error"] = '<div class="alert alert-danger alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> Invalid User and password. </div>';
                    }
                   
                    
                }
            }
            $data["active"] = "login";
            
            $this->load->view("admin/login",$data);
        }
	}
    function dashboard(){
        if(_is_user_login($this))
        {
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
            
                $data["appointments"] = $this->business_model->get_business_appointment("","",date("Y-m-d"));
                $data["chart_appointment"] = $this->business_model->get_business_appointment_group($from_date,$to_date);
                $data["reviews_count"] = $this->business_model->get_reviews_counts();
    
            
            
            $data["user_count"] =  $this->users_model->get_users_counts("2");
            
            
            $this->load->view("admin/dashboard",$data);
            
        }
    }
 

   
/* ========== Categories =========== */
    public function addcategories()
	{
	   if(_is_user_login($this)){
	       
            $data["error"] = "";
            $data["active"] = "addcat";
            if(isset($_REQUEST["addcatg"]))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('cat_title', 'Categories Title', 'trim|required');
                //$this->form_validation->set_rules('parent', 'Categories Parent', 'trim|required');
                
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
                    $this->category_model->add_category(); 
                    $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Your request added successfully...
                                    </div>');
                    redirect('admin/addcategories');
               	}
            }
	   	$this->load->view('admin/categories/addcat',$data);
        }
        else
        {
            redirect('admin');
        }
	}
    
    public function editcategory($id)
	{
	   if(_is_user_login($this))
       {
            $q = $this->db->query("select * from `categories` WHERE id=".$id);
            $data["getcat"] = $q->row();
            
	        $data["error"] = "";
            $data["active"] = "listcat";
            if(isset($_REQUEST["savecat"]))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('cat_title', 'Categories Title', 'trim|required');
                $this->form_validation->set_rules('cat_id', 'Categories Id', 'trim|required');
                //$this->form_validation->set_rules('parent', 'Categories Parent', 'trim|required');
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
                    $this->category_model->edit_category(); 
                    $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Your category saved successfully...
                                    </div>');
                    redirect('admin/listcategories');
               	}
            }
	   	   $this->load->view('admin/categories/editcat',$data);
        }
        else
        {
            redirect('admin');
        }
	}
    
    public function listcategories()
	{
	   if(_is_user_login($this)){
	       $data["error"] = "";
	       $data["active"] = "listcat";
           $data["allcat"] = $this->category_model->get_categories();
           $this->load->view('admin/categories/listcat',$data);
        }
        else
        {
            redirect('admin');
        }
    }
    
    public function deletecat($id)
	{
	   if(_is_user_login($this)){
	        
            $this->db->delete("categories",array("id"=>$id));
            $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Your item deleted successfully...
                                    </div>');
            redirect('admin/listcategories');
        }
        else
        {
            redirect('admin');
        }
    }

      
/* ========== End Categories ========== */    
/*----------- Users related functions , manage all users listings and process ------------*/
    public function add_user(){
        /*if(_is_user_login($this)){
            $data = array();
            if($_POST){
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required');
                $this->form_validation->set_rules('user_email', 'Email Id', 'trim|required');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
                $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    
        		}else
                {
                        $user_fullname = $this->input->post("user_fullname");
                        $user_email = $this->input->post("user_email");
                        $user_password = $this->input->post("user_password");
                        $user_type = $this->input->post("user_type");
                        
                        
                        $status = ($this->input->post("status")=="on")? 1 : 0;
                        
                            $this->common_model->data_insert("users",
                                array(
                                "user_fullname"=>$user_fullname,
                                "user_email"=>$user_email,
                                "user_password"=>md5($user_password),
                                "user_type_id"=>$user_type,
                                "user_status"=>$status));
                            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> User Added Successfully
                                </div>');
                        
                }
            }
            
            $data["user_types"] = $this->users_model->get_user_type();
            $this->load->view("admin/users/add_user",$data);
        }*/
    }
	 public function listuser($user_id){
        if(_is_user_login($this)){
            $data = array();
            $data["users"] = $this->users_model->get_user($user_id);
            
            $this->load->view("admin/users/list",$data);
        }
    }


    //================================ user history ===========================================
    public function user_history($user_id){

        if(_is_user_login($this)){
            $data = array();
//            $user_history = new User_history();
            $q = $this->db->query("select * from user_history where user_id =".$user_id);
            $data["history"] = $q->result();
            $data["user_id"] = $user_id;
            $this->load->view("admin/users/list_user_history",$data);
        }
    }

    public function add_user_history($user_id){

        if(_is_user_login($this)){
            $data["user_id"] = $user_id;
            $this->load->view("admin/users/add_user_history",$data);
//            $this->load->view("admin/users/list_user_history",$data);
        }

    }

    public function save_user_history(){

        if(_is_user_login($this)){
            $array = array(
                'user_id' =>$this->input->post("user_id"),
                'name' =>$this->input->post("name"),
                'type' =>$this->input->post("type"),
                'doctor' =>$this->input->post("doctor_name"),
                'hospital' =>$this->input->post("hospital"),
                'date' =>$this->input->post("date"),
                'description' =>$this->input->post("description"),
            );
            if(isset( $_FILES["attachment"]) && $_FILES["attachment"]["size"] > 0)
            {
                $config['upload_path']          = './uploads/history/';
                $config['allowed_types']        = 'pdf|jpeg';
                if(!is_dir($config['upload_path']))
                {
                    mkdir($config['upload_path']);
                }
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('attachment'))
                {
                    $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Error!</strong> The File Format Is Not Supported Only jpeg & pdf 
                                </div>');
                    $data["user_id"] = $this->input->post("user_id");
                    $this->load->view("admin/users/add_user_history",$data);
                    return;

                }
                else
                {
                    $img_data = $this->upload->data();
                    $user_image=$img_data['file_name'];
                    $array["attachment"] =$user_image;
                }
            }
            $this->common_model->data_insert("user_history",$array);
            $data = array();
            $q = $this->db->query("select * from user_history where user_id =".$this->input->post("user_id"));
            $data["history"] = $q->result();
            $data["user_id"] = $this->input->post("user_id");

            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> History Added Successfully
                                </div>');

            $this->load->view("admin/users/add_user_history",$data);

        }

    }

    public function edit_user_history($id){

        if(_is_user_login($this)){

            $data = array();
//            $user_history = new User_history();
            $q = $this->db->query("select * from user_history where id =".$id);
            $data["history"] = $q->result();
            $this->load->view("admin/users/edit_user_history",$data);

        }

    }

    public function update_user_history($id){

        if(_is_user_login($this)){
            $array = array(
                'user_id' =>$this->input->post("user_id"),
                'name' =>$this->input->post("name"),
                'type' =>$this->input->post("type"),
                'doctor' =>$this->input->post("doctor_name"),
                'hospital' =>$this->input->post("hospital"),
                'date' =>$this->input->post("date"),
                'description' =>$this->input->post("description"),
            );
            if(isset( $_FILES["attachment"]) && $_FILES["attachment"]["size"] > 0)
            {
                $config['upload_path']          = './uploads/history/';
                $config['allowed_types']        = 'pdf|jpeg';
                if(!is_dir($config['upload_path']))
                {
                    mkdir($config['upload_path']);
                }
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('attachment'))
                {
                    $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Error!</strong> The File Format Is Not Supported Only jpeg & pdf 
                                </div>');
                    $data["user_id"] = $this->input->post("user_id");
                    $this->load->view("admin/users/edit_user_history",$data);
                    return;

                }
                else
                {
                    $img_data = $this->upload->data();
                    $user_image=$img_data['file_name'];
                    $array["attachment"] =$user_image;
                }
            }
            $this->common_model->data_update("user_history",$array,array("id"=>$id));
            $data = array();
            $q = $this->db->query("select * from user_history where user_id =".$this->input->post("user_id"));
            $data["history"] = $q->result();
            $data["user_id"] = $this->input->post("user_id");

            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> History Updated Successfully
                                </div>');

            $this->load->view("admin/users/edit_user_history",$data);

        }

    }

    public function delete_user_history($id,$user_id)
    {
        if(_is_user_login($this)){

            $this->db->delete("user_history",array("id"=>$id));
            $this->session->set_flashdata("success_req",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Your history deleted successfully...
                                    </div>');
            $data = array();
//            $user_history = new User_history();
            $q = $this->db->query("select * from user_history where user_id =".$user_id);
            $data["history"] = $q->result();
            $data["user_id"] = $user_id;
            $this->load->view("admin/users/list_user_history",$data);
        }
        else
        {
            redirect('admin');
        }
    }




    public function edit_user($user_id){
        if(_is_user_login($this)){
            $data = array();
            $data["user_types"] = $this->users_model->get_user_type();
            
            $user = $this->users_model->get_user_by_id($user_id);
            $user_type_id = _get_current_user_type_id($this);
            $c_user_id = _get_current_user_id($this);
            if($user_type_id != 0){
                if($c_user_id != $user_id){
                    exit();
                }
            }
            
            $data["user"] = $user;
            if($_POST){
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    
        		}else
                {
                        $user_fullname = $this->input->post("user_fullname");
                        $user_type = $this->input->post("user_type");
                        
                        $update_array = array(
                                "user_fullname"=>$user_fullname,
                                "user_phone"=>$this->input->post("user_phone"),
                                "user_bdate"=>date("Y-m-d",strtotime($this->input->post("user_bdate"))));
                        if($user_id != _get_current_user_id($this)){
                            $status = ($this->input->post("status")=="on")? 1 : 0;
                            $update_array["user_status"] = $status;
                        }        
                        $user_password = $this->input->post("user_password");
                        if(_decrypt_val($user->user_password) != $user_password && trim($user_password) != ""){
                            $update_array["user_password"]= _encrypt_val($user_password);
                        }
                        if(isset( $_FILES["user_image"]) && $_FILES["user_image"]["size"] > 0)
                        {
                            $config['upload_path']          = './uploads/profile/';
                            $config['allowed_types']        = 'gif|jpg|png|jpeg';
                            if(!is_dir($config['upload_path']))
                            {
                                mkdir($config['upload_path']);
                            }
                            $this->load->library('upload', $config);
                            if ( ! $this->upload->do_upload('user_image'))
                            {
                                $error = array('error' => $this->upload->display_errors());
                            }
                            else
                            {
                                $img_data = $this->upload->data();
                                $user_image=$img_data['file_name'];
                                 $update_array["user_image"] =$user_image;
                            }
                        }
                            $this->common_model->data_update("users",$update_array,array("user_id"=>$user_id)
                                );
                            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> User Added Successfully
                                </div>');
                                redirect("admin/edit_user/".$user->user_id);
                        
                }
            }
            
            
            $this->load->view("admin/users/edit_user",$data);
        }
    }
    function delete_user($user_id){
        if(_is_user_login($this)){
            $data = array();
            $user  = $this->users_model->get_user_by_id($user_id);
            if($user){
                $this->db->query("Delete from users where user_id = '".$user->user_id."'");
                redirect("admin/listuser/".$user->user_type_id);
            }
        }
    }
/*------------END Users -----------------*/    

/* ========== Business Setting ========== */
    public function list_business()
	{
	   if(_is_user_login($this)){
	       $data["error"] = "";
	       $data["active"] = "business";
           if(_get_current_user_type_id($this)==0){
                $data["business"] = $this->business_model->get_businesses($userid=0);
                $this->load->view('admin/business/list',$data);
           }
        }
        else
        {
            redirect('admin');
        }
    }

    
    public function business_add(){
        if(_is_user_login($this)){
           $data["categories"] = $this->category_model->get_categories();
           
           
                $this->load->library('form_validation');
                $this->form_validation->set_rules('bus_password', 'Password', 'trim|required');
                $this->form_validation->set_rules('bus_title', 'Business Title', 'trim|required');
                $this->form_validation->set_rules('bus_email', 'email', 'trim|required|valid_email|is_unique[users.user_email]');
                $this->form_validation->set_rules('bus_phone', 'phone Number', 'trim|required|is_natural');
                $this->form_validation->set_rules('buscat[]', 'Category Name', 'trim|required');
                //$this->form_validation->set_rules('lat_log', 'Latitude Longitude', 'trim|required');
                //$this->form_validation->set_rules('location', 'Business Location ', 'trim|required');
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
  		            
                     $user_fullname = $this->input->post("bus_title");
                        $user_email = $this->input->post("bus_email");
                        $user_password = $this->input->post("bus_password");
                        
                        
                        
                            $user_id = $this->common_model->data_insert("users",
                                array(
                                "user_fullname"=>$user_fullname,
                                "user_email"=>$user_email,
                                "user_password"=>_encrypt_val($user_password),
                                "user_type_id"=>3,
                                "user_status"=>1));     
                    
                    $busslug = url_title($this->input->post('bus_title'), 'dash', TRUE);
            
           
                    $lat = $this->input->post("lat");
                    $long = $this->input->post("lon");
            
            
          
                $savebus = array(
                "user_id"=>$user_id,
                            "bus_title"=>$this->input->post("bus_title"),
                            "bus_slug"=>$busslug,
                            "bus_email"=>$this->input->post("bus_email"),
                            "bus_contact"=>$this->input->post("bus_phone"),
                            "bus_description"=>$this->input->post("busdesc"),
                            "bus_google_street"=>$this->input->post("address"),
                            "bus_latitude"=>$lat,
                            "bus_longitude"=>$long,
                            "bus_fee"=>$this->input->post('bus_fee'),
                            "bus_con_time"=>$this->input->post('bus_con_time'),
                            "city_id"=>$this->input->post('city_id'),
                            "country_id"=>$this->input->post('country_id'),
                            "locality_id"=>$this->input->post('locality_id'),
                            "bus_status"=>"1"
                            );
            
                if($_FILES["bus_logo"]["size"] > 0){
                    $config['upload_path']          = './uploads/business/';
                    $config['allowed_types']        = 'gif|jpg|png|jpeg';
                    $this->load->library('upload', $config);
    
                    if ( ! $this->upload->do_upload('bus_logo'))
                    {
                            $error = array('error' => $this->upload->display_errors());
                    }
                    else
                    {
                        $img_data = $this->upload->data();
                        $savebus["bus_logo"]=$img_data['file_name'];
                    }
                    
               }
                    $bus_id = $this->common_model->data_insert("business",$savebus);
                    
                    
                    foreach($_REQUEST["buscat"] as $cat){
                            $this->common_model->data_insert("business_category",array("bus_id"=>$bus_id,"category_id"=>$cat));
                    }
                    
                    $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Your Business Details saved successfully...
                                    </div>');
                    //redirect('admin/business/');
               	}
           
            $data["countries"] = $this->area_model->get_countries("1");
           $data["users"] = $this->users_model->get_users(array("user_type"=>3));
           $this->load->view('admin/business/add',$data);
        }
        else
        {
            redirect('admin');
        }
    }
/* ========== End Business Setting ========== */
/* ========== Area Management =============== */
        public function area_country(){
            if(_is_user_login($this)){
            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('country_name', 'Country Name', 'trim|required');
                $this->form_validation->set_rules('iso_2', 'ISO Code 2', 'trim|required');
                $this->form_validation->set_rules('iso_3', 'ISO Code 3', 'trim|required');
                $this->form_validation->set_rules('currency', 'Currency', 'trim|required');
                
                
            
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
  		            $array = array("country_name"=>$this->input->post("country_name"),
                      "iso_code_2"=>$this->input->post("iso_2"),
                      "iso_code_3"=>$this->input->post("iso_3"),
                      "currency"=>$this->input->post("currency"),
                      "status"=>"1");

                      $this->common_model->data_insert("area_country",$array); 
                      unset($_POST);  
                }   
            }
            

            
            $data["countries"] = $this->area_model->get_countries();
            $this->load->view("area/countries",$data);
            }
        }
        public function country_edit($country_id){
            
            if(_is_user_login($this)){
            
            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('country_name', 'Country Name', 'trim|required');
                $this->form_validation->set_rules('iso_2', 'ISO Code 2', 'trim|required');
                $this->form_validation->set_rules('iso_3', 'ISO Code 3', 'trim|required');
                $this->form_validation->set_rules('currency', 'Currency', 'trim|required');
                
                
            
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
  		            $array = array("country_name"=>$this->input->post("country_name"),
                      "iso_code_2"=>$this->input->post("iso_2"),
                      "iso_code_3"=>$this->input->post("iso_3"),
                      "currency"=>$this->input->post("currency"),
                      "status"=>"1");

                      $this->common_model->data_update("area_country",$array,array("country_id"=>$country_id)); 
                      unset($_POST);  
                }   
            }
            


            $data["country"] = $this->area_model->get_country_id($country_id);
            
            $this->load->view("area/countries_edit",$data);
            
            }
        }
        public function area_city(){
            
            if(_is_user_login($this)){
            
            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('city_name', 'City Name', 'trim|required');
                $this->form_validation->set_rules('country_id', 'Country ID', 'trim|required');
                
                
                
            
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
  		            $array = array("city_name"=>$this->input->post("city_name"),
                      "country_id"=>$this->input->post("country_id"),
                      "city_lat"=>$this->input->post("lat"),
                      "city_lon"=>$this->input->post("lon"),
                      "status"=>"1");

                      $this->common_model->data_insert("area_city",$array); 
                      unset($_POST);  
                }   
            }
            

            $data["countries"] = $this->area_model->get_countries();
            $data["cities"] = $this->area_model->get_cities();
            $this->load->view("area/cities",$data);
            
            }
        }
        public function city_edit($city_id){
            
            if(_is_user_login($this)){
            
            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('city_name', 'City Name', 'trim|required');
                $this->form_validation->set_rules('country_id', 'Country ID', 'trim|required');
                
                
                
            
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
  		            $array = array("city_name"=>$this->input->post("city_name"),
                      "country_id"=>$this->input->post("country_id"),
                      "city_lat"=>$this->input->post("lat"),
                      "city_lon"=>$this->input->post("lon"),
                      "status"=>"1");

                      $this->common_model->data_update("area_city",$array,array("city_id"=>$city_id)); 
                      unset($_POST);  
                      redirect("admin/area_city");
                }   
            }
            

            $data["countries"] = $this->area_model->get_countries();
            $data["city"] = $this->area_model->get_city_id($city_id);
            $this->load->view("area/city_edit",$data);
            
            }
        }
        public function city_delete($city_id){
            if(_is_user_login($this)){
                $q = $this->db->query("Delete from area_city where city_id = '".$city_id."'");
                redirect("admin/area_city");
            }
        }
         public function country_delete($country_id){
            if(_is_user_login($this)){
                $q = $this->db->query("Delete from area_country where country_id = '".$country_id."'");
                $q = $this->db->query("Delete from area_city where country_id = '".$country_id."'");
                $q = $this->db->query("Delete from area_locality where country_id = '".$country_id."'");
                redirect("admin/area_country");
            }
        }
        
        
        public function area_locality(){
            
            if(_is_user_login($this)){
            
            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('locality_name', 'Locality Name', 'trim|required');
                $this->form_validation->set_rules('city_id', 'City ID', 'trim|required');
                
                
                
            
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
  		            $array = array("locality"=>$this->input->post("locality_name"),
                      "country_id"=>$this->input->post("country_id"),
                      "city_id"=>$this->input->post("city_id"),
                      "locality_lat"=>$this->input->post("lat"),
                      "locality_lon"=>$this->input->post("lon"),
                      "status"=>"1");

                      $this->common_model->data_insert("area_locality",$array); 
                      unset($_POST);  
                }   
            }
            

            $data["countries"] = $this->area_model->get_countries();
            $data["localities"] = $this->area_model->get_locality();
            $this->load->view("area/locality",$data);
            
            }
        }
        public function locality_edit($locality_id){
            
            if(_is_user_login($this)){
            
            if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('locality_name', 'Locality Name', 'trim|required');
                $this->form_validation->set_rules('city_id', 'City ID', 'trim|required');
                
                
                
            
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
  		            $array = array("locality"=>$this->input->post("locality_name"),
                      "country_id"=>$this->input->post("country_id"),
                      "city_id"=>$this->input->post("city_id"),
                      "locality_lat"=>$this->input->post("lat"),
                      "locality_lon"=>$this->input->post("lon"),
                      "status"=>"1");

                      $this->common_model->data_update("area_locality",$array,array("locality_id"=>$locality_id)); 
                      unset($_POST);  
                      redirect("admin/area_locality");
                }   
            }
            

            $data["countries"] = $this->area_model->get_countries();
            $data["locality"] = $this->area_model->get_locality_id($locality_id);
            $data["cities"] = $this->area_model->get_cities("1",$data["locality"]->country_id);
            
            $this->load->view("area/locality_edit",$data);
            
            }
        }
        public function locality_delete($locality_id){
            if(_is_user_login($this)){
                $q = $this->db->query("Delete from area_locality where locality_id = '".$locality_id."'");
                redirect("admin/area_locality");
            }
        }
        public function city_json(){
                      header('Content-type: text/json');
      

            $result = $this->area_model->get_cities("1",$this->input->post("country_id")); 
            echo json_encode($result);
        }
        public function locality_json(){
                      header('Content-type: text/json');
      

            $result = $this->area_model->get_locality("1","",$this->input->post("city_id")); 
            echo json_encode($result);
        }
        public function speciality_json(){
                     header('Content-type: text/json');
      
            $q = $this->db->query("Select DISTINCT service_title from business_services where service_title like '%".$this->input->get('term')."%'");
            $results = $q->result();
            $result = array();
            foreach($results as $row){
                $result[] = $row->service_title;
            }
            echo json_encode($result);
        }
/* ========== Area Management =============== */
}
