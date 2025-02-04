// Funzione per validare la registrazione di un utente
function validaRegistrazioneUtente(event) {
    // Previene l'invio predefinito del modulo
    event.preventDefault();

    // Crea una nuova richiesta XMLHttpRequest
    const x = new XMLHttpRequest();
    // Configura la richiesta per inviare i dati al server
    x.open("POST", "../php/validazioneRegistrazioneUtente.php", true);

    // Crea un oggetto FormData e aggiunge i dati del modulo
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

    // Invia la richiesta con i dati del modulo
    x.send(formData);

    // Definisce la funzione da eseguire quando la richiesta è completata
    x.onload = function() {
        // Analizza la risposta JSON
        const response = JSON.parse(x.responseText);
        // Verifica se la registrazione è stata effettuata con successo
        if (response.success) {
            // Reindirizza l'utente alla pagina di conferma della registrazione
            window.location.href = "../html/registrazioneEffettuataUtente.html";
        } 
        else {
            // Mostra un messaggio di errore
            document.getElementById("stato").textContent = response.message;
        }
    }
}

// Funzione per validare la registrazione di un personal trainer
function validaRegistrazionePT(event) {
    // Previene l'invio predefinito del modulo
    event.preventDefault();

    // Crea una nuova richiesta XMLHttpRequest
    const x = new XMLHttpRequest();
    // Configura la richiesta per inviare i dati al server
    x.open("POST", "../php/validazioneRegistrazionePT.php", true);

    // Crea un oggetto FormData e aggiunge i dati del modulo
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
    formData.append("specializzazione", document.getElementById("specializzazione").value);
    formData.append("esperienza", document.getElementById("esperienza").value);
    formData.append("certificato_medico", document.getElementById("certificato_medico").files[0]);

    // Invia la richiesta con i dati del modulo
    x.send(formData);

    // Definisce la funzione da eseguire quando la richiesta è completata
    x.onload = function() {
        // Analizza la risposta JSON
        const response = JSON.parse(x.responseText);
        // Verifica se la registrazione è stata effettuata con successo
        if (response.success) {
            // Reindirizza l'utente alla pagina di conferma della registrazione
            window.location.href = "../html/registrazioneEffettuataPT.html";
        } 
        else {
            // Mostra un messaggio di errore
            document.getElementById("stato").textContent = response.message;
        }
    }
}