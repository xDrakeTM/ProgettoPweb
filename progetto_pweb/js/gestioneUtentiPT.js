document.addEventListener('DOMContentLoaded', function() {
    const filterInput = document.getElementById('filtro-input');
    const ptTableBody = document.getElementById('TableBody');

    filterInput.addEventListener('keyup', function() {
        const filterValue = filterInput.value.toLowerCase();
        const rows = ptTableBody.getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const cells = row.getElementsByTagName('td');
            const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filterValue));
            row.style.display = match ? '' : 'none';
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