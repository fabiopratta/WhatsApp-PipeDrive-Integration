<?php

$numero = $_REQUEST['numero'];
$mensagem = $_REQUEST['mensagem'];
$config = $_REQUEST['config'];

$comando = "yowsup-cli demos -s 55{$numero} \"{$mensagem}\" -c {$config}";
$retorno  = shell_exec($comando);

//echo $retorno;
//yowsup-cli demos -s 5514997157886 "Envio de Mensagem teste" -c /etc/yowsup/config.conf

?>
