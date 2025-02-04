// Funzione per validare il login
function validaLogin(event) {
    // Previene l'invio predefinito del modulo
    event.preventDefault();
    // Recupera i valori degli input email e password
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    // Recupera l'elemento per mostrare i messaggi di avviso
    const warning = document.getElementById('warning');

    // Invia una richiesta POST al server per validare il login
    fetch('validazioneLogin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        // Codifica i dati del modulo come URL-encoded
        body: 'email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password)
    })
    .then(response => response.json()) // Converte la risposta in formato JSON
    .then(data => {
        // Verifica se il login è stato effettuato con successo
        if (data.success) {
            // Reindirizza l'utente alla pagina appropriata in base al tipo di utente
            if (data.user_tipo === 'utente') {
                window.location.href = 'homeUtente.php';
            } 
            else if (data.user_tipo === 'personal_trainer') {
                window.location.href = 'homePT.php';
            } 
            else if (data.user_tipo === 'admin') {
                window.location.href = 'homeAdmin.php';
            }
        } 
        else {
            // Mostra un messaggio di errore se il login non è riuscito
            warning.textContent = data.message;
        }
    })
    .catch(() => {
        // Mostra un messaggio di errore in caso di problemi con la richiesta
        warning.textContent = 'Errore durante il login. Riprova.';
    });
}