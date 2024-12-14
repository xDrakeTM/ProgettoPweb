document.addEventListener('DOMContentLoaded', function() {
    // Grafico per la percentuale di obiettivi completati
    const percentualeCompletatiCtx = document.getElementById('percentualeCompletatiChart').getContext('2d');
    new Chart(percentualeCompletatiCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completati', 'Non Completati'],
            datasets: [{
                data: [percentualeCompletati, 100 - percentualeCompletati],
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Percentuale di Obiettivi Completati',
                    font: {
                        size: 24
                    }
                }
            }
        }
    });

    // Grafico per la media di esercizi quantitativi eseguiti ad ogni allenamento
    const mediaEserciziQuantitativiCtx = document.getElementById('mediaEserciziQuantitativiChart').getContext('2d');
    new Chart(mediaEserciziQuantitativiCtx, {
        type: 'bar',
        data: {
            labels: ['Media Esercizi Quantitativi'],
            datasets: [{
                label: 'Media Esercizi Quantitativi per Allenamento',
                data: [mediaEserciziQuantitativi],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Media di Esercizi Quantitativi Eseguiti ad Ogni Allenamento',
                    font: {
                        size: 24
                    }
                }
            }
        }
    });

    // Grafico per il progresso negli allenamenti
    const progressoAllenamentiCtx = document.getElementById('progressoAllenamentiChart').getContext('2d');
    const quantitativiLabels = quantitativiData.map(item => item.obiettivo);
    const progresso1Data = quantitativiData.map(item => item.progresso1);
    const progresso2Data = quantitativiData.map(item => item.progresso2);
    const progresso3Data = quantitativiData.map(item => item.progresso3);

    new Chart(progressoAllenamentiCtx, {
        type: 'line',
        data: {
            labels: quantitativiLabels,
            datasets: [
                {
                    label: 'Ripetizioni',
                    data: progresso1Data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                },
                {
                    label: 'Serie',
                    data: progresso2Data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    fill: false
                },
                {
                    label: 'Peso',
                    data: progresso3Data,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1,
                    fill: false
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Progresso negli Allenamenti',
                    font: {
                        size: 24
                    }
                }
            }
        }
    });

    // Grafico per lo stato degli obiettivi continuativi
    const statoContinuativiCtx = document.getElementById('statoContinuativiChart').getContext('2d');
    const continuativiLabels = continuativiData.map(item => item.obiettivo);
    const progresso2ContinuativiData = continuativiData.map(item => item.progresso2);

    new Chart(statoContinuativiCtx, {
        type: 'bar',
        data: {
            labels: continuativiLabels,
            datasets: [
                {
                    label: 'Progresso',
                    data: progresso2ContinuativiData,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Stato degli Obiettivi Continuativi',
                    font: {
                        size: 24
                    }
                }
            }
        }
    });
});