<?php

/**
 * Name: WHMCS OneSignal addon Module
 * Description: This Module provides you Send Push Notification to your Mobile App Both Android and IOS.
 * Version 1.0
 * Created by Mothersoft Technologies
 * Website: https://www.mothersoft.in/
 */

use WHMCS\Database\Capsule;
add_hook('InvoiceCreated', 1, function($vars) {
	//get the code
    $widgetScript =  Capsule::table('tbladdonmodules')->select('value')-> WHERE('module', '=' , 'onesignal')->WHERE('setting' , '=', 'onesignal-appid')->pluck('value');
    if (is_array($widgetScript)) {
        $widgetScript = current($widgetScript);
    }
    if ($widgetScript) {
        // $widgetScript = addslashes($widgetScript); // this breaks the widget script when displayed on client side
        // $widgetScript = htmlentities($widgetScript); // this displays the script as html text and prevents proper rendering of the script
        $widgetScript = trim($widgetScript);
    } else {
        return;
    }
    
    // get the API key, if set
    $apikey =  Capsule::table('tbladdonmodules')->select('value')-> WHERE('module', '=' , 'onesignal')->WHERE('setting' , '=', 'onesignal-restapikey')->pluck('value');
    if (is_array($apikey)) {
        $apikey = current($apikey);
    }
    if ($apikey) {
        $apikey = trim($apikey);
    }
	
	$invoice_details = Capsule::table('tblinvoices')->join(
             'tblclients',
             'tblinvoices.userid',
                  '=', 'tblclients.id'
         )->select(
               'tblinvoices.duedate',
               'tblclients.firstname',
               'tblinvoices.total',
			'tblinvoices.userid'
				
        )
		-> WHERE('tblinvoices.id', '=' , $vars['invoiceid'])->first();
	$User_device = Capsule::table('tblcustomfieldsvalues')-> WHERE('fieldid', '=' , 'enter your field id')->  WHERE('relid', '=' ,$invoice_details->userid )->first();
	
	$str= "Dear ". $invoice_details->firstname.".Your invoice id ".$vars['invoiceid']." , duedate ".$invoice_details->duedate. " Total ".$invoice_details->total;
	
	//$str .= print_r($vars, true);
	$content = array("en" => $str);        
        $fields = array(
            'app_id' => 'Enter your app id',
            'include_player_ids' => array($User_device->value),
            'data' => array("invoiceid" => $vars['invoiceid']),
            'contents' => $content
        );
	
        $fields = json_encode($fields);  
		logActivity($fields, 0);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
		//logActivity($response, 0);
        curl_close($ch);   
});

add_hook('InvoicePaymentReminder', 1, function($vars) {
	
	$invoice_details = Capsule::table('tblinvoices')->join(
             'tblclients',
             'tblinvoices.userid',
                  '=', 'tblclients.id'
         )->select(
               'tblinvoices.duedate',
			   'tblinvoices.date',
               'tblclients.phonenumber',
               'tblinvoices.total',
			'tblinvoices.userid'
        )
		-> WHERE('tblinvoices.id', '=' , $vars['invoiceid'])->first();
	$User_device = Capsule::table('tblcustomfieldsvalues')-> WHERE('fieldid', '=' , 'enter your field id')->  WHERE('relid', '=' ,$invoice_details->userid )->first();
	
	$str= "Dear Customer, This is a billing reminder that your invoice no. ".$vars['invoiceid']." which was generated on ".$invoice_details->date." is due on ".$invoice_details->duedate.". Thank you";
	
	//$str .= print_r($vars, true);
	$content = array("en" => $str);        
        $fields = array(
            'app_id' => 'Enter your app id',
            'include_player_ids' => array($User_device->value),
            'data' => array("invoiceid" => $vars['invoiceid']),
            'contents' => $content
        );
	
        $fields = json_encode($fields);  
		logActivity($fields, 0);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
		//logActivity($response, 0);
        curl_close($ch);   
});

add_hook('UserChangePassword', 1, function($vars) {
	$invoice_details = Capsule::table('tblinvoices')->join(
             'tblclients',
             'tblinvoices.userid',
                  '=', 'tblclients.id'
         )->select(
               'tblinvoices.duedate',
			   'tblinvoices.date',
               'tblclients.phonenumber',
               'tblinvoices.total',
			'tblinvoices.userid'
        )
		-> WHERE('tblinvoices.id', '=' , $vars['invoiceid'])->first();
	$User_device = Capsule::table('tblcustomfieldsvalues')-> WHERE('fieldid', '=' , 'enter your field id')->  WHERE('relid', '=' ,$invoice_details->userid )->first();
	$str= "Dear Customer, A request has been received to change the password for your Account, if you did not initiate this request, please contact us immediately at support@test.com. Thank you.";
	
	//$str .= print_r($vars, true);
	$content = array("en" => $str);        
        $fields = array(
            'app_id' => 'd5da1e7b-afbe-4395-b296-f962b9db0af5',
            'include_player_ids' => array($User_device->value),
            'data' => array("invoiceid" => $vars['invoiceid']),
            'contents' => $content
        );
	
        $fields = json_encode($fields);  
		logActivity($fields, 0);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
		//logActivity($response, 0);
        curl_close($ch);   
});

add_hook('InvoicePaid', 1, function($vars) {
    $invoice_details = Capsule::table('tblinvoices')->join(
             'tblclients',
             'tblinvoices.userid',
                  '=', 'tblclients.id'
         )->select(
               'tblinvoices.duedate',
			   'tblinvoices.date',
               'tblclients.phonenumber',
               'tblinvoices.total',
			'tblinvoices.userid'
        )
		-> WHERE('tblinvoices.id', '=' , $vars['invoiceid'])->first();
	$User_device = Capsule::table('tblcustomfieldsvalues')-> WHERE('fieldid', '=' , 'enter your field id')->  WHERE('relid', '=' ,$invoice_details->userid )->first();
	$str= "Dear Customer, We Received your payment, Total Paid ".$invoice_details->total.". Thank you";
	// Config variables. Consult http://api.textlocal.in/docs for more info.
	
	$content = array("en" => $str);        
        $fields = array(
            'app_id' => 'Enter your app id',
            'include_player_ids' => array($User_device->value),
            'data' => array("invoiceid" => $vars['invoiceid']),
            'contents' => $content
        );
	
        $fields = json_encode($fields);  
		logActivity($fields, 0);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
		//logActivity($response, 0);
        curl_close($ch);   
});
