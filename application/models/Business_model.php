<?php
class Business_model extends CI_Model
{
    /* get all business list for user and andmin */
    function get_businesses($userid)
    {
        if ($userid == 0) {
            $q = $this->db->query("Select business.*, users.user_fullname from business inner join users on users.user_id = business.user_id");
            return $q->result();
        }
        if ($userid == 3) {
            $sql = "Select business.*, users.user_fullname from business inner join users on users.user_id = business.user_id where business.user_id=" . _get_current_user_id($this);
            $q = $this->db->query($sql);
            return $q->result();
        }
    }
    function get_business_by_category($cat_id = "", $lat = "", $lon = "", $rad = "", $search = "", $offcet = "", $number_row = "", $params = array())
    {


        $filter = "";
        $join = "";
        if ($cat_id != "") {
            $filter .= "  and `business_category`.`category_id`='" . $cat_id . "'  ";
            $join .= " left outer join `business_category` on `business_category`.`bus_id` = `business`.`bus_id` ";
        }

        /*if($search!=""){
    
    $sparts = explode(" ",$search);
    $stext = "";
    foreach($sparts as $sp){
        $stext .= " or `business`.`bus_google_street` like '%".$sp."%' ";
    }     
    $filter .= " and (`business`.`bus_title` like '%".$search."%' or `business`.`bus_description` like '%".$search."%'  $stext ) ";
}*/
        if ($search != "") {

            $sparts = explode(" ", $search);
            $stext = "";
            foreach ($sparts as $sp) {
                $stext .= " or business.bus_google_street like '%" . $sp . "%' ";
            }
            $join .= " left outer join business_doctinfo on business_doctinfo.bus_id = business.bus_id ";
            $filter .= " and (business.bus_title like '%" . $search . "%' or business.bus_description like '%" . $search . "%' or business_doctinfo.doct_name like '%" . $search . "%' or business_doctinfo.doct_degree like '%" . $search . "%' or business_doctinfo.doct_speciality like '%" . $search . "%' $stext ) ";
        }

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $filter .= " and " . trim($key) . " = '" . trim($value) . "' ";
            }
        }

        $near_fields = "";
        $near_order = "";
        if ($this->config->item("ALLOW_NEAR_BY") && $lat != "" && $lon != "" && $rad != "") {
            $near_fields = " , 111.1111 *
    DEGREES(ACOS(COS(RADIANS(bus_latitude))
         * COS(RADIANS($lat))
         * COS(RADIANS(bus_longitude) - RADIANS($lon))
         + SIN(RADIANS(bus_latitude))
         * SIN(RADIANS($lat)))) AS distance_in_km
";
            $near_order = " HAVING distance_in_km < $rad   
 ORDER BY distance_in_km
";
        }
        $limit = "";

        if ($offcet != "" && $number_row != "") {
            $limit .= " limit " . $offcet . "," . $number_row;
        }
        $sql = "Select DISTINCT business.*,area_country.currency,business_appointment_schedule.working_days, business_appointment_schedule.morning_time_start, business_appointment_schedule.evening_time_end, users.user_fullname, ifnull(reviews.avg_rating, 0) as avg_rating, ifnull(reviews.total_rating, 0) as total_rating, ifnull(reviews.count, 0) as review_count " . $near_fields . "
 FROM business 
 inner join users on users.user_id = business.user_id 
 left outer join (Select count(id) as count, avg(ratings) as avg_rating, sum(ratings) as total_rating, bus_id from business_reviews group by bus_id ) as reviews on reviews.bus_id = business.bus_id
 left outer join business_appointment_schedule on business_appointment_schedule.bus_id = business.bus_id
 left outer join area_country on area_country.country_id = business.country_id
 " . $join . "
 where business.bus_status = 1 " . $filter . " 
" . $near_order . " " . $limit;

        $q = $this->db->query($sql);
        return $q->result();
    }

    function get_business_details_by_id($id, $user_id = "")
    {
        $filter = "";
        if (!empty($user_id)) {
            $filter .= " and business.user_id = '" . $user_id . "' ";
        }

        $sql = " Select business.*,business_appointment_schedule.working_days, business_appointment_schedule.morning_time_start, business_appointment_schedule.evening_time_end, users.user_fullname, ifnull(reviews.avg_rating, 0) as avg_rating, ifnull(reviews.total_rating, 0) as total_rating, ifnull(reviews.count, 0) as review_count, 
area_country.country_name, area_country.currency as currency_code 
 FROM business 
 inner join users on users.user_id = business.user_id 
 left outer join (Select count(id) as count, avg(ratings) as avg_rating, sum(ratings) as total_rating, bus_id from business_reviews group by bus_id ) as reviews on reviews.bus_id = business.bus_id
 left outer join business_appointment_schedule on business_appointment_schedule.bus_id = business.bus_id
 left outer join area_country on business.country_id = area_country.country_id
 where (business.bus_id = '" . $id . "' or business.bus_slug = '" . $id . "') $filter limit 1";

        $q = $this->db->query($sql);
        return $q->row();
    }
    public function get_businesses_by_id($id)
    {
        $q = $this->db->query("select * from business where  bus_id = '" . $id . "' limit 1");
        return $q->row();
    }
    public function get_businesses_doctor($bus_id = "")
    {
        if ($bus_id == "") {
            $q = $this->db->query("select * from business_doctinfo where  bus_id in (select bus_id from business where user_id = '" . $this->session->userdata("user_id") . "')");
            return $q->result();
        } else {
            $q = $this->db->query("select * from business_doctinfo where  bus_id = '" . $bus_id . "'");
            return $q->result();
        }
    }
    public function get_businesses_doctor_by_id($doct_id, $user_id = "")
    {
        $filter = "";
        $join = "";
        if (!empty($user_id)) {
            $filter .= " and business.user_id = '" . $user_id . "' ";
            $join .= " inner join business on business.bus_id = business_doctinfo.bus_id ";
        }
        $q = $this->db->query("select business_doctinfo.* from business_doctinfo $join where  business_doctinfo.doct_id = '" . $doct_id . "' " . $filter);
        return $q->row();
    }
    public function get_businesses_category()
    {
        $q = $this->db->query("select * from business_category");
        return $q->result();
    }
    public function set_listing($id)
    {
        $filter = "";
        $user_type_id = _get_current_user_type_id($this);
        if ($user_type_id != 0)
            $filter .= " and user_id = '" . _get_current_user_id($this) . "' ";
        $q = $this->db->query("select * from `business` WHERE business.bus_id =" . $id . " $filter");
        return $q->row();
    }
    /* business service */
    public function get_business_service($id, $user_id = "")
    {
        $filter = "";
        $join = "";
        if (!empty($user_id)) {
            $filter .= " and business.user_id = '" . $user_id . "' ";
            $join .= " inner join business on business.bus_id = business_services.bus_id ";
        }

        $q = $this->db->query("select business_services.* from `business_services` $join WHERE business_services.bus_id =" . $id . " " . $filter);
        return $q->result();
    }
    public function get_business_service_by_id($id)
    {
        $q = $this->db->query("select business_services.* from business_services
         where  business_services.id = '" . $id . "' limit 1");
        return $q->row();
    }
    /* business review */
    public function get_business_review($id, $user_id = "")
    {
        $filter = "";
        $join = "";
        if (!empty($user_id)) {
            $filter .= " and business.user_id = '" . $user_id . "' ";
            $join .= " inner join business on business.bus_id = business_reviews.bus_id ";
        }
        $q = $this->db->query("select business_reviews.*, users.user_fullname from `business_reviews` $join inner join users on users.user_id = business_reviews.user_id WHERE business_reviews.bus_id =" . $id . " $filter order by on_date DESC");
        return $q->result();
    }
    public function get_business_review_by_id($id)
    {
        $q = $this->db->query("select  business_reviews.*, users.user_fullname  from business_reviews 
        inner join users on users.user_id = business_reviews.user_id  where  business_reviews.id = '" . $id . "' limit 1");
        return $q->row();
    }

    /* business photo */
    public function get_business_photo($id, $user_id = "")
    {
        $filter = "";
        $join = "";
        if (!empty($user_id)) {
            $filter .= " and business.user_id = '" . $user_id . "' ";
            $join .= " inner join business on business.bus_id = business_photo.bus_id ";
        }

        $q = $this->db->query("select business_photo.* from `business_photo` $join WHERE business_photo.bus_id =" . $id . " " . $filter);
        return $q->result();
    }
    public function get_business_photo_by_id($id)
    {
        $q = $this->db->query("select business_photo.* from business_photo 
        where  business_photo.id = '" . $id . "' limit 1");
        return $q->row();
    }
    /* business appointment */
    public function get_business_appointment_group($from_date = "", $to_date = "", $user_id = "", $doct_id = "")
    {
        $filter = "";
        if ($from_date != "") {
            $filter .= " and appointment_date >= '" . $from_date . "' ";
        }
        if ($to_date != "") {
            $filter .= " and appointment_date <= '" . $to_date . "' ";
        }

        $join = "";
        if ($user_id != "") {
            $join .= " inner join (select bus_id from business where business.user_id = '" . $user_id . "') as buss on buss.bus_id =  business_appointment.bus_id ";
            //$filter .=" and business.user_id = '".$user_id."' ";
        }
        if ($doct_id != "") {
            $filter .= " and business_appointment.doct_id = '" . $doct_id . "' ";
        }
        $sql = "Select count(*) as count_app,business_appointment.appointment_date  from business_appointment $join where 1 " . $filter . " group by appointment_date ";

        $q = $this->db->query($sql);

        return $q->result();
    }
    //  public function get_user_appointment($user_id){
    //     $sql = "Select business_appointment.*, business.*, area_country.country_name, area_country.currency, business_doctinfo.doct_name, business_doctinfo.doct_degree,
    //     IF(business_appointment.payment_type = 'paypal' OR business_appointment.status = 1, true, false) as is_paid
    //     from business_appointment 
    //     inner join business on business.bus_id = business_appointment.bus_id
    //     inner join business_doctinfo on business_doctinfo.doct_id =  business_appointment.doct_id
    //     left outer join area_country on area_country.country_id = business.country_id
    //     where business_appointment.user_id = '".$user_id."' order by business_appointment.appointment_date DESC,business_appointment.start_time DESC";
    //     $q = $this->db->query($sql);
    //     return $q->result();

    //  }
    public function get_user_appointment($user_id)
    {
        $sql = "Select business_appointment.*, business.*, area_country.country_name, area_country.currency, business_doctinfo.doct_name, business_doctinfo.doct_photo, business_doctinfo.doct_degree, 
        IF(business_appointment.payment_type = 'paypal' OR business_appointment.status = 1, true, false) as is_paid
        , ifnull(bus_app_service.taken_time, '00:00:00') as taken_time, ifnull(bus_app_service.total_service,0) as total_service, ( ifnull(bus_app_service.total_amount,0) + business.bus_fee) as total_amount
        from business_appointment 
        inner join business on business.bus_id = business_appointment.bus_id
        inner join business_doctinfo on business_doctinfo.doct_id =  business_appointment.doct_id
        left outer join area_country on area_country.country_id = business.country_id
        left outer join (Select SEC_TO_TIME( SUM( TIME_TO_SEC( business_services.business_approxtime * business_appointment_services.service_qty ) ) ) as taken_time
        , SUM(`business_appointment_services`.service_qty) as total_service 
        ,  sum( (business_services.service_price - (business_services.service_price * business_services.service_discount / 100 ) * business_appointment_services.service_qty ) ) as total_amount
        , `business_appointment_services`.`busness_appointment_id` from `business_appointment_services`
            inner join `business_services` on `business_services`.`id` = `business_appointment_services`.`busness_service_id` group by `business_appointment_services`.`busness_appointment_id` ) as bus_app_service  on bus_app_service.`busness_appointment_id` = `business_appointment`.`id`
        where business_appointment.user_id = '" . $user_id . "' order by business_appointment.appointment_date DESC,business_appointment.start_time DESC";
        $q = $this->db->query($sql);
        return $q->result();
    }
    public function get_business_appointment($bus_id = "", $user_id = "", $fdate = "", $tdate = "", $doct_id = "")
    {
        $filter = "";
        if ($bus_id != "") {
            $filter .= "and business_appointment.bus_id =" . $bus_id;
        }
        $join = "";
        if ($user_id != "") {
            $join .= " inner join (select bus_id from business where business.user_id = '" . $user_id . "') as buss on buss.bus_id =  business_appointment.bus_id ";
            //$filter .=" and business.user_id = '".$user_id."' ";
        }
        if ($fdate != "") {
            $filter .= " and business_appointment.appointment_date >= '" . $fdate . "'";
        }
        if ($tdate != "") {
            $filter .= " and business_appointment.appointment_date <= '" . $tdate . "'";
        }
        if ($doct_id != "") {
            $filter .= " and business_appointment.doct_id = '" . $doct_id . "'";
        }
        $sql = "select business_appointment.*, users.user_fullname, ifnull(bus_app_service.taken_time, '00:00:00') as taken_time,
            IF(business_appointment.payment_type = 'paypal' OR business_appointment.status = 1, true, false) as is_paid
            from `business_appointment` 
            inner join users on users.user_id = business_appointment.user_id
            left outer join (Select SEC_TO_TIME( SUM( TIME_TO_SEC( business_services.business_approxtime * business_appointment_services.service_qty ) ) ) as taken_time, `business_appointment_services`.`busness_appointment_id` from `business_appointment_services`
            inner join `business_services` on `business_services`.`id` = `business_appointment_services`.`busness_service_id` group by `business_appointment_services`.`busness_appointment_id` ) as bus_app_service  on bus_app_service.`busness_appointment_id` = `business_appointment`.`id`
            " . $join . " 
            WHERE 1 " . $filter;

        $q = $this->db->query($sql);

        return $q->result();
    }
    public function get_doctor_appointment($fdate = "", $tdate = "", $doct_id = "")
    {
        $filter = "";
        $join = "";
        if ($fdate != "") {
            $filter .= " and business_appointment.appointment_date >= '" . $fdate . "'";
        }
        if ($tdate != "") {
            $filter .= " and business_appointment.appointment_date <= '" . $tdate . "'";
        }
        if ($doct_id != "") {
            $filter .= " and business_appointment.doct_id = '" . $doct_id . "'";
        }
        $sql = "select business_appointment.*, users.user_fullname, ifnull(bus_app_service.taken_time, '00:00:00') as taken_time,
         IF(business_appointment.payment_type = 'paypal' OR business_appointment.status = 1, true, false) as is_paid
         from `business_appointment` 
            inner join users on users.user_id = business_appointment.user_id
            left outer join (Select SEC_TO_TIME( SUM( TIME_TO_SEC( business_services.business_approxtime * business_appointment_services.service_qty ) ) ) as taken_time, `business_appointment_services`.`busness_appointment_id` from `business_appointment_services`
            inner join `business_services` on `business_services`.`id` = `business_appointment_services`.`busness_service_id` group by `business_appointment_services`.`busness_appointment_id` ) as bus_app_service  on bus_app_service.`busness_appointment_id` = `business_appointment`.`id`
            " . $join . " 
            WHERE 1 " . $filter;

        $q = $this->db->query($sql);

        return $q->result();
    }
    public function get_business_appointment_by_id($id)
    {
        //$q = $this->db->query("select * from business_appointment where  id = '".$id."' limit 1");
        $sql = "Select business_appointment.*, business.*, area_country.country_name, area_country.currency, business_doctinfo.doct_name, business_doctinfo.doct_photo, business_doctinfo.doct_degree, 
        IF(business_appointment.payment_type = 'paypal' OR business_appointment.status = 1, true, false) as is_paid
        , ifnull(bus_app_service.taken_time, '00:00:00') as taken_time, IFNULL(bus_app_service.total_service, 0 ) as total_service, ( IFNULL( bus_app_service.total_amount, 0 ) + business.bus_fee) as total_amount
        from business_appointment 
        inner join business on business.bus_id = business_appointment.bus_id
        inner join business_doctinfo on business_doctinfo.doct_id =  business_appointment.doct_id
        left outer join area_country on area_country.country_id = business.country_id
        left outer join (Select SEC_TO_TIME( SUM( TIME_TO_SEC( business_services.business_approxtime * business_appointment_services.service_qty ) ) ) as taken_time
        , SUM(`business_appointment_services`.service_qty) as total_service 
        ,  sum( (business_services.service_price - (business_services.service_price * business_services.service_discount / 100 ) * business_appointment_services.service_qty ) ) as total_amount
        , `business_appointment_services`.`busness_appointment_id` from `business_appointment_services`
            inner join `business_services` on `business_services`.`id` = `business_appointment_services`.`busness_service_id` group by `business_appointment_services`.`busness_appointment_id` ) as bus_app_service  on bus_app_service.`busness_appointment_id` = `business_appointment`.`id`
        where business_appointment.id = '" . $id . "' order by business_appointment.appointment_date DESC,business_appointment.start_time DESC";
        $q = $this->db->query($sql);
        return $q->row();
    }
    public function get_business_appointment_temp_by_id($id)
    {
        //$q = $this->db->query("select * from business_appointment_temp where  id = '".$id."' limit 1");
        $sql = "Select business_appointment_temp.*, business.*, area_country.country_name, area_country.currency, business_doctinfo.doct_name, business_doctinfo.doct_photo, business_doctinfo.doct_degree, 
        ifnull(bus_app_service.taken_time, '00:00:00') as taken_time, IFNULL(bus_app_service.total_service, 0 ) as total_service, ( IFNULL( bus_app_service.total_amount, 0 ) + business.bus_fee) as total_amount
        from business_appointment_temp 
        inner join business on business.bus_id = business_appointment_temp.bus_id
        inner join business_doctinfo on business_doctinfo.doct_id =  business_appointment_temp.doct_id
        left outer join area_country on area_country.country_id = business.country_id
        left outer join (Select SEC_TO_TIME( SUM( TIME_TO_SEC( business_services.business_approxtime * business_appointment_services_temp.service_qty ) ) ) as taken_time
        , SUM(`business_appointment_services_temp`.service_qty) as total_service 
        ,  sum( (business_services.service_price - (business_services.service_price * business_services.service_discount / 100 ) * business_appointment_services_temp.service_qty ) ) as total_amount
        , `business_appointment_services_temp`.`busness_appointment_id` from `business_appointment_services_temp`
            inner join `business_services` on `business_services`.`id` = `business_appointment_services_temp`.`busness_service_id` group by `business_appointment_services_temp`.`busness_appointment_id` ) as bus_app_service  on bus_app_service.`busness_appointment_id` = `business_appointment_temp`.`id`
        where business_appointment_temp.id = '" . $id . "' order by business_appointment_temp.appointment_date DESC,business_appointment_temp.start_time DESC";
        $q = $this->db->query($sql);
        return $q->row();
    }
    public function get_business_appointment_by_user_id($user_id, $id)
    {
        $q = $this->db->query("select * from business_appointment where  id = '" . $id . "' and user_id = '" . $user_id . "' limit 1");
        return $q->row();
    }
    public function get_business_appointment_count()
    {

        $typeid = _get_current_user_type_id($this);
        if ($typeid == 3 || $typeid == "3") {
            $user_id = _get_current_user_id($this);
            $q = $this->db->query("Select count(*) as total_count from business_appointment where bus_id in (select bus_id from business where user_id = '" . $user_id . "')");
            $row = $q->row();
            return $row->total_count;
        } else if ($typeid == 1 || $typeid == "1") {
            $user_id = _get_current_user_id($this);
            $q = $this->db->query("Select count(*) as total_count from business_appointment where doct_id = '" . $user_id . "'");
            $row = $q->row();
            return $row->total_count;
        } else {
            $q = $this->db->query("Select count(*) as total_count from business_appointment ");
            $row = $q->row();
            return $row->total_count;
        }
    }
    /* business appointment service */
    public function get_business_appointment_service($id)
    {
        $q = $this->db->query("select business_appointment_services.*, business_services.business_approxtime, business_services.service_title,  business_services.service_price, business_services.service_discount from `business_appointment_services` inner join business_services on business_services.id = business_appointment_services.busness_service_id WHERE business_appointment_services.busness_appointment_id =" . $id);
        return $q->result();
    }
    public function get_business_appointment_service_temp($id)
    {
        $q = $this->db->query("select business_appointment_services_temp.*, business_services.business_approxtime, business_services.service_title,  business_services.service_price, business_services.service_discount from `business_appointment_services_temp` inner join business_services on business_services.id = business_appointment_services_temp.busness_service_id WHERE business_appointment_services_temp.busness_appointment_id =" . $id);
        return $q->result();
    }
    public function get_business_appointment_service_by_id($id)
    {
        $q = $this->db->query("select * from business_appointment where  id = '" . $id . "' limit 1");
        return $q->row();
    }
    public function get_business_schedule($id)
    {
        $q = $this->db->query("select business_appointment_schedule.* from business_appointment_schedule 
        
        where business_appointment_schedule.bus_id = '" . $id . "' limit 1");
        return $q->row();
    }
    public function get_business_reviews($bus_id)
    {
        $q = $this->db->query("Select business_reviews.*,users.user_fullname, users.user_image from business_reviews 
        inner join users on users.user_id = business_reviews.user_id 
        where business_reviews.bus_id = '" . $bus_id . "' order by business_reviews.on_date DESC");
        return $q->result();
    }
    public function get_business_reviews_by_id($id)
    {
        $q = $this->db->query("Select business_reviews.*,users.user_fullname, users.user_image from business_reviews 
        inner join users on users.user_id = business_reviews.user_id 
        where business_reviews.id = '" . $id . "' order by business_reviews.on_date DESC");
        return $q->row();
    }
    public function get_reviews_counts($bus_id = "", $user_id = "")
    {
        $filter = "";
        if ($bus_id != "") {
            $filter .= " and business_reviews.bus_id = '" . $bus_id . "' ";
        }
        $join = "";
        if ($user_id != "") {
            $join .= " inner join (select bus_id from business where user_id = '" . $user_id . "' ) as buss on buss.bus_id = business_reviews.bus_id ";
        }
        $sql = "Select count(*) as count_review  from business_reviews $join where 1 " . $filter;

        $q = $this->db->query($sql);
        $row = $q->row();
        return $row->count_review;
    }


    /*----- Time Slot for Appointment -----*/
    public function get_time_slot($date, $bus_id, $doct_id)
    {
        $time_slots_date_array = array();
        $time_slots_array = array();
        $result_app = $this->db->query("Select * from business_appointment where appointment_date = '" . $date . "' and bus_id = '" . $bus_id . "' and doct_id = '" . $doct_id . "'");
        $appointment = $result_app->result();

        $result = $this->db->query("Select * from business_appointment_schedule where  bus_id = '" . $bus_id . "'  limit 1");
        $schedule = $result->row();
        if (!empty($schedule)) {
            $allow_days =  explode(',', $schedule->working_days);

            $c_day = strtolower(date('D', strtotime($date)));
            if (in_array($c_day, $allow_days)) {

                $time_slots_morning_array = array();
                $start_time_morning =  $schedule->morning_time_start;
                $next_time_morning;
                do {
                    $next_time_morning = strtotime("+" . $schedule->morning_tokens . " minutes", strtotime($start_time_morning));
                    $time = date("H:i:s", $next_time_morning);

                    $start_time_morning = $time;
                    $is_booked = false;
                    foreach ($appointment as $app) {
                        if (strtotime($app->start_time) == $next_time_morning) {
                            $is_booked = true;
                        }
                    }
                    if ($date == date("Y-m-d") && strtotime($time) <= strtotime(date("H:i:s"))) {
                        $is_booked = true;
                    }
                    $time_slots_morning_array[] = array("slot" => $time, "is_booked" => $is_booked, "time_token" => 1);
                } while ($next_time_morning < strtotime($schedule->morning_time_end));
                $time_slots_array["morning"] = $time_slots_morning_array;

                $time_slots_afternoon_array = array();
                $start_time_afternoon =  $schedule->afternoon_time_start;
                $next_time_afternoon = "";
                do {
                    $next_time_afternoon = strtotime("+" . $schedule->afternoon_tokens . " minutes", strtotime($start_time_afternoon));
                    $time = date("H:i:s", $next_time_afternoon);

                    $start_time_afternoon = $time;
                    $is_booked = false;
                    foreach ($appointment as $app) {
                        if (strtotime(date("H:i:s", strtotime($app->start_time))) == strtotime($time)) {
                            $is_booked = true;
                        }
                    }
                    if ($date == date("Y-m-d") && strtotime($time) <= strtotime(date("H:i:s"))) {
                        $is_booked = true;
                    }
                    $time_slots_afternoon_array[] = array("slot" => $time, "is_booked" => $is_booked, "time_token" => 2);
                } while ($next_time_afternoon < strtotime($schedule->afternoon_time_end));

                $time_slots_array["afternoon"] = $time_slots_afternoon_array;


                $time_slots_evening_array = array();
                $start_time_evening =  $schedule->evening_time_start;
                $next_time_evening;
                do {
                    $next_time_evening = strtotime("+" . $schedule->evening_tokens . " minutes", strtotime($start_time_evening));
                    $time = date("H:i:s", $next_time_evening);

                    $start_time_evening = $time;
                    $is_booked = false;
                    foreach ($appointment as $app) {
                        if (strtotime($app->start_time) == $next_time_evening) {
                            $is_booked = true;
                        }
                    }
                    if ($date == date("Y-m-d") && strtotime($time) <= strtotime(date("H:i:s"))) {
                        $is_booked = true;
                    }
                    $time_slots_evening_array[] = array("slot" => $time, "is_booked" => $is_booked, "time_token" => 3);
                } while ($next_time_evening < strtotime($schedule->evening_time_end));
                $time_slots_array["evening"] = $time_slots_evening_array;

                $time_slots_date_array[$date] = $time_slots_array;

                //$date =  date("Y-m-d",strtotime("+1 Day" , strtotime( $date )));

            }
            return $time_slots_array;
        } else {
            return array();
        }
    }
    /*----- End time slot for appoitnment -----*/
    /*----- Time Slot for Appointment
    public function get_time_slot($bus_id, $total_time, $app_date){
                        $data = array();
                        $date  = date("Y-m-d");
                        if($app_date!=""){
                            $date = $app_date; 
                        }
                        
                        $day_name =  date("D", strtotime($date));
                        $q = $this->db->query("Select * from business_appointment_schedule where bus_id = '".$bus_id."' limit 1");
                        $schedule = $q->row();
                        if(!empty($schedule)){
                            
                            
                            $total_expand_time =  explode(':',$total_time);
                            $total_expand_time_add = '+'.$total_expand_time[0].' hour +'.$total_expand_time[1].' minutes';
                            
                            $day_array = explode(",",$schedule->working_days);
                            if(in_array(strtolower($day_name) ,$day_array)){
                               
                            $morning =  $this->db->query("Select  ifnull( SEC_TO_TIME( SUM( TIME_TO_SEC( services.business_approxtime * a_service.service_qty ) ) ), '00:00:00' ) AS total_time, count(DISTINCT(app.id)) as total_app from 
                            business_appointment_services as a_service
                            inner join business_appointment as app on app.id = a_service.busness_appointment_id
                            inner join business_services as services on services.id = a_service.busness_service_id
                            where app.appointment_date = '".$date."' and app.bus_id = '".$bus_id."' and app.time_token = 1
                            limit 1
                            ");
                            
                            
                            $morning_total_time  = $morning->row();
                            
                            $q = $this->db->query("select ADDTIME(morning_time_start, '".$morning_total_time->total_time."') as time_slot, morning_time_end from business_appointment_schedule where bus_id = '".$bus_id."' limit 1");
                            $morning_time_slot = $q->row();
                            
                            $m_slot = strtotime($total_expand_time_add, strtotime($morning_time_slot->time_slot));
                            if($m_slot > strtotime($morning_time_slot->morning_time_end)){
                                
                            }else{              
                            
                                $data["morning_appointment"] = $morning_total_time->total_app;
                                $data["morning_time_slot"] = $morning_time_slot->time_slot;
                                $data["morning_time_slot_end"] = date("H:i:s",$m_slot);
                                $data["morning_time_end"] = $morning_time_slot->morning_time_end;
                        
                            }
                            


                            $afternoon =  $this->db->query("Select  ifnull( SEC_TO_TIME( SUM( TIME_TO_SEC( services.business_approxtime * a_service.service_qty ) ) ), '00:00:00' ) AS total_time, count(DISTINCT(app.id)) as total_app  from 
                            business_appointment_services as a_service
                            inner join business_appointment as app on app.id = a_service.busness_appointment_id
                            inner join business_services as services on services.id = a_service.busness_service_id
                            where app.appointment_date = '".$date."' and app.bus_id = '".$bus_id."' and app.time_token = 2
                            limit 1
                            ");
                            
                            
                            $afternoon_total_time  = $afternoon->row();
                            
                            $q2 = $this->db->query("select ADDTIME(afternoon_time_start, '".$afternoon_total_time->total_time."') as time_slot, afternoon_time_end from business_appointment_schedule where bus_id = '".$bus_id."' limit 1");
                            $afternoon_time_slot = $q2->row();
                            
                            $af_slot = strtotime($total_expand_time_add, strtotime($afternoon_time_slot->time_slot));
                            if($af_slot > strtotime($afternoon_time_slot->afternoon_time_end)){
                                
                            }else{              

                            
                            $data["afternoon_appointment"] = $afternoon_total_time->total_app;
                            $data["afternoon_time_slot"] = $afternoon_time_slot->time_slot;
                            $data["afternoon_time_slot_end"] = date("H:i:s",$af_slot);
                            $data["afternoon_time_end"] = $afternoon_time_slot->afternoon_time_end;
    
                            }
                            
                            
                            $evening =  $this->db->query("Select  ifnull( SEC_TO_TIME( SUM( TIME_TO_SEC( services.business_approxtime * a_service.service_qty ) ) ), '00:00:00' ) AS total_time, count(DISTINCT(app.id)) as total_app  from 
                            business_appointment_services as a_service
                            inner join business_appointment as app on app.id = a_service.busness_appointment_id
                            inner join business_services as services on services.id = a_service.busness_service_id
                            where app.appointment_date = '".$date."' and app.bus_id = '".$bus_id."' and app.time_token = 3
                            limit 1
                            ");
                            
                            
                            $evening_total_time  = $evening->row();
                            
                            $q3 = $this->db->query("select ADDTIME(evening_time_start, '".$evening_total_time->total_time."') as time_slot, evening_time_end from business_appointment_schedule where bus_id = '".$bus_id."' limit 1");
                            $evening_time_slot = $q3->row();
                            
                            $ev_slot = strtotime($total_expand_time_add, strtotime($evening_time_slot->time_slot));
                            if($ev_slot > strtotime($evening_time_slot->evening_time_end)){
                                
                            }else{              

                            
                            $data["evening_appointment"] = $evening_total_time->total_app;
                            $data["evening_time_slot"] = $evening_time_slot->time_slot;
                            $data["evening_time_slot_end"] = date("H:i:s",$ev_slot);
                            $data["evening_time_end"] = $evening_time_slot->evening_time_end;
                            $data["responce"] = true;
                            
                            }
                            
                            }else{
                                $data["responce"] = false;
                                $data["error"] = $day_name." IS Off Day";
                            }
                        }else{
                                $data["responce"] = false;
                                $data["error"] = " No time schedule set";
                        }
                        return $data;
    }
    /*----- End time slot for appoitnment -----*/

    function get_business_appointment_total($app_id){
        $q = $this->db->query("Select ( ifnull(sum( (business_services.service_price - (business_services.service_price * business_services.service_discount / 100 ) * business_appointment_services.service_qty ) ), 0 ) + business.bus_fee ) as total_amount from business_appointment_services 
                        inner join business_services on business_appointment_services.busness_service_id  = business_services.id 
                        inner join business_appointment on business_appointment.bus_id = business_appointment_services.busness_appointment_id
                        inner join business on business.bus_id = business_appointment.bus_id
                        where business_appointment_services.busness_appointment_id = '".$app_id."'");
                        return $q->row();
    }
    function get_business_appointment_total_temp($app_id){
        $q = $this->db->query("Select ( ifnull( sum( (business_services.service_price - (business_services.service_price * business_services.service_discount / 100 ) * business_appointment_services_temp.service_qty ) ) , 0) + business.bus_fee ) as total_amount from business_appointment_services_temp 
                        inner join business_services on business_appointment_services_temp.busness_service_id  = business_services.id 
                        inner join business_appointment_temp on business_appointment_temp.id = business_appointment_services_temp.busness_appointment_id
                        inner join business on business.bus_id = business_appointment_temp.bus_id
                        where business_appointment_services_temp.busness_appointment_id = '".$app_id."'");
                        return $q->row();
    }

    function is_user_business($bus_id)
    {
        $user_type_id = _get_current_user_type_id($this);
        if ($user_type_id == 0) {
            return true;
        } else {
            $user_id = _get_current_user_id($this);
            $q = $this->db->query("Select * from business where user_id = '" . $user_id . "' and bus_id = '" . $bus_id . "'");
            $row = $q->row();
            if (!empty($row))
                return true;
        }
        return false;
    }
}
