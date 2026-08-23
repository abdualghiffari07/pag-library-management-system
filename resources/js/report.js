document.addEventListener('DOMContentLoaded', () => {
    const chartContainer = document.getElementById('reportChart');

    if (!chartContainer) {
        return;
    }

    const data = window.pagReportData || {};

    const months = Array.isArray(data.months) ? data.months : [];
    const visitors = Array.isArray(data.visitors) ? data.visitors : [];
    const loans = Array.isArray(data.loans) ? data.loans : [];

    const buttons = document.querySelectorAll('.report-chart-button');

    const title = document.getElementById('reportChartTitle');
    const description = document.getElementById('reportChartDescription');
    const legend = document.getElementById('reportChartLegend');
    const total = document.getElementById('reportChartTotal');

    let currentType = 'visitors';

    function getCurrentData() {
        if (currentType === 'loans') {
            return {
                title: 'Statistik Peminjaman',
                description: 'Jumlah transaksi peminjaman berdasarkan periode',
                legend: 'Peminjaman',
                data: loans
            };
        }

        return {
            title: 'Statistik Pengunjung',
            description: 'Jumlah pengunjung unik berdasarkan periode',
            legend: 'Pengunjung',
            data: visitors
        };
    }

    function renderChart() {
        const current = getCurrentData();

        title.textContent = current.title;
        description.textContent = current.description;
        legend.textContent = current.legend;

        const values = months.map((_, index) => {
            const value = Number(current.data[index] ?? 0);

            return Number.isFinite(value) ? value : 0;
        });

        const totalValue = values.reduce(
            (sum, value) => sum + value,
            0
        );

        total.textContent = totalValue.toLocaleString('id-ID');

        updateButtons();

        drawChart(months, values);
    }

    function updateButtons() {
        buttons.forEach((button) => {
            const isActive =
                button.dataset.reportChart === currentType;

            if (isActive) {
                button.classList.add(
                    'bg-white',
                    'text-gray-800',
                    'shadow-sm',
                    'dark:bg-gray-700',
                    'dark:text-white'
                );

                button.classList.remove(
                    'text-gray-500',
                    'dark:text-gray-400'
                );
            } else {
                button.classList.remove(
                    'bg-white',
                    'text-gray-800',
                    'shadow-sm',
                    'dark:bg-gray-700',
                    'dark:text-white'
                );

                button.classList.add(
                    'text-gray-500',
                    'dark:text-gray-400'
                );
            }
        });
    }

    function drawChart(labels, values) {
        const width = Math.max(
            chartContainer.clientWidth,
            600
        );

        const height = 350;

        const padding = {
            top: 25,
            right: 20,
            bottom: 50,
            left: 45
        };

        const chartWidth =
            width -
            padding.left -
            padding.right;

        const chartHeight =
            height -
            padding.top -
            padding.bottom;

        const maxValue = Math.max(
            ...values,
            1
        );

        const yMax =
            maxValue <= 5
                ? 5
                : Math.ceil(maxValue / 5) * 5;

        const points = values.map((value, index) => {
            const x =
                labels.length <= 1
                    ? padding.left + chartWidth / 2
                    : padding.left +
                      (index / (labels.length - 1)) *
                          chartWidth;

            const y =
                padding.top +
                chartHeight -
                (value / yMax) * chartHeight;

            return {
                x,
                y,
                value
            };
        });

        let svg = `
            <svg
                width="100%"
                height="${height}"
                viewBox="0 0 ${width} ${height}"
                preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg"
            >
        `;

        /*
        |--------------------------------------------------------------------------
        | GRID
        |--------------------------------------------------------------------------
        */

        const gridCount = 5;

        for (let i = 0; i <= gridCount; i++) {
            const y =
                padding.top +
                (chartHeight / gridCount) * i;

            const value = Math.round(
                yMax -
                (yMax / gridCount) * i
            );

            svg += `
                <line
                    x1="${padding.left}"
                    y1="${y}"
                    x2="${width - padding.right}"
                    y2="${y}"
                    stroke="currentColor"
                    stroke-opacity="0.08"
                    stroke-width="1"
                />

                <text
                    x="${padding.left - 10}"
                    y="${y + 4}"
                    text-anchor="end"
                    fill="currentColor"
                    fill-opacity="0.45"
                    font-size="11"
                >
                    ${value}
                </text>
            `;
        }

        /*
        |--------------------------------------------------------------------------
        | AREA
        |--------------------------------------------------------------------------
        */

        if (points.length > 0) {
            const first = points[0];
            const last = points[points.length - 1];

            let areaPath =
                `M ${first.x} ${height - padding.bottom}`;

            points.forEach((point) => {
                areaPath +=
                    ` L ${point.x} ${point.y}`;
            });

            areaPath +=
                ` L ${last.x} ${height - padding.bottom} Z`;

            svg += `
                <path
                    d="${areaPath}"
                    fill="currentColor"
                    fill-opacity="0.08"
                />
            `;
        }

        /*
        |--------------------------------------------------------------------------
        | LINE
        |--------------------------------------------------------------------------
        */

        if (points.length > 0) {
            let linePath =
                `M ${points[0].x} ${points[0].y}`;

            for (let i = 1; i < points.length; i++) {
                linePath +=
                    ` L ${points[i].x} ${points[i].y}`;
            }

            svg += `
                <path
                    d="${linePath}"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            `;
        }

        /*
        |--------------------------------------------------------------------------
        | POINTS
        |--------------------------------------------------------------------------
        */

        points.forEach((point) => {
            svg += `
                <circle
                    cx="${point.x}"
                    cy="${point.y}"
                    r="4"
                    fill="currentColor"
                />

                <circle
                    cx="${point.x}"
                    cy="${point.y}"
                    r="7"
                    fill="currentColor"
                    fill-opacity="0.08"
                />
            `;
        });

        /*
        |--------------------------------------------------------------------------
        | LABEL
        |--------------------------------------------------------------------------
        */

        labels.forEach((label, index) => {
            const point = points[index];

            if (!point) {
                return;
            }

            svg += `
                <text
                    x="${point.x}"
                    y="${height - 18}"
                    text-anchor="middle"
                    fill="currentColor"
                    fill-opacity="0.55"
                    font-size="11"
                >
                    ${label}
                </text>
            `;
        });

        svg += `</svg>`;

        chartContainer.innerHTML = svg;

        chartContainer.classList.remove(
            'text-brand-500',
            'text-gray-800'
        );

        chartContainer.classList.add(
            'text-brand-500'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE PENGUNJUNG / PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const type =
                button.dataset.reportChart;

            if (
                type !== 'visitors' &&
                type !== 'loans'
            ) {
                return;
            }

            currentType = type;

            renderChart();
        });
    });

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    window.addEventListener('resize', () => {
        renderChart();
    });

    renderChart();
});