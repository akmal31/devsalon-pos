<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// added by stu
if (!function_exists('encryptIt')){
	function encryptIt( $q ) {
		$cryptKey  = 'OUjpuR79Wo8a235g45gC1io8w24uEb';
		$code  = 'AES-128-ECB';
		$encrypted_string = base64_encode(openssl_encrypt($q,$code,$cryptKey));
		
		return($encrypted_string);
	}
}

if (!function_exists('decryptIt')){
	function decryptIt( $q ) {
		$q = base64_encode($q);
		$cryptKey  = 'OUjpuR79Wo8a235g45gC1io8w24uEb';
		$code  = 'AES-128-ECB';
		$encrypted_string = openssl_decrypt($q,$code,$cryptKey);
		
		return($encrypted_string);
	}
}

if (!function_exists('urlSEO')){
	function urlSEO($url) {
		$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
		$url = trim($url, "-");
		$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
		$url = strtolower($url);
		$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
		return $url;
	}
}

if (!function_exists('see')){
	function see($temp)
	{
		echo '<pre>';
		print_r($temp);
		echo '</pre>';
	}
}

if( ! function_exists('genRandomString')){
	function genRandomString($length=10){
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$string = "";
		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters))];
		}
		return $string;
	}
}

if( ! function_exists('secure_input')){
	function secure_input($data){

		$filter_sql = stripslashes(strip_tags(htmlspecialchars(trim($data),ENT_QUOTES)));
		return $filter_sql;
	}
}

if(!function_exists('generate_id')){
	function generate_id(){
		$min 		= 1000;
		$max 		= 9999;
		$randnumber = rand($min, $max);

		return $randnumber;
	}
}

if(!function_exists('ceiling')){
	function ceiling($number){
		$significance = 1000;
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
	}
}

if(!function_exists('unique_id')){
	function unique_id(){
		$min 		= 001;
		$max 		= 499;
		$randnumber = rand($min, $max);
		return $randnumber;
	}
}

if(!function_exists('base64url_encode')){
	function base64url_encode($data) {
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
}

if(!function_exists('base64url_decode')){
	function base64url_decode($data) {
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
}

function string_shorten($text, $char) {
    $text = substr($text, 0, $char); //First chop the string to the given character length
    if(substr($text, 0, strrpos($text, ' '))!='') $text = substr($text, 0, strrpos($text, ' ')); //If there exists any space just before the end of the chopped string take upto that portion only.
    //In this way we remove any incomplete word from the paragraph
    $text = $text.'...'; //Add continuation ... sign
    return $text; //Return the value
}

function httpGet($token,$url)
{
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_CUSTOMREQUEST => 'GET',
	CURLOPT_HTTPHEADER => array(
		'Authorization: Bearer '.$token
	),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
}

function httpSend($token,$url,$method, $data="")
{
	$curl = curl_init($url);
	$token= array('Content-Type: application/json','Authorization: Bearer '.$token);
	if($method=="POST"){
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}else{
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $token);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
	
function httpSendFile($token,$url,$method, $data="")
{
	$curl = curl_init();
	$token= array('Authorization: Bearer '.$token);	
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_HTTPHEADER => $token,
	));
	
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
?>