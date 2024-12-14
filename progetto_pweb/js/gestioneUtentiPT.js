document.addEventListener('DOMContentLoaded', function() {
    const filterInput = document.getElementById('filtro-input');
    const ptTableBody = document.getElementById('TableBody');

    filterInput.addEventListener('keyup', function() {
        const filterValue = filterInput.value.toLowerCase();
        const rows = ptTableBody.getElementsByTagName('tr');

        Array.from(rows).forEach(function(row) {
            const cells = row.getElementsByTagName('td');
            let match = false;

            Array.from(cells).forEach(function(cell) {
                if (cell.textContent.toLowerCase().includes(filterValue)) {
                    match = true;
                }
            });

            if (match) {
                row.style.display = '';
            } 
            else {
                row.style.display = 'none';
            }
        });
    });
});

function eliminaAccount(userId) {
    if (confirm("Sei sicuro di voler eliminare questo account?")) {
        const x = new XMLHttpRequest();
        x.open("POST", "../php/validazioneEliminazioneAccount.php", true);
        x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        x.send("user_id=" + encodeURIComponent(userId) + "&user_tipo=admin");

        x.onload = function() {
            const response = JSON.parse(x.responseText);
            if (response.success) {
                alert("Account eliminato con successo!");
                location.reload();
            } 
            else {
                alert("Errore: " + response.message);
            }
        };
    }
}