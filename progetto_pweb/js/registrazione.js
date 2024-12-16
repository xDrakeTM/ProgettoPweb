function validaRegistrazioneUtente(event) {
    event.preventDefault();

    const x = new XMLHttpRequest();
    x.open("POST", "../php/validazioneRegistrazioneUtente.php", true);

    const formData = new FormData();
    formData.append("nome", document.getElementById("nome").value);
    formData.append("cognome", document.getElementById("cognome").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("data_nascita", document.getElementById("data_nascita").value);
    formData.append("password", document.getElementById("password").value);
    formData.append("conf_password", document.getElementById("conf_password").value);
    formData.append("risposta1", document.getElementById("risposta1").value);
    formData.append("risposta2", document.getElementById("risposta2").value);
    formData.append("genere", document.getElementById("genere").value);
    formData.append("altezza", document.getElementById("altezza").value);
    formData.append("peso", document.getElementById("peso").value);
    formData.append("data_emissione_certificato", document.getElementById("data_emissione_certificato").value);
    formData.append("informazioni_mediche", document.getElementById("informazioni_mediche").value);
    formData.append("note", document.getElementById("note").value);
    formData.append("certificato_medico", document.getElementById("certificato_medico").files[0]);

    x.send(formData);

    x.onload = function() {
        if (JSON.parse(x.responseText).success) {
            window.location.href = "../html/registrazioneEffettuataUtente.html";
        } 
        else {
            document.getElementById("stato").textContent = JSON.parse(x.responseText).message;
        }
    }
}

function validaRegistrazionePT(event) {
    event.preventDefault();

    const x = new XMLHttpRequest();
    x.open("POST", "../php/validazioneRegistrazionePT.php", true);

    const formData = new FormData();
    formData.append("nome", document.getElementById("nome").value);
    formData.append("cognome", document.getElementById("cognome").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("data_nascita", document.getElementById("data_nascita").value);
    formData.append("genere", document.getElementById("genere").value);
    formData.append("cellulare", document.getElementById("cellulare").value);
    formData.append("password", document.getElementById("password").value);
    formData.append("conf_password", document.getElementById("conf_password").value);
    formData.append("risposta1", document.getElementById("risposta1").value);
    formData.append("risposta2", document.getElementById("risposta2").value);
    formData.append("curriculum",  document.getElementById("curriculum").files[0]);

    x.onload = function() {
        if (JSON.parse(x.responseText).success) {
            window.location.href = "../html/registrazioneEffettuataPT.html";
        }
        else {
            document.getElementById("stato").textContent = JSON.parse(x.responseText).message;
        }
    };

    x.send(formData);
}