document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('mapSebaran');

    if (!mapElement) {
        return;
    }

    const sppgData = Array.isArray(window.sppgData)
        ? window.sppgData
        : [];

    const searchInput = document.getElementById('searchSppg');
    const clearSearch = document.getElementById('clearSearch');
    const searchResultList = document.getElementById('searchResultList');
    const searchCard = document.querySelector('.map-search-card');

    const map = L.map('mapSebaran').setView(
        [-6.9389, 107.7524],
        13
    );

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);

    const activeIcon = L.divIcon({
        className: 'custom-marker-wrapper',

        html: `
            <div class="custom-marker active-marker">
                <i class="bi bi-building-fill"></i>
            </div>
        `,

        iconSize: [42, 42],
        iconAnchor: [21, 42],
        popupAnchor: [0, -40]
    });

    const inactiveIcon = L.divIcon({
        className: 'custom-marker-wrapper',

        html: `
            <div class="custom-marker inactive-marker">
                <i class="bi bi-building-fill"></i>
            </div>
        `,

        iconSize: [42, 42],
        iconAnchor: [21, 42],
        popupAnchor: [0, -40]
    });

    const markerCollection = {};
    const markerBounds = [];

    function escapeHtml(value) {
        const element = document.createElement('div');

        element.textContent = value ?? '-';

        return element.innerHTML;
    }

    function normalize(value) {
        return String(value ?? '')
            .toLowerCase()
            .trim();
    }

    function resetMapPosition() {
        if (markerBounds.length === 0) {
            return;
        }

        map.fitBounds(markerBounds, {
            padding: [50, 50],
            maxZoom: 15
        });
    }

    sppgData.forEach(function (item) {
        if (
            item.latitude === null ||
            item.longitude === null
        ) {
            return;
        }

        const markerIcon =
            item.status === 'Aktif'
                ? activeIcon
                : inactiveIcon;

        const marker = L.marker(
            [
                Number(item.latitude),
                Number(item.longitude)
            ],
            {
                icon: markerIcon
            }
        ).addTo(map);

        const statusClass =
            item.status === 'Aktif'
                ? 'popup-status-active'
                : 'popup-status-inactive';

        marker.bindPopup(`
            <div class="map-popup">

                <h5>
                    ${escapeHtml(item.nama_sppg)}
                </h5>

                <div class="popup-location">
                    <i class="bi bi-geo-alt-fill"></i>
                    ${escapeHtml(item.nama_desa)}
                </div>

                <div class="popup-row">
                    <span>Ketua</span>

                    <strong>
                        ${escapeHtml(item.ketua_sppg)}
                    </strong>
                </div>

                <div class="popup-row">
                    <span>Mitra/Yayasan</span>

                    <strong>
                        ${escapeHtml(item.mitra_yayasan)}
                    </strong>
                </div>

                <div class="popup-row">
                    <span>Status</span>

                    <strong class="${statusClass}">
                        ${escapeHtml(item.status)}
                    </strong>
                </div>

                <a
                    href="${escapeHtml(item.detail_url)}"
                    class="popup-detail-button"
                >
                    <i class="bi bi-eye"></i>
                    Lihat Detail
                </a>

            </div>
        `);

        markerCollection[item.id] = marker;

        markerBounds.push([
            Number(item.latitude),
            Number(item.longitude)
        ]);
    });

    resetMapPosition();

    function findSppg(keyword) {
        const search = normalize(keyword);

        if (!search) {
            return [];
        }

        return sppgData.filter(function (item) {
            const searchableText = [
                item.nama_sppg,
                item.ketua_sppg,
                item.mitra_yayasan,
                item.nama_desa,
                item.alamat,
                item.status
            ]
                .map(normalize)
                .join(' ');

            return searchableText.includes(search);
        });
    }

    function focusMarker(item) {
        const marker = markerCollection[item.id];

        if (!marker) {
            return;
        }

        map.setView(
            [
                Number(item.latitude),
                Number(item.longitude)
            ],
            17,
            {
                animate: true
            }
        );

        marker.openPopup();

        searchInput.value = item.nama_sppg;

        clearSearch.classList.remove('d-none');
        searchResultList.classList.add('d-none');
    }

    function renderSearchResult(results) {
        searchResultList.innerHTML = '';

        if (results.length === 0) {
            searchResultList.innerHTML = `
                <div class="search-empty">

                    <i class="bi bi-search"></i>

                    <div>
                        <strong>Data tidak ditemukan</strong>

                        <span>
                            Coba gunakan kata pencarian lain.
                        </span>
                    </div>

                </div>
            `;

            searchResultList.classList.remove('d-none');

            return;
        }

        results.slice(0, 10).forEach(function (item) {
            const button = document.createElement('button');

            button.type = 'button';
            button.className = 'search-result-item';

            button.innerHTML = `
                <div class="result-icon">
                    <i class="bi bi-building"></i>
                </div>

                <div class="result-content">

                    <strong>
                        ${escapeHtml(item.nama_sppg)}
                    </strong>

                    <span>
                        ${escapeHtml(item.nama_desa)}
                        •
                        ${escapeHtml(item.mitra_yayasan)}
                    </span>

                </div>

                <i class="bi bi-chevron-right"></i>
            `;

            button.addEventListener('click', function () {
                focusMarker(item);
            });

            searchResultList.appendChild(button);
        });

        searchResultList.classList.remove('d-none');
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value;

            if (!keyword.trim()) {
                clearSearch.classList.add('d-none');
                searchResultList.classList.add('d-none');

                return;
            }

            clearSearch.classList.remove('d-none');

            renderSearchResult(
                findSppg(keyword)
            );
        });

        searchInput.addEventListener('keydown', function (event) {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();

            const results = findSppg(this.value);

            if (results.length > 0) {
                focusMarker(results[0]);
            }
        });
    }

    if (clearSearch) {
        clearSearch.addEventListener('click', function () {
            searchInput.value = '';

            clearSearch.classList.add('d-none');
            searchResultList.classList.add('d-none');

            resetMapPosition();

            searchInput.focus();
        });
    }

    document.addEventListener('click', function (event) {
        if (
            searchCard &&
            !searchCard.contains(event.target)
        ) {
            searchResultList.classList.add('d-none');
        }
    });

    setTimeout(function () {
        map.invalidateSize();
    }, 300);
});