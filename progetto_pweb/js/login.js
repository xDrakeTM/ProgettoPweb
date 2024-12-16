function validaLogin(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const warning = document.getElementById('warning');

    fetch('validazioneLogin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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
            warning.textContent = data.message;
        }
    })
    .catch(() => {
        warning.textContent = 'Errore durante il login. Riprova.';
    });
}