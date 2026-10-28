document.addEventListener('DOMContentLoaded', function () {

    const exportPdfBtn =
        document.getElementById('exportPdfBtn');

    if (!exportPdfBtn) return;

    exportPdfBtn.addEventListener('click', exportStatistikPdf);

});

/*Export PDF*/

function exportStatistikPdf() {

    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: 'a4'
    });

    pdf.setFillColor(18, 57, 87);
    pdf.rect(0, 0, 210, 30, "F");

    pdf.setTextColor(255, 255, 255);
    pdf.setFont("helvetica", "bold");
    pdf.setFontSize(18);

    pdf.text(
        "LAPORAN STATISTIK MONITORING",
        105,
        15,
        { align: "center" }
    );

    pdf.setFontSize(11);

    pdf.text(
        "Sistem Informasi SIG SPPG Kecamatan Cileunyi",
        105,
        23,
        { align: "center" }
    );

    const desa = document.getElementById("desa");
    const analisis = document.getElementById("analisis");
    const urutan = document.getElementById("urutanData");
    const jumlahData = document.getElementById("limitData");

    const tanggalCetak = new Date().toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
    });

    pdf.setTextColor(0, 0, 0);
    pdf.setFont("helvetica", "normal");
    pdf.setFontSize(11);

    const startX = 20;
    const colonX = 55;
    const valueX = 60;

    let y = 45;

    pdf.text("Tanggal Cetak", startX, y);
    pdf.text(":", colonX, y);
    pdf.text(tanggalCetak, valueX, y);

    y += 8;

    pdf.text("Desa", startX, y);
    pdf.text(":", colonX, y);
    pdf.text(
        desa.options[desa.selectedIndex].text,
        valueX,
        y
    );

    y += 8;

    pdf.text("Analisis", startX, y);
    pdf.text(":", colonX, y);
    pdf.text(
        analisis.options[analisis.selectedIndex].text,
        valueX,
        y
    );

    y += 8;

    pdf.text("Urutan", startX, y);
    pdf.text(":", colonX, y);
    pdf.text(
        urutan.options[urutan.selectedIndex].text,
        valueX,
        y
    );

    y += 8;

    pdf.text("Jumlah Data", startX, y);
    pdf.text(":", colonX, y);
    pdf.text(
        jumlahData.options[jumlahData.selectedIndex].text,
        valueX,
        y
    );

    y += 10;

    pdf.setDrawColor(18,57,87);
    pdf.setLineWidth(0.5);
    pdf.line(20,y,190,y);

    y += 10;

    const totalSppg =
        document.getElementById("summarySppg").textContent.trim();

    const totalPegawai =
        document.getElementById("summaryPegawai").textContent.trim();

    const totalKapasitas =
        document.getElementById("summaryKapasitas").textContent.trim();

    pdf.setFont("helvetica", "bold");
    pdf.setFontSize(13);
    pdf.setTextColor(18, 57, 87);

    pdf.text(
        "RINGKASAN STATISTIK",
        20,
        y
    );

    y += 8;

function drawSummaryCard(x, title, value) {
const cardWidth = 52;    
const cardHeight = 25;

    pdf.setFillColor(245, 248, 250);
    pdf.setDrawColor(210, 220, 228);
    pdf.setLineWidth(0.3);

    pdf.roundedRect(
        x,
        y,
        cardWidth,
        cardHeight,
        3,
        3,
        "FD"
    );

    pdf.setFont("helvetica", "normal");
    pdf.setFontSize(9);
    pdf.setTextColor(102, 112, 133);

    pdf.text(
        title,
        x + 5,
        y + 8
    );

    pdf.setFont("helvetica", "bold");
    pdf.setFontSize(16);
    pdf.setTextColor(18, 57, 87);

    pdf.text(
        value,
        x + 5,
        y + 18
    );
}

drawSummaryCard(
    20,
    "Total SPPG",
    totalSppg
);

drawSummaryCard(
    79,
    "Total Pegawai",
    totalPegawai
);

drawSummaryCard(
    138,
    "Total Kapasitas",
    totalKapasitas
);

y += 35;

const chartContainer = document.querySelector('.chart-container');
const chartCard = document.querySelector('.chart-card');
const isChartVisible = chartContainer &&
                       !chartContainer.classList.contains('d-none') &&
                       (chartCard ? !chartCard.classList.contains('d-none') : true);

if (isChartVisible) {
    pdf.setFont("helvetica", "bold");
    pdf.setFontSize(13);
    pdf.setTextColor(18,57,87);

    pdf.text(
        "GRAFIK STATISTIK",
        20,
        y
    );

    y += 8;

    const canvas =
        document.getElementById("chartUtama");

    if (canvas) {
        try {
            const chartImage =
                canvas.toDataURL(
                    "image/png",
                    1.0
                );

            pdf.addImage(
                chartImage,
                "PNG",
                20,
                y,
                170,
                55
            );

            y += 65;
        } catch (e) {
            console.error("Gagal mengekspor grafik ke PDF:", e);
        }
    }
}

pdf.setFont("helvetica", "bold");
pdf.setFontSize(13);
pdf.setTextColor(18, 57, 87);

pdf.text(
    "DATA STATISTIK",
    20,
    y
);

y += 5;

const tableHeaders = [];

document
    .querySelectorAll(".statistic-table thead th")
    .forEach(function (heading) {
        tableHeaders.push(
            heading.textContent
                .replace(/\s+/g, " ")
                .trim()
        );
    });

const tableRows = [];

document
    .querySelectorAll("#statistikTableBody tr")
    .forEach(function (row) {
        const cells = [];

        row.querySelectorAll("td").forEach(function (cell) {
            cells.push(
                cell.textContent
                    .replace(/\s+/g, " ")
                    .trim()
            );
        });

        if (cells.length === 3) {
            tableRows.push(cells);
        }
    });

    if (typeof pdf.autoTable !== "function") {
    alert("Library AutoTable belum berhasil dimuat.");
    return;
}

pdf.autoTable({
    head: [tableHeaders],
    body: tableRows,

    startY: y,

    margin: {
        left: 20,
        right: 20,
        top: 20,
        bottom: 20
    },

    theme: "grid",

    styles: {
        font: "helvetica",
        fontSize: 9,
        cellPadding: 3,
        valign: "middle",
        textColor: [31, 41, 55],
        lineColor: [210, 220, 228],
        lineWidth: 0.2
    },

    headStyles: {
        fillColor: [18, 57, 87],
        textColor: [255, 255, 255],
        fontStyle: "bold",
        halign: "center"
    },

    alternateRowStyles: {
        fillColor: [245, 248, 250]
    },

    columnStyles: {
        0: {
            cellWidth: 15,
            halign: "center"
        },

        1: {
            cellWidth: 100
        },

        2: {
            cellWidth: 55,
            halign: "center"
        }
    }
});

    pdf.save("Laporan-statistik.pdf");

}