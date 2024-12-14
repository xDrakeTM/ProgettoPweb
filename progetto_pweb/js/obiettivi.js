document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('compilaObiettiviForm');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            salvaProgressi(this);
        });
    }
});

function salvaProgressi(form) {
    const x = new XMLHttpRequest();
    x.open("POST", "../php/salvaProgressi.php", true);
    x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();

    x.onload = function() {
        if (x.status === 200) {
            const response = JSON.parse(x.responseText);
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

    x.send(params);
}