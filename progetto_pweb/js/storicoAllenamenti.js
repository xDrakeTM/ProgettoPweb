// Aggiunge un listener per l'evento DOMContentLoaded per eseguire il codice quando il documento è completamente caricato
document.addEventListener('DOMContentLoaded', function() {
    // Recupera l'input del filtro e tutte le righe della tabella
    const filtraInput = document.getElementById('filter');
    const rows = document.querySelectorAll('tbody tr');

    // Aggiunge un listener per l'evento 'input' dell'input del filtro
    filtraInput.addEventListener('input', function() {
        // Filtra la tabella in base al valore dell'input
        filtraTabella(filtraInput.value.toLowerCase());
    });

    // Aggiunge un listener per il pulsante 'Filtra Oggi'
    document.getElementById('filtraOggi').addEventListener('click', filtraOggi);
    // Aggiunge un listener per il pulsante 'Filtra Tutti'
    document.getElementById('filtraTutti').addEventListener('click', filtraTutti);

    // Funzione per filtrare la tabella in base al valore dell'input
    function filtraTabella(filtraValue) {
        rows.forEach(row => {
            // Recupera tutte le celle della riga
            const cells = row.querySelectorAll('td');
            // Verifica se almeno una cella contiene il valore del filtro
            const show = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filtraValue));
            // Mostra o nasconde la riga in base al risultato della verifica
            row.style.display = show ? '' : 'none';
        });
    }

    // Funzione per filtrare la tabella per gli allenamenti di oggi
    function filtraOggi() {
        // Recupera la data di oggi in formato 'it-IT'
        const today = new Date().toLocaleDateString('it-IT');
        rows.forEach(row => {
            // Recupera la data dell'allenamento dalla riga
            const data = row.getAttribute('data-data');
            // Mostra o nasconde la riga in base alla data
            row.style.display = (data === today) ? '' : 'none';
        });
    }

    // Funzione per mostrare tutte le righe della tabella
    function filtraTutti() {
        rows.forEach(row => {
            row.style.display = '';
        });
    }
});