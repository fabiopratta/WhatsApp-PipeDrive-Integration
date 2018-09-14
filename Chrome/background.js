
var context = "selection";


var id = chrome.contextMenus.create({"title": "WhatsAPP para o numero '%s' ", "contexts":[context], "id": "context" + context});


chrome.contextMenus.onClicked.addListener(onClickHandler);


function onClickHandler(info, tab) {
    var sText = info.selectionText;
    sText=sText.replace(/[^0-9]/g,'');
    var numero = "55"+sText;
    chrome.windows.create({
        'url': chrome.extension.getURL("viewmessages.html?phone=" + numero),
        'type': 'panel',
        height: 630, width: 440
    }, function (window) {
        //alert(JSON.stringify(window));
        //janelas.push({id: window.id, celular: val.de_phone});
    });
};