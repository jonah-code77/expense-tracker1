/* dashboard.js — Spending Overview chart */
(function () {
    const ctx = document.getElementById('spendingChart');
    if (!ctx) return;

    const labels  = window._chartLabels  || ['Jan','Feb','Mar','Apr','May','Jun'];
    const income  = window._chartIncome  || [45000,62000,38000,71000,55000,80000];
    const expense = window._chartExpense || [32000,41000,29000,53000,47000,61000];

    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Income',
                    data: income,
                    backgroundColor: 'rgba(16,185,129,.12)',
                    borderColor: '#34d399',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Expenses',
                    data: expense,
                    backgroundColor: 'rgba(244,63,94,.10)',
                    borderColor: '#fb7185',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { family: "'DM Sans', sans-serif", size: 12 },
                        boxWidth: 10, boxHeight: 10,
                        color: '#8896b3',
                        padding: 16
                    }
                },
                tooltip: {
                    backgroundColor: '#1e2a40',
                    titleColor: '#f0f4ff',
                    bodyColor: '#8896b3',
                    borderColor: 'rgba(255,255,255,.08)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: c => '  ₦' + c.parsed.y.toLocaleString()
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: { family: "'DM Sans', sans-serif", size: 11 }, color: '#4f607e' }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,.04)' },
                    border: { display: false, dash: [4,4] },
                    ticks: {
                        font: { family: "'DM Sans', sans-serif", size: 11 },
                        color: '#4f607e',
                        callback: v => '₦' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
                    }
                }
            }
        }
    });

    /* Period switcher */
    const sel = document.getElementById('chartPeriod');
    if (sel) {
        sel.addEventListener('change', function () {
            /* window.location = BASE_URL + '/home?period=' + this.value; */
            console.log('Period changed to', this.value, 'months');
        });
    }
})();