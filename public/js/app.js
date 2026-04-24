let dark = localStorage.getItem('theme') !== 'light';

// Terapkan theme saat halaman load
(function () {
    const saved = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
    dark = saved === 'dark';
})();

function toggleTheme() {
    dark = !dark;
    const theme = dark ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    const pill = document.getElementById('tpill');
    if (pill) pill.classList.toggle('light', !dark);
    document.dispatchEvent(new Event('themeChange'));
}

function toggleDD(e) {
    e.stopPropagation();
    document.getElementById('pdd').classList.toggle('open');
}
document.addEventListener('click', () => {
    const pdd = document.getElementById('pdd');
    if (pdd) pdd.classList.remove('open');
});

function openM(id) {
    const pdd = document.getElementById('pdd');
    if (pdd) pdd.classList.remove('open');
    document.getElementById(id).classList.add('open');
}
function closeM(id) {
    document.getElementById(id).classList.remove('open');
}
function closeOut(e, id) {
    if (e.target === document.getElementById(id)) closeM(id);
}

function togPwd(id, btn) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    btn.style.opacity = inp.type === 'text' ? '1' : '0.5';
}

function toggleSubmenu(btn) {
    const group = btn.closest('.menu-group');
    if (!group) return;

    const sub = group.querySelector('.submenu');
    const caret = group.querySelector('.caretIcon');
    if (!sub) return;

    const isOpen = sub.style.maxHeight && sub.style.maxHeight !== '0px' && sub.style.maxHeight !== '0';

    if (isOpen) {
        sub.style.maxHeight = '0';
        if (caret) caret.style.transform = 'rotate(0deg)';
    } else {
        sub.style.maxHeight = sub.scrollHeight + 'px';
        if (caret) caret.style.transform = 'rotate(180deg)';
    }
}

let coll = false;
const sb = document.getElementById('sidebar');

function toggleDsk() {
    coll = !coll;
    if (!sb) return;
    sb.classList.toggle('collapsed', coll);

    const tIco = document.getElementById('tIco');
    if (tIco) {
        tIco.innerHTML = coll
            ? '<path d="M9 6l6 6-6 6"/>'
            : '<path d="M15 6l-6 6 6 6"/>';
    }

    if (coll) {
        document.querySelectorAll('.menu-group .submenu').forEach(sub => sub.style.maxHeight = '0');
        document.querySelectorAll('.menu-group .caretIcon').forEach(c => c.style.transform = 'rotate(0deg)');
    } else {
        document.querySelectorAll('.menu-group .submenu').forEach(sub => {
            if (sub.style.maxHeight !== '0px' && sub.style.maxHeight !== '0') {
                sub.style.maxHeight = sub.scrollHeight + 'px';
                const caret = sub.closest('.menu-group')?.querySelector('.caretIcon');
                if (caret) caret.style.transform = 'rotate(180deg)';
            }
        });
    }

}

function openMob() {
    if (sb) sb.classList.add('mob-open');
    const ov = document.getElementById('overlay');
    if (ov) ov.classList.add('active');
}
function closeMob() {
    if (sb) sb.classList.remove('mob-open');
    const ov = document.getElementById('overlay');
    if (ov) ov.classList.remove('active');
}

window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        closeMob();
    }
});

let _activeTrigger = null;

function initUserDropdown() {
    if (document.getElementById('globalActDD')) return;

    const dd = document.createElement('div');
    dd.id = 'globalActDD';
    dd.innerHTML = `
        <button class="act-dd-item" id="ddEditBtn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Edit
        </button>
        <button class="act-dd-item act-dd-danger" id="ddDeleteBtn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
            </svg>
            Delete
        </button>
    `;
    document.body.appendChild(dd);

    document.getElementById('ddEditBtn').addEventListener('click', _onEditClick);
    document.getElementById('ddDeleteBtn').addEventListener('click', _onDeleteClick);
    document.addEventListener('click', _onOutsideClick);
    window.addEventListener('scroll', closeDD, true);
}

function _onEditClick() {
    if (!_activeTrigger) return;
    closeDD();
    if (_activeTrigger.dataset.entity === 'employee') {
        openEditEmployee(_activeTrigger);
    } else {
        openEditUser(
            _activeTrigger.dataset.name,
            _activeTrigger.dataset.email,
            _activeTrigger.dataset.role,
            _activeTrigger.dataset.updateRoute
        );
    }
}

function openAddEmployee() {
    document.getElementById('addEmployeeForm').reset();
    document.getElementById('addBranch').selectedIndex = 0;
    document.getElementById('addDepartment').selectedIndex = 0;
    document.getElementById('addLocation').selectedIndex = 0;
    document.getElementById('addIsActive').value = '1';
    openM('mAddEmployee');
}

function openEditEmployee(trigger) {
    document.getElementById('editEmployeeName').value = trigger.dataset.name || '';
    document.getElementById('editEmployeeNIK').value = trigger.dataset.nik || '';
    document.getElementById('editMachineBarcode').value = trigger.dataset.machineBarcode || '';

    const branchSel = document.getElementById('editBranch');
    Array.from(branchSel.options).forEach(opt => {
        opt.selected = opt.value === (trigger.dataset.branchId || '');
    });

    const deptSel = document.getElementById('editDepartment');
    Array.from(deptSel.options).forEach(opt => {
        opt.selected = opt.value === (trigger.dataset.departmentId || '');
    });

    document.getElementById('editPosition').value = trigger.dataset.position || '';

    const locSel = document.getElementById('editLocation');
    Array.from(locSel.options).forEach(opt => {
        opt.selected = opt.value === (trigger.dataset.locationId || '');
    });

    document.getElementById('editTitle').value = trigger.dataset.title || '';
    document.getElementById('editEmployeeStatus').value = trigger.dataset.employeeStatus || '';

    const activeSel = document.getElementById('editIsActive');
    Array.from(activeSel.options).forEach(opt => {
        opt.selected = opt.value === (trigger.dataset.isActive ?? '1');
    });

    document.getElementById('editEmployeeForm').action = trigger.dataset.updateRoute;
    openM('mUpdateEmployee');
}

function _onDeleteClick() {
    if (!_activeTrigger) return;
    closeDD();
    if (_activeTrigger.dataset.entity === 'employee') {
        openDeleteEmployee(
            _activeTrigger.dataset.name,
            _activeTrigger.dataset.deleteId
        );
    } else {
        openDeleteUser(
            _activeTrigger.dataset.name,
            _activeTrigger.dataset.deleteId
        );
    }
}

function openDeleteEmployee(name, id) {
    Swal.fire({
        title: `Delete ${name}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}

function _onOutsideClick(e) {
    if (!e.target.closest('#globalActDD') && !e.target.closest('.act-trigger')) {
        closeDD();
    }
}

function bindTriggers() {
    document.querySelectorAll('.act-trigger').forEach(btn => {
        btn.replaceWith(btn.cloneNode(true));
    });

    document.querySelectorAll('.act-trigger').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const dd = document.getElementById('globalActDD');

            if (_activeTrigger === btn && dd.classList.contains('show')) {
                closeDD();
                return;
            }

            _activeTrigger = btn;

            const rect    = btn.getBoundingClientRect();
            const ddWidth = 152;
            let left = rect.right - ddWidth;
            let top  = rect.bottom + 6;

            if (left < 8) left = 8;
            if (top + 100 > window.innerHeight) top = rect.top - 90;

            dd.style.left = left + 'px';
            dd.style.top  = top  + 'px';
            dd.classList.add('show');
        });
    });
}

function closeDD() {
    document.getElementById('globalActDD')?.classList.remove('show');
}

function openEditUser(name, email, role, updateRoute) {
    const parts = name.split(' ');
    document.getElementById('editFirstName').value = parts[0] || '';
    document.getElementById('editLastName').value  = parts.slice(1).join(' ') || '';
    document.getElementById('editEmail').value     = email;

    const sel = document.getElementById('editRole');
    Array.from(sel.options).forEach(opt => {
        opt.selected = opt.value === role.toLowerCase();
    });

    document.getElementById('editUserForm').action = updateRoute;
    openM('mUpdateUser');
}

// shift schedule helpers
function openAddShiftSchedule() {
    const form = document.getElementById('addShiftScheduleForm');
    if (form) form.reset();
    openM('mAddShiftSchedule');
}

function openEditShiftSchedule(trigger) {
    const form = document.getElementById('editShiftScheduleForm');
    form.action = trigger.dataset.updateRoute;

    // Select by ID/form context instead of index
    const form_ = document.querySelector('#mEditShiftSchedule form');
    
    // Shift Code
    const codeSelect = form_.querySelector('select[name="shift_code_id"]');
    if (codeSelect) {
        codeSelect.value = trigger.dataset.shiftCodeId || '';
    }

    // Day Type
    const daySelect = form_.querySelector('select[name="day_type"]');
    if (daySelect) {
        daySelect.value = trigger.dataset.dayType || '';
    }

    // Schedule Code
    const schedCode = form_.querySelector('input[name="schedule_code"]');
    if (schedCode) {
        schedCode.value = trigger.dataset.scheduleCode || '';
    }

    // Times
    const startTime = form_.querySelector('input[name="start_time"]');
    if (startTime) {
        startTime.value = trigger.dataset.startTime || '';
    }

    const endTime = form_.querySelector('input[name="end_time"]');
    if (endTime) {
        endTime.value = trigger.dataset.endTime || '';
    }

    // Checkboxes - use type selector to get checkbox, not hidden field
    const isDayOff = form_.querySelector('input[type="checkbox"][name="is_day_off"]');
    if (isDayOff) {
        isDayOff.checked = trigger.dataset.isDayOff === '1';
    }

    const isOvernight = form_.querySelector('input[type="checkbox"][name="is_overnight"]');
    if (isOvernight) {
        isOvernight.checked = trigger.dataset.isOvernight === '1';
    }

    openM('mEditShiftSchedule');
}

function openDeleteShiftSchedule(name, id) {
    Swal.fire({
        title: `Delete ${name}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}

function openDeleteUser(name, id) {
    Swal.fire({
        title: `Delete ${name}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initUserDropdown();
    bindTriggers();
    // Sync pill button state dengan theme yang tersimpan
    const pill = document.getElementById('tpill');
    if (pill) pill.classList.toggle('light', !dark);
});

function toggleFilterPanel() {
    const panel = document.getElementById('filterPanel');
    panel.classList.toggle('hidden');

    if (!panel.classList.contains('hidden')) {
        setTimeout(() => {
            document.addEventListener('click', closeFilterOnOutside);
        }, 0);
    }
}

function closeFilterOnOutside(e) {
    const panel  = document.getElementById('filterPanel');
    const btn    = document.getElementById('filterToggleBtn');
    if (!panel.contains(e.target) && !btn.contains(e.target)) {
        panel.classList.add('hidden');
        document.removeEventListener('click', closeFilterOnOutside);
    }
}

const _activeChips = {};

function toggleChip(el, inputName, value) {
    if (!_activeChips[inputName]) {
        _activeChips[inputName] = new Set();
    }

    const isActive = _activeChips[inputName].has(value);

    if (inputName === 'status') {
        _activeChips[inputName].clear();
        document.querySelectorAll(`[onclick*="'status'"]`).forEach(c => _deactivateChip(c));
    }

    if (isActive) {
        _activeChips[inputName].delete(value);
        _deactivateChip(el);
    } else {
        _activeChips[inputName].add(value);
        _activateChip(el);
    }

    _updateHiddenInputs();
    _updateBadge();
}

function _activateChip(el) {
    el.style.background    = 'rgba(124,58,237,.25)';
    el.style.color         = '#a78bfa';
    el.style.borderColor   = 'rgba(124,58,237,.5)';
    el.dataset.active      = 'true';
}

function _deactivateChip(el) {
    el.style.background    = 'var(--bg-ghost)';
    el.style.color         = 'var(--text-2)';
    el.style.borderColor   = 'var(--border)';
    el.dataset.active      = 'false';
}

function _updateHiddenInputs() {
    const container = document.getElementById('hiddenInputs');
    const form      = document.getElementById('filterForm');

    form.querySelectorAll('input[type=hidden]').forEach(i => i.remove());

    Object.entries(_activeChips).forEach(([name, values]) => {
        values.forEach(val => {
            const input   = document.createElement('input');
            input.type    = 'hidden';
            input.name    = name;
            input.value   = val;
            form.appendChild(input);
        });
    });
}

function clearSearch() {
    const input = document.getElementById('filterSearch');
    const btn   = document.getElementById('clearSearch');
    input.value = '';
    btn.classList.add('hidden');
    input.focus();
    _updateBadge();
}

document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('filterSearch');
    if (!input) return;

    input.addEventListener('input', () => {
        const btn = document.getElementById('clearSearch');
        btn.classList.toggle('hidden', input.value.length === 0);
        _updateBadge();
    });
});


function _updateBadge() {
    const badge      = document.getElementById('filterBadge');
    const searchVal  = document.getElementById('filterSearch')?.value || '';
    let count = 0;

    Object.values(_activeChips).forEach(set => count += set.size);
    if (searchVal.length > 0) count++; 

    if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

function resetFilters() {
    Object.keys(_activeChips).forEach(k => _activeChips[k].clear());
    document.querySelectorAll('.filter-chip').forEach(c => _deactivateChip(c));

    const searchInput = document.getElementById('filterSearch');
    if (searchInput) searchInput.value = '';
    document.getElementById('clearSearch')?.classList.add('hidden');

    _updateHiddenInputs();
    _updateBadge();

    window.location.href = document.getElementById('filterForm').action;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.filter-chip[data-active="true"]').forEach(chip => {
        const match = chip.getAttribute('onclick').match(/'([^']+)',\s*'([^']+)'/);
        if (match) {
            const [, inputName, value] = match;
            if (!_activeChips[inputName]) _activeChips[inputName] = new Set();
            _activeChips[inputName].add(value);
            _activateChip(chip);
        }
    });
    _updateHiddenInputs();
    _updateBadge();
});

function openEditLocation(id, name, description, address, latitude, longitude, isActive) {
    document.getElementById('editLocationForm').action = `/locations/${id}`;

    document.getElementById('editLocationName').value = name;
    document.getElementById('editLocationDescription').value = description;
    document.getElementById('editLocationAddress').value = address;
    document.getElementById('editLocationLatitude').value = latitude;
    document.getElementById('editLocationLongitude').value = longitude;
    document.getElementById('editLocationIsActive').value = isActive;

    openM('mEditLocation');
}

function openDeleteLocation(name, id) {
    Swal.fire({
        title: `Delete ${name}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-location-${id}`).submit();
        }
    });
}

function openEditDepartment(id, name, subtitle, head_employee_id) {
    document.getElementById('editDepartmentForm').action = `/departments/${id}`;
    document.getElementById('editDepartmentName').value = name;
    document.getElementById('editDepartmentSubtitle').value = subtitle;
    document.getElementById('editDepartmentHead').value = head_employee_id;

    openM('mEditDepartment');
}

function openDeleteDepartment(name, id) {
    Swal.fire({
        title: `Delete ${name}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-department-${id}`).submit();
        }
    });
}


function openEditBranch(id, name, is_active) {
    document.getElementById('editBranchForm').action = `/branches/${id}`;
    document.getElementById('editBranchName').value = name;
    document.getElementById('editBranchIsActive').value = is_active;

    openM('mEditBranch');
}

function openDeleteBranch(name, id) {
    Swal.fire({
        title: `Delete ${name}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-branch-${id}`).submit();
        }
    });
}

function openEditShiftGroup(id, name, description) {
    document.getElementById('editShiftGroupForm').action = `/shift_groups/${id}`;
    document.getElementById('editShiftGroupName').value = name;
    document.getElementById('editShiftGroupDescription').value = description;

    openM('mEditShiftGroup');
}

// ── ShiftCode modal helpers ──
function openEditShiftCode(id, code, shiftId, hasIdt) {
    const form = document.getElementById('editShiftCodeForm');
    if (!form) return;
    form.action = `/shift_codes/${id}`;
    const codeInput = form.querySelector('input[name="code"]');
    const shiftSelect = form.querySelector('select[name="shift_id"]');
    const idtSelect = form.querySelector('select[name="has_idt"]');
    if (codeInput) codeInput.value = code;
    if (shiftSelect) shiftSelect.value = shiftId;
    if (idtSelect) idtSelect.value = hasIdt;

    openM('mEditShiftCode');
}

function openDeleteShiftCode(code, id) {
    Swal.fire({
        title: `Delete ${code}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-shift-code-${id}`).submit();
        }
    });
}

function openDeleteShiftGroup(name, id) {
    Swal.fire({
        title: `Delete ${name}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-shift-group-${id}`).submit();
        }
    });
}

// ── Search + Filter ──
document.getElementById('shiftSearch').addEventListener('input', filterShifts);

function filterShifts() {
    const search = document.getElementById('shiftSearch').value.toLowerCase();
    const status = document.getElementById('statusFilter').value.toLowerCase();

    document.querySelectorAll('.shift-row').forEach(row => {
        const code    = row.dataset.code.toLowerCase();
        const name    = row.dataset.name.toLowerCase();
        const rowStat = row.dataset.status.toLowerCase();

        const matchSearch = !search || code.includes(search) || name.includes(search);
        const matchStatus = !status || rowStat === status;

        row.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
}

// ── Sort AZ ──
let _sortAsc = true;
function toggleSort(btn) {
    _sortAsc = !_sortAsc;
    btn.textContent = _sortAsc ? 'AZ' : 'ZA';
    btn.style.background = _sortAsc ? 'var(--bg-ghost)' : 'rgba(124,58,237,.2)';
    btn.style.color = _sortAsc ? 'var(--text-2)' : '#a78bfa';

    const tbody = document.querySelector('#shiftTable tbody');
    const rows  = Array.from(tbody.querySelectorAll('.shift-row'));
    rows.sort((a, b) => {
        const na = a.dataset.name, nb = b.dataset.name;
        return _sortAsc ? na.localeCompare(nb) : nb.localeCompare(na);
    });
    rows.forEach(r => tbody.appendChild(r));
}

// ── Color picker (Add modal) ──
function selectColor(el, color) {
    document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedColor').value = color;
}
// ── Shift schedule search + filter ──
document.getElementById('scheduleSearch')?.addEventListener('input', filterSchedules);

function filterSchedules() {
    const search = document.getElementById('scheduleSearch').value.toLowerCase();
    const dayType = document.getElementById('dayTypeFilter').value.toLowerCase();

    document.querySelectorAll('.schedule-row').forEach(row => {
        const code    = row.dataset.scheduleCode.toLowerCase();
        const scode   = row.dataset.shiftCodeName.toLowerCase();
        const dtype   = row.dataset.dayType.toLowerCase();

        const matchSearch = !search || code.includes(search) || scode.includes(search);
        const matchDay    = !dayType || dtype === dayType;

        row.style.display = (matchSearch && matchDay) ? '' : 'none';
    });
}

// ── Sort schedules by shift code name ┎
let _sortSchedAsc = true;
function toggleSortSchedules(btn) {
    _sortSchedAsc = !_sortSchedAsc;
    btn.textContent = _sortSchedAsc ? 'AZ' : 'ZA';
    btn.style.background = _sortSchedAsc ? 'var(--bg-ghost)' : 'rgba(124,58,237,.2)';
    btn.style.color = _sortSchedAsc ? 'var(--text-2)' : '#a78bfa';

    const tbody = document.querySelector('#scheduleTable tbody');
    const rows  = Array.from(tbody.querySelectorAll('.schedule-row'));
    rows.sort((a, b) => {
        const na = a.dataset.shiftCodeName, nb = b.dataset.shiftCodeName;
        return _sortSchedAsc ? na.localeCompare(nb) : nb.localeCompare(na);
    });
    rows.forEach(r => tbody.appendChild(r));
}
// ── Color picker (Edit modal) ──
function selectEditColor(el, color) {
    document.querySelectorAll('.edit-color-dot').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');
}


function openDeleteShiftCode(name, id) {
    Swal.fire({
        title: `Delete ${name}?`,
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        background: '#1e1b2e',
        color: '#e2e8f0',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-shift-code-${id}`).submit();
        }
    });
}
