<?php
/**
 * @file           insertMessages.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 18/05/2018
 * @since 18/05/2018
 */

$token = $_REQUEST['token'];

$token = "f9eb3ec13397d43690e2b5dfd34344b5399456f6";


/** PROCURO A PESSOA PELO TELEFONE */
function findPerson($phone, $token)
{
	$url = 'https://api.pipedrive.com/v1/persons/find?term='.$phone.'&start=0&api_token='.$token;
	//GET request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
// Create an array from the data that is sent back from the API
// As the original content from server is in JSON format, you need to convert it to PHP array
	$result = json_decode($output, true);

	//print_r($result);
	return $result['data'][0];
}
