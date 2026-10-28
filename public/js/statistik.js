document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Data dari Laravel
    |--------------------------------------------------------------------------
    */
    const sppgData = window.sppgData;

    /*
    |--------------------------------------------------------------------------
    | Elemen halaman
    |--------------------------------------------------------------------------
    */
    const desaSelect = document.getElementById('desa');
    const analisisSelect = document.getElementById('analisis');
    const urutanDataSelect = document.getElementById('urutanData');
    const limitDataSelect = document.getElementById('limitData');
    const exportPdfBtn = document.getElementById('exportPdfBtn');

    const btnBar = document.getElementById('btnBar');
    const btnPie = document.getElementById('btnPie');
    const btnLine = document.getElementById('btnLine');

    const chartCanvas = document.getElementById('chartUtama');
    const chartContainer = document.querySelector('.chart-container');
    const emptyChart = document.getElementById('emptyChart');
    const infoNoChart = document.getElementById('infoNoChart');
    const infoNoChartDesc = document.getElementById('infoNoChartDesc');
    const chartToolbar = document.getElementById('chartToolbar');
    const chartCard = document.querySelector('.chart-card');
    const chartTypeWrapper = document.querySelector('.chart-type');

    const judulGrafik = document.getElementById('judulGrafik');
    const subjudulGrafik = document.getElementById('subjudulGrafik');
    const chartBadge = document.getElementById('chartBadge');

    const tableBody = document.getElementById('statistikTableBody');
    const tableNameHeading = document.getElementById('tableNameHeading');
    const tableValueHeading = document.getElementById('tableValueHeading');
    const statistikTotal = document.getElementById('statistikTotal');
    const tableDescription = document.getElementById('tableDescription');

    const summarySppg = document.getElementById('summarySppg');
    const summaryPegawai = document.getElementById('summaryPegawai');
    const summaryKapasitas = document.getElementById('summaryKapasitas');

    /*
    |--------------------------------------------------------------------------
    | Variabel grafik
    |--------------------------------------------------------------------------
    */
    let chartUtama = null;
    let currentChartType = 'bar';

    const chartColors = [
        '#123957',
        '#1d5a80',
        '#287aa7',
        '#3d96bd',
        '#63b3cf',
        '#8acddd',
        '#a9dde7',
        '#f4a261',
        '#e76f51',
        '#6c757d',
        '#7b61ff',
        '#2a9d8f'
    ];

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */
    function formatNumber(value) {
        return new Intl.NumberFormat('id-ID').format(
            Number(value) || 0
        );
    }

    function formatDateIndonesia(dateValue) {
        if (!dateValue) {
            return '-';
        }

        const date = new Date(dateValue + 'T00:00:00');

        if (Number.isNaN(date.getTime())) {
            return '-';
        }

        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }).format(date);
    }

    function escapeHtml(value) {
        const element = document.createElement('div');
        element.textContent = value ?? '-';

        return element.innerHTML;
    }

    function getSelectedVillageName() {
        return desaSelect.options[
            desaSelect.selectedIndex
        ].text;
    }

    function getFilteredSppg() {
        const selectedVillage = desaSelect.value;

        if (selectedVillage === 'all') {
            return [...sppgData];
        }

        return sppgData.filter(function (item) {
            return String(item.desa_id) ===
                String(selectedVillage);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Atur pilihan urutan berdasarkan analisis
    |--------------------------------------------------------------------------
    */
    function updateUrutanOptions() {
        const currentValue = urutanDataSelect.value;
        const isPeresmian =
            analisisSelect.value === 'peresmian';
        const isSingleVillageJumlahSppg =
            desaSelect.value !== 'all' &&
            analisisSelect.value === 'jumlah_sppg';

        if (isPeresmian) {
            urutanDataSelect.innerHTML = `
                <option value="desc">Terbaru</option>
                <option value="asc">Terlama</option>
            `;
        } else if (isSingleVillageJumlahSppg) {
            urutanDataSelect.innerHTML = `
                <option value="asc">Nama A - Z</option>
                <option value="desc">Nama Z - A</option>
            `;
        } else {
            urutanDataSelect.innerHTML = `
                <option value="desc">Terbanyak</option>
                <option value="asc">Tersedikit</option>
            `;
        }

        if (
            currentValue === 'asc' ||
            currentValue === 'desc'
        ) {
            urutanDataSelect.value = currentValue;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Ringkasan
    |--------------------------------------------------------------------------
    */
    function updateSummary() {
        const filteredData = getFilteredSppg();

        const totalEmployees = filteredData.reduce(
            function (total, item) {
                return total +
                    (Number(item.jumlah_pegawai) || 0);
            },
            0
        );

        const totalCapacity = filteredData.reduce(
            function (total, item) {
                return total +
                    (Number(item.kapasitas) || 0);
            },
            0
        );

        summarySppg.textContent =
            formatNumber(filteredData.length);

        summaryPegawai.textContent =
            formatNumber(totalEmployees);

        summaryKapasitas.textContent =
            formatNumber(totalCapacity);
    }

    /*
    |--------------------------------------------------------------------------
    | Data semua desa
    |--------------------------------------------------------------------------
    */
    function getAllVillageData(analysis) {
        const grouped = {};

        if (Array.isArray(window.desaData)) {
            window.desaData.forEach(function (d) {
                grouped[d.nama_desa] = {
                    jumlah_sppg: 0,
                    kapasitas: 0,
                    pegawai: 0
                };
            });
        }

        sppgData.forEach(function (item) {
            const villageName =
                item.nama_desa || 'Tidak diketahui';

            if (!grouped[villageName]) {
                grouped[villageName] = {
                    jumlah_sppg: 0,
                    kapasitas: 0,
                    pegawai: 0
                };
            }

            grouped[villageName].jumlah_sppg += 1;

            grouped[villageName].kapasitas +=
                Number(item.kapasitas) || 0;

            grouped[villageName].pegawai +=
                Number(item.jumlah_pegawai) || 0;
        });

        const sortedVillages =
            Object.keys(grouped).sort(function (a, b) {
                return a.localeCompare(b, 'id');
            });

        return {
            labels: sortedVillages,

            values: sortedVillages.map(function (village) {
                return grouped[village][analysis] || 0;
            })
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Data satu desa
    |--------------------------------------------------------------------------
    */
    function getSingleVillageData(villageId, analysis) {
        const filteredData = sppgData.filter(function (item) {
            return String(item.desa_id) ===
                String(villageId);
        });

        return {
            labels: filteredData.map(function (item) {
                return item.nama_sppg;
            }),

            values: filteredData.map(function (item) {
                if (analysis === 'kapasitas') {
                    return Number(item.kapasitas) || 0;
                }

                if (analysis === 'pegawai') {
                    return Number(item.jumlah_pegawai) || 0;
                }

                return 1;
            })
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Data SPPG terbaru
    |--------------------------------------------------------------------------
    */
    function getPeresmianData(villageId, order) {
        let filteredData = [...sppgData];

        if (villageId !== 'all') {
            filteredData = filteredData.filter(
                function (item) {
                    return String(item.desa_id) ===
                        String(villageId);
                }
            );
        }

        const rows = filteredData
            .filter(function (item) {
                return item.tanggal_peresmian;
            })
            .sort(function (a, b) {
                const dateA = new Date(
                    a.tanggal_peresmian + 'T00:00:00'
                );

                const dateB = new Date(
                    b.tanggal_peresmian + 'T00:00:00'
                );

                return order === 'asc'
                    ? dateA - dateB
                    : dateB - dateA;
            });

        return {
            rows: rows,

            labels: rows.map(function (item) {
                return item.nama_sppg;
            }),

            dates: rows.map(function (item) {
                return item.tanggal_peresmian;
            }),

            /*
             * Nilai ranking digunakan agar grafik horizontal
             * tetap memiliki panjang batang yang terbaca.
             */
            values: rows.map(function (_, index) {
                return rows.length - index;
            })
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Informasi grafik
    |--------------------------------------------------------------------------
    */
    function getChartInformation(
        analysis,
        allVillages,
        villageName,
        order
    ) {
        if (analysis === 'peresmian') {
            const isOldest = order === 'asc';
            const orderLabel = isOldest ? 'Terlama' : 'Terbaru';

            return {
                title: allVillages
                    ? `Peresmian SPPG ${orderLabel} Kecamatan Cileunyi`
                    : `Peresmian SPPG ${orderLabel} di Desa ${villageName}`,

                subtitle: allVillages
                    ? `Daftar SPPG berdasarkan tanggal peresmian ${orderLabel.toLowerCase()}`
                    : `Daftar SPPG berdasarkan peresmian ${orderLabel.toLowerCase()} di Desa ${villageName}`,

                dataset: 'Tanggal Peresmian',
                unit: 'Data'
            };
        }

        if (allVillages) {
            const information = {
                jumlah_sppg: {
                    title: 'Jumlah SPPG pada Setiap Desa',
                    subtitle:
                        'Perbandingan jumlah SPPG berdasarkan desa',
                    dataset: 'Jumlah SPPG',
                    unit: 'SPPG'
                },

                kapasitas: {
                    title:
                        'Kapasitas Produksi pada Setiap Desa',
                    subtitle:
                        'Total kapasitas seluruh SPPG berdasarkan desa',
                    dataset: 'Kapasitas Produksi',
                    unit: 'Porsi / Hari'
                },

                pegawai: {
                    title:
                        'Jumlah Pegawai pada Setiap Desa',
                    subtitle:
                        'Total pegawai seluruh SPPG berdasarkan desa',
                    dataset: 'Jumlah Pegawai',
                    unit: 'Orang'
                }
            };

            return information[analysis];
        }

        const information = {
            jumlah_sppg: {
                title:
                    `Daftar SPPG di Desa ${villageName}`,
                subtitle:
                    'Daftar SPPG yang berada pada desa terpilih',
                dataset: 'Jumlah SPPG',
                unit: 'SPPG'
            },

            kapasitas: {
                title:
                    `Kapasitas Produksi SPPG di Desa ${villageName}`,
                subtitle:
                    'Kapasitas produksi masing-masing SPPG',
                dataset: 'Kapasitas Produksi',
                unit: 'Porsi / Hari'
            },

            pegawai: {
                title:
                    `Jumlah Pegawai SPPG di Desa ${villageName}`,
                subtitle:
                    'Jumlah pegawai pada masing-masing SPPG',
                dataset: 'Jumlah Pegawai',
                unit: 'Orang'
            }
        };

        return information[analysis];
    }

    /*
    |--------------------------------------------------------------------------
    | Tabel normal
    |--------------------------------------------------------------------------
    */
    function updateNormalTable(
        labels,
        values,
        information,
        allVillages
    ) {
        tableBody.innerHTML = '';

        const isSingleVillageJumlahSppg =
            !allVillages &&
            analisisSelect.value === 'jumlah_sppg';

        tableNameHeading.textContent =
            allVillages ? 'Nama Desa' : 'Nama SPPG';

        tableValueHeading.textContent =
            isSingleVillageJumlahSppg ? 'Keterangan' : 'Nilai';

        tableDescription.textContent =
            isSingleVillageJumlahSppg
                ? 'Daftar SPPG yang terdaftar pada desa terpilih'
                : 'Rincian data yang ditampilkan pada grafik';

        if (labels.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3"
                        class="text-center py-4 text-muted">
                        <i class="bi bi-inbox me-2"></i>
                        Data statistik belum tersedia.
                    </td>
                </tr>
            `;

            statistikTotal.textContent =
                `0 ${information.unit}`;

            return;
        }

        labels.forEach(function (label, index) {
            const value = Number(values[index]) || 0;

            const row = document.createElement('tr');

            const valueHtml = isSingleVillageJumlahSppg
                ? `<span>1</span> <span class="table-unit">Unit SPPG</span>`
                : `<span>${formatNumber(value)}</span> <span class="table-unit">${information.unit}</span>`;

            row.innerHTML = `
                <td>${index + 1}</td>

                <td>
                    ${escapeHtml(label)}
                </td>

                <td>
                    ${valueHtml}
                </td>
            `;

            tableBody.appendChild(row);
        });

        const total = values.reduce(
            function (sum, value) {
                return sum + (Number(value) || 0);
            },
            0
        );

        statistikTotal.textContent =
            `${formatNumber(total)} ${information.unit}`;
    }

    /*
    |--------------------------------------------------------------------------
    | Tabel peresmian
    |--------------------------------------------------------------------------
    */
    function updatePeresmianTable(rows, order) {
        tableBody.innerHTML = '';

        tableNameHeading.textContent = 'Nama SPPG';
        tableValueHeading.textContent = 'Tanggal Peresmian';

        tableDescription.textContent =
            order === 'asc'
                ? 'Daftar SPPG berdasarkan tanggal peresmian terlama'
                : 'Daftar SPPG berdasarkan tanggal peresmian terbaru';

        if (rows.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3"
                        class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x me-2"></i>
                        Data tanggal peresmian belum tersedia.
                    </td>
                </tr>
            `;

            statistikTotal.textContent = '0 Data';

            return;
        }

        rows.forEach(function (item, index) {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${index + 1}</td>

                <td>
                    <span>
                        ${escapeHtml(item.nama_sppg)}
                    </span>

                    <div class="small text-muted mt-1">
                        <i class="bi bi-geo-alt me-1"></i>
                        ${escapeHtml(item.nama_desa)}
                    </div>
                </td>

                <td>
                    <span class="date-badge">
                        <i class="bi bi-calendar-event me-1"></i>

                        ${formatDateIndonesia(
                            item.tanggal_peresmian
                        )}
                    </span>
                </td>
            `;

            tableBody.appendChild(row);
        });

        statistikTotal.textContent =
            `${rows.length} Data`;
    }

    /*
    |--------------------------------------------------------------------------
    | Opsi grafik normal
    |--------------------------------------------------------------------------
    */
    function getNormalChartOptions(
        chartType,
        information
    ) {
        const isPieChart = chartType === 'pie';

        return {
            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false
            },

            plugins: {
                legend: {
                    display: isPieChart,
                    position: 'bottom',

                    labels: {
                        usePointStyle: true,
                        padding: 18,

                        font: {
                            family: 'Poppins',
                            size: 12
                        }
                    }
                },

                tooltip: {
                    backgroundColor: '#123957',
                    padding: 12,
                    cornerRadius: 8,

                    callbacks: {
                        label: function (context) {
                            const value = isPieChart
                                ? context.parsed
                                : context.parsed.y;

                            return `${context.dataset.label}: ` +
                                `${formatNumber(value)} ` +
                                `${information.unit}`;
                        }
                    }
                }
            },

            scales: isPieChart ? {} : {
                x: {
                    grid: {
                        display: false
                    },

                    ticks: {
                        color: '#667085',
                        maxRotation: 45,
                        minRotation: 0
                    }
                },

                y: {
                    beginAtZero: true,

                    grid: {
                        color:
                            'rgba(18, 57, 87, 0.08)'
                    },

                    ticks: {
                        color: '#667085',
                        precision: 0
                    }
                }
            }
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Grafik normal
    |--------------------------------------------------------------------------
    */
    function renderNormalChart(
        statisticData,
        information
    ) {
        const isPieChart =
            currentChartType === 'pie';

        const backgroundColors = isPieChart
            ? statisticData.labels.map(
                function (_, index) {
                    return chartColors[
                        index % chartColors.length
                    ];
                }
            )
            : currentChartType === 'line'
                ? 'rgba(18, 57, 87, 0.14)'
                : '#123957';

        const borderColors = isPieChart
            ? '#ffffff'
            : '#123957';

        chartUtama = new Chart(chartCanvas, {
            type: currentChartType,

            data: {
                labels: statisticData.labels,

                datasets: [{
                    label: information.dataset,
                    data: statisticData.values,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth:
                        currentChartType === 'line' ? 3 : 1,
                    borderRadius:
                        currentChartType === 'bar' ? 8 : 0,
                    borderSkipped: false,
                    fill:
                        currentChartType === 'line',
                    tension: 0.35,
                    pointRadius:
                        currentChartType === 'line' ? 5 : 0,
                    pointHoverRadius:
                        currentChartType === 'line' ? 7 : 0
                }]
            },

            options: getNormalChartOptions(
                currentChartType,
                information
            )
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Grafik peresmian
    |--------------------------------------------------------------------------
    */
    function renderPeresmianChart(statisticData) {
        /*
         * Pie dan line kurang cocok untuk tanggal per SPPG.
         * Karena itu peresmian selalu memakai bar horizontal.
         */
        currentChartType = 'bar';
        updateActiveChartButton();

        chartUtama = new Chart(chartCanvas, {
            type: 'bar',

            data: {
                labels: statisticData.labels,

                datasets: [{
                    label: 'Tanggal Peresmian',
                    data: statisticData.values,
                    backgroundColor: '#123957',
                    borderColor: '#123957',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },

            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#123957',
                        padding: 12,
                        cornerRadius: 8,

                        callbacks: {
                            title: function (items) {
                                if (!items.length) {
                                    return '';
                                }

                                return statisticData.labels[
                                    items[0].dataIndex
                                ];
                            },

                            label: function (context) {
                                const index =
                                    context.dataIndex;

                                return 'Diresmikan: ' +
                                    formatDateIndonesia(
                                        statisticData.dates[index]
                                    );
                            }
                        }
                    }
                },

                scales: {
                    x: {
                        display: false,
                        beginAtZero: true
                    },

                    y: {
                        grid: {
                            display: false
                        },

                        ticks: {
                            color: '#344054',
                            font: {
                                family: 'Poppins',
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Tombol grafik aktif
    |--------------------------------------------------------------------------
    */
    function updateActiveChartButton() {
        document
            .querySelectorAll('.chart-btn')
            .forEach(function (button) {
                button.classList.remove('active');
            });

        const activeButton =
            document.querySelector(
                `.chart-btn[data-chart="${currentChartType}"]`
            );

        if (activeButton) {
            activeButton.classList.add('active');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Atur tombol grafik berdasarkan analisis
    |--------------------------------------------------------------------------
    */
    function updateChartButtons() {
        const isPeresmian =
            analisisSelect.value === 'peresmian';

        btnPie.disabled = isPeresmian;
        btnLine.disabled = isPeresmian;

        btnPie.classList.toggle(
            'chart-btn-disabled',
            isPeresmian
        );

        btnLine.classList.toggle(
            'chart-btn-disabled',
            isPeresmian
        );

        if (isPeresmian) {
            currentChartType = 'bar';
        }

        updateActiveChartButton();
    }


    function limitStatisticData(statisticData, limit) {
    if (limit === null) {
        return statisticData;
    }

    return {
        ...statisticData,

        labels: statisticData.labels.slice(0, limit),

        values: statisticData.values.slice(0, limit),

        dates: statisticData.dates
            ? statisticData.dates.slice(0, limit)
            : undefined,

        rows: statisticData.rows
            ? statisticData.rows.slice(0, limit)
            : undefined
    };
}

    /*
    |--------------------------------------------------------------------------
    | Render utama
    |--------------------------------------------------------------------------
    */
    function renderChart() {
        const selectedVillage = desaSelect.value;
        const selectedAnalysis = analisisSelect.value;
        const selectedOrder = urutanDataSelect.value;
        const selectedLimit =
            limitDataSelect.value === 'all'
                ? null
                : Number(limitDataSelect.value) || 10;

        const allVillages =
            selectedVillage === 'all';

        const villageName =
            getSelectedVillageName();

        const information =
            getChartInformation(
                selectedAnalysis,
                allVillages,
                villageName,
                selectedOrder
            );

        judulGrafik.textContent =
            information.title;

        subjudulGrafik.textContent =
            information.subtitle;

        chartBadge.textContent =
            allVillages
                ? 'Semua Desa'
                : villageName;

        updateSummary();

        if (chartUtama) {
            chartUtama.destroy();
            chartUtama = null;
        }

        if (infoNoChart) {
            infoNoChart.classList.add('d-none');
        }

        if (chartCard) {
            chartCard.classList.remove('d-none');
        }

        if (chartTypeWrapper) {
            chartTypeWrapper.classList.remove('d-none');
        }

        // KASUS 1: Analisis PERESMIAN
        if (selectedAnalysis === 'peresmian') {
            if (chartToolbar) chartToolbar.classList.remove('d-none');

            let statisticData =
                getPeresmianData(
                    selectedVillage,
                    selectedOrder
                );

            statisticData =
                limitStatisticData(
                    statisticData,
                    selectedLimit
                );

            updatePeresmianTable(
                statisticData.rows,
                selectedOrder
            );

            if (statisticData.rows.length === 0) {
                chartContainer.classList.add('d-none');
                emptyChart.classList.remove('d-none');

                return;
            }

            chartContainer.classList.remove('d-none');
            emptyChart.classList.add('d-none');

            renderPeresmianChart(statisticData);

            return;
        }

        // KASUS 2: JUMLAH SPPG DI SATU DESA TERTENTU
        // (Setiap SPPG bernilai 1 unit sehingga grafik perbandingan tidak diperlukan, langsung tampilkan data statistik)
        if (!allVillages && selectedAnalysis === 'jumlah_sppg') {
            const filteredSppg = getFilteredSppg();

            if (chartToolbar) chartToolbar.classList.remove('d-none');
            if (chartTypeWrapper) chartTypeWrapper.classList.add('d-none');
            if (chartCard) chartCard.classList.add('d-none');

            if (filteredSppg.length === 0) {
                updateNormalTable([], [], information, allVillages);
                return;
            }

            let combinedData = filteredSppg.map(function (item) {
                return {
                    label: item.nama_sppg,
                    value: 1
                };
            });

            // Urutkan berdasarkan nama
            combinedData.sort(function (a, b) {
                return selectedOrder === 'desc'
                    ? b.label.localeCompare(a.label, 'id')
                    : a.label.localeCompare(b.label, 'id');
            });

            if (selectedLimit !== null) {
                combinedData = combinedData.slice(0, selectedLimit);
            }

            const statisticData = {
                labels: combinedData.map(item => item.label),
                values: combinedData.map(item => item.value)
            };

            updateNormalTable(
                statisticData.labels,
                statisticData.values,
                information,
                allVillages
            );

            return;
        }

        // KASUS 3: SEMUA DESA (atau SATU DESA untuk analisis Kapasitas / Pegawai)
        if (chartToolbar) chartToolbar.classList.remove('d-none');

        let statisticData = allVillages
            ? getAllVillageData(selectedAnalysis)
            : getSingleVillageData(
                selectedVillage,
                selectedAnalysis
            );

        let combinedData = statisticData.labels.map(
            function (label, index) {
                return {
                    label: label,
                    value: Number(statisticData.values[index]) || 0
                };
            }
        );

        combinedData.sort(function (a, b) {
            return selectedOrder === 'asc'
                ? a.value - b.value
                : b.value - a.value;
        });

        if (selectedLimit !== null) {
            combinedData = combinedData.slice(0, selectedLimit);
        }

        statisticData = {
            labels: combinedData.map(function (item) {
                return item.label;
            }),

            values: combinedData.map(function (item) {
                return item.value;
            })
        };

        updateNormalTable(
            statisticData.labels,
            statisticData.values,
            information,
            allVillages
        );

        if (statisticData.labels.length === 0) {
            chartContainer.classList.add('d-none');
            emptyChart.classList.remove('d-none');

            return;
        }

        chartContainer.classList.remove('d-none');
        emptyChart.classList.add('d-none');

        renderNormalChart(
            statisticData,
            information
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Event dropdown
    |--------------------------------------------------------------------------
    */
    desaSelect.addEventListener('change', function () {
        updateUrutanOptions();
        updateChartButtons();
        renderChart();
    });

    analisisSelect.addEventListener('change', function () {
        updateUrutanOptions();
        updateChartButtons();
        renderChart();
    });

    urutanDataSelect.addEventListener('change', function () {
        renderChart();
    });

    limitDataSelect.addEventListener('change', function () {
        renderChart();
    });

    /*
    |--------------------------------------------------------------------------
    | Event jenis grafik
    |--------------------------------------------------------------------------
    */
    document
        .querySelectorAll('.chart-btn')
        .forEach(function (button) {

            button.addEventListener('click', function () {
                if (this.disabled) {
                    return;
                }

                currentChartType =
                    this.dataset.chart;

                updateActiveChartButton();
                renderChart();
            });

        });

    /*
    |--------------------------------------------------------------------------
    | Event tombol analisis cepat (quick analysis)
    |--------------------------------------------------------------------------
    */
    document.querySelectorAll('.quick-analysis-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetAnalysis = this.dataset.analysis;
            if (targetAnalysis && analisisSelect) {
                analisisSelect.value = targetAnalysis;
                updateUrutanOptions();
                updateChartButtons();
                renderChart();
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Tampilan awal
    |--------------------------------------------------------------------------
    */
    updateUrutanOptions();
    updateChartButtons();
    updateActiveChartButton();
    renderChart();

});