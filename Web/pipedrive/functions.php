<?php
/**
 * @file           functions.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 22/05/2018
 * @since 22/05/2018
 */


function createURL($idcontact, $url,  $token)
{
	//eb1bec8a5281c6d8228952860b74b841ead67901

	$pessoa = [
		'eb1bec8a5281c6d8228952860b74b841ead67901' => $url
	];

	//URL for Deal listing with your company domain name and $api_token variable
	//https://api.pipedrive.com/v1/persons/3415?api_token=f9eb3ec13397d43690e2b5dfd34344b5399456f6
	$url = 'https://grupoperaltas.pipedrive.com/v1/persons/'.$idcontact.'?api_token=' . $token;

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "PUT",
		CURLOPT_POSTFIELDS => json_encode($pessoa),
		CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/json",
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #:" . $err;
	} else {
		//echo $response;
	}
}

function isUrl ($phone, $token){
	$pessoa = findPerson($phone,$token);

	if(!$pessoa['id'])
	{
		return false;
	}

	$detailsPessoa = getDetails($pessoa['id'],$token);

	$url = $detailsPessoa['eb1bec8a5281c6d8228952860b74b841ead67901'];

	if($url != "" && strlen($url) < 1)
	{
		return true;
	}else{
		return false;
	}
}


//insertURL('3415',"http://uol.com", 'f9eb3ec13397d43690e2b5dfd34344b5399456f6');
/** PROCURO A PESSOA PELO TELEFONE */
function findPerson($phone, $token)
{
	$url = 'https://grupoperaltas.pipedrive.com/v1/persons/find?term='.$phone.'&start=0&api_token='.$token;
	//GET request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
	$output = curl_exec($ch);
	curl_close($ch);
// Create an array from the data that is sent back from the API
// As the original content from server is in JSON format, you need to convert it to PHP array
	$result = json_decode($output, true);

	//print_r($result);
	return $result['data'][0];
}

function getDetails($idcontact, $token)
{
	//https://api.pipedrive.com/v1/persons/3415?api_token=f9eb3ec13397d43690e2b5dfd34344b5399456f6
	$url = 'https://grupoperaltas.pipedrive.com/v1/persons/'.$idcontact.'?api_token='.$token;
	//GET request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
	$output = curl_exec($ch);
	curl_close($ch);
// Create an array from the data that is sent back from the API
// As the original content from server is in JSON format, you need to convert it to PHP array
	$result = json_decode($output, true);

	//print_r($result);
	return $result['data'];
}


function addActivity($personId, $token, $url)
{
	$dataUrl = [
		"subject" => "Nova conversa WhatsAPP Arquivada\n\r",
		"done" => 1,
		"type" => "WhatsApp",
		"person_id" => $personId,
		"note" => $url
		];
	$url = 'https://grupoperaltas.pipedrive.com/v1/activities?api_token=' . $token;
	//GET request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $dataUrl);
	$output = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($output, true);
	$idActivity =  $result['data']['id'];
}

function getActivities($idcontact, $token)
{
	//https://api.pipedrive.com/v1/persons/3415?api_token=f9eb3ec13397d43690e2b5dfd34344b5399456f6/persons/{id}/activities
	$url = 'https://grupoperaltas.pipedrive.com/v1/persons/'.$idcontact.'/activities?api_token='.$token;
	//GET request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
	$output = curl_exec($ch);
	curl_close($ch);
// Create an array from the data that is sent back from the API
// As the original content from server is in JSON format, you need to convert it to PHP array
	$result = json_decode($output, true);

	//print_r($result);
	return $result['data'];
}

function checkAtivities($idContact, $data, $token)
{
	$atv = getActivities($idContact,$token);
	foreach ($atv as $atvidade)
	{
		$termo = 'data='.$data;
		$pattern = '/' . $termo . '/';//Padrão a ser encontrado na string $tags
		if (!preg_match($pattern, $atvidade['nota'])) {

		}
	}
}
