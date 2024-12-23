document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('compilaObiettiviForm');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
        });
    }
});

function salvaProgresso(obiettivoId) {
    const progresso1Input = document.getElementById('progresso1-' + obiettivoId);
    const progresso2Input = document.getElementById('progresso2-' + obiettivoId);
    const progresso3Input = document.getElementById('progresso3-' + obiettivoId);

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

    const x = new XMLHttpRequest();
    x.open("POST", "../php/salvaProgresso.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    x.onload = function() {
        if (x.status === 200) {
            const response = JSON.parse(x.responseText);
            if (response.success) {
                alert("Progresso salvato con successo!");
                const obiettivoDiv = document.getElementById('obiettivo-' + obiettivoId);
                const hr = obiettivoDiv.previousElementSibling;
                if (hr && hr.tagName === 'HR') {
                    hr.remove();
                }
                obiettivoDiv.remove();
            } 
            else {
                alert("Errore: " + response.message);
            }
        } 
        else {
            alert("Errore nella richiesta: " + x.status);
        }
    };

    x.send(params);
}