<?php
class Users_model extends CI_Model{
    public function get_users($filter=array()){
        $filter = "";
        if(!empty($filter))
        {
            if(key_exists("user_type")){
                $filter .=" and users.user_type_id = '".$filter["user_type"]."'";
            }
            if(key_exists("status")){
                $filter .=" and users.user_status = '".$filter["status"]."'";
            }
        }
        $q = $this->db->query("select users.*, user_types.user_type_title from users inner join user_types on user_types.user_type_id = users.user_type_id where 1 ".$filter);
        return $q->result();
    }
    public function get_users_counts($user_type = ""){
        $filter = "";
        if($user_type!=""){
            $filter .=" and user_type_id = '".$user_type."' ";
        }
        $q = $this->db->query("Select count(*) as total_users from users where 1 ".$filter);
        $row = $q->row();
        return $row->total_users;
    }
    public function get_user($user_id){
        $q = $this->db->query("select users.*, user_types.user_type_title from users inner join user_types on user_types.user_type_id = users.user_type_id where user_types.user_type_id=".$user_id);
        return $q->result();
    }
    public function get_user_messages($user_id="",$read=""){
        $filter = "";
        if($user_id!=""){
            $filter = " and ( user_messages.user_id = '".$user_id."' or user_messages.user_id = 0 ) ";
        }
        if($read!=""){
            $filter = " and  user_messages.message_status = '".$read."' ";
            
        }
        $sql = "Select user_messages.*, users.user_email from user_messages left outer join users on users.user_id = user_messages.user_id where 1 ".$filter." order by on_date DESC";
        $q = $this->db->query($sql);
        return $q->result();
    }
    public function get_user_by_id($id){
        $q = $this->db->query("select * from users where  user_id = '".$id."' limit 1");
        return $q->row();
    }
    public function get_user_type(){
        $q = $this->db->query("select * from user_types");
        return $q->result();
    }
    public function get_user_type_id($id){
        $q = $this->db->query("select * from user_types where user_type_id = '".$id."'");
        return $q->row();
    }
    public function get_user_type_access($type_id){
        $q = $this->db->query("select * from user_type_access where user_type_id = '".$type_id."'");
        return $q->result();
    }

    function check_match_password($old_pass,$userid)
	{	
		$q=$this->db->get_where('users',array('user_id'=>$userid));
        $user = $q->row();
        if($old_pass == _decrypt_val($user->user_password)){
            return 1;
        }
        return 0;
		// if($q->num_rows()>0)
		// {
		// 	return 1;
		// }
		// else
		// {
		// 	return 0;
		// }			
	}  

}
?>