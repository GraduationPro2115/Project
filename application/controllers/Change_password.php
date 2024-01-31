<?php defined('BASEPATH') or exit('No direct script access allowed');
class Change_password extends MY_Controller
{
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = $this->router->fetch_class();

        // if (!_is_user_login($this)) {
        //     redirect("login");
        //     exit();
        // }

        $this->load->model("users_model");
    }

    public function index()
    {
        if (isset($_POST['change_password'])) {
            $this->action();
        }
        $this->data["field"] = $this->input->post();
       
        $this->load->view("admin/change_password", $this->data);
    }

    function action()
    {
        $post = $this->input->post();
        $this->load->library('form_validation');

        $this->form_validation->set_rules('old_password', "Old Password",
            'trim|required');

        $this->form_validation->set_rules('new_password', "New Password",
            'trim|required');
        $this->form_validation->set_rules('confirm_password', "Confirm Password",
            'trim|required|matches[new_password]');

        $responce = array();
        if ($this->form_validation->run() == false) {

            if ($this->form_validation->error_string() != "") {              
                $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> ' . $this->form_validation->error_string() . '
                                    </div>');
            }
        } else {
            $userid = _get_current_user_id($this);

            $old_pass = $post['old_password'];
            $is_match = $this->users_model->check_match_password($old_pass,$userid);
            if ($is_match == 1) {
                //if(!IS_TEST){
                    $add_data = array("user_password" => _encrypt_val($post['confirm_password']));
                    $this->common_model->data_update("users", $add_data, array("user_id" => $userid), false);
                //}
               
                //_set_flash_message(_l("msg_password_change_success"), 'success');
                $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                        <i class="fa fa-check"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Your password is updated successfully...
                                    </div>');
            } else {
                $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> Old password not match.
                                    </div>');
            }
        }
    }

}
