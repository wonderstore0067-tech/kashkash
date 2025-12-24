<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/* * ********Encrypt******* */
/*function hash_password($password){
return password_hash($password, PASSWORD_BCRYPT);
}
/* * *******Compare******** */
/*function verify_password_hash($password, $hash){
return password_verify($password, $hash) ? "verified" : "invalid";
}*/
function name_format($name=''){
	if(!empty($name)){
	  return  ucfirst(strtolower($name));
	}else{
		return false;
	}
}
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function get_dob($dateOfBirth=''){
	if(!empty($dateOfBirth)){
	$today = date("Y-m-d");
	$diff = date_diff(date_create($dateOfBirth), date_create($today));
	return $diff->format('%y');
	}else{
		return false;
	}
}
function update_data($table = null, $data = array(), $where = array()) {
	$ci = &get_instance();
	$ci->db->update($table, $data, $where);
	if ($ci->db->affected_rows() > 0) {
		return true;
	} else {
		return false;
	}

}

///** * create a encoded id for sequrity pupose  */
if (!function_exists('encode_id')) {
	// function encode_id($id, $salt = SECURE_SALT) { 
	// 	$ci = &get_instance();
	// 	$id = $ci->encrypt->encode($id . $salt);
	// 	$id = str_replace("=", "~", $id);
	// 	$id = str_replace("+", "_", $id);
	// 	$id = str_replace("/", "-", $id);
	// 	return $id;
	// }

	function encode_id($id, $salt = SECURE_SALT) { 
		$ci = &get_instance();
		$id = $ci->encryption->encrypt($id . $salt);
		$id = str_replace("=", "~", $id);
		$id = str_replace("+", "_", $id);
		$id = str_replace("/", "-", $id);
		return $id;
	}
}

/** * decode the id which made by encode_id() */
if (!function_exists('decode_id')) {
	// function decode_id($id, $salt = SECURE_SALT) {
	// 	$ci = &get_instance();
	// 	$id = str_replace("_", "+", $id);
	// 	$id = str_replace("~", "=", $id);
	// 	$id = str_replace("-", "/", $id);
	// 	$id = $ci->encrypt->decode($id);
	// 	if ($id && strpos($id, $salt) !== false) {
	// 		return str_replace($salt, "", $id);
	// 	}
	// }

	function decode_id($id, $salt = SECURE_SALT) {
		$ci = &get_instance();
		// $ci->load->library('encryption');
		$id = str_replace("_", "+", $id);
		$id = str_replace("~", "=", $id);
		$id = str_replace("-", "/", $id);
		$id = $ci->encryption->decrypt($id);
		if ($id && strpos($id, $salt) !== false) {
			return str_replace($salt, "", $id);
		}
	}
}

// Get Session User details for admin purpose
function getuserdetails() {
	$CI = get_instance();
	$id = $CI->session->userdata['logged_in']['session_userid'];
	$CI->load->model('dynamic_model');
	$return = $CI->dynamic_model->get_user($id);
	return $return;
}
// Get Session User details for users
function getdetailsofuser() {
	$CI = get_instance();
	if (isset($CI->session->userdata['logged_user'])) {
		$id = $CI->session->userdata['logged_user']['session_userid'];
		$CI->load->model('dynamic_model');
		$return = $CI->dynamic_model->get_user($id);

		return $return;
	} else {
		return false;
	}
}
function get_generalsettings() {
	$return = getdatafromtable('general_setting');
	return $return;
}
function makeslug($slugdata) {
	$title = $slugdata;
	$title = trim(preg_replace('/\s+/', ' ', preg_replace('/[^a-zA-Z0-9_.]/', ' ', $title)));
	return strtolower(str_replace(' ', '_', $title));
}

// Get table data
function getdatafromtable($tbnm, $condition = array(), $data = '*', $limit = '', $offset = '') {
	$CI = get_instance();
	$CI->load->model('dynamic_model');
	$result = $CI->dynamic_model->getdatafromtable($tbnm, $condition, $data, $limit, $offset);
	return $result;
}

function get_options($value) {
	$CI = get_instance();
	$CI->load->model('dynamic_model');
	$condition = array('option_name' => $value);
	$result = $CI->dynamic_model->getoptions($condition);
	return $result[0]['option_value'];
}

// Get Table record count
function getdatacount($tbnm, $condition = array()) {
	$CI = get_instance();
	$CI->load->model('dynamic_model');
	$result = $CI->dynamic_model->countdata($tbnm, $condition);
	return $result[0]['counting'];
}

/* * ********** Email Function  ************* */
if (!function_exists('email_function')) {
	function email_function($to, $subject, $msg, $cc = '', $attachemt = '') {
		$CI = get_instance();
		$CI->load->library('email');
		$CI->email->from('nitesh.sethi@consagous.com', 'Donmac');
		$CI->email->to($to);
		$CI->email->subject($subject);
		$CI->email->message($msg);
		$CI->email->set_mailtype('html');
		if ($attachemt != '') {
			$CI->email->attach($attachemt);
		}
		if ($CI->email->send()) {
			$result = "1";
		} else {
			$result = "0";
		}
		return $result;
	}
}

// Get user role ID using User ID
if (!function_exists('get_role_id')) {
	function get_role_id($userid) {
		$CI = get_instance();
		$CI->load->model('dynamic_model');
		if (!empty($userid)) {
			$condition = array('User_Id' => $userid);
			$user_role = $CI->dynamic_model->getdatafromtable('user_in_roles', $condition, 'User_Id,Role_Id');
			
			if (!empty($user_role)) {
				// $condition1 = array('Id' => $user_role[0]['Role_Id']);
				// $result = $CI->dynamic_model->getdatafromtable('roles', $condition1, 'Role_Name');
				return $user_role[0]['Role_Id'];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

// Get user role Name using Role ID
if (!function_exists('get_role_name')) {
	function get_role_name($userid) {
		$CI = get_instance();
		$CI->load->model('dynamic_model');
		if (!empty($userid)) {
			$condition = array('User_Id' => $userid);
			$user_role = $CI->dynamic_model->getdatafromtable('user_in_roles', $condition, 'User_Id,Role_Id');
			if (!empty($user_role)) {
				$condition1 = array('Id' => $user_role[0]['Role_Id']);
				$result = $CI->dynamic_model->getdatafromtable('roles', $condition1, 'Role_Name');
				return $result[0]['Role_Name'];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

// Get Mall Name using Role ID
if (!function_exists('get_mall_name')) {
	function get_mall_name($mallid) {
		$CI = get_instance();
		$CI->load->model('dynamic_model');
		$condition = array('mall_id' => $mallid);
		$result = $CI->dynamic_model->getdatafromtable('shopping_mall', $condition, 'mall_name');
		return $result[0]['mall_name'];
	}
}

// Get category Name using Category ID
if (!function_exists('get_category_name')) {
	function get_category_name($catid) {
		$CI = get_instance();
		$CI->load->model('dynamic_model');
		$condition = array('id' => $catid);
		$result = $CI->dynamic_model->getdatafromtable('category', $condition, 'cat_name');
		return $result[0]['cat_name'];
	}
}

// Get Limited Words
if (!function_exists('limit_words')) {
	function limit_words($string, $word_limit) {
		$words = explode(" ", $string);
		return implode(" ", array_splice($words, 0, $word_limit));
	}
}

// Get Date with right format
if (!function_exists('get_formated_date')) {
	function get_formated_date($timestramdate) {
		$formated_date = date("d-m-Y", $timestramdate);
		return $formated_date;
	}
}

// Version Check API
// function version_check_helper()
// {
// 	$arg = array();
// 	$CI  = get_instance();
// $version_code = $CI->input->get_request_header('version', true);

// if(!empty($version_code))
// {
// 	$api_version    = config_item('api_version');
// 	$api_forcefully = config_item('api_forcefully');
// 	if($version_code >= $api_version)
// 	{
// 		$arg['status']       = 1;
// 		$arg['data']['code'] = '465';
// 		$arg['message']      = 'App is Uptodate';
// 	}
// 	else
// 	{
// 		if($api_forcefully)
// 		{
// 			$arg['status']       = 0;
// 			$arg['data']['code'] = '467';
// 			$arg['message']      = 'Major Update Available';
// 		}
// 		else
// 		{
// 			$arg['status']       = 0;
// 			$arg['data']['code'] = '466';
// 			$arg['message']      = 'Minor Update Available';
// 		}
// 	}
// }
// else
// {
// 	$arg['status']  = 0;
// 	$arg['message'] = 'Please Send App Version';
// }
// return $arg;
// }

function version_check_helper() {
	$arg = array();
	$CI = get_instance();
	 $version_code = $CI->input->get_request_header('version', true);

	if (!empty($version_code)) {
		$api_version = config_item('api_version');
		$api_forcefully = config_item('api_forcefully');
		if ($version_code >= $api_version) {
			$arg['status'] = 1;
			$arg['error_code'] = '465';
			$arg['error_line']= __line__;
			$arg['message'] = 'App is Up to date';
		} else {
			if ($api_forcefully) {
				$arg['status'] = 0;
				$arg['error_code'] = '467';
				$arg['error_line']= __line__;
				$arg['message'] = 'Major Update Available';
			} else {
				$arg['status'] = 0;
				$arg['error_code'] = '466';
				$arg['error_line']= __line__;
				$arg['message'] = 'Minor Update Available';
			}
		}
	} else {
		$arg['status'] = 0;
		$arg['message'] = 'Please Send App Version';
	}
	return $arg;
}

// Get single Validation Error for API
function get_form_error($error) {
	if (count($error) > 0) {
		foreach ($error as $key => $value) {
			return $value;
			break;
		}
	}
}

// Function for get miles
if (!function_exists('get_miles')) {
	function get_miles($latitude1, $longitude1, $latitude2, $longitude2) {
		$theta = $longitude1 - $longitude2;
		$dist = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		//$miles = $miles * 0.8684;
		$km = $miles * 1.609344;
		return round($km, 2);
	}
}

if (!function_exists('generateRandomString')) {
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

// if (!function_exists('generateQrcode'))
// {
// 	function generateQrcode($mobile)
// 	{
// 		//return $qr_number = substr($mobile, 0, 2).genrateRandom(8,'alphanum');
// 	    //$mobile = '20 Persantage offer in Product test';
// 		$qr_number = substr($mobile, 0, 2).generateRandomString(10);
// 		$new_array = array(
// 			'qr_code' => $qr_number.'.png',
// 	        'qr_number' => $qr_number
// 	    );

// 		$qr_url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$mobile&choe=UTF-8";
// 		$img = file_get_contents($qr_url);  // get image data from $url

// 		$save_to = 'uploads/coupon_qr/'. $qr_number.'.png';  // add image with the same name in 'imgs/' folder
// 		if(file_put_contents($save_to, $img)) {
// 			return $qr_number;
// 		} else {
// 			return false;
// 		}
// 	}
// }

if (!function_exists('generateQrcode')) {
	function generateQrcode($mobile) {
		//return $qr_number = substr($mobile, 0, 2).genrateRandom(8,'alphanum');
		//$mobile = '20 Persantage offer in Product test';
		$qr_number = substr($mobile, 0, 2) . generateRandomString(10);
		$new_array = array(
			'qr_code' => $qr_number . '.png',
			'qr_number' => $qr_number,
		);

		$qr_url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$qr_number&choe=UTF-8";
		$img = file_get_contents($qr_url); // get image data from $url

		$save_to = 'uploads/qrcodes/' . $qr_number . '.png'; // add image with the same name in 'imgs/' folder
		if (file_put_contents($save_to, $img)) {
			return $qr_number;
		} else {
			return false;
		}
	}
}

if (!function_exists('getrating')) {
	function getrating($shopid) {
		if ($shopid != '') {
			$CI = get_instance();
			$CI->load->model('dynamic_model');

			$where_rating = array(
				'rate_shop_id' => $shopid,
				'rate_status' => "1",
			);
			$ratinginfo = $CI->dynamic_model->getdatafromtable('reviews', $where_rating, "count(*) as total, sum(rate_rating) as rate_rating");
			$total = $ratinginfo[0]['total'];
			$totalcount = $ratinginfo[0]['rate_rating'];
			if ($totalcount > "0") {
				$rating = round($totalcount / $total);
				$totalrating = (string) number_format((float) $rating, 1, '.', '');
			} else {
				$totalrating = "0";
			}
			return $totalrating;
		}
	}
}

// Get ip_info
if (!function_exists('ip_info')) {
	function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
		$output = NULL;
		if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
			$ip = $_SERVER["REMOTE_ADDR"];
			$ip = $_SERVER["HTTP_HOST"];
			// if ($deep_detect) {
			//     if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
			//         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			//     if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
			//         $ip = $_SERVER['HTTP_CLIENT_IP'];
			// }
		}
		$purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
		$support = array("country", "countrycode", "state", "region", "city", "location", "address");
		$continents = array(
			"AF" => "Africa",
			"AN" => "Antarctica",
			"AS" => "Asia",
			"EU" => "Europe",
			"OC" => "Australia (Oceania)",
			"NA" => "North America",
			"SA" => "South America",
		);
		//if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
		$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
		if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
			switch ($purpose) {
			case "location":
				$output = array(
					"city" => @$ipdat->geoplugin_city,
					"state" => @$ipdat->geoplugin_regionName,
					"country" => @$ipdat->geoplugin_countryName,
					"country_code" => @$ipdat->geoplugin_countryCode,
					"continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
					"continent_code" => @$ipdat->geoplugin_continentCode,
				);
				break;
			case "address":
				$address = array($ipdat->geoplugin_countryName);
				if (@strlen($ipdat->geoplugin_regionName) >= 1) {
					$address[] = $ipdat->geoplugin_regionName;
				}

				if (@strlen($ipdat->geoplugin_city) >= 1) {
					$address[] = $ipdat->geoplugin_city;
				}

				$output = implode(", ", array_reverse($address));
				break;
			case "city":
				$output = @$ipdat->geoplugin_city;
				break;
			case "state":
				$output = @$ipdat->geoplugin_regionName;
				break;
			case "region":
				$output = @$ipdat->geoplugin_regionName;
				break;
			case "country":
				$output = @$ipdat->geoplugin_countryName;
				break;
			case "countrycode":
				$output = @$ipdat->geoplugin_countryCode;
				break;
			}
		}
		//}
		return $output;
	}
}

// Get OS
if (!function_exists('getOS')) {
	function getOS() {
		global $user_agent;
		$os_platform = "Unknown OS Platform";

		$os_array = array(
			'/windows nt 10/i' => 'Windows 10',
			'/windows nt 6.3/i' => 'Windows 8.1',
			'/windows nt 6.2/i' => 'Windows 8',
			'/windows nt 6.1/i' => 'Windows 7',
			'/windows nt 6.0/i' => 'Windows Vista',
			'/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
			'/windows nt 5.1/i' => 'Windows XP',
			'/windows xp/i' => 'Windows XP',
			'/windows nt 5.0/i' => 'Windows 2000',
			'/windows me/i' => 'Windows ME',
			'/win98/i' => 'Windows 98',
			'/win95/i' => 'Windows 95',
			'/win16/i' => 'Windows 3.11',
			'/macintosh|mac os x/i' => 'Mac OS X',
			'/mac_powerpc/i' => 'Mac OS 9',
			'/linux/i' => 'Linux',
			'/ubuntu/i' => 'Ubuntu',
			'/iphone/i' => 'iPhone',
			'/ipod/i' => 'iPod',
			'/ipad/i' => 'iPad',
			'/android/i' => 'Android',
			'/blackberry/i' => 'BlackBerry',
			'/webos/i' => 'Mobile',
		);

		foreach ($os_array as $regex => $value) {
			if (preg_match($regex, $user_agent)) {
				$os_platform = $value;
			}
		}
		return $os_platform;
	}
}

if (!function_exists('getBrowser')) {
	function getBrowser() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browser = "Unknown Browser";
		$browser_array = array(
			'/msie/i' => 'Internet Explorer',
			'/firefox/i' => 'Firefox',
			'/safari/i' => 'Safari',
			'/chrome/i' => 'Chrome',
			'/edge/i' => 'Edge',
			'/opera/i' => 'Opera',
			'/netscape/i' => 'Netscape',
			'/maxthon/i' => 'Maxthon',
			'/konqueror/i' => 'Konqueror',
			'/mobile/i' => 'Handheld Browser',
		);

		foreach ($browser_array as $regex => $value) {
			if (preg_match($regex, $user_agent)) {
				$browser = $value;
			}
		}
		return $browser;
	}
}

/* function used for encrypt password with sha512  */
if (!function_exists('encrypt_password')) {
	function encrypt_password($password) {
		$ci = &get_instance();
		$key = $ci->config->item('encryption_key');
		$salt1 = hash('sha512', $key . $password);
		$salt2 = hash('sha512', $password . $key);
		$hashed_password = hash('sha512', $salt1 . $password . $salt2);
		return $hashed_password;
	}
}

if (!function_exists('getuniquenumber')) {
	function getuniquenumber() {
		//////////////////GENERATE TRX #
		$a1 = date("ymd", time());
		$a2 = rand(100, 999);
		$u = substr(uniqid(), 7);
		$c = chr(rand(97, 122));
		$c2 = chr(rand(97, 122));
		$c3 = chr(rand(97, 122));
		$ok = "$c$u$c2$a2$c3";
		$txn_id = strtoupper($ok);
		return $txn_id;
		//////////////////GENERATE TRX #
	}
}

//Push Android
if (!function_exists('sendPushAndroid')) {
	function sendPushAndroid($to, $title, $type, $message = '', $group_id = '',$chat_id='',$payment_status='') {
		$ci = &get_instance();
		$apiKey = $ci->config->item('android_server_key');

		$msg = array('body' => $message,
			'title' => $title,
			'type' => $type,
			'message' => $title,
			"sound" => "default",
			//"click_action" => "com.shikha365.activities.SplashActivity",
		);
		$fields = array(
			'to' => $to,
			'data' => array('title' => 'dapplepay', 'message' => $title, 'type' => $type, 'sound' => 'default', 'group_id' => $group_id,'chat_id' => $chat_id,'payment_status' => $payment_status),
			'content_available' => true,
			'priority' => 'high',
		);

		$headers = array(
			'Authorization: key=' . $apiKey,
			'Content-Type: application/json',
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$response = curl_exec($ch);
		curl_close($ch);
		//print_r($response);
	}
}

//Push Ios
if (!function_exists('sendPushIos')) {
	function sendPushIos($to, $title, $type, $message = '', $group_id = '',$chat_id='',$payment_status='') {
		$message = $message;
		$token = $to;
		$title = $title;
		$notification_setting = '';

		$ci = &get_instance();

		$apiKey = $ci->config->item('android_server_key');
		$icon = 'https://static.pexels.com/photos/4825/red-love-romantic-flowers.jpg';
		$msg = array('body' => $title,
			'title' => $title,
			'type' => "$type",
			'notification_setting' => $notification_setting,
			'group_id' => $group_id,
			'chat_id' => $chat_id,
			'payment_status' => $payment_status,
			'icon' => 'icon',
			"sound" => "default",
			"click_action" => "FCM_PLUGIN_ACTIVITY",
		);

		$fields = array(
			'to' => $token,
			'notification' => $msg,
			'data' => $msg,
			'content_available' => true,
			'priority' => 'high',
		);

		$headers = array(
			'Authorization: key=' . $apiKey,
			'Content-Type: application/json',
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$response = curl_exec($ch);
		curl_close($ch);
	}
}

if (!function_exists('numberTowords')) {
	function numberTowords($num) {
		$ones = array(
			1 => "one",
			2 => "two",
			3 => "three",
			4 => "four",
			5 => "five",
			6 => "six",
			7 => "seven",
			8 => "eight",
			9 => "nine",
			10 => "ten",
			11 => "eleven",
			12 => "twelve",
			13 => "thirteen",
			14 => "fourteen",
			15 => "fifteen",
			16 => "sixteen",
			17 => "seventeen",
			18 => "eighteen",
			19 => "nineteen",
		);
		$tens = array(
			1 => "ten",
			2 => "twenty",
			3 => "thirty",
			4 => "forty",
			5 => "fifty",
			6 => "sixty",
			7 => "seventy",
			8 => "eighty",
			9 => "ninety",
		);
		$hundreds = array(
			"hundred",
			"thousand",
			"million",
			"billion",
			"trillion",
			"quadrillion",
		); //limit t quadrillion

		$num = number_format($num, 2, ".", ",");
		$num_arr = explode(".", $num);
		$wholenum = $num_arr[0];
		$decnum = $num_arr[1];
		$whole_arr = array_reverse(explode(",", $wholenum));
		krsort($whole_arr);
		$rettxt = "";
		foreach ($whole_arr as $key => $i) {
			if ($i < 20) {
				$rettxt .= $ones[$i];
			} elseif ($i < 100) {
				$rettxt .= $tens[substr($i, 0, 1)];
				$rettxt .= " " . $ones[substr($i, 1, 1)];
			} else {
				$rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
				$rettxt .= " " . $tens[substr($i, 1, 1)];
				$rettxt .= " " . $ones[substr($i, 2, 1)];
			}
			if ($key > 0) {
				$rettxt .= " " . $hundreds[$key] . " ";
			}
		}

		if ($decnum > 0) {
			$rettxt .= " and ";
			if ($decnum < 20) {
				$rettxt .= $ones[$decnum];
			} elseif ($decnum < 100) {
				$rettxt .= $tens[substr($decnum, 0, 1)];
				$rettxt .= " " . $ones[substr($decnum, 1, 1)];
			}
		}
		return $rettxt;
	}
}

if (!function_exists('check_expiry_year')) {
	function check_expiry_year($expiry_year) {
		$cur_year = date('Y');
		if ($expiry_year >= $cur_year) {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('check_expiry_month_year')) {
	function check_expiry_month_year($expiry_month, $expiry_year) {
		$cur_year = date('Y');
		$cur_month = date('m');
		if ($expiry_year >= $cur_year) {
			if ($expiry_year == $cur_year) {
				if ($expiry_month >= $cur_month) {
					return true;
				} else {
					return false;
				}

			} else {
				return true;
			}
		} else {
			return false;
		}
	}
}
function notification_redirect($title, $text, $type, $cc, $btnst, $btntxt, $redirect) {
	echo "
	<script>
	swal({
	title: '$title',
	text: '$text',
	type: '$type',
	showCancelButton: '$cc',
	confirmButtonClass: '$btnst',
	confirmButtonText: '$btntxt'
	}, function() {
	            window.location = '$redirect';
	        });
	</script>
	";
}

function getPaymentStatusText($status) {
	$payment_status = '-';
	if ($status == 1) {
		$payment_status = '<span class="badge badge-warning">Pending</span>';
	}
	if ($status == 2) {
		$payment_status = '<span class="badge badge-primary">Processed</span>';
	}
	if ($status == 3) {
		$payment_status = '<span class="badge badge-warning">Hold</span>';
	}
	if ($status == 4) {
		$payment_status = '<span class="badge badge-danger">Reject</span>';
	}
	if ($status == 5) {
		$payment_status = '<span class="badge badge-primary">Refund</span>';
	}
	if ($status == 6) {
		$payment_status = '<span class="badge badge-success">Success</span>';
	}
	if ($status == 7) {
		$payment_status = '<span class="badge badge-danger">Cancel</span>';
	}
	if ($status == 'SUCCESS') {
		$payment_status = '<span class="badge badge-success">Success</span>';
	}
	if ($status == 'PAID TO RECEIVER') {
		$payment_status = '<span class="badge badge-success">PAID TO RECEIVER</span>';
	}
	if ($status == 'PAID TO AGENT') {
		$payment_status = '<span class="badge badge-success">PAID TO AGENT</span>';
	}
	return $payment_status;
}
function getPaymentTypeText($status) {
	$payment_type = '-';
	if ($status == 1) {
		$payment_type = '<span class="badge badge-warning">Withdrawal</span>';
	}
	if ($status == 2) {
		$payment_type = '<span class="badge badge-primary">Deposited</span>';
	}
	if ($status == 3) {
		$payment_type = '<span class="badge badge-success">Sent </span>';
	}
	if ($status == 4) {
		$payment_type = '<span class="badge badge-info">Received</span>';
	}

	return $payment_type;
}
function getPaymentTypeName($status) {
	$payment_type = '-';
	if ($status == 1) {
		$payment_type = 'Withdrawal';
	}
	if ($status == 2) {
		$payment_type = 'Deposited';
	}
	if ($status == 3) {
		$payment_type = 'Sent';
	}
	if ($status == 4) {
		$payment_type = 'Received';
	}

	return $payment_type;
}
function dateFormat($time, $strtime = '') {
	if ($strtime == 1) {
		return date("d-M-y - h:i A ", $time);
	} else {
		return date("d-M-y - h:i A ", strtotime($time));
	}
}
function get_current_wallet_balance($userid = '') {
	$CI = get_instance();
	$CI->load->model('dynamic_model');
	$condition = array('Id' => base64_decode($userid));
	$result = $CI->db->select('Current_Wallet_Balance,Id')->get_where('users', $condition)->result_array();
	$amount = isset($result[0]['Current_Wallet_Balance']) ? $result[0]['Current_Wallet_Balance'] : '0.00';
	return $amount;
}

function get_payment_method_name($method_id = '', $userid = '') {
	$CI = get_instance();
	$CI->load->model('dynamic_model');
	$condition = array('Id' => $method_id, 'User_Id' => $userid);
	$result = $CI->dynamic_model->getdatafromtable('User_Payment_Methods', $condition);

	if ($result[0]['Is_Visa_Debit_Card'] == 1) {

		$method_name = 'Visa Debit Card';

	} elseif ($result[0]['Is_Visa_Credit_Card'] == 1) {

		$method_name = 'Visa Credit Card';

	} elseif ($result[0]['Is_Bank'] == 1) {

		$method_name = $result[0]['Wallet_Bank_Name'];

	} elseif ($result[0]['Is_Debit_Card'] == 1) {

		$method_name = 'Debit Card';

	} elseif ($result[0]['Is_Credit_Card'] == 1) {

		$method_name = 'Credit Card';

	} elseif ($result[0]['Is_Prepaid_Card'] == 1) {

		$method_name = 'Prepaid Card';

	} else {
		$method_name = 'Wallet';
	}

	return $method_name;
}

function graph_transaction_deposit($i) {
	$CI = get_instance();
	$dp = $CI->db->query("SELECT SUM(Amount) as amount,COUNT(*) as count FROM Transactions WHERE MONTH(Creation_Date_Time) = " . $i . " AND Tran_Type_Id=2 AND Tran_Status_Id = 6")->result_array();
	return $dp;
}
function graph_transaction_wd($i = '') {
	$CI = get_instance();

	$wd = $CI->db->query("SELECT SUM(Amount) as amount FROM Transactions  WHERE MONTH(Creation_Date_Time) = " . $i . " AND Tran_Type_Id=1 AND Tran_Status_Id = 6 ")->result_array();
	return $wd;
}
function get_top_users($roleid = '', $adminid = '') {
	$CI = get_instance();
	$CI->db->select('Users.FirstName,Users.LastName,Users.Mobile_No,Users.Creation_Date_Time,Users.Is_Active,Users.Current_Wallet_Balance,user_in_roles.User_Id,user_in_roles.Role_Id');
	$CI->db->from('users');
	$CI->db->join('user_in_roles', 'Users.Id=user_in_roles.User_Id');
	$CI->db->where('user_in_roles.Role_Id', $roleid);
	$CI->db->where('user_in_roles.Role_Id !=', $adminid);
	$CI->db->order_by('Users.Current_Wallet_Balance DESC');
	$query = $CI->db->get();
	return $query->result_array();

}
function getUserFromLast7Days() {
	$CI = get_instance();
	$get_records = $CI->db->query("SELECT u.Id,u.FirstName,u.LastName,u.Mobile_No,u.Creation_Date_Time,u.Is_Active,u.Current_Wallet_Balance,ur.Role_Id,ur.User_Id FROM Users as u INNER JOIN user_in_roles as ur ON ur.User_Id = u.Id WHERE (ur.Role_Id = 3 OR ur.Role_Id = 4) AND u.Creation_Date_Time >= DATE_ADD(CURDATE(),INTERVAL -7 DAY) order by u.Id DESC ")->result_array();
	$result = (empty($get_records)) ? '' : $get_records;
	return $result;

}
function admin_permission($adminid) {
	$CI = get_instance();
	$getAdminPermissions = $CI->db->query("SELECT r.Id, r.Role_Name, arp.Permission FROM admin_roles_permission as arp INNER JOIN Roles as r INNER JOIN user_in_roles as ur ON ur.Role_Id = r.Id AND arp.Role_Id = ur.Role_Id WHERE ur.User_Id = $adminid ")->result_array();
	return $getAdminPermissions;
}
function user_login_count($userid) {
	$CI = get_instance();
	$CI->load->model('dynamic_model');
	$where = array('User_Id' => $userid);
	$login_count = $CI->dynamic_model->getdatafromtable('User_Logins', $where);
	if ($login_count) {
		$logincount = count($login_count);
	} else {
		$logincount = 0;
	}
	return $logincount;
}
function getUserData($userid) {
	$CI = get_instance();
	$CI->db->select('Users.FirstName,Users.LastName,Users.Email,Users.Mobile_No,Users.Creation_Date_Time,Users.Is_Active,Users.Current_Wallet_Balance,user_in_roles.User_Id,user_in_roles.Role_Id');
	$CI->db->from('users');
	$CI->db->join('user_in_roles', 'Users.Id=user_in_roles.User_Id');
	$CI->db->where('Users.Id', $userid);
	$query = $CI->db->get();

	return $query->result_array();
}

function rightsidebartemp($data = array()) {
	$CI = get_instance();
	$CI->load->view('web_templates/rightsidebar', $data);

}
function promo_slidertemp($data = array()) {
	$CI = get_instance();
	$CI->load->view('web_templates/promo_slider', $data);

}

function sendSms($phone, $message) {
	$CI = &get_instance();
	if (!empty($phone) && !empty($message)) {

		$send_otp = $CI->config->item('send_otp');
		if ($send_otp == '0') {
			return true;
		}
		//$msg91_authkey = $CI->config->item('msg91_authkey');
		$site_name = 'YugPay';
		//Your authentication key
		$authKey = $CI->config->item('msg91_authkey');
		$mobileNumber = $phone;
		$senderId = ucfirst($site_name);

		$message = str_replace('\\r\\n', '', $message);
		$message = str_replace('\r\n', '', $message);
		$message = str_replace('\r', '', $message);
		$message = str_replace('\n', '', $message);

		//$message = urlencode($message);
		$route = "4";
		$postData = array(
			'authkey' => $authKey,
			'mobiles' => $mobileNumber,
			'message' => $message,
			'sender' => $senderId,
			'route' => $route,
			'country' => '91',
		);

		//API URL
		$url = "https://control.msg91.com/api/sendhttp.php";
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData,
		));
		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$output = curl_exec($ch);
		if (curl_errno($ch)) {
			//echo 'error:' . curl_error($ch);
		}
		curl_close($ch);
		return true;
	} else {
		return false;
	}
}

function generatePIN($digits = 6) {
	$i = 0; //counter
	$pin = ""; //our default pin is blank.
	while ($i < $digits) {
		//generate a random number between 0 and 9.
		$pin .= mt_rand(0, 9);
		$i++;
	}
	return $pin;
}
function generate_Pin(){
 
    $pin= substr(str_shuffle("0123456789"),0,4);
    return $pin;
}
function image_check($image, $url, $imgCategory = 'user') {
	$CI = &get_instance();
	$filename = "$url$image";
	if (!empty($image)) {
		if (@getimagesize($filename)) {
			return $url . $image;
		} else {
			return $url . 'default.svg';
		}
	} else {
		return $url . 'default.svg';
	}
}
function doc_image_check($image, $url, $flag = '') {
	$CI = &get_instance();
	$filename = "$url$image";
	$defaultimg = base_url() . "assets/images/no-image.jpg";
	$height = ($flag == 1) ? "200px" : "60px";
	$width = ($flag == 1) ? "200px" : "60px";
	$class = ($flag == 1) ? "img-responsive" : "";
	if (!empty($image)) {
		if (@getimagesize($filename)) {
			return '<a href="' . $filename . '" target="_blank"><img src="' . $filename . '" class="' . $class . '" height="' . $height . '" width="' . $width . '"></a>';
		} else {
			if ($flag !== 2) {
				return ($flag == 1) ? '<a href="' . $defaultimg . '" target="_blank"><img src="' . $defaultimg . '" class="' . $class . '" height="' . $height . '" width="' . $width . '"></a>' : '-';
			} else {
				return false;
			}
		}
	} else {
		if ($flag !== 2) {
			return ($flag == 1) ? '<a href="' . $defaultimg . '" target="_blank"><img src="' . $defaultimg . '" class="' . $class . '" height="' . $height . '" width="' . $width . '"></a>' : '-';
		} else {
			return false;
		}
	}
}

if (!function_exists('checkUserStatus')) {
	function checkUserStatus() {
		$arg = array();
		$ci = &get_instance();
		$ci->load->model('dynamic_model');
		$auth_token = $ci->input->get_request_header('Authorization');
		$user_token = json_decode(base64_decode($auth_token));
		 $user_id = $user_token->userid;

		$where10 = " where Id = '" . $user_id . "'";
		$result10 = $ci->dynamic_model->select('users', $where10);

		$where = " where Mobile_No = '" . $result10[0]['Mobile_No'] . "'";
		$user_login_check = $ci->dynamic_model->select('users', $where);

		$where2 = " where User_Id = '" .$user_id . "'";
		$doc_data = $ci->dynamic_model->select('users_documents',$where2);
		$where3 = " where User_Id = '" .$user_id . "'";
		$role_data = $ci->dynamic_model->select('user_in_roles',$where3);
		if ($user_login_check) {
			$userid = $user_login_check[0]['Id'];
			$where1 = " where Id = '" . $userid . "'";
			$userdetails = $ci->dynamic_model->select('users', $where1, 'Id, Is_Active');
			if ($userdetails[0]['Is_Active'] == 0) {
				$arg['status'] = 101;
				$arg['error_code'] = 461;
				$arg['error_line']= __line__;
				$arg['message'] = 'You cannot access your account.please contact with administrator for further transactions.';
			}elseif((!empty($doc_data && $doc_data[0]['Is_Verified'] == 0 && $role_data[0]['Role_Id']== 3))){
				$arg['status'] = 101;
				$arg['error_code'] = 461;
				$arg['error_line']= __line__;
				$arg['message'] = 'Document is not Verified.Please contact to Administration';
			}
		} else {
			$arg['status'] = 0;
			$arg['error_code'] = 103;
			$arg['error_line']= __line__;
			$arg['message'] = 'Invalid mobile number';
		}
		return $arg;
	}
}

if (!function_exists('checkUserStatusById')) {
	function checkUserStatusById($user_id = '') {
		$arg = array();
		$ci = &get_instance();
		$ci->load->model('dynamic_model');

		$where10 = " where Id = '" . $user_id . "'";
		$result10 = $ci->dynamic_model->select('users', $where10);

		$where = " where Mobile_No = '" . $result10[0]['Mobile_No'] . "'";
		$user_login_check = $ci->dynamic_model->select('users', $where);

		if ($user_login_check) {
			$userid = $user_login_check[0]['Id'];
			$where1 = " where Id = '" . $userid . "'";
			$userdetails = $ci->dynamic_model->select('users', $where1, 'Id, Is_Active,Login_Token');
			if ($userdetails[0]['Login_Token'] == '') {
				$arg['status'] = 101;
				$arg['error_code'] = 461;
				$arg['error_line']= __line__;
				$arg['message'] = $ci->lang->line('session_expire');
			} else {
				if ($userdetails[0]['Is_Active'] == 0) {
					$arg['status'] = 101;
					$arg['error_code'] = 461;
					$arg['error_line']= __line__;
					$arg['message'] = 'You cannot access your account.please contact with administrator for further transactions.';
				}
			}
		} else {
			$arg['status'] = 0;
			$arg['error_code'] = 103;
			$arg['error_line']= __line__;
			$arg['message'] = 'Invalid mobile number';
		}
		return $arg;
	}
}
if (!function_exists('unVerifiedDocumentStatus')) {
	function unVerifiedDocumentStatus() {
		$ci = &get_instance();
		$ci->load->model('dynamic_model');

		$header = getallheaders();
		$user_id = $header['userid'];
		$token = $header['token'];

		$where10 = " where Id = '" . $user_id . "'";
		$result10 = $ci->dynamic_model->select('users', $where10);

		// $where            = " where User_Id = '".$userid."' AND Role_Id = 3";
		// $user_login_check = $ci->dynamic_model->select('user_in_roles',$where);

		if ($result10) {
			$userid = $result10[0]['Id'];
			$where1 = " where User_Id = '" . $userid . "'";
			$userdetails = $ci->dynamic_model->select('users_documents', $where1, 'Id, Is_Verified');

			if ($userdetails[0]['Is_Verified'] == 0) {
				$message = array('status' => 0, 'error' => 109, 'message' => "Document is not verified.");
				return $message;
			}
		} else {
			$message = array('status' => 0, 'error' => 103, 'message' => "Invalid details");
			return $message;
		}
	}
}

if (!function_exists('check_authorization')) {
	//Check Auth for customer or merchant
	function check_authorization($logout = NULL) {
		$ci = &get_instance();
		$ci->load->model('dynamic_model');
		$ci->lang->load("message", "english");

		$auth_token = $ci->input->get_request_header('Authorization');
		$user_token = json_decode(base64_decode($auth_token));
		if (!empty($user_token)) {
			$usid = $user_token->userid;
			$auth_key = $user_token->token;
			if ($usid != '' && $auth_key != '') {
				$condition = array(
					'Id' => $usid,
					'Login_Token' => $auth_key,
				);
				$loguser = $ci->dynamic_model->getdatafromtable('users', $condition);
				if ($loguser) {
					if ($usid == $loguser[0]['Id'] && $auth_key == $loguser[0]['Login_Token']) {

						if (!empty($logout)) {
							$data2 = array(
								'Login_Token' => '',
								'Is_LoggedIn' => '0',
							);
							$wheres = array("Id" => $usid);
							$result = $ci->dynamic_model->updateRowWhere("users", $wheres, $data2);

							$data2 = array(
								'Device_Id' => '',
								'Device_Type' => '',
							);
							$wheress = array("User_Id" => $usid);
							$result = $ci->dynamic_model->updateRowWhere("user_in_roles", $wheress, $data2);

							return $ci->lang->line('logout_success');
						} else {
							return true;
						}

					} else {
						return $ci->lang->line('session_expire');
					}

				} else {
					return $ci->lang->line('varify_token_userid');
				}
			} else {
				return $ci->lang->line('header_required');
			}
		} else {
			return $ci->lang->line('header_required');
		}
	}
}

// Get user id
if (!function_exists('getuserid')) {
	function getuserid() {
		$ci = &get_instance();
		$ci->load->model('dynamic_model');
		$ci->lang->load("message", "english");

		$auth_token = $ci->input->get_request_header('Authorization');
		$user_token = json_decode(base64_decode($auth_token));

		$where = array("id" => $user_token->userid);
		$users = $ci->dynamic_model->getdatafromtable("users", $where);
		if (!empty($users)) {
			return $users[0]['Id'];
		} else {
			return false;
		}
	}
}
function get_permission_detail($user_id = '') {
	$CI = &get_instance();
	$CI->db->select('*');
	$CI->db->from('admin_roles_permission');
	$CI->db->where('User_Id', $user_id);
	$result = $CI->db->get()->result_array();

	if (!empty($result)) {
		return $result;
	} else {
		return false;
	}
}

// Check Limit
if (!function_exists('check_limit')) {
	function check_limit($amount, $userid, $limit_type, $tran_type_id) {
		$ci = &get_instance();
		//$ci->load->model('master_model');
		$ci->load->model('dynamic_model');
		$ci->lang->load("message", "english");
		$arg = array();
		$arg1 = array();
		if ($limit_type == "daily") {
			$condt = "To_User_Id = " . $userid . " And cast(Creation_Date_Time as DATE) ='" . date('Y-m-d') . "' AND Tran_Type_Id = '" . $tran_type_id . "' AND Created_By != '1' GROUP BY Tran_Type_Id DESC";

			$get_transition = getdatafromtable('transactions', $condt, "Tran_Type_Id, count(Id) as total, SUM(`Amount`) as totalamount");

			$condition2 = array("tran_type_id" => $tran_type_id);
			$getlimit = getdatafromtable('transactions_limit', $condition2);

			if($get_transition){
				$user_left_amount_limit = $getlimit[0]['daily_limit'] - $get_transition[0]['totalamount'];
			}
			else{
				$user_left_amount_limit = $getlimit[0]['daily_limit'];
			}
			
			// if ($amount <= $user_left_amount_limit && (isset($get_transition) ? $get_transition[0]['total'] : 0 ) <= $getlimit[0]['count_limit']) {
				if ($amount <= $user_left_amount_limit && ($get_transition!= null ? $get_transition[0]['total'] : 0 ) <= $getlimit[0]['count_limit']) {
				// if(!empty($getlimit))
				// {
				// 	$arg = array("user_left_amount_limit"=>$user_left_amount_limit,"total"=>$get_transition[0]['total'],"count_limit"=>$getlimit[0]['count_limit']);
				// 	return $arg;
				return true;
			} else {
				return false;
				//return $arg;
			}
		}

		if ($limit_type == "monthly") {
			$end = date('Y-m-d');
			$start = date("Y-m-01", strtotime($end));
			$condt = "To_User_Id = " . $userid . " And cast(Creation_Date_Time as DATE) BETWEEN '" . $start . "' AND '" . $end . "' AND Tran_Type_Id = '" . $tran_type_id . "' AND Created_By != '1' GROUP BY Tran_Type_Id DESC";

			$get_transition = getdatafromtable('transactions', $condt, "Tran_Type_Id, count(Id) as total, SUM(`Amount`) as totalamount");

			$condition2 = array("tran_type_id" => $tran_type_id);
			$getlimit = getdatafromtable('transactions_limit', $condition2);

			if($get_transition){
				$user_left_amount_limit = $getlimit[0]['monthly_limit'] - $get_transition[0]['totalamount'];
			}
			else{
				$user_left_amount_limit = $getlimit[0]['daily_limit'];
			}
			if ($amount <= $user_left_amount_limit && ($get_transition!= null ? $get_transition[0]['total'] : 0) <= $getlimit[0]['monthly_trans_limit'])
			//if(!empty($getlimit))
			{
				// $arg1 = array("user_left_amount_limit"=>$user_left_amount_limit,"total"=>$get_transition[0]['total'],"count_limit"=>$getlimit[0]['monthly_trans_limit']);
				// return $arg1;
				return true;
			} else {
				return false;
				//return $arg1;
			}
		}
	}
}

if (!function_exists('pilvo_sms')) {
	function pilvo_sms($country_code, $mobile_no, $otpmsg) {
		require_once './plivo/plivo.php';
		$auth_id = PILVO_AUTH_ID;
		$auth_token = PILVO_AUTH_TOKEN;

		$p = new RestAPI($auth_id, $auth_token);
		if (!empty($mobile_no)) {
			$dst = $mobile_no;
			$country_code = (!empty($country_code)) ? $country_code : 1;
			$message = $otpmsg;
			//$message="Thank you. We will notify you with any important updates, including the link to download Dapple Pay when we launch";
			// Send a message
			if ($country_code == 1) {
				$params = array(
					'src' => '13053061487', // Sender's phone number with country code
					'dst' => "+" . $country_code . $dst, // Receiver's phone number with country code
					'text' => $message, // Your SMS text message
					// To send Unicode text
					'url' => 'http://dapplepay.com/', // The URL to which with the status of the message is sent
					'method' => 'POST', // The method used to call the url
				);
			} else {
				$params = array(
					'src' => '14059216935', // Sender's phone number with country code
					'dst' => "+" . $country_code . $dst, // Receiver's phone number with country code
					'text' => $message, // Your SMS text message
					// To send Unicode text
					'url' => 'http://dapplepay.com/', // The URL to which with the status of the message is sent
					'method' => 'POST', // The method used to call the url
				);
			}

			// Send message
			$response = $p->send_message($params);
			// print_r( $response['response']);die;
			if (@$response['response']['error'] == '') {
				if (@$response['status'] == 200 || 202) {
					//$return = array('status'=>true,'message'=>'Mesage send successfully to your mobile number please check');
					return true;
				} else {
					// $return = array('status'=>false,'message'=>'message send fail');
					return false;
				}
			} else {
				$error_msg = @$response['response']['error'];
				// $return = array('status'=>false,'message'=>'Mobile number is invalid '.$error_msg);
				return false;
			}
		} else {
			//$return = array('status'=>false,'message'=>'Mobile number Cannot be left blank');
			return false;
		}
		//echo json_encode($return);
	}
}

function sendEmailCI($to, $from = '', $subject = '', $description = '', $body = '', $attachments = array(), $filePath = '') {
    $CI =& get_instance();

    // SMTP configuration
    $config = array(
        'useragent' => "CodeIgniter",
        'protocol' => 'smtp',
        'smtp_host' => SMTP_HOST,
        'smtp_port' => 465,  // Use port 465 for SSL
        'smtp_user' => SMTP_USER,
        'smtp_pass' => SMTP_PASS,
        'smtp_crypto' => 'ssl', // SSL encryption
        'mailtype' => 'html',  // HTML emails
        'charset' => 'utf-8',
        'wordwrap' => TRUE,
        'newline' => "\r\n", // Ensure proper line endings
		'crlf' => "\r\n"
    );
	
    // Load email library with configuration
    $CI->load->library('email');
    $CI->email->initialize($config);

    // Set sender email
    if (empty($from)) {
        $from = $config['smtp_user'];
    }
    $site_name = SITE_TITLE; // Ensure SITE_TITLE is defined

    // Load the email template
    // $template = $CI->load->view('emailtemplate', compact('subject', 'description', 'body'), TRUE);

    // // Prepare email
    // $CI->email->from($from, $site_name);
    // $CI->email->to($to);
    // $CI->email->subject($subject);
    // $CI->email->message($template); // Send the HTML content

	$CI->email->from($from, $site_name);
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($description); // Send the HTML content

    // Handle attachments
    if (!empty($attachments)) {
        foreach ($attachments as $attachment) {
            $file_path = !empty($filePath) ? $filePath : config_item('root_url'); // Ensure root_url is defined in config
            $CI->email->attach($file_path . $attachment);
        }
    }

    // Send email and return result
    if ($CI->email->send()) {
        return true;
    } else {
        // Log the error for debugging
        $debug_info = $CI->email->print_debugger(array('headers', 'subject', 'body'));
        file_put_contents('email_debug.txt', $debug_info, FILE_APPEND);
        return false;
    }
}






// function sendEmailCI($to, $from = '', $subject = '', $description = '', $body = '', $attachments = array(), $filePath = '') {
// 	require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
// 	require 'vendor/phpmailer/phpmailer/src/SMTP.php';
// 	require 'vendor/phpmailer/phpmailer/src/Exception.php';

// 	$mail = new PHPMailer(true);
// 	try {
// 		// Server settings
// 		$mail->isSMTP();
// 		$mail->Host       = 'smtpout.secureserver.net';  // GoDaddy SMTP server
// 		$mail->SMTPAuth   = true;
// 		$mail->Username   = 'support@kashkash.net'; // SMTP username
// 		$mail->Password   = '+tCq#F6P$$';             // SMTP password
// 		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
// 		$mail->Port       = 465;                         // or 465 if SSL
// 		// Recipients
// 		$mail->setfrom('support@kashkash.net', 'Your Name');
// 		$mail->addaddress($to, 'Recipient Name');

// 		// Content
// 		$mail->isHTML(true);
// 		$mail->Subject = 'Subject';
// 		$mail->Body    = 'HTML message body';
// 		$mail->AltBody = 'Plain text message body';
// 		$mail->send();
// 		echo 'Message has been sent';
// 	} catch (Exception $e) {
// 		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// 	}

// }

function androidPush($post) {
	$message = $post['message'];
	$device_token = $post['token'];
	$title = $post['title'];
	$user_id = @$post['user_id'];
	$chat_id = @$post['chat_id'];
	$notification_setting = $post['notification_setting'];

	// $headers = array(
	// 	'Authorization: key=' . $apiKey,
	// 	'Content-Type: application/json',
	// );

	try {
		$serviceAccountFilePath = "pvKeylive.json";
		if (!file_exists($serviceAccountFilePath)) {
			throw new Exception("Service account file not found.");
		}

		$credential = new ServiceAccountCredentials(
			"https://www.googleapis.com/auth/firebase.messaging",
			json_decode(file_get_contents($serviceAccountFilePath), true)
		);

		$httpHandler = HttpHandlerFactory::build();
		$token = $credential->fetchAuthToken($httpHandler);

		if (!isset($token['access_token'])) {
			throw new Exception("Failed to fetch access token.");
		}

		// echo $device_token;
		// Prepare the cURL request
		$ch = curl_init("https://fcm.googleapis.com/v1/projects/kashkash-deaa2/messages:send");

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token['access_token']
		];

		$postData = [
			'message' => [
				'token' => $device_token,
				'notification' => [
					'title' => $title,
					'body' => $message
					// 'image' => 'https://cdn.shopify.com/s/files/1/1061/1924/files/Sunglasses_Emoji.png?2976903553660223024'
				],
				'webpush' => [
					'fcm_options' => [
						'link' => 'https://kashkash.net'
					]
				],
				'data' => [
					'user_id' => $user_id,
					'chat_id' => $chat_id
				],
			]
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);

		if ($response === false) {
			throw new Exception('Curl error: ' . curl_error($ch));
		}

		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		// echo $response;
		
		$responseDecoded = json_decode($response, true);
		// echo $responseDecoded;

		if ($httpCode !== 200) {
			throw new Exception('Error response from FCM: ' . json_encode($responseDecoded));
		}

		return $responseDecoded;
	} catch (Exception $e) {
		return ['error' => $e->getMessage()];
	}
}


function iosPush($post) {
	$message = $post['message'];
	$device_token = $post['token'];
	$title = $post['title'];
	$user_id = @$post['user_id'];
	$chat_id = $post['chat_id'];
	$notification_setting = $post['notification_setting'];

	// $headers = array(
	// 	'Authorization: key=' . $apiKey,
	// 	'Content-Type: application/json',
	// );

	try {
		$serviceAccountFilePath = "pvKeylive.json";
		if (!file_exists($serviceAccountFilePath)) {
			throw new Exception("Service account file not found.");
		}

		$credential = new ServiceAccountCredentials(
			"https://www.googleapis.com/auth/firebase.messaging",
			json_decode(file_get_contents($serviceAccountFilePath), true)
		);

		$httpHandler = HttpHandlerFactory::build();
		$token = $credential->fetchAuthToken($httpHandler);

		if (!isset($token['access_token'])) {
			throw new Exception("Failed to fetch access token.");
		}

		// echo $device_token;
		// Prepare the cURL request
		$ch = curl_init("https://fcm.googleapis.com/v1/projects/kashkash-deaa2/messages:send");

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token['access_token']
		];

		$postData = [
			'message' => [
				'token' => $device_token,
				'notification' => [
					'title' => $title,
					'body' => $message
				],
				'webpush' => [
					'fcm_options' => [
						'link' => 'https://kashkash.net'
					]
				],
				'data' => [
					'user_id' => $user_id,
					'chat_id' => $chat_id
				],
				'apns' => [
					'headers' => [
						'apns-priority' => '10'
					],
					'payload' => [
						'aps' => [
							'alert' => [
								'title' => $title,
								'body' => $message
							],
							'sound' => 'default',
							'badge' => 1
						]
					]
				]
			]
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);

		if ($response === false) {
			throw new Exception('Curl error: ' . curl_error($ch));
		}

		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		// echo $response;
		
		$responseDecoded = json_decode($response, true);
		// echo $responseDecoded;

		if ($httpCode !== 200) {
			throw new Exception('Error response from FCM: ' . json_encode($responseDecoded));
		}

		return $responseDecoded;
	} catch (Exception $e) {
		return ['error' => $e->getMessage()];
	}
}