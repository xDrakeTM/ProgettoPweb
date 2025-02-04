// Aggiunge un listener per l'evento DOMContentLoaded per eseguire il codice quando il documento è completamente caricato
document.addEventListener('DOMContentLoaded', function() {
    // Recupera il contesto del canvas per il grafico delle registrazioni
    const ctx = document.getElementById('registrationsChart').getContext('2d');
    
    // Crea un nuovo grafico di tipo 'line' utilizzando Chart.js
    const registrationsChart = new Chart(ctx, {
        type: 'line',
        data: {
            // Etichette per l'asse X (mesi dell'anno)
            labels: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
            datasets: [{
                // Dati per le registrazioni degli utenti
                label: 'Registrazioni Utenti',
                data: registrazioniUtenti,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false
            }, {
                // Dati per le registrazioni dei personal trainer
                label: 'Registrazioni Personal Trainer',
                data: registrazioniPTs,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: false
            }]
        },
        options: {
            // Rende il grafico responsive
            responsive: true,
            scales: {
                x: {
                    // Inizia l'asse X da zero
                    beginAtZero: true
                },
                y: {
                    // Inizia l'asse Y da zero
                    beginAtZero: true
                }
            }
        }
    });
});