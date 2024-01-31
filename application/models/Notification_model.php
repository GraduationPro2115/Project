<?php
class Notification_model extends CI_Model{
    function send_notification(){
        if(isset($_POST["type"]) && $_POST["type"] == "notification"){
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
                $this->form_validation->set_rules('message', 'Message', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		}else
                {
                        $fcm_options = array();
                        
                        $message = array("title"=>$this->input->post("subject"),
                        "body"=>$this->input->post("message"),
                        "message"=>$this->input->post("message"),"image"=>"","created_at"=>date("Y-m-d h:i:s"));
                        
                        if($_FILES["file"]["size"] > 0){
                            $config['upload_path']          = './uploads/notification/';
                            if(!file_exists($config['upload_path'])){
                                mkdir($config['upload_path']);
                            }
                            $config['allowed_types']        = 'gif|jpg|png|jpeg';
                            $this->load->library('upload', $config);
            
                            if ( ! $this->upload->do_upload('file'))
                            {
                                    $error = array('error' => $this->upload->display_errors());
                            }
                            else
                            {
                                $img_data = $this->upload->data();
                                $message["image"] = base_url("uploads/notification/".$img_data['file_name']);
                                $message["imageUrl"] = base_url("uploads/notification/".$img_data['file_name']);
                                $fcm_options["image"] = base_url("uploads/notification/".$img_data['file_name']);
                            }

                            
                       }
                        $this->load->helper("gcm_helper");
                        $gcm = new GCM();
                            $result = $gcm->send_topics($this->config->item("FCM_TOPIC"),$message,"android",$fcm_options);    
                            
                }
                
            }
    }
}
?>