document.addEventListener('DOMContentLoaded', function () {
    // Weekly Project Revenue
    let optionsMonthlyProjectRevenue = {
        annotations: {
            position: 'back'
        },
        dataLabels: {
            enabled: false
        },
        chart: {
            type: 'bar',
            height: 300
        },
        fill: {
            opacity: 1
        },
        plotOptions: {},
        series: [{
            name: '₱',
            data: revenue
        }],
        colors: '#435ebe',
        xaxis: {
            categories: weekRange,
        },
    }

    let chartMonthlyProjectRevenue = new ApexCharts(document.querySelector("#monthly-project-revenue"),
        optionsMonthlyProjectRevenue);

    chartMonthlyProjectRevenue.render();
});

