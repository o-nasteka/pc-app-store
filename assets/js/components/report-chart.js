document.addEventListener('DOMContentLoaded', function() {
    var chartCanvas = document.getElementById('activityChart');
    if (chartCanvas && window.activityChartData) {
        const ctx = chartCanvas.getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: window.activityChartData.data,
            options: window.activityChartData.options
        });
    }
});
