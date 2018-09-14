var url   = window.location.search;
var parametrosDaUrl = url.split("?")[1];
var listaDeParametros = parametrosDaUrl.split("&");
var hash = {}
for(var i = 0; i < listaDeParametros.length; i++){
    var parametro = listaDeParametros[i].split("=");
    var chave = parametro[0];
    var valor = parametro[1];
    hash[chave] = valor;
}

var mensagemDe = hash['phone'];
var mensagemPara = localStorage.celularnumero;

var CELULAR = document.getElementById("numeroDe").value;


//troca o titulo da janela
var titlePage = "Mensagem de "+mensagemDe;
document.title = titlePage;


//Envio a mensagem do formulario
$("#sendClient").click(function (){
    var mensagem = $("#messageClient").val();
    var config = "/etc/yowsup/"+CELULAR+".conf";
    $.ajax({
        method: "POST",
        url: "http://187.63.83.168/whatsapi/Web/webservice.php",
        data: { numero: mensagemDe, mensagem: mensagem, config: config, envio: mensagemPara, action: 'sendMessage' }
    })
        .done(function( msg ) {
            $("#messageClient").empty();
            $("#messageClient").val('');
            $("#messageClient").attr("value", "");
        });
});

