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
var dataUltimaRecebida = null;
var TOKEN = localStorage.tokenpipedrive;
var CELULAR = localStorage.celularnumero;
//console.log(hash['phone']);

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
        data: { numero: mensagemDe, mensagem: mensagem, config: config, envio: mensagemPara,token: TOKEN, action: 'sendMessage' }
    })
        .done(function( msg ) {

            if(msg!="ENVIADO"){
                alert("Impossivel enviar esta mensagem!");
                return;
            }
            //recebeMensagemNew();
            var dataAtual = new Date().toLocaleString({ timeZone: 'America/Sao_Paulo' });


            //var date2 = new Date().toISOString().substr(0, 19).replace('T', ' ');
            var resultPrint = '<div class="bubble alt green">' +
                '     <div class="txt">' +
                '     <p class="message"><b>' + mensagem + '</b></p><br/>' +
                '     <span class="timestamp">'+dataAtual+'</span>' +
                '     </div>' +
                '     <div class="bubble-arrow alt"></div>' +
                '     </div>';
            $('#mensagensEnviadasRecebidas').html($('#mensagensEnviadasRecebidas').html() + resultPrint);

            var idSWig = $("#messageClient").data("id");
            var selector = '[data-id~="'+idSWig+'"]';
            $(selector).html("");

            $("#messageClient").empty();
            $("#messageClient").val('');
            $("#messageClient").attr("value", "");


            $("#mensagensEnviadasRecebidas").animate({ scrollTop: 999999 }, 'slow');
        });
});

//GEt messages do numero para o nuemro localStorage
function recebeMensagemAll(){
    $('#mensagensEnviadasRecebidas').html('');
    $.getJSON( "http://187.63.83.168/whatsapi/Web/webservice.php?de="+mensagemDe+"&para="+mensagemPara+"&action=messages", function( data ) {

        var resultMensagem = data;
        //Pega Numero PipE Drive
        var mensagemNome = "";
        $.getJSON( "http://187.63.83.168/whatsapi/Web/webservice.php?phone="+mensagemDe+"&token="+TOKEN+"&action=getNamePipe", function( data ) {
            if(data){
                mensagemNome = data.name;
            }
        }).done(function( data ) {

            $.each(resultMensagem, function( key, val ) {
                var resultPrint = '';
                dataUltimaRecebida = key;
                var dataForm = new Date(key).toLocaleString({ timeZone: 'America/Sao_Paulo' });
                if(val.match(/<b>/)){
                    resultPrint += '<div class="bubble alt green">' +
                        '     <div class="txt">' +
                        '     <p class="message">' + val + '</p><br/>' +
                        '     <span class="timestamp">'+dataForm+'</span>' +
                        '     </div>' +
                        '     <div class="bubble-arrow alt"></div>' +
                        '     </div>';
                }else{
                    resultPrint += '<div class="bubble white">' +
                        '     <div class="txt">' +
                        '     <p class="name">'+mensagemNome+" ("+mensagemDe+')</p>'+
                        '     <p class="message">' + val + '</p><br/>' +
                        '     <span class="timestamp">'+dataForm+'</span>' +
                        '     </div>' +
                        '     <div class="bubble-arrow"></div>' +
                        '     </div>';
                }
                $('#mensagensEnviadasRecebidas').html($('#mensagensEnviadasRecebidas').html() + resultPrint);
            });
            $("#mensagensEnviadasRecebidas").animate({ scrollTop: 999999 }, 'slow');
        });
    });

}
recebeMensagemAll();


function recebeMensagemNew(){
    $.getJSON( "http://187.63.83.168/whatsapi/Web/webservice.php?de="+mensagemDe+"&para="+mensagemPara+"&data="+dataUltimaRecebida+"&action=getNewMessages", function( result ) {
        var resultMensagem = result;
        //Pega Numero PipE Drive
        var mensagemNome = "";
        $.getJSON( "http://187.63.83.168/whatsapi/Web/webservice.php?phone="+mensagemDe+"&token="+TOKEN+"&action=getNamePipe", function( data ) {
            if(data){
                mensagemNome = data.name;
            }
        }).done(function( data ) {
            $.each(resultMensagem, function( key, val ) {
                var resultPrint = '';
                dataUltimaRecebida = key;
                if(val.mensagem.match(/<b>/)){
                    resultPrint += '<div class="bubble alt green">' +
                        '     <div class="txt">' +
                        '     <p class="message">' + val.mensagem + '</p><br/>' +
                        '     <span class="timestamp">'+key+'</span>' +
                        '     </div>' +
                        '     <div class="bubble-arrow alt"></div>' +
                        '     </div>';
                }else{
                    resultPrint += '<div class="bubble white">' +
                        '     <div class="txt">' +
                        '     <p class="name">'+mensagemNome+" ("+mensagemDe+')</p>'+
                        '     <p class="message">' + val.mensagem + '</p><br/>' +
                        '     <span class="timestamp">'+key+'</span>' +
                        '     </div>' +
                        '     <div class="bubble-arrow"></div>' +
                        '     </div>';
                }
                $('#mensagensEnviadasRecebidas').html($('#mensagensEnviadasRecebidas').html() + resultPrint);
            });
            $("#mensagensEnviadasRecebidas").animate({ scrollTop: 999999 }, 'slow');
        });
    });
}

setInterval(function() {
    recebeMensagemNew();
}, 15000);


function saveActivity(){
    var idPerson;
    var telefone;
    var dataF;
    $.getJSON( "http://187.63.83.168/whatsapi/Web/webservice.php?phone="+mensagemDe+"&token="+TOKEN+"&action=getNamePipe", function( data ) {

        if(!data){
            return true;
        }
        idPerson = data.id;
        telefone = data.phone;
        //var date2 = new Date().toISOString().substr(0, 19).replace('T', ' ');
        dataF = new Date().toISOString().substr(0, 10);
        //url = "http://187.63.83.168/whatsapi/Web/viewMessages.php?phone="+telefone+"&data="+dataF;
    }).done(function( data ) {
        $.getJSON( "http://187.63.83.168/whatsapi/Web/webservice.php?token="+TOKEN+"&action=saveActivity&idPerson="+idPerson+"&telefone="+telefone+"&data="+dataF, function( data ) {
           console.log(data);
        }).done(function( data ) {
            console.log(data);
        });
    });
}


saveActivity();



$(function() {
    // Initializes and creates emoji set from sprite sheet
    window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: '../vendor/emogi/img/',
        popupButtonClasses: 'fa fa-smile-o'
    });
    // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
    // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
    // It can be called as many times as necessary; previously converted input fields will not be converted again
    window.emojiPicker.discover();
});