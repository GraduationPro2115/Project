<?php
Class MY_Controller Extends CI_Controller{

    public function __construct(){
        parent::__construct();
        if($this->session->userdata("user_id") != "" && $this->session->userdata("user_id") != NULL){
        $user = $this->users_model->get_user_by_id(_get_current_user_id($this));
        $newdata = array(
                                                   'user_name'  => $user->user_fullname,
                                                   'user_email'     => $user->user_email,
                                                   'logged_in' => TRUE,
                                                   'user_id'=>$user->user_id,
                                                   'user_type_id'=>$user->user_type_id,
                                                   'user_image'=>$user->user_image
                                                  );
                            $this->session->set_userdata($newdata);
                       
        }
        $this->db->trans_strict(FALSE);
    }
    public function get_business_list(){
        $this->load->model("business_model");
        return $this->business_model->get_businesses($userid=3);
    }
    public function common(){
        // code here
        $this->load->model("category_model");
        $data["categories"] = $this->category_model->get_categories();
        
        $this->load->model("area_model");
        $data["countries"] = $this->area_model->get_countries("1");
        $data["cities"] = $this->area_model->get_cities("1");
        return $data;
    }
}
?>