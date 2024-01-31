<?php
class Doctor_model extends CI_Model{
    function get_doctor_by_id($doct_id){
        $q  = $this->db->query("Select business_doctinfo.*,users.user_email from business_doctinfo 
        inner join users on users.user_id = business_doctinfo.doct_id where business_doctinfo.doct_id = '".$doct_id."' limit 1");
        return $q->row();
    }
}
?>