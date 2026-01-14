<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo position-relative d-flex align-items-center justify-content-center">
      <a href="/hris/dashboard/" class="text-nowrap logo-img">
        <img src="<?= $BASE_URL ?>assets/images/logos/logo.png" width="140" />
      </a>

      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer position-absolute end-0 me-3"
        id="sidebarCollapse">
        <i class="ti ti-x fs-6"></i>
      </div>
    </div>

    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Main menu</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?php if ($CURRENT_PAGE == "Dashboard") { ?>active<?php } ?>" href="/hris/dashboard"
            aria-expanded="false">
            <i class="ti ti-atom"></i>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?php if ($CURRENT_PAGE == "User") { ?>active<?php } ?> justify-content-between"
            href="/hris/users/" aria-expanded="false">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex">
                <i class="ti ti-users"></i>
              </span>
              <span class="hide-menu">User Management</span>
            </div>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link <?php if ($CURRENT_PAGE == "Employee") { ?>active<?php } ?> justify-content-between"
            href="/hris/employees/" aria-expanded="false">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex">
                <i class="ti ti-building"></i>
              </span>
              <span class="hide-menu">Employees</span>
            </div>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link <?php if ($CURRENT_PAGE == "Attendance") { ?>active<?php } ?> justify-content-between"
            href="/hris/attendance/" aria-expanded="false">
            <div class="d-flex align-items-center gap-3">
              <span class="d-flex">
                <i class="ti ti-calendar"></i>
              </span>
              <span class="hide-menu">Attendances</span>
            </div>
          </a>
        </li>


      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>