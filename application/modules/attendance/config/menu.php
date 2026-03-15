<?php

// module name
$HmvcMenu["attendance"] = array(
    //set icon
    "icon"           => "<i class='fa fa-user'></i>", 

    // fleet type
        'atn_form'    => array( 
            "controller" => "Home",
            "method"     => "index",
            "permission" => "create"
        ), 
        'monthly_attendance' => array( 
            "controller" => "Home",
            "method"     => "monthly_manual_attendance",
            "permission" => "write"
        ),
        'missing_attendance' => array( 
            "controller" => "Home",
            "method"     => "missing_attendance",
            "permission" => "write"
        ),
        'lateness_early_closing' => array( 
            "controller" => "Home",
            "method"     => "lateness_early_closing",
            "permission" => "read"
        ),  
        'atn_log_datewise'  => array( 
            "controller" => "Home",
            "method"     => "att_log_report",
            "permission" => "read"
        ),
        'device_connection'  => array( 
            "controller" => "Home",
            "method"     => "device_connection",
            "permission" => "read"
        ),
        'shift_setup'  => array( 
            "controller" => "Shift",
            "method"     => "shift_setup",
            "permission" => "read"
        ),
        'shift_roster'  => array( 
            "controller" => "Shift",
            "method"     => "shift_roster",
            "permission" => "read"
        ),
  

);