<?php 

function strip_zeros_from_date($marked_string = ""){
	// first remove the marked zeros
	$no_zeros = str_replace("*0","",$marked_string);
	// the remove any remaining marks
	$cleaned_string = str_replace("*", "", $no_zeros);
	return $cleaned_string;
}

function redirect_to($location = NULL){
	if ($location!=NULL) {
		header("Location: {$location}");
		exit;
	}
}

function output_message($message = ""){
	if(!empty($message)){
		return "<p class = \"message\">{$message}</p>";
	}else{
		return "";
	}
}

// The function __autoload is deprecated as of version 7.2.0
// function __autoload($class_name){
// 	$class_name = strtolower($class_name);
// 	$path = "../includes/{$class_name}.php";
// 	if (file_exists($path)) {
// 		require_once($path);
// 	}else{
// 		echo "<br />" . "The file {$class_name}.php couldn't be found.";
// 	}
// }

spl_autoload_register(function($class_name){
	$class_name = strtolower($class_name);
	$path = LIB_PATH.DS."{$class_name}.php";
	if(file_exists($path)){
		require_once($path);
	}else{
		echo "<br />" . "The file {$class_name}.php couldn't be found.";
	}
});

function include_layout_template($template=""){
	include(SITE_ROOT.DS."public".DS."layouts".DS.$template);
}

function log_action($action, $message = ""){

$logfile = SITE_ROOT.DS."logs".DS."log.txt";
$new = file_exists($logfile) ? true : false;
if ($handle = fopen($logfile, "a")) {
	$timestamp = strftime("%m/%d/%Y %H:%M:%S", time());
	$content = "{$timestamp} | {$action} : {$message} \n";
	fwrite($handle, $content);
	fclose($handle);
	if($new){ chmod($logfile, 0755); }
} else {
	echo "Couldn't open log file for writing.";
}

}

function datetime_to_text($datetime=""){
	$unixdatetime = strtotime($datetime);
	return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}        





 ?>