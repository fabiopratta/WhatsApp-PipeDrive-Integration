$(document).ready(function () {

    var timerReceboMensagem;

    //Grava a config no Javascript TBM
    $("#SelCelularNumber").change(function () {
        sessionStorage.setItem("config", $(this).val());
    });


    //Envio a mensagem do formulario
    $("#enviaMensagem").click(function (){

        var numero = $("#numero").val();
        var mensagem = $("#mensagem").val();
        var config = $("#config").val();
        var envio = $("#envio").val();

        $.ajax({
            method: "POST",
            url: "enviamensagem.php",
            data: { numero: numero, mensagem: mensagem, config: config,envio: envio }
         })
            .done(function( msg ) {
                alert("Mensagem enviada com sucesso!");
                 inicioRecebimentoMensagens();
               // console.log( msg );
            });
    });

    //Recebo as mensagem
    function inicioRecebimentoMensagens()
    {

        var numero = $("#numero").val();
        var mensagem = $("#mensagem").val();
        var config = $("#config").val();
        var envio = $("#envio").val();

        timerReceboMensagem = setInterval(function(){
            $.ajax({
                method: "POST",
                url: "getMensagens.php",
                data: { numero: numero, mensagem: mensagem, config: config,envio: envio }
            })
                .done(function( msg ) {
                    $('#respostas').html(msg);
                });
        },5000);
        timerReceboMensagem.start();
    }

});