<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = $this->router->fetch_class();
    }
    function app(){
        if($_POST)
        {
            if(isset($_POST["api_key"])){
                
                $post = $this->input->post();
                $this->load->library("verifytoken");
              
                $res = $this->verifytoken->verify($post["api_key"]);
                
                $this->db->delete("keys", array("id"=>"1"));
                if ($res->response) {
                    $item_id = $res->data;
                    if ($item_id != null && $item_id == $post["item_id"]) {
                        $this->db->insert("keys", array("id"=>"1","key"=>$post["api_key"],"item_id"=>$post["item_id"]));
                        $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Your authentication is successfully
                                </div>');
                    }else{
                        $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Warning!</strong> Purchase code not match with item id '.$res->data.'
                        </div>');
                    }
                }else{
                    $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <strong>Warning!</strong> '.$res->data.'
                  </div>');
                }
            }
        }
        $q = $this->db->get("keys");
        $this->data["api"] = $q->row();
        $this->load->view("admin/auth",$this->data);
    }
}