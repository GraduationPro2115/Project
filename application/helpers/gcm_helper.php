<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class GCM {

    //put your code here
    // constructor
    function __construct() {
        
    }

    /**
     * Sending Push Notification
     */
    public function send($type, $fields){
        $CI =& get_instance();
        $api_key =  $CI->config->item("FCM_SERVER_KEY");
        
        $project_id =  $CI->config->item("FCM_PROJECT_ID");
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        
        
        
        $headers = array(
            'Authorization: key=' .$api_key ,
            'Content-Type: application/json'
        );

        // $headers = array(
        //     'Authorization: Bearer ' .$api_key ,
        //     'Content-Type: application/json'
        // );
        
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        return $result;

    }
    public function send_notification2($registatoin_ids, $message, $type) {
        
        $fields = 
        array('message'=>
            array(
                'token' => $registatoin_ids,
                'data' => $message,
                'notification' => $message,
                'priority' => "high",
                'content_available' => true
            )
        )
        ; 
       return $this->send($type, $fields);
    }
    public function send_notification($registatoin_ids, $message, $type) {
        
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
			'notification' => $message,
			'priority' => "high",
			'content_available' => true
        ); 
       return $this->send($type, $fields);
    }
    public function send_topics($topics, $message, $type, $fcm_options=array()) {
        
        $fields = array(
            'to' => $topics,
            'data' => $message,
			'notification' => $message,
            'fcm_options' => $fcm_options,
			'priority' => "high",
			'content_available' => true
        ); 
       return $this->send($type, $fields);
    }

}