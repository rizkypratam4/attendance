$(document).ready(function () {
  if (typeof loadUsers === "function") loadUsers();
  if (typeof loadEmployees === "function") loadEmployees();

  function loadUsers() {
    $("#userTableBody").load("/hris/users/list.php");
  }
  function loadEmployees() {
    $("#employeeTableBody").load("/hris/employees/list.php");
  }

  $(document).on("submit", "#createUserForm", function (e) {
    e.preventDefault();

    $.post(
      "/hris/users/action.php",
      $(this).serialize(),
      function (res) {
        if (res.status === "success") {
          loadUsers();
          $("#createUserModal").modal("hide");
        } else {
          Swal.fire("Gagal", res.message, "error");
        }
      },
      "json",
    );
  });

  $(document).on("click", ".btn-edit-user", function () {
    $("#edit_id").val($(this).data("id"));
    $("#edit_name").val($(this).data("name"));
    $("#edit_username").val($(this).data("username"));
    $("#editUserModal").modal("show");
  });

  $(document).on("submit", "#editUserForm", function (e) {
    e.preventDefault();

    $.post(
      "/hris/users/update.php",
      $(this).serialize(),
      function (res) {
        if (res.status === "success") {
          loadUsers();
          Swal.fire("Berhasil", res.message, "success").then(() =>
            location.reload(),
          );
        } else {
          Swal.fire("Gagal", res.message, "error");
        }
      },
      "json",
    );
  });

  $(document).on("click", ".btn-delete-user", function () {
    const id = $(this).data("id");
    const name = $(this).data("name");

    Swal.fire({
      title: "Hapus User?",
      text: `User ${name} akan dihapus`,
      icon: "warning",
      showCancelButton: true,
    }).then((res) => {
      if (res.isConfirmed) {
        $.post(
          "/hris/users/delete.php",
          { id },
          function (r) {
            if (r.status === "success") {
              Swal.fire("Berhasil", r.message, "success").then(() =>
                loadUsers(),
              );
            }
          },
          "json",
        );
      }
    });
  });

  $(document).on("click", ".btn-edit-employee", function () {
    $("#edit_id").val($(this).data("id"));
    $("#edit_name").val($(this).data("name"));
    $("#edit_nik").val($(this).data("nik"));
    $("#editEmployeeModal").modal("show");
  });

  $(document).on("submit", "#editEmployeeForm", function (e) {
    e.preventDefault();

    $.post(
      "/hris/employees/action.php",
      $(this).serialize(),
      function (res) {
        if (res.status === "success") {
          loadEmployees();
          Swal.fire("Berhasil", res.message, "success").then(() =>
            location.reload(),
          );
        } else {
          Swal.fire("Gagal", res.message, "error");
        }
      },
      "json",
    );
  });

  $(document).on("click", ".btn-delete-employee", function () {
    const id = $(this).data("id");
    const name = $(this).data("name");

    Swal.fire({
      title: "Hapus Employee?",
      text: `Employee ${name} akan dihapus`,
      icon: "warning",
      showCancelButton: true,
    }).then((res) => {
      if (res.isConfirmed) {
        $.post(
          "/hris/employees/delete.php",
          { id },
          function (r) {
            if (r.status === "success") {
              Swal.fire("Berhasil", r.message, "success").then(() =>
                loadEmployees(),
              );
            }
          },
          "json",
        );
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("todayChart");

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Hadir", "Terlambat", "Tidak Hadir"],
      datasets: [
        {
          data: [110, 8, 7],
          backgroundColor: ["#1e4db7", "#ffc107", "#dc3545"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "bottom",
        },
      },
    },
  });
});

document.addEventListener("DOMContentLoaded", function () {
    const trendCtx = document.getElementById('trendChart').getContext('2d');

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Hadir',
                data: [20, 40, 60, 50, 108, 110, 112],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

});

document.addEventListener("DOMContentLoaded", function () {
  const deptCtx = document.getElementById('deptLateChart').getContext('2d');

    new Chart(deptCtx, {
        type: 'bar',
        data: {
            labels: ['Produksi', 'Gudang', 'HR', 'IT', 'Finance'],
            datasets: [{
                label: 'Jumlah Keterlambatan',
                data: [42, 31, 18, 9, 6],
                backgroundColor: '#dc3545',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 5 }
                }
            }
        }
    });
});