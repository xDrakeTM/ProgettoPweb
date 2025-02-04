// Funzione per confermare un appuntamento
function confermaAppuntamento(id) {
    // Chiede conferma all'utente prima di confermare l'appuntamento
    if (confirm("Confermare questo appuntamento?")) {
        // Aggiorna lo stato dell'appuntamento a 'confermato'
        aggiornaStatoAppuntamento(id, 'confermato');
    }
}

// Funzione per rifiutare un appuntamento
function rifiutaAppuntamento(id) {
    // Chiede conferma all'utente prima di rifiutare l'appuntamento
    if (confirm("Rifiutare questo appuntamento?")) {
        // Aggiorna lo stato dell'appuntamento a 'cancellato'
        aggiornaStatoAppuntamento(id, 'cancellato');
    }
}

// Funzione per aggiornare lo stato di un appuntamento
function aggiornaStatoAppuntamento(id, stato) {
    // Crea una nuova richiesta XMLHttpRequest
    const x = new XMLHttpRequest();
    // Configura la richiesta per inviare i dati al server
    x.open("POST", "../php/vediAllenamenti.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Definisce la funzione da eseguire quando la richiesta è completata
    x.onload = function() {
        // Verifica se la richiesta è stata completata con successo
        if (this.status === 200) {
            // Analizza la risposta JSON
            const response = JSON.parse(this.responseText);
            // Verifica se l'aggiornamento è stato effettuato con successo
            if (response.success) {
                // Rimuove l'elemento dell'appuntamento dalla pagina
                document.getElementById('appuntamento-' + id).remove();
                // Mostra un messaggio di conferma
                alert("Appuntamento " + stato + " con successo.");
            } 
            else {
                // Mostra un messaggio di errore
                alert("Errore: " + response.message);
            }
        }
    };

    // Invia la richiesta con i dati dell'appuntamento
    x.send("id=" + encodeURIComponent(id) + "&stato=" + encodeURIComponent(stato));
}