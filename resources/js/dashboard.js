import ApexCharts from 'apexcharts';

const transactionsDataEl = document.getElementById('stockify-chart-data');

if (transactionsDataEl) {
    const data = JSON.parse(transactionsDataEl.textContent);
    const dark = document.documentElement.classList.contains('dark');
    const labelColor = dark ? '#9CA3AF' : '#6B7280';
    const borderColor = dark ? '#374151' : '#E5E7EB';

    const chart = new ApexCharts(document.getElementById('stockify-chart'), {
        chart: {
            type: 'area',
            height: 320,
            fontFamily: 'Inter, sans-serif',
            foreColor: labelColor,
            toolbar: { show: false },
            zoom: { enabled: false },
        },
        series: [
            { name: 'Barang Masuk', data: data.inData, color: '#1A56DB' },
            { name: 'Barang Keluar', data: data.outData, color: '#F05252' },
        ],
        stroke: { curve: 'smooth', width: 2 },
        fill: {
            type: 'gradient',
            gradient: { opacityFrom: 0.35, opacityTo: 0.02 },
        },
        dataLabels: { enabled: false },
        grid: { borderColor, strokeDashArray: 4 },
        xaxis: { categories: data.labels, labels: { style: { colors: labelColor } } },
        yaxis: { labels: { style: { colors: labelColor } } },
        legend: {
            labels: { colors: labelColor },
            itemMargin: { horizontal: 10 },
        },
        tooltip: { y: { formatter: (v) => v + ' unit' } },
    });

    chart.render();

    document.addEventListener('dark-mode', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const lc = isDark ? '#9CA3AF' : '#6B7280';
        const bc = isDark ? '#374151' : '#E5E7EB';
        chart.updateOptions({ grid: { borderColor: bc }, xaxis: { labels: { style: { colors: lc } } }, yaxis: { labels: { style: { colors: lc } } }, legend: { labels: { colors: lc } } });
    });
}

const categoryDataEl = document.getElementById('stockify-category-data');

if (categoryDataEl) {
    const data = JSON.parse(categoryDataEl.textContent);
    const dark = document.documentElement.classList.contains('dark');
    const strokeColor = dark ? '#1f2937' : '#ffffff';

    const chart = new ApexCharts(document.getElementById('stockify-category-chart'), {
        series: data.values,
        labels: data.labels,
        colors: ['#1A56DB', '#FDBA8C', '#17B0BD', '#F05252', '#0E9F6E', '#9061F9', '#D03801', '#3F83F8'],
        chart: {
            type: 'donut',
            height: 320,
            fontFamily: 'Inter, sans-serif',
        },
        stroke: { colors: [strokeColor] },
        plotOptions: {
            pie: {
                donut: { size: '70%', labels: { show: true, name: { fontSize: '14px' }, value: { fontSize: '16px', fontWeight: 600 }, total: { show: true, label: 'Total Stok' } } },
            },
        },
        dataLabels: { enabled: false },
        legend: { position: 'bottom', labels: { colors: dark ? '#9CA3AF' : '#6B7280' } },
        tooltip: { y: { formatter: (v) => v + ' unit' } },
    });

    chart.render();
}
