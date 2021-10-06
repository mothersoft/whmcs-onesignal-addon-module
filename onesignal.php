<?php

/**
 * Name: WHMCS OneSignal addon Module
 * Description: This Module provides you Send Push Notification to your Mobile App Both Android and IOS.
 * Version 1.0
 * Created by Mothersoft Technologies
 * Website: https://www.mothersoft.in/
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function onesignal_config() {
	$configarray = array(
    	"name" => "Onsignal WHMCS Module",
    	"description" => "A module designed to make it easier for clients to integrate Onsignal into their websites, with no template edits",
    	"version" => "1.0.1",
    	"author" => "<a href='https://www.Onsignal.com/'>onsignal.com</a> Team",
    	"language" => "english",
    	"fields" => array(
                "onesignal-appid" => array (
                        "FriendlyName" => "APP ID", 
                        "Type" => "text", 
                         "Size" => "100",
                        "Description" => "Enter the onesignal here", 
                        "Default" => "", 
                    ),
                "onesignal-restapikey" => array (
                        "FriendlyName" => "Rest API Key", 
                        "Type" =>  "text", 
                        "Size" => "100", 
                        "Description" => "onesignal rest key' ", 
                        "Default" => "", 
                    ),           
        	)
    );
	return $configarray;
}
