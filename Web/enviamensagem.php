<?php

require_once "conecta_mysql.php";


$numero = trim($_REQUEST['numero']);
$mensagem = $_REQUEST['mensagem'];
$config = $_REQUEST['config'];
$numeroEnvio = trim($_REQUEST['envio']);

//insertData('55'.$numeroEnvio,'55'.$numero,'<b><i>'.$mensagem.'</i></b>');

$comando = "yowsup-cli demos -s 55{$numero} \"{$mensagem}\" -c {$config}";
echo shell_exec($comando);
?>
