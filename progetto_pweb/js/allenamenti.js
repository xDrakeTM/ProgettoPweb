// Aggiunge un listener per l'evento DOMContentLoaded per eseguire il codice quando il documento è completamente caricato
document.addEventListener('DOMContentLoaded', function() {
    // Crea una nuova richiesta XMLHttpRequest
    const x = new XMLHttpRequest();
    // Configura la richiesta per ottenere i dati degli allenamenti
    x.open('GET', '../php/getAllenamenti.php', true);

    // Definisce la funzione da eseguire quando la richiesta è completata
    x.onload = function() {
        // Verifica se la richiesta è stata completata con successo
        if (x.status === 200) {
            try {
                // Analizza la risposta JSON
                const appuntamenti = JSON.parse(x.responseText);

                // Verifica se la risposta è un array
                if (Array.isArray(appuntamenti)) {
                    // Recupera l'elemento della tabella dove verranno inseriti gli appuntamenti
                    const appuntamentiTable = document.getElementById("allenamentiTableBody");
                    
                    // Itera su ogni appuntamento nell'array
                    appuntamenti.forEach(function(appuntamento) {
                        // Crea una nuova riga per la tabella
                        const row = document.createElement("tr");
                        
                        // Crea e popola la cella per la data
                        const dataCell = document.createElement("td");
                        dataCell.textContent = appuntamento.data;
                        
                        // Crea e popola la cella per l'ora di inizio
                        const oraInizioCell = document.createElement("td");
                        oraInizioCell.textContent = appuntamento.ora_inizio;
                        
                        // Crea e popola la cella per l'ora di fine
                        const oraFineCell = document.createElement("td");
                        oraFineCell.textContent = appuntamento.ora_fine;
                        
                        // Crea e popola la cella per il nome del trainer
                        const trainerCell = document.createElement("td");
                        trainerCell.textContent = appuntamento.nome + " " + appuntamento.cognome;

                        // Crea e popola la cella per lo stato dell'appuntamento
                        const statusCell = document.createElement("td");
                        statusCell.textContent = appuntamento.stato;
                        statusCell.style.fontWeight = "bold";

                        // Imposta l'ID della cella dello stato in base allo stato dell'appuntamento
                        switch(appuntamento.stato) {
                            case 'prenotato':
                                statusCell.id = "giallo";
                                break;
                            
                            case 'confermato':
                                statusCell.id = "verde";
                                break;
                            
                            case 'cancellato':
                                statusCell.id = "rosso";
                                break;
                        }

                        // Aggiunge le celle alla riga
                        row.appendChild(dataCell);
                        row.appendChild(oraInizioCell);
                        row.appendChild(oraFineCell);
                        row.appendChild(trainerCell);
                        row.appendChild(statusCell);

                        // Aggiunge la riga alla tabella
                        appuntamentiTable.appendChild(row);
                    });
                } else {
                    console.error("La risposta non è un array:", appuntamenti);
                }
            } catch (error) {
                console.error("Errore nel parsing JSON:", error);
            }
        } else {
            console.error("Errore nella richiesta:", x.status);
        }
    };

    // Invia la richiesta
    x.send();
});