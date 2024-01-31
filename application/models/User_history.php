<?php


class User_history extends CI_Model
{
    public function get_user_history($user_id){
        $q = $this->db->query("select * from user_jistory where user_id =".$user_id);
        return $q->result();
    }

}