<?php
/**
 * @file           confirmacontato.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - FormPipe | 05/01/2018
 * @since 05/01/2018
 */


$ip = "wordpress.brotasecoresort.com.br";
$user = "landpage";
$pass = "2355grupo";
$database = "landpage_contato";


$conexao = mysqli_connect($ip,$user,$pass,$database) or die(mysqli_error());

//Salvo Contato
$nome = strip_tags($_REQUEST['nome']);
$email = strip_tags($_REQUEST['email']);
$telefone = strip_tags($_REQUEST['telefone']);
$mensagemT = strip_tags($_REQUEST['mensagem']);
$checkin = strip_tags($_REQUEST['checkin']);
$checkout = strip_tags($_REQUEST['checkout']);
$token = trim(md5(uniqid()));
$adultos = strip_tags($_REQUEST['adulto']);
$criancas = strip_tags($_REQUEST['crianca']);

$dataArray = array(
  'nome' => $nome,
  'email' => $email,
  'telefone' => $telefone,
  'mensagem' => $mensagemT,
  'checkin' => $checkin,
  'checkout' => $checkout,
    'adulto' => $adultos,
    'crianca' => $criancas
);

$dataJson = json_encode($dataArray);

$link = "http://www.brotasecoresort.com.br/contato-facebook/confirmaemail.php?token=".$token;
//Envio Email
require ('phpmailer/PHPMailerAutoload.php');

// Inicia a classe PHPMailer
$mail = new PHPMailer();
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(true); // Define que a mensagem será SMTP
$mail->Host = "sub5.mail.dreamhost.com"; // Endereço do servidor SMTP
$mail->Port = 465;
$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
$mail->SMTPSecure = 'ssl';
$mail->Username = 'contato@brotasecoresort.com.br'; // Usuário do servidor SMTP
$mail->Password = 'grupo2355'; // Senha do servidor SMTP
// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->From = "contato@brotasecoresort.com.br"; // Seu e-mail
$mail->FromName = "Brotas Eco Resort"; // Seu nome
// Define os destinatário(s)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddAddress($email, $email);
// Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->Subject  = "Confirmar envio de contato"; // Assunto da mensagem
$mail->Body = "Obrigado pelo contato, para confirmar o envio de seu email clique no link abaixo. <br/> <a href='$link'>$link</a> <br/> Obrigado.";
$mail->AltBody = "Obrigado pelo contato, para confirmar o envio de seu email copie e cole o link em seu navegador. \r\n $link \r\n Obrigado.";
// Define os anexos (opcional)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
// Envia o e-mail
//$enviado = $mail->Send();
// Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();
// Exibe uma mensagem de resultado
/*
if ($enviado) {
    $sqlInsert = mysqli_query($conexao,"INSERT INTO contatos (token,dados) VALUES ('$token','$dataJson')") or die(mysqli_error());
    ?>
    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/resources/home/ber-logo-horizontal.png" />
    <p><strong>Obrigado! Sua mensagem foi enviada com sucesso!</strong></p>
    <p style="color: red;"><strong>Em breve você receberá um email para confirmar o envio.</strong></p>
<?php
}
else {
    ?>
    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/resources/home/ber-logo-horizontal.png" />
    <p><?php echo "<b>Informações do erro:</b> " . $mail->ErrorInfo; ?></p>
    <?php
}*/

$sqlInsert = mysqli_query($conexao,"INSERT INTO contatos (token,dados) VALUES ('$token','$dataJson')") or die(mysqli_error());


if($sqlInsert)
{
    echo '<p><strong>Obrigado! Sua mensagem foi enviada com sucesso!</strong></p>';
}

// Pipedrive API token
$api_token = '0b89d278f9d3debfe30b08cb441f295f84832371';

$ip = "wordpress.brotasecoresort.com.br";
$user = "landpage";
$pass = "2355grupo";
$database = "landpage_contato";


$conexao = mysqli_connect($ip,$user,$pass,$database) or die(mysqli_error());


$sql = mysqli_query($conexao,"SELECT * FROM contatos WHERE token ='$token' LIMIT 1") or die(mysqli_error());
$dados = mysqli_fetch_array($sql);
$dadosUsuario = json_decode($dados['dados'],true);

$nome = strip_tags($dadosUsuario['nome']);
$email = strip_tags($dadosUsuario['email']);
$telefone = strip_tags($dadosUsuario['telefone']);
$mensagemT = strip_tags($dadosUsuario['mensagem']);
$checkin = strip_tags($dadosUsuario['checkin']);
$checkout = strip_tags($dadosUsuario['checkout']);
$adultos = strip_tags($dadosUsuario['adulto']);
$crianca = strip_tags($dadosUsuario['crianca']);


$pessoa = array(
	'name' => $nome,
	'email' => $email,
	'phone' => $telefone,
	'visible_to' => '3',
	'7c8ea1fcd77ff2f5789f3a423d26907288ad1d63' => "101"
);

$mensagem = $mensagemT;

$idpessoa = findPerson($pessoa['email'],$token);
if($idpessoa == "")
{
	$idpessoa = addPerson($pessoa,$api_token);
}


$negocio = array (
	'title' => $pessoa['name'],
	'person_id' => $idpessoa,
	'user_id' => "3183119",
	'stage_id' => "48",
	'visible_to' => '3',
	'ff4a8817f3bea40f6cf912919a8df3d366b110d1' => formataData($checkin),
	'ff4a8817f3bea40f6cf912919a8df3d366b110d1_until' => formataData($checkout),
	'705a81908d43bc50fa231caed16415cdd944a2bc' => $adultos,
	'85f9a0c38553064f1df7fc6130368bdad3dc8f31' => $crianca
);


$idNegocio = addDeal($negocio,$api_token);


addNote($idNegocio,$mensagem, $idpessoa, $api_token);

function addNote($idnegocio, $mensagem, $idperson, $token)
{
	$note = array (
		'content' => $mensagem, 'deal_id' => $idnegocio, 'person_id' => $idperson
	);

	//URL for Deal listing with your company domain name and $api_token variable
	$url = 'https://grupoperaltas.pipedrive.com/v1/notes?api_token=' . $token;
	//GET request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $note);
	//echo 'Sending request...' . PHP_EOL;
	$output = curl_exec($ch);
	curl_close($ch);
	// Create an array from the data that is sent back from the API
	// As the original content from server is in JSON format, you need to convert it to PHP array
	$result = json_decode($output, true);
	//echo 'Negocio:' . PHP_EOL;
	return $result['data']['id'];
}


function addDeal(array $deal, $token)
{
	//URL for Deal listing with your company domain name and $api_token variable
	$url = 'https://grupoperaltas.pipedrive.com/v1/deals?api_token=' . $token;
	//GET request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $deal);
	//echo 'Sending request...' . PHP_EOL;
	$output = curl_exec($ch);
	curl_close($ch);
	// Create an array from the data that is sent back from the API
	// As the original content from server is in JSON format, you need to convert it to PHP array
	$result = json_decode($output, true);
	//echo 'Negocio:' . PHP_EOL;
	return $result['data']['id'];
}


function findPerson($email, $token)
{
	$url = 'https://api.pipedrive.com/v1/persons/find?term='.$email.'&start=0&search_by_email=1&api_token=f9eb3ec13397d43690e2b5dfd34344b5399456f6';
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
	return $result['data'][0]['id'];
}



function addPerson(array $person, $token)
{
	//URL for Deal listing with your company domain name and $api_token variable
	$url = 'https://grupoperaltas.pipedrive.com/v1/persons?api_token=' . $token;
	//GET request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $person);

	// echo 'Sending request...' . PHP_EOL;
	$output = curl_exec($ch);
	curl_close($ch);
	// Create an array from the data that is sent back from the API
	// As the original content from server is in JSON format, you need to convert it to PHP array
	$result = json_decode($output, true);
	// echo 'Person:' . PHP_EOL;

	//print_r($result);die();
	return $result['data']['id'];
}


function formataData($data){
	$dtF = explode("/",trim($data));
	return $dtF[2]."-".$dtF[1]."-".$dtF[0];
}