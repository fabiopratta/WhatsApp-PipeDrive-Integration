<?php
/**
 * @file           conecta_mysql.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 29/01/2018
 * @since 29/01/2018
 */


error_reporting(E_ALL);
ini_set("display_errors", 1);


define("SERVER", "localhost");
define("USER", "root");
define("PASS", "F25b6i87@");
define("DATABASE", "whatsapi");

function getConnection()
{
    $con = mysqli_connect(SERVER,USER,PASS, DATABASE) or die (mysqli_connect_errno());
    return $con;
}


function insertData($to_phone, $to_from, $message)
{
    $con = getConnection();
    $query = "INSERT INTO mensagens (data,de_phone,para_phone,message,visto) VALUES (NOW(),'$to_phone','$to_from','$message','0')";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
}


function getMessages($to_phone, $to_from)
{
    $con = getConnection();
    $query = "SELECT * FROM mensagens WHERE de_phone = '$to_phone' AND para_phone = '$to_from'";

    $result = mysqli_query($con, $query) or die(mysqli_error($con));

    $mensagens = [];
    while($mensagem = mysqli_fetch_array($result))
    {
     $mensagens[$mensagem['data']] = $mensagem['message'];
    }

    return $mensagens;
}

function getMessagesAll($number)
{
	$con = getConnection();
	$query = "SELECT * FROM mensagens WHERE de_phone = '$number'";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));
	$mensagens = [];
	while($mensagem = mysqli_fetch_array($result))
	{
		$mensagens[$mensagem['data']] = $mensagem['message'];
	}
	return $mensagens;
}


function getMessagesAllCliente($number)
{
	$con = getConnection();
	$query = "SELECT * FROM mensagens WHERE de_phone = '$number'";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));
	$mensagens = [];
	while($mensagem = mysqli_fetch_array($result))
	{
		$mensagens[$mensagem['data']]['de'] = $mensagem['de_phone'];
		$mensagens[$mensagem['data']]['para'] = $mensagem['para_phone'];
		$mensagens[$mensagem['data']]['mensagem'] = $mensagem['message'];
	}
	return $mensagens;
}

function getMessagesAllOperador($number)
{
	$con = getConnection();
	$query = "SELECT * FROM mensagens WHERE para_phone = '$number'";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));
	$mensagens = [];
	while($mensagem = mysqli_fetch_array($result))
	{
		$mensagens[$mensagem['data']]['de'] = $mensagem['de_phone'];
		$mensagens[$mensagem['data']]['para'] = $mensagem['para_phone'];
		$mensagens[$mensagem['data']]['mensagem'] = $mensagem['message'];
	}
	return $mensagens;
}

function getMessagesAllNoRead($number)
{
	$con = getConnection();
	$query = "SELECT * FROM mensagens WHERE para_phone = '$number' AND visto = '0' ";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));
	$mensagens = [];
	while($mensagem = mysqli_fetch_array($result))
	{
		$mensagens[] = $mensagem;
	}
	updateAllRead($number);
	return $mensagens;
}

function updateAllRead($number)
{
	$con = getConnection();
	$query = "UPDATE mensagens SET visto = '1' WHERE para_phone = '$number'";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));

	//verifica a URL e salva ela caso nao existe no pipe drive
	//http://187.63.83.168/whatsapi/Web/viewMessages.php?phone=5514997157886

}

function getMessagesData($de,$para,$data){
	$con = getConnection();

	$atual =  date("Y-m-d H:i:s");

	$mensagens = [];

	//Cliente
	$query = "SELECT * FROM mensagens WHERE  de_phone = '$de' AND para_phone='$para' AND data <= '$atual' AND data > '$data'";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));
	while($mensagem = mysqli_fetch_array($result))
	{
		$mensagens[$mensagem['data']]['de'] = $mensagem['de_phone'];
		$mensagens[$mensagem['data']]['para'] = $mensagem['para_phone'];
		$mensagens[$mensagem['data']]['mensagem'] = $mensagem['message'];
	}
	//updateAllReadMessages($de,$para);
	return $mensagens;
}

function updateAllReadMessages($de,$para) {
	$con = getConnection();
	$query = "UPDATE mensagens SET visto = '1' WHERE para_phone = '$para' AND de_phone = '$de' ";
	$result = mysqli_query($con, $query) or die(mysqli_error($con));
}




