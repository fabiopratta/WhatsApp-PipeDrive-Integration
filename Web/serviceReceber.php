<?php
/**
 * @file           serviceReceber.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 24/01/2018
 * @since 24/01/2018
 */


//yowsup-cli demos -y -c whatsapp_config.txt
$config = $_REQUEST['config'];
$comando = "yowsup-cli demos -c {$config} -e 2>&1";

//$retorno = passthru($comando);

$retorno = shell_exec("$comando" );
echo $retorno;