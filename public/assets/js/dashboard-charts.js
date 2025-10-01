// Dashboard Charts Handler
class DashboardCharts {
    constructor() {
        this.charts = {};
        this.init();
    }

    init() {
        this.destroyExistingCharts();
        this.createBarChart();
        this.createLineChart();
    }

    destroyExistingCharts() {
        // Destroy bar chart if exists
        const barChartElement = document.getElementById('chart-bars');
        if (barChartElement) {
            const barChart = Chart.getChart(barChartElement);
            if (barChart) {
                barChart.destroy();
            }
        }

        // Destroy line chart if exists
        const lineChartElement = document.getElementById('chart-line');
        if (lineChartElement) {
            const lineChart = Chart.getChart(lineChartElement);
            if (lineChart) {
                lineChart.destroy();
            }
        }
    }

    createBarChart() {
        const ctx = document.getElementById("chart-bars");
        if (!ctx) return;

        // Get data from data attributes
        const months = JSON.parse(ctx.getAttribute('data-months') || '[]');
        const barangMasukData = JSON.parse(ctx.getAttribute('data-barang-masuk') || '[]');
        const barangKeluarData = JSON.parse(ctx.getAttribute('data-barang-keluar') || '[]');

        this.charts.bar = new Chart(ctx, {
            type: "bar",
            data: {
                labels: months,
                datasets: [
                    {
                        label: "Barang Masuk",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "#fff",
                        data: barangMasukData,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Barang Keluar",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "rgba(255, 255, 255, 0.5)",
                        data: barangKeluarData,
                        maxBarThickness: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: Math.max(...barangMasukData, ...barangKeluarData) + 10,
                            beginAtZero: true,
                            padding: 15,
                            font: {
                                size: 14,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                            color: "#fff",
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                },
            },
        });
    }

    createLineChart() {
        const ctx2 = document.getElementById("chart-line");
        if (!ctx2) return;

        // Get data from data attributes
        const months = JSON.parse(ctx2.getAttribute('data-months') || '[]');
        const barangMasukData = JSON.parse(ctx2.getAttribute('data-barang-masuk') || '[]');
        const barangKeluarData = JSON.parse(ctx2.getAttribute('data-barang-keluar') || '[]');

        // Create gradients
        const gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, "rgba(203,12,159,0.2)");
        gradientStroke1.addColorStop(0.2, "rgba(72,72,176,0.0)");
        gradientStroke1.addColorStop(0, "rgba(203,12,159,0)");

        const gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke2.addColorStop(1, "rgba(20,23,39,0.2)");
        gradientStroke2.addColorStop(0.2, "rgba(72,72,176,0.0)");
        gradientStroke2.addColorStop(0, "rgba(20,23,39,0)");

        this.charts.line = new Chart(ctx2, {
            type: "line",
            data: {
                labels: months,
                datasets: [
                    {
                        label: "Barang Masuk",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: barangMasukData,
                        maxBarThickness: 6,
                    },
                    {
                        label: "Barang Keluar",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: barangKeluarData,
                        maxBarThickness: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#b2b9bf",
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#b2b9bf",
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                },
            },
        });
    }

    // Method to destroy all charts
    destroy() {
        Object.values(this.charts).forEach(chart => {
            if (chart) {
                chart.destroy();
            }
        });
        this.charts = {};
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.dashboardCharts = new DashboardCharts();
});

// Destroy charts when navigating away (for SPA)
window.addEventListener('beforeunload', function() {
    if (window.dashboardCharts) {
        window.dashboardCharts.destroy();
    }
});
