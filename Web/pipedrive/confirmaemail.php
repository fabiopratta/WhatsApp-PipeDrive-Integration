<?php
/**
 * @file           confirmaemail.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - FormPipe | 05/01/2018
 * @since 05/01/2018
 */

// Pipedrive API token
$api_token = '0b89d278f9d3debfe30b08cb441f295f84832371';

$ip = "wordpress.brotasecoresort.com.br";
$user = "landpage";
$pass = "2355grupo";
$database = "landpage_contato";

$token = strip_tags($_REQUEST['token']);

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
?>


<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <style>
        img{display:block; margin:50px auto 40px;}
        p{text-align:center; font-family:arial; font-size:18px; color:#555;}
        a{color:#fff; font-family:arial; font-size:16px; text-decoration:none; text-shadow:1px 1px 1px #333;}
        .botao{width:130px; height:auto; background:#27ae60; margin:30px auto 0; text-align:center; padding:10px 0; border-radius:3px;}
        .botao:hover{background:#2980b9;}
    </style>

    <title>Obrigado</title>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-84623205-1', 'auto');
        ga('send', 'pageview');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '228553114161354');
        fbq('track', "Lead");</script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=228553114161354&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

</head>

<body>

<img src="images/logo.png">
<p>Obrigado! Sua mensagem foi enviada com sucesso!</p>
<p>Em breve entraremos em contato.</p>
<a href="./"><div class="botao">VOLTAR</div></a>

<script language= "JavaScript">setTimeout("document.location = './'",5000);</script>

</body>
</html>


