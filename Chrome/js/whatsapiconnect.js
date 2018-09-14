/*
//ROdando o socket
var connection, wsUrir;

wsUrir = "ws://192.168.10.200:8090";

var websocket = new WebSocket(wsUrir);
var phoneNumber = localStorage.celularnumero;

websocket.onopen = function(event) {
    var data = {
        "celular": localStorage.celularnumero,
        "mensagem": "Obrigado pelo contato"
    };
    websocket.send(JSON.stringify(data));
    showMessage("Connection is established for " +  phoneNumber);
}

websocket.onmessage = function(event) {
    //var Data = JSON.parse(event.data);
    showMessage(event.data);
};

websocket.onerror = function(event){
    showMessage("Problem due to some Error");
};
websocket.onclose = function(event){
    showMessage("Connection Closed");
};

function sendMessagem(message)
{
    websocket.send(message);
}

function showMessage(message){
    console.log(message);
}
*/

var janelas = [];
function getMessagesNoRead()
{
    var celular = localStorage.celularnumero;
    console.log("getMessagesNoRead()");
    $.getJSON("http://187.63.83.168/whatsapi/Web/webservice.php?celular="+celular+"&action=noread&token="+localStorage.tokenpipedrive, function( data ) {
        var items = [];
        $.each(  data, function( key, val ) {

           // console.log(data);
            var notification = new Notification('NOVAS MENSAGEM '+val.de_phone, {
                icon: chrome.extension.getURL("icons/icon256.png"),
                body: val.message+""
            });

            var idWidow = findIndexByKeyValue(janelas, 'celular', val.de_phone);

            chrome.windows.getAll({}, function(window_list) {

                window_list.forEach(function(chromeWindow) {

                    chrome.windows.get(idWidow,function(){
                        var position = findIndexKeyPosition(janelas, 'id', idWidow);
                        janelas.splice(position,1);
                        idWidow = -1;
                    });

                    if (chromeWindow.id == idWidow) {
                        //Update opened window
                        chrome.windows.update(idWidow, {focused: true});
                        return;
                    }
                });

                if (idWidow == -1) {
                    chrome.windows.create({
                        url: chrome.extension.getURL("viewmessages.html?phone=" + val.de_phone),
                        type: 'panel',
                        height: 700,
                        width: 440
                    }, function (window) {
                        //alert(JSON.stringify(window));
                       janelas.push({id: window.id, celular: val.de_phone});
                    });
                }
            });
        });
    });
}

function findIndexByKeyValue(_array, key, value) {
    for (var i = 0; i < _array.length; i++) {
        if (_array[i][key] == value) {
            return _array[i]['id'];
        }
    }
    return -1;
}


function findIndexKeyPosition(_array, key, value) {
    for (var i = 0; i < _array.length; i++) {
        if (_array[i][key] == value) {
            return i;
        }
    }
    return -1;
}

setInterval(getMessagesNoRead, 5000);