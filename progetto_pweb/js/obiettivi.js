// Aggiunge un listener per l'evento DOMContentLoaded per eseguire il codice quando il documento è completamente caricato
document.addEventListener('DOMContentLoaded', function() {
    // Recupera il modulo con l'ID 'compilaObiettiviForm'
    const form = document.getElementById('compilaObiettiviForm');
    // Verifica se il modulo esiste
    if (form) {
        // Aggiunge un listener per l'evento 'submit' del modulo
        form.addEventListener('submit', function(event) {
            // Previene l'invio predefinito del modulo
            event.preventDefault();
            // Chiama la funzione salvaProgressi passando il modulo come argomento
            salvaProgressi(this);
        });
    }
});

// Funzione per salvare i progressi degli obiettivi
function salvaProgressi(form) {
    // Crea una nuova richiesta XMLHttpRequest
    const x = new XMLHttpRequest();
    // Configura la richiesta per inviare i dati al server
    x.open("POST", "../php/salvaProgressi.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Recupera i dati del modulo e li converte in formato URL-encoded
    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();

    // Definisce la funzione da eseguire quando la richiesta è completata
    x.onload = function() {
        // Verifica se la richiesta è stata completata con successo
        if (x.status === 200) {
            // Analizza la risposta JSON
            const response = JSON.parse(x.responseText);
            // Verifica se il salvataggio è stato effettuato con successo
            if (response.success) {
                alert("Progressi salvati con successo!");
            } 
            else {
                alert("Errore: " + response.message);
            }
        } 
        else {
            alert("Errore nella richiesta: " + x.status);
        }
    };

    // Invia la richiesta con i parametri
    x.send(params);
}