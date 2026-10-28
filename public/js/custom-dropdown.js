/**
 * SIG SPPG - Custom Dropdown System
 * Matches the modern profile dropdown aesthetic across Admin, User, and Kepala SPPG
 */
(function () {
    'use strict';

    /**
     * Context dictionary to provide contextual icons and headers
     */
    function getContextConfig(select) {
        const id = (select.id || '').toLowerCase();
        const name = (select.name || '').toLowerCase();
        const labelEl = select.id ? document.querySelector('label[for="' + select.id + '"]') : null;
        const labelText = labelEl ? labelEl.textContent.trim() : '';

        // 1. Desa Filter
        if (id === 'desa' || name === 'desa' || name === 'desa_id' || select.classList.contains('filter-select')) {
            return {
                icon: null,
                itemDefaultIcon: null,
                headerTitle: 'Filter Desa',
                headerSubtitle: 'Kecamatan Cileunyi',
                getItemIcon: function () {
                    return null;
                }
            };
        }

        // 2. Analisis
        if (id === 'analisis' || name === 'analisis') {
            return {
                icon: 'bi-pie-chart-fill',
                headerTitle: 'Kategori Analisis',
                headerSubtitle: 'Pilih parameter visualisasi',
                getItemIcon: function (val) {
                    switch (val) {
                        case 'jumlah_sppg':
                            return 'bi-building';
                        case 'peresmian':
                            return 'bi-calendar-event';
                        case 'kapasitas':
                            return 'bi-boxes';
                        case 'pegawai':
                            return 'bi-people-fill';
                        default:
                            return 'bi-bar-chart-fill';
                    }
                }
            };
        }

        // 3. Urutan Data
        if (id === 'urutandata' || id === 'urutan' || name === 'urutan') {
            return {
                icon: 'bi-arrow-down-up',
                headerTitle: 'Urutan Data',
                headerSubtitle: 'Arah pengurutan chart & tabel',
                getItemIcon: function (val, text) {
                    const t = text.toLowerCase();
                    if (t.includes('terbaru') || t.includes('terbanyak') || t.includes('z - a')) {
                        return 'bi-sort-numeric-down-alt';
                    }
                    return 'bi-sort-numeric-down';
                }
            };
        }

        // 4. Limit Data
        if (id === 'limitdata' || select.classList.contains('chart-limit-select')) {
            return {
                icon: null,
                headerTitle: 'Batas Data',
                headerSubtitle: 'Jumlah entri data ditampilkan',
                getItemIcon: function () {
                    return null;
                }
            };
        }

        // 5. Role
        if (id === 'role' || name === 'role') {
            return {
                icon: 'bi-shield-lock-fill',
                headerTitle: 'Role Akses',
                headerSubtitle: 'Tingkat hak akses akun',
                getItemIcon: function (val) {
                    return val === 'admin' ? 'bi-shield-check' : 'bi-person-badge-fill';
                }
            };
        }

        // 6. SPPG Select
        if (id === 'sppg_id' || name === 'sppg_id') {
            return {
                icon: 'bi-building',
                headerTitle: 'Unit SPPG',
                headerSubtitle: 'Daftar unit SPPG aktif',
                getItemIcon: function () {
                    return 'bi-building';
                }
            };
        }

        // 7. Status
        if (id === 'status' || name === 'status') {
            return {
                icon: 'bi-toggle-on',
                headerTitle: 'Status Operasional',
                headerSubtitle: 'Pilih status data',
                getItemIcon: function (val) {
                    const v = String(val).toLowerCase();
                    if (v.includes('aktif') && !v.includes('tidak')) {
                        return 'bi-check-circle-fill text-success';
                    }
                    if (v.includes('tidak')) {
                        return 'bi-x-circle-fill text-danger';
                    }
                    return 'bi-circle';
                }
            };
        }

        // 8. Field Perubahan (Permohonan)
        if (id === 'field_perubahan' || name === 'field_perubahan') {
            return {
                icon: 'bi-sliders',
                headerTitle: 'Pilih Data Usulan',
                headerSubtitle: 'Pilih kolom yang ingin diubah',
                getItemIcon: function () {
                    return 'bi-pencil-square';
                }
            };
        }

        // Default Fallback
        return {
            icon: 'bi-filter',
            headerTitle: labelText || 'Pilih Opsi',
            headerSubtitle: 'Pilih salah satu data berikut',
            getItemIcon: function () {
                return 'bi-check2';
            }
        };
    }

    /**
     * Initializes a custom dropdown for a single select element
     */
    function initCustomDropdown(select) {
        if (!select || select.dataset.customDropdownInit === 'true') {
            return;
        }

        // Ignore selects explicitly excluded or multiple selects
        if (select.hasAttribute('multiple') || select.dataset.noCustom === 'true') {
            return;
        }

        select.dataset.customDropdownInit = 'true';

        const config = getContextConfig(select);

        // 1. Create Wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-dropdown-wrapper';
        if (select.id) {
            wrapper.dataset.forId = select.id;
        }

        // Copy classes or width hints if useful
        if (select.classList.contains('filter-select')) {
            wrapper.classList.add('filter-select-wrapper');
        }
        if (select.classList.contains('chart-limit-select')) {
            wrapper.classList.add('chart-limit-select-wrapper');
        }

        // 2. Create Trigger Button
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'custom-dropdown-trigger';
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');
        if (select.disabled) {
            trigger.disabled = true;
        }

        const triggerContent = document.createElement('div');
        triggerContent.className = 'custom-dropdown-trigger-content';

        if (config.icon) {
            const triggerIcon = document.createElement('i');
            triggerIcon.className = 'bi ' + config.icon + ' custom-dropdown-icon';
            triggerContent.appendChild(triggerIcon);
        }

        const triggerLabel = document.createElement('span');
        triggerLabel.className = 'custom-dropdown-label';

        triggerContent.appendChild(triggerLabel);

        const chevron = document.createElement('i');
        chevron.className = 'bi bi-chevron-down custom-dropdown-chevron';

        trigger.appendChild(triggerContent);
        trigger.appendChild(chevron);

        // 3. Create Dropdown Menu (Floating Card)
        const menu = document.createElement('div');
        menu.className = 'custom-dropdown-menu';
        menu.setAttribute('role', 'listbox');

        // Header
        const header = document.createElement('div');
        header.className = 'custom-dropdown-header';
        header.innerHTML = '<strong>' + config.headerTitle + '</strong><small>' + config.headerSubtitle + '</small>';

        const divider = document.createElement('hr');
        divider.className = 'custom-dropdown-divider';

        // Search Box container inside Dropdown Menu
        const searchBox = document.createElement('div');
        searchBox.className = 'custom-dropdown-search-box';

        const searchIcon = document.createElement('i');
        searchIcon.className = 'bi bi-search custom-dropdown-search-icon';

        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'custom-dropdown-search-input';
        searchInput.placeholder = 'Ketik untuk mencari...';
        searchInput.setAttribute('aria-label', 'Cari opsi');
        searchInput.setAttribute('autocomplete', 'off');

        searchBox.appendChild(searchIcon);
        searchBox.appendChild(searchInput);

        // Options List Container
        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'custom-dropdown-options';

        menu.appendChild(header);
        menu.appendChild(divider);
        menu.appendChild(searchBox);
        menu.appendChild(optionsContainer);

        // Assemble Wrapper
        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);

        /**
         * Filter option items based on search query
         */
        function filterItems(query) {
            const q = (query || '').toLowerCase().trim();
            let visibleCount = 0;
            const items = Array.from(optionsContainer.querySelectorAll('.custom-dropdown-item:not(.no-results-item)'));

            items.forEach(function (item) {
                const textEl = item.querySelector('.item-text');
                const text = (textEl ? textEl.textContent : item.textContent).toLowerCase();
                if (!q || text.includes(q)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            let noResults = optionsContainer.querySelector('.no-results-item');
            if (visibleCount === 0) {
                if (!noResults) {
                    noResults = document.createElement('div');
                    noResults.className = 'custom-dropdown-item no-results-item disabled text-muted text-center py-2';
                    noResults.textContent = 'Tidak ada data yang cocok';
                    optionsContainer.appendChild(noResults);
                }
                noResults.style.display = '';
            } else if (noResults) {
                noResults.style.display = 'none';
            }
        }

        function updateSearchBoxVisibility() {
            if (select.options.length > 4 || select.id === 'sppg_id' || select.name === 'sppg_id') {
                searchBox.style.display = '';
            } else {
                searchBox.style.display = 'none';
            }
        }

        searchInput.addEventListener('input', function (e) {
            e.stopPropagation();
            filterItems(this.value);
        });

        searchInput.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        searchInput.addEventListener('keydown', function (e) {
            e.stopPropagation();
            if (e.key === 'Enter') {
                e.preventDefault();
                const visibleItem = Array.from(optionsContainer.querySelectorAll('.custom-dropdown-item:not(.no-results-item):not(.disabled)'))
                    .find(item => item.style.display !== 'none');
                if (visibleItem) {
                    selectValue(visibleItem.dataset.value);
                    closeDropdown();
                }
            } else if (e.key === 'Escape') {
                closeDropdown();
            }
        });

        // Insert into DOM: Place wrapper right after the select
        select.classList.add('custom-select-native-hidden');
        select.parentNode.insertBefore(wrapper, select.nextSibling);

        /**
         * Sync visibility and disabled state from native <select> to wrapper
         */
        function syncVisibilityAndDisabled() {
            const isHidden = select.classList.contains('d-none') ||
                select.style.display === 'none' ||
                select.hidden;
            if (isHidden) {
                wrapper.classList.add('d-none');
                wrapper.style.display = 'none';
            } else {
                wrapper.classList.remove('d-none');
                wrapper.style.display = '';
            }

            if (select.disabled) {
                trigger.disabled = true;
                wrapper.classList.add('disabled');
            } else {
                trigger.disabled = false;
                wrapper.classList.remove('disabled');
            }
        }

        /**
         * Rebuild option items from native <select>
         */
        function syncOptions() {
            optionsContainer.innerHTML = '';
            const options = Array.from(select.options);
            let selectedOption = null;

            options.forEach(function (opt) {
                const item = document.createElement('div');
                item.className = 'custom-dropdown-item';
                item.setAttribute('role', 'option');
                item.dataset.value = opt.value;

                const isSelected = opt.selected || select.value === opt.value;
                if (isSelected && !selectedOption) {
                    selectedOption = opt;
                    item.classList.add('active');
                    item.setAttribute('aria-selected', 'true');
                } else {
                    item.setAttribute('aria-selected', 'false');
                }

                if (opt.disabled) {
                    item.classList.add('disabled');
                }

                // Item icon
                const iconClass = config.getItemIcon ? config.getItemIcon(opt.value, opt.text) : null;
                if (iconClass) {
                    const itemIcon = document.createElement('i');
                    itemIcon.className = 'bi ' + iconClass + ' item-icon';
                    item.appendChild(itemIcon);
                }

                // Item label
                const itemText = document.createElement('span');
                itemText.className = 'item-text';
                itemText.textContent = opt.text;

                // Item checkmark
                const itemCheck = document.createElement('i');
                itemCheck.className = 'bi bi-check2 item-check';

                item.appendChild(itemText);
                item.appendChild(itemCheck);

                item.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (opt.disabled) return;

                    selectValue(opt.value);
                    closeDropdown();
                });

                optionsContainer.appendChild(item);
            });

            // Fallback for label if none selected
            if (!selectedOption && options.length > 0) {
                selectedOption = options[0];
            }

            if (selectedOption) {
                triggerLabel.textContent = selectedOption.text;
                triggerLabel.title = selectedOption.text;
            } else {
                triggerLabel.textContent = 'Pilih Opsi';
            }

            // Sync visibility and disabled state
            syncVisibilityAndDisabled();
        }

        /**
         * Select a value and fire appropriate events
         */
        function selectValue(val) {
            const prevVal = select.value;
            select.value = val;

            // Highlight active item
            Array.from(optionsContainer.children).forEach(function (child) {
                if (child.dataset.value === String(val)) {
                    child.classList.add('active');
                    child.setAttribute('aria-selected', 'true');
                    triggerLabel.textContent = child.querySelector('.item-text').textContent;
                    triggerLabel.title = triggerLabel.textContent;
                } else {
                    child.classList.remove('active');
                    child.setAttribute('aria-selected', 'false');
                }
            });

            // Dispatch native change and input events
            const changeEvent = new Event('change', { bubbles: true });
            changeEvent._fromCustomDropdown = true;
            select.dispatchEvent(changeEvent);

            const inputEvent = new Event('input', { bubbles: true });
            inputEvent._fromCustomDropdown = true;
            select.dispatchEvent(inputEvent);

            // If inline onchange exists, trigger it explicitly
            if (typeof select.onchange === 'function') {
                select.onchange.call(select);
            }
        }

        /**
         * Open / Close handlers
         */
        function openDropdown() {
            // Close all other dropdowns first
            closeAllDropdowns(wrapper);

            // Collision check: dropup if near bottom of screen
            const rect = trigger.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const menuHeight = 280;

            if (spaceBelow < menuHeight && rect.top > menuHeight) {
                wrapper.classList.add('dropup');
            } else {
                wrapper.classList.remove('dropup');
            }

            wrapper.classList.add('open');
            trigger.setAttribute('aria-expanded', 'true');

            // Reset and update search box
            updateSearchBoxVisibility();
            if (searchInput) {
                searchInput.value = '';
                filterItems('');
                if (searchBox.style.display !== 'none') {
                    setTimeout(function () {
                        searchInput.focus();
                    }, 50);
                }
            }

            // Scroll active item into view
            const activeItem = optionsContainer.querySelector('.custom-dropdown-item.active');
            if (activeItem) {
                activeItem.scrollIntoView({ block: 'nearest' });
            }
        }

        function closeDropdown() {
            wrapper.classList.remove('open');
            trigger.setAttribute('aria-expanded', 'false');
        }

        function toggleDropdown() {
            if (wrapper.classList.contains('open')) {
                closeDropdown();
            } else {
                openDropdown();
            }
        }

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            if (select.disabled) return;
            toggleDropdown();
        });

        // Sync when native select changes from other code
        select.addEventListener('change', function (e) {
            if (e._fromCustomDropdown) return;
            const currentVal = select.value;
            Array.from(optionsContainer.children).forEach(function (child) {
                if (child.dataset.value === String(currentVal)) {
                    child.classList.add('active');
                    child.setAttribute('aria-selected', 'true');
                    triggerLabel.textContent = child.querySelector('.item-text').textContent;
                    triggerLabel.title = triggerLabel.textContent;
                } else {
                    child.classList.remove('active');
                    child.setAttribute('aria-selected', 'false');
                }
            });
        });

        // Hook programmatic `.value` setter on select instance
        const protoDescriptor = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'value');
        if (protoDescriptor) {
            Object.defineProperty(select, 'value', {
                get: function () {
                    return protoDescriptor.get.call(this);
                },
                set: function (newVal) {
                    protoDescriptor.set.call(this, newVal);
                    // Update custom dropdown label & active
                    Array.from(optionsContainer.children).forEach(function (child) {
                        if (child.dataset.value === String(newVal)) {
                            child.classList.add('active');
                            child.setAttribute('aria-selected', 'true');
                            triggerLabel.textContent = child.querySelector('.item-text').textContent;
                            triggerLabel.title = triggerLabel.textContent;
                        } else {
                            child.classList.remove('active');
                            child.setAttribute('aria-selected', 'false');
                        }
                    });
                },
                configurable: true
            });
        }

        // MutationObserver to detect dynamic attribute updates (class, disabled, style, hidden)
        const observer = new MutationObserver(function () {
            syncOptions();
            syncVisibilityAndDisabled();
        });
        observer.observe(select, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled', 'class', 'style', 'hidden'] });

        // Initial build
        syncOptions();
        syncVisibilityAndDisabled();
    }

    /**
     * Closes all active dropdowns across the page
     */
    function closeAllDropdowns(excludeWrapper) {
        document.querySelectorAll('.custom-dropdown-wrapper.open').forEach(function (w) {
            if (w !== excludeWrapper) {
                w.classList.remove('open');
                const btn = w.querySelector('.custom-dropdown-trigger');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });

        // Also close profile dropdowns if any
        if (!excludeWrapper || !excludeWrapper.closest('.admin-profile-button')) {
            const adminProfileBtn = document.getElementById('adminProfileButton');
            const adminProfileDropdown = document.getElementById('adminProfileDropdown');
            if (adminProfileBtn && adminProfileDropdown && !excludeWrapper?.closest?.('#adminProfileDropdown')) {
                adminProfileBtn.classList.remove('active');
                adminProfileDropdown.classList.remove('show');
            }

            const userProfileBtn = document.getElementById('userNavProfileButton');
            const userProfileDropdown = document.getElementById('userNavProfileDropdown');
            if (userProfileBtn && userProfileDropdown && !excludeWrapper?.closest?.('#userNavProfileDropdown')) {
                userProfileBtn.classList.remove('active');
                userProfileDropdown.classList.remove('show');
            }
        }
    }

    /**
     * Initializes all custom dropdowns in root container
     */
    function initAllCustomDropdowns(root) {
        const base = root || document;

        // Select all selects to enhance:
        // .filter-select, .form-select, select.form-control, .filter-wrapper select, .filter-card select, select[data-custom-dropdown]
        const selector = 'select.filter-select, select.form-select, select.form-control, .filter-wrapper select, .filter-card select, select[data-custom-dropdown]';
        const selects = base.querySelectorAll(selector);

        selects.forEach(function (select) {
            initCustomDropdown(select);
        });
    }

    // Global document click closes open dropdowns
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-dropdown-wrapper') && !e.target.closest('.admin-profile-button') && !e.target.closest('.admin-profile-dropdown')) {
            closeAllDropdowns();
        }
    });

    // Keyboard Esc key closes all dropdowns
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllDropdowns();
        }
    });

    // Handle Navbar Profile Dropdowns smoothly
    function setupProfileDropdowns() {
        const setupPair = function (btnId, dropdownId) {
            const btn = document.getElementById(btnId);
            const menu = document.getElementById(dropdownId);
            if (btn && menu && !btn.dataset.profileInit) {
                btn.dataset.profileInit = 'true';
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = menu.classList.contains('show');
                    closeAllDropdowns();
                    if (isOpen) {
                        btn.classList.remove('active');
                        menu.classList.remove('show');
                    } else {
                        btn.classList.add('active');
                        menu.classList.add('show');
                    }
                });
            }
        };

        setupPair('adminProfileButton', 'adminProfileDropdown');
        setupPair('userNavProfileButton', 'userNavProfileDropdown');
    }

    /**
     * Auto-dismiss alert notifications after 5 seconds with a countdown progress bar
     */
    function initAutoDismissAlerts() {
        const alerts = document.querySelectorAll('.alert');

        alerts.forEach(function (alert) {
            if (alert.dataset.autoDismissInit === 'true') return;
            alert.dataset.autoDismissInit = 'true';

            // Remove/hide close (X) button if present
            const closeBtn = alert.querySelector('.btn-close, [data-bs-dismiss="alert"]');
            if (closeBtn) {
                closeBtn.remove();
            }

            // Create progress bar track & fill
            const track = document.createElement('div');
            track.className = 'alert-progress-bar-track';
            const fill = document.createElement('div');
            fill.className = 'alert-progress-bar-fill';
            track.appendChild(fill);
            alert.appendChild(track);

            alert.style.transition = 'opacity 0.6s ease, transform 0.6s ease, margin-top 0.6s ease, max-height 0.6s ease';
            alert.style.opacity = '1';

            const totalDuration = 5000; // 5000ms
            let remainingTime = totalDuration;
            let lastTimestamp = null;
            let isPaused = false;

            function updateProgress(timestamp) {
                if (!lastTimestamp) lastTimestamp = timestamp;
                const delta = timestamp - lastTimestamp;
                lastTimestamp = timestamp;

                if (!isPaused) {
                    remainingTime -= delta;
                    const percent = Math.max(0, (remainingTime / totalDuration) * 100);
                    fill.style.width = percent + '%';

                    if (remainingTime <= 0) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';

                        setTimeout(function () {
                            if (window.bootstrap && window.bootstrap.Alert) {
                                const bsAlert = window.bootstrap.Alert.getOrCreateInstance(alert);
                                if (bsAlert) {
                                    bsAlert.close();
                                    return;
                                }
                            }
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 600);
                        return;
                    }
                }

                requestAnimationFrame(updateProgress);
            }

            alert.addEventListener('mouseenter', function () {
                isPaused = true;
            });

            alert.addEventListener('mouseleave', function () {
                isPaused = false;
                lastTimestamp = performance.now();
            });

            requestAnimationFrame(updateProgress);
        });
    }

    // Auto-run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initAllCustomDropdowns();
            setupProfileDropdowns();
            initAutoDismissAlerts();
        });
    } else {
        initAllCustomDropdowns();
        setupProfileDropdowns();
        initAutoDismissAlerts();
    }

    // Expose helpers globally
    window.initCustomDropdowns = initAllCustomDropdowns;
    window.initCustomDropdown = initCustomDropdown;
    window.initAutoDismissAlerts = initAutoDismissAlerts;
})();
