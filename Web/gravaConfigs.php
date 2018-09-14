<?php
/**
 * @file           gravaConfigs.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 24/01/2018
 * @since 24/01/2018
 */

$configFile = $_REQUEST['configFile'];



$ponteiro = fopen ($configFile, "r");
while (!feof ($ponteiro)) {
  $linha[] = fgets($ponteiro, 4096);
}
fclose ($ponteiro);
$numero = explode("phone=55", $linha[1]);


$expira = time() + 3600;
setcookie("config", $configFile, $expira);
setcookie("numero", $numero[1]);

if(isset($_COOKIE['config']))
{
    header("location: paginaPrincipal.php");
}