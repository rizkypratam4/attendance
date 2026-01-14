<?php
$USE_LAYOUT = true;
$BASE_URL = '/hris/';

switch ($_SERVER["SCRIPT_NAME"]) {

    case "/hris/dashboard/index.php":
        $CURRENT_PAGE = "Dashboard";
        $PAGE_TITLE = "Dashboard";
        break;

    case "/hris/attendance/index.php":
        $CURRENT_PAGE = "Attendance";
        $PAGE_TITLE = "Attendance";
        break;

    case "/hris/users/index.php":
        $CURRENT_PAGE = "User";
        $PAGE_TITLE = "User Management";
        break;

    case "/hris/employees/index.php":
        $CURRENT_PAGE = "Employees";
        $PAGE_TITLE = "Employees";
        break;

    default:
        $CURRENT_PAGE = "Index";
        $PAGE_TITLE = "Sign in";
        $USE_LAYOUT = false;
}
