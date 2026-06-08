<script>
(function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    /* ── 1. Sortable Columns ── */
    function initSortable() {
        document.querySelectorAll('.table-admin[data-sortable]').forEach(function(table) {
            var headers = table.querySelectorAll('th[data-sort]');
            headers.forEach(function(header) {
                header.style.cursor = 'pointer';
                header.style.userSelect = 'none';
                header.addEventListener('click', function() {
                    var key = header.dataset.sort;
                    var tbody = table.querySelector('tbody');
                    if (!tbody) return;
                    var rows = Array.from(tbody.querySelectorAll('tr'));
                    if (rows.length === 0) return;

                    var isAsc = header.classList.contains('sort-asc');
                    headers.forEach(function(h) {
                        h.classList.remove('sort-asc', 'sort-desc');
                        h.title = 'Sort by column';
                    });
                    header.classList.add(isAsc ? 'sort-desc' : 'sort-asc');
                    header.title = isAsc ? 'Sorted descending' : 'Sorted ascending';

                    var colIndex = Array.from(header.parentElement.children).indexOf(header);

                    rows.sort(function(a, b) {
                        var aVal = a.children[colIndex]?.textContent.trim().toLowerCase() || '';
                        var bVal = b.children[colIndex]?.textContent.trim().toLowerCase() || '';
                        if (aVal < bVal) return isAsc ? 1 : -1;
                        if (aVal > bVal) return isAsc ? -1 : 1;
                        return 0;
                    });

                    rows.forEach(function(row) { tbody.appendChild(row); });
                });
            });
        });
    }

    /* ── 2. AJAX Flash Message ── */
    function showToast(message, type) {
        type = type || 'success';
        var iconMap = { success: 'check-circle', error: 'alert-circle', warning: 'alert-triangle', info: 'info' };
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: message,
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
            });
        }
    }

    /* ── 3. AJAX Actions ── */
    function initAjaxActions() {
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-ajax-action]');
            if (!btn) return;
            e.preventDefault();

            var form = btn.closest('form');
            if (!form) return;

            var action = btn.dataset.ajaxAction;
            var confirmMsg = btn.dataset.confirm || 'Are you sure?';
            if (!confirm(confirmMsg)) return;

            var url = form.action;
            var formData = new FormData(form);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    showToast(res.message || 'Success', 'success');
                    if (action === 'delete') {
                        var row = btn.closest('tr');
                        if (row) {
                            row.style.transition = 'opacity 0.3s, transform 0.3s';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';
                            setTimeout(function() { row.remove(); }, 300);
                        }
                    } else if (action === 'toggle-access' && res.row) {
                        updateRow(btn, res.row);
                    }
                    if (res.reload) {
                        setTimeout(function() { window.location.reload(); }, 500);
                    }
                } else {
                    showToast(res.message || 'Failed', 'error');
                }
            })
            .catch(function() {
                showToast('Network error', 'error');
            });
        });
    }

    function updateRow(btn, data) {
        var row = btn.closest('tr');
        if (!row) return;

        if (data.status_html) {
            var statusCell = row.querySelector('.badge-status');
            if (statusCell) {
                var temp = document.createElement('div');
                temp.innerHTML = data.status_html;
                statusCell.outerHTML = temp.innerHTML;
            }
        }
        if (data.unlocked_by_html) {
            var unlockedCell = row.querySelectorAll('td')[5];
            if (unlockedCell) {
                unlockedCell.innerHTML = data.unlocked_by_html;
            }
        }
        if (data.actions_html) {
            var actionCell = row.querySelector('.dropdown');
            if (actionCell) {
                var temp = document.createElement('div');
                temp.innerHTML = data.actions_html;
                actionCell.outerHTML = temp.innerHTML;
            }
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    /* ── 4. Live Search with Debounce ── */
    function initLiveSearch() {
        var searchTimers = {};

        document.querySelectorAll('[data-live-search]').forEach(function(form) {
            var targetId = form.dataset.liveTarget;
            if (!targetId) return;

            var inputs = form.querySelectorAll('input[type="text"], input[type="search"], select');
            inputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    var key = form.id || form.name || 'default';
                    clearTimeout(searchTimers[key]);
                    searchTimers[key] = setTimeout(function() {
                        liveSearchSubmit(form, targetId);
                    }, 350);
                });
                if (input.tagName === 'SELECT') {
                    input.addEventListener('change', function() {
                        liveSearchSubmit(form, targetId);
                    });
                }
            });
        });

        // Intercept pagination links
        document.addEventListener('click', function(e) {
            var link = e.target.closest('.pagination a');
            if (!link) return;
            e.preventDefault();

            // Find the live search form by looking at target containers
            var container = link.closest('[id]');
            var targetId = null;
            var liveForm = null;

            // Walk up to find the container that matches a live-target
            while (container && !targetId) {
                var cid = container.id;
                if (cid) {
                    liveForm = document.querySelector('[data-live-target="' + cid + '"]');
                    if (liveForm) targetId = cid;
                }
                container = container.parentElement;
            }

            if (!targetId || !liveForm) return;

            var url = link.href;

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.text(); })
            .then(function(html) {
                replaceTableContent(targetId, html);
            });
        });
    }

    function liveSearchSubmit(form, targetId) {
        var params = new URLSearchParams(new FormData(form));
        var url = form.action + '?' + params.toString();

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.text(); })
        .then(function(html) {
            replaceTableContent(targetId, html);
        });
    }

    function replaceTableContent(targetId, html) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var container = document.getElementById(targetId);
        if (!container) return;

        var newContainer = doc.getElementById(targetId);
        if (newContainer) {
            container.innerHTML = newContainer.innerHTML;
            if (typeof lucide !== 'undefined') lucide.createIcons();
            initSortable();
        }
    }

    /* ── Confirm Delete via SweetAlert2 ── */
    window.confirmDelete = function(el, message) {
        if (typeof Swal === 'undefined') return confirm(message);
        var form = el.closest('form');
        Swal.fire({
            title: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e74c3c',
        }).then(function(result) {
            if (result.isConfirmed && form) form.submit();
        });
        return false;
    };

    /* ── Init on page load ── */
    document.addEventListener('DOMContentLoaded', function() {
        initSortable();
        initAjaxActions();
        initLiveSearch();
    });

})();
</script>
