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
        });
    }
});

// Funzione per salvare il progresso di un obiettivo
function salvaProgresso(obiettivoId) {
    // Recupera gli input dei progressi per l'obiettivo specificato
    const progresso1Input = document.getElementById('progresso1-' + obiettivoId);
    const progresso2Input = document.getElementById('progresso2-' + obiettivoId);
    const progresso3Input = document.getElementById('progresso3-' + obiettivoId);

    // Inizializza i parametri della richiesta
    let params = "id=" + encodeURIComponent(obiettivoId);
    if (progresso1Input) {
        params += "&progresso1=" + encodeURIComponent(progresso1Input.value);
    }

    if (progresso2Input) {
        params += "&progresso2=" + encodeURIComponent(progresso2Input.value);
    }

    if (progresso3Input) {
        params += "&progresso3=" + encodeURIComponent(progresso3Input.value);
    }

    // Crea una nuova richiesta XMLHttpRequest
    const x = new XMLHttpRequest();
    // Configura la richiesta per inviare i dati al server
    x.open("POST", "../php/salvaProgresso.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Definisce la funzione da eseguire quando la richiesta è completata
    x.onload = function() {
        // Verifica se la richiesta è stata completata con successo
        if (x.status === 200) {
            try {
                // Analizza la risposta JSON
                const response = JSON.parse(x.responseText);
                // Verifica se il salvataggio è stato effettuato con successo
                if (response.success) {
                    alert("Progresso salvato con successo!");
                    // Recupera l'elemento dell'obiettivo e l'elemento <hr> precedente
                    const obiettivoDiv = document.getElementById('obiettivo-' + obiettivoId);
                    const hr = obiettivoDiv.previousElementSibling;
                    // Rimuove l'elemento <hr> se esiste
                    if (hr && hr.tagName === 'HR') {
                        hr.remove();
                    }
                    obiettivoDiv.remove();
                } else {
                    alert("Errore: " + response.message);
                }
            } catch (e) {
                console.error("Errore nel parsing della risposta JSON:", e);
                console.error("Risposta del server:", x.responseText);
            }
        } else {
            alert("Errore nella richiesta: " + x.status);
        }
    };

    // Invia la richiesta con i parametri
    x.send(params);
}