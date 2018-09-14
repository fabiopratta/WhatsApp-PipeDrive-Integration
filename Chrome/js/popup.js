
var DEBUG = true;


function salvarPropriedades()
{
    var tokenPipeDrive = document.getElementById('inputApiPipeDriver').value;
    var celularNumero = document.getElementById('inputNumeroCelular').value;

    if(tokenPipeDrive == ""){
        alert("Obrigatorio digitar um token da API do PIPE DRIVE");
    }
    if(celularNumero == ""){
        alert("Obrigatorio digitar um NUMERO DE CELULAR valido!");
    }


    localStorage.tokenpipedrive = tokenPipeDrive;
    localStorage.celularnumero = celularNumero;

    sessionStorage.tokenpipedrive = tokenPipeDrive;
    sessionStorage.celularnumero = celularNumero;

    chrome.storage.local.set({'tokenpipedrive': tokenPipeDrive});
    chrome.storage.local.set({'celularnumero': celularNumero});

    if(DEBUG) {
        console.log("Salvar as propriedades :: Token " + tokenPipeDrive + " :: Celular " + celularNumero);
    }
}

function restore_options() {

    if(localStorage.celularnumero)
    {
        document.getElementById('inputNumeroCelular').value = localStorage.celularnumero;
    }

    if(localStorage.tokenpipedrive)
    {
        document.getElementById('inputApiPipeDriver').value = localStorage.tokenpipedrive;
    }
}

document.addEventListener('DOMContentLoaded', restore_options);
document.getElementById('salvarPropriedades').addEventListener('click',salvarPropriedades);