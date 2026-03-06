function initializeCharts() {
    const hourlyCtx = document.getElementById('hourlyChart')?.getContext('2d');
    if (hourlyCtx) {
        new Chart(hourlyCtx, {
            type: 'line',
            data: {
                labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
                datasets: [{
                    label: 'Builds',
                    data: [45, 32, 28, 25, 22, 30, 55, 89, 145, 234, 278, 298, 312, 334, 356, 378, 412, 445, 467, 432, 398, 356, 289, 178],
                    borderColor: '#0066cc',
                    backgroundColor: 'rgba(0, 102, 204, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    const successCtx = document.getElementById('successChart')?.getContext('2d');
    if (successCtx) {
        new Chart(successCtx, {
            type: 'doughnut',
            data: {
                labels: ['Success', 'Failed'],
                datasets: [{
                    data: [98.7, 1.3],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                cutout: '70%'
            }
        });
    }

    const latencyCtx = document.getElementById('latencyChart')?.getContext('2d');
    if (latencyCtx) {
        new Chart(latencyCtx, {
            type: 'bar',
            data: {
                labels: ['Web2APK', 'AppYet', 'MIT', 'Custom'],
                datasets: [{
                    label: 'Latency (ms)',
                    data: [87, 112, 345, 56],
                    backgroundColor: ['#0066cc', '#4da6ff', '#ffaa00', '#10b981'],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
}

function exportHistory() {
    const history = storage.get('buildHistory', []);
    const dataStr = JSON.stringify(history, null, 2);
    const blob = new Blob([dataStr], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `build-history-${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    window.URL.revokeObjectURL(url);
    showToast('History exported successfully', 'success');
}

function clearHistory() {
    if (confirm('Are you sure you want to clear all build history?')) {
        storage.remove('buildHistory');
        showToast('History cleared', 'success');
        setTimeout(() => location.reload(), 1000);
    }
}

function filterHistory() {
    const searchTerm = document.getElementById('searchHistory')?.value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus')?.value;
    const dateFilter = document.getElementById('filterDate')?.value;
    const rows = document.querySelectorAll('#historyTableBody tr');

    rows.forEach(row => {
        if (row.cells.length < 2) return;
        const name = row.cells[1]?.textContent.toLowerCase() || '';
        const status = row.cells[5]?.textContent.toLowerCase() || '';
        let show = true;

        if (searchTerm && !name.includes(searchTerm)) show = false;
        if (statusFilter !== 'all' && !status.includes(statusFilter)) show = false;

        row.style.display = show ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initializeCharts();
    
    const searchInput = document.getElementById('searchHistory');
    if (searchInput) {
        searchInput.addEventListener('input', filterHistory);
    }
    
    const statusFilter = document.getElementById('filterStatus');
    if (statusFilter) {
        statusFilter.addEventListener('change', filterHistory);
    }
    
    const dateFilter = document.getElementById('filterDate');
    if (dateFilter) {
        dateFilter.addEventListener('change', filterHistory);
    }
});
