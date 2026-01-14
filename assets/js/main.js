$(document).ready(function () {

    loadUsers();
    loadEmployees();

    // =====================
    // USERS
    // =====================
    $('#createUserForm').submit(function (e) {
        e.preventDefault();

        $.post('/hris/users/action.php', $(this).serialize(), function (res) {
            if (res.status === 'success') {
                loadUsers();
                $('#createUserModal').modal('hide');
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        }, 'json');
    });

    $(document).on('click', '.btn-edit-user', function () {
        $('#edit_id').val($(this).data('id'));
        $('#edit_name').val($(this).data('name'));
        $('#edit_username').val($(this).data('username'));
        $('#editUserModal').modal('show');
    });

    $('#editUserForm').submit(function (e) {
        e.preventDefault();

        $.post('/hris/users/update.php', $(this).serialize(), function (res) {
            if (res.status === 'success') {
                Swal.fire('Berhasil', res.message, 'success')
                    .then(() => location.reload());
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        }, 'json');
    });

    $(document).on('click', '.btn-delete-user', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'Hapus User?',
            text: `User ${name} akan dihapus`,
            icon: 'warning',
            showCancelButton: true
        }).then(res => {
            if (res.isConfirmed) {
                $.post('/hris/users/delete.php', { id }, function (r) {
                    if (r.status === 'success') {
                        Swal.fire('Berhasil', r.message, 'success')
                            .then(() => loadUsers());
                    }
                }, 'json');
            }
        });
    });

    // =====================
    // EMPLOYEES
    // =====================
    $(document).on('click', '.btn-edit-employee', function () {
        $('#edit_id').val($(this).data('id'));
        $('#edit_name').val($(this).data('name'));
        $('#editEmployeeModal').modal('show');
    });

    $('#editEmployeeForm').submit(function (e) {
        e.preventDefault();

        $.post('/hris/employees/action.php', $(this).serialize(), function (res) {
            if (res.status === 'success') {
                Swal.fire('Berhasil', res.message, 'success')
                    .then(() => location.reload());
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        }, 'json');
    });

    $(document).on('click', '.btn-delete-employee', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'Hapus Employee?',
            text: `Employee ${name} akan dihapus`,
            icon: 'warning',
            showCancelButton: true
        }).then(res => {
            if (res.isConfirmed) {
                $.post('/hris/employees/delete.php', { id }, function (r) {
                    if (r.status === 'success') {
                        Swal.fire('Berhasil', r.message, 'success')
                            .then(() => loadEmployees());
                    }
                }, 'json');
            }
        });
    });

});


