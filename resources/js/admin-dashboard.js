
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const data = window.eventHubDashboardChartData;

    if (!data) {
        return;
    }

    // 1. Biểu đồ đăng ký theo tháng
    const trendCanvas = document.getElementById(
        'registrationTrendChart'
    );

    if (trendCanvas) {
        new Chart(trendCanvas, {
            type: 'bar',

            data: {
                labels: data.months,

                datasets: [
                    {
                        label: 'Đăng ký còn hiệu lực',
                        data: data.registrationCounts,
                        backgroundColor: 'rgba(59, 130, 246, 0.75)',
                        borderColor: 'rgb(37, 99, 235)',
                        borderWidth: 1,
                        borderRadius: 8,
                        maxBarThickness: 40,
                    }
                ],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: 'index',
                },

                plugins: {
                    legend: {
                        display: false,
                    },

                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                    },
                },

                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#64748b',
                        },
                    },

                    y: {
                        beginAtZero: true,

                        ticks: {
                            precision: 0,
                            stepSize: 1,
                            color: '#64748b',
                        },

                        grid: {
                            color: 'rgba(148,163,184,0.15)',
                        },
                    },
                },
            },
        });
    }

    // 2. Biểu đồ trạng thái sự kiện
    const statusCanvas = document.getElementById(
        'eventStatusChart'
    );

    if (
        statusCanvas &&
        data.eventStatusCounts.some(value => value > 0)
    ) {
        new Chart(statusCanvas, {
            type: 'doughnut',

            data: {
                labels: data.eventStatusLabels,

                datasets: [
                    {
                        data: data.eventStatusCounts,

                        backgroundColor: [
                            '#10b981', // Đã công bố
                            '#f59e0b', // Bản nháp
                            '#f43f5e', // Đã hủy
                        ],

                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 8,
                    }
                ],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                cutout: '68%',

                plugins: {
                    legend: {
                        position: 'bottom',

                        labels: {
                            usePointStyle: true,
                            padding: 16,
                        },
                    },
                },
            },
        });
    }
});
