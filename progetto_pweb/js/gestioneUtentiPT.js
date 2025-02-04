// Aggiunge un listener per l'evento DOMContentLoaded per eseguire il codice quando il documento è completamente caricato
document.addEventListener('DOMContentLoaded', function() {
    // Recupera l'input del filtro e il corpo della tabella
    const filterInput = document.getElementById('filter-input');
    const ptTableBody = document.getElementById('TableBody');

    // Aggiunge un listener per l'evento 'keyup' dell'input del filtro
    filterInput.addEventListener('keyup', function() {
        // Recupera il valore del filtro e lo converte in minuscolo
        const filterValue = filterInput.value.toLowerCase();
        // Recupera tutte le righe della tabella
        const rows = ptTableBody.getElementsByTagName('tr');

        // Itera su ogni riga della tabella
        Array.from(rows).forEach(row => {
            // Recupera tutte le celle della riga
            const cells = row.getElementsByTagName('td');
            // Verifica se almeno una cella contiene il valore del filtro
            const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filterValue));
            // Mostra o nasconde la riga in base al risultato della verifica
            row.style.display = match ? '' : 'none';
        });
    });
});

// Funzione per eliminare un account
function eliminaAccount(userId) {
    // Chiede conferma all'utente prima di eliminare l'account
    if (confirm("Sei sicuro di voler eliminare questo account?")) {
        // Crea una nuova richiesta XMLHttpRequest
        const x = new XMLHttpRequest();
        // Configura la richiesta per inviare i dati al server
        x.open("POST", "../php/validazioneEliminazioneAccount.php", true);
        x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        // Invia la richiesta con i dati dell'utente
        x.send("user_id=" + encodeURIComponent(userId) + "&user_tipo=admin");

        // Definisce la funzione da eseguire quando la richiesta è completata
        x.onload = function() {
            // Analizza la risposta JSON
            const response = JSON.parse(x.responseText);
            // Verifica se l'eliminazione è stata effettuata con successo
            if (response.success) {
                alert("Account eliminato con successo!");
                // Ricarica la pagina
                location.reload();
            } 
            else {
                alert("Errore: " + response.message);
            }
        };
    }
}