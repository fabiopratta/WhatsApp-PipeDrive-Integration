<?php

require_once "conecta_mysql.php";

$phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : "";
$dataPassada = isset($_REQUEST['data']) ? $_REQUEST['data'] : "";

$mensagensCli = getMessagesAllCliente($phone);
$mensagensOp = getMessagesAllOperador($phone);
$mensagensArr = array_merge( $mensagensCli, $mensagensOp );
ksort( $mensagensArr );
$resultArr = [];
foreach ( $mensagensArr as $data => $mensagensArr ) {
	$result = "";
	$mensagens = str_replace("000","",$mensagensArr['mensagem']);
	$html = preg_replace("/\\\\u([0-9A-F]{2,5})/i", "&#x$1;", $mensagens);
	$resultArr[ $data ]['mensagem'] = $html;
	$resultArr[ $data ]['de'] = $mensagensArr['de'];
	$resultArr[ $data ]['para'] = $mensagensArr['para'];
}

$datas = array_keys($resultArr);
$termo = $dataPassada;
$pattern = '/' . $termo . '/';

foreach ($datas as $data)
{
    if (!preg_match($pattern, $data)) {
        unset($resultArr[$data]);
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Fabio Pratta <fabiobrotas@hotmail.com>">
    <title>Mensagens</title>
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/viewmessages.css" rel="stylesheet">
</head>
<body>
<div class="speech-wrapper" id="mensagensEnviadasRecebidas">

					<?php
					  foreach ($resultArr as $data => $mensagem){
						  $termo = 'b';
						  $pattern = '/' . $termo . '/';//Padrão a ser encontrado na string $tags
						  if (preg_match($pattern, $mensagem['mensagem'])) {


						      ?>
                              <!--loop mensagens enviadas-->
                              <div class="bubble alt green">
                                  <div class="txt">
                                      <p class="name"><?php echo $mensagem['de']; ?></p>
                                      <p class="message"><?php echo $mensagem['mensagem']; ?></p><br/>
                                      <span class="timestamp"><?php echo $data; ?></span>
                                  </div>
                                  <div class="bubble-arrow alt"></div>
                              </div>
                              <?php  } else {
							  $numeroResposta =  $mensagem['para'];?>
                              <!--loop mensagens enviadas-->
                              <div class="bubble white">
                                  <div class="txt">
                                      <p class="name"><?php echo $mensagem['de']; ?></p>
                                      <p class="message"><?php echo $mensagem['mensagem']; ?></p><br/>
                                      <span class="timestamp"><?php echo $data; ?></span>
                                  </div>
                                  <div class="bubble-arrow"></div>
                              </div>
						 <?php } ?>
					<?php }?>
</div>
<div id="sendMessagem">
    <input type="hidden" id="numeroDe" value="<?php echo $numeroResposta; ?>" />
    <div class="row">
        <div class="col-md-10">
            <textarea class="form-control" id="messageClient" rows="3"></textarea>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-success btn-lg btn-block" id="sendClient">Enviar Mensagem</button>
        </div>
    </div>
</div>

</body>
<script src="js/jquery.js"></script>
<script src="js/viewmessages.js"></script>
</html>
