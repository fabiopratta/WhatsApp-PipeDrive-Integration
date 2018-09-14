<script>
    //console.log(sessionStorage.getItem("config"));
</script>
<?php
/**
 * @file           paginaPrincipal.php
 * @author         Fabio Pratta <fabiobrotas@hotmail.com>
 * @copyright      Copyright - WhatsApi | 24/01/2018
 * @since 24/01/2018
 */
//echo $_COOKIE['config'];
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Sistema WhatsAPP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="js/funcoes.js?<?php echo uniqid();?>"></script>
</head>
<body>

<div class="container">
    <div class="enviar">
        <form method="post" action="enviamensagem.php">
            <div class="form-group">
                <label for="numero">Numero:</label>
                <input type="numero" class="form-control" id="numero" aria-describedby="numeroAjuda" placeholder="Ex.: 14997157886">
                <small id="numeroAjuda" class="form-text text-muted">Digite um numero de celular com o DDD.</small>
            </div>

            <div class="form-group">
                <label for="mensagem">Mensagem:</label>
                <textarea name="mensagem" id="mensagem" class="form-control"></textarea>
            </div>


            <div class="form-group">
                <input type="hidden" name="config" id="config" class="form-control" value="<?=$_COOKIE['config'];?>" />
                <input type="hidden" name="envio" id="envio" class="form-control" value="<?=$_COOKIE['numero'];?>" />
            </div>

            <button type="button" id="enviaMensagem" class="btn btn-primary">Enviar Mensagem</button>
        </form>
    </div>

    <div id="respostas">
    Mensagem Aqui
    </div>
</div>
</form>
</body>
</html>
