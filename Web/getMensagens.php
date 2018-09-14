<?php
/**
 * @file           getMensagens.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 30/01/2018
 * @since 30/01/2018
 */

require_once "conecta_mysql.php";


$numero = trim($_REQUEST['numero']);
$numeroEnvio = trim($_REQUEST['envio']);

$mensagensCli = getMessages('55'.$numero,'55'.$numeroEnvio);
$mensagensOp = getMessages('55'.$numeroEnvio,'55'.$numero);


$mensagensArr = array_merge($mensagensCli,$mensagensOp);

ksort($mensagensArr);


foreach ($mensagensArr as $mensagens)
{
    echo ''.$mensagens.'<br/>';
}
/*
foreach ($mensagensCli as $mensagem)
{
    echo '<b><i>'.$mensagem.'</i></b><br/>';
}


$mensagensOp = getMessages('55'.$numeroEnvio,'55'.$numero);

foreach ($mensagensOp as $mensagem)
{
    echo ''.$mensagem.'<br/>';
}*/