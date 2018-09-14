<?php
/**
 * @file           webservice.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 26/03/2018
 * @since 26/03/2018
 */
set_time_limit(200);
//Conecta mysql get Messages
require_once "conecta_mysql.php";
require_once "pipedrive/functions.php";


$acao = $_REQUEST['action'];
$celular = isset($_REQUEST['celular']) ? $_REQUEST['celular'] : "";

$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : "";

//Pego todas as nao lidas
function getAllNotRead($celular,$token)
{
	$dados = getMessagesAllNoRead($celular);
	echo json_encode($dados);

	//check url
	foreach ($dados as $dataPhone){
		$phone = str_replace("55","",$dataPhone['de_phone']);
		if(!isUrl($phone,$token))
		{
			$pessoa = findPerson($phone,$token);
			//print_r($pessoa);die();
			$url = "http://187.63.83.168/whatsapi/Web/viewMessages.php?phone=".$dataPhone['de_phone'];
			createURL($pessoa['id'], $url,  $token);
		}
	}
}

if($acao=="noread")
{
	getAllNotRead($celular,$token);
}



//Pego todas as mensagens de numero para numer
function getMessagesRead($de, $para) {

	$mensagensCli = getMessages( $de, $para );
	$mensagensOp  = getMessages( $para, $de );

	$mensagensArr = array_merge( $mensagensCli, $mensagensOp );
	ksort( $mensagensArr );

	$resultArr = [];

	foreach ( $mensagensArr as $data => $mensagens ) {

		$mensagens = str_replace("000","",$mensagens);
		$html = preg_replace("/\\\\u([0-9A-F]{2,5})/i", "&#x$1;", $mensagens);

		$texto = preg_replace("/\\\\x([0-9A-F]{2,5})/i", "&#x$1;", $html);

		$resultArr[ $data ] = $texto;

	}
	echo json_encode($resultArr);
}

if($acao=="messages")
{
	$de = $_REQUEST['de'];
	$para = $_REQUEST['para'];
	getMessagesRead($de,$para,$token);
}


//Envia Mensagem
function enviaMensagem($token) {
	$numero      = trim( $_REQUEST['numero'] );
	$mensagem    = $_REQUEST['mensagem'];
	$config      = $_REQUEST['config'];
	$numeroEnvio = trim( $_REQUEST['envio'] );

	$comando = "sudo /usr/local/bin/yowsup-cli demos -s {$numero} \"{$mensagem}\" -c {$config}";
	#$comando = "yowsup-cli demos -s 5514997157886 \"TESTEOK\" -c /etc/yowsup/config.conf";
	//echo $comando."<br/>";
	//exec_timeout($comando,60);

	//exec_timeout($comando,60);

	//shell_exec($comando);
	$out = array();
	exec( $comando, $out );
	$ultimaLinha = count($out);

	$varCheck = $out[$ultimaLinha-1];

	$return = [];
	if($varCheck=="Yowsdown"){

		$numeroLimpo = str_replace( "55", "", $numero );
		$pessoa      = findPerson( $numeroLimpo, $token );
		$url         = "http://187.63.83.168/whatsapi/Web/viewMessages.php?phone=" . $numero;
		if ( $pessoa['id'] ) {
			createURL( $pessoa['id'], $url, $token );
		}


		insertData( $numeroEnvio, $numero, '<b>' . $mensagem . '</b>' );

		echo "ENVIADO";
	}
}
/**
 * Execute a command and return it's output. Either wait until the command exits or the timeout has expired.
 *
 * @param string $cmd     Command to execute.
 * @param number $timeout Timeout in seconds.
 * @return string Output of the command.
 * @throws \Exception
 */
function exec_timeout($cmd, $timeout) {
	// File descriptors passed to the process.
	$descriptors = array(
		0 => array('pipe', 'r'),  // stdin
		1 => array('pipe', 'w'),  // stdout
		2 => array('pipe', 'w')   // stderr
	);

	// Start the process.
	$process = proc_open('exec ' . $cmd, $descriptors, $pipes);

	if (!is_resource($process)) {
		throw new \Exception('Could not execute process');
	}

	// Set the stdout stream to none-blocking.
	stream_set_blocking($pipes[1], 0);

	// Turn the timeout into microseconds.
	$timeout = $timeout * 1000000;

	// Output buffer.
	$buffer = '';

	// While we have time to wait.
	while ($timeout > 0) {
		$start = microtime(true);

		// Wait until we have output or the timer expired.
		$read  = array($pipes[1]);
		$other = array();
		stream_select($read, $other, $other, 0, $timeout);

		// Get the status of the process.
		// Do this before we read from the stream,
		// this way we can't lose the last bit of output if the process dies between these     functions.
		$status = proc_get_status($process);

		// Read the contents from the buffer.
		// This function will always return immediately as the stream is none-blocking.
		$buffer .= stream_get_contents($pipes[1]);

		if (!$status['running']) {
			// Break from this loop if the process exited before the timeout.
			break;
		}

		// Subtract the number of microseconds that we waited.
		$timeout -= (microtime(true) - $start) * 1000000;
	}

	// Check if there were any errors.
	$errors = stream_get_contents($pipes[2]);

	if (!empty($errors)) {
		throw new \Exception($errors);
	}

	// Kill the process in case the timeout expired and it's still running.
	// If the process already exited this won't do anything.
	proc_terminate($process, 9);

	// Close all streams.
	fclose($pipes[0]);
	fclose($pipes[1]);
	fclose($pipes[2]);

	proc_close($process);

	return $buffer;
}


if($acao=='sendMessage')
{
	enviaMensagem($token);
}

//PIPE

if($acao == "getNamePipe"){
	$token = $_REQUEST['token'];
	$phone = $_REQUEST['phone'];
	$phone = str_replace("55","", $phone);

	$person  = findPerson($phone, $token);
	echo json_encode($person);
}

if($acao == "saveActivity"){

	$idPerson = $_REQUEST['idPerson'];
	$token = $_REQUEST['token'];
	$telefone = $_REQUEST['telefone'];
	$data = $_REQUEST['data'];
	$urlGenerate = "http://187.63.83.168/whatsapi/Web/viewMessages.php?phone=55".$telefone."&data=".$data;

	addActivity($idPerson, $token, $urlGenerate);
}

if($acao == "getNewMessages")
{
	$para = $_REQUEST['para'];
	$de = $_REQUEST['de'];
	$data = $_REQUEST['data'];

	$mensagensArr = getMessagesData($de,$para,$data);
	$resultArr = [];
	foreach ( $mensagensArr as $data => $mensagens ) {
		$result = "";
		$mensagens = str_replace("000","",$mensagens);
		$html = preg_replace("/\\\\u([0-9A-F]{2,5})/i", "&#x$1;", $mensagens);
		$texto = preg_replace("/\\\\x([0-9A-F]{2,5})/i", "&#x$1;", $html);
		$resultArr[ $data ] = $texto;

	}
	echo json_encode($resultArr);
}