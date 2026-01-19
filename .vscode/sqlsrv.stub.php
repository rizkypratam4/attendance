<?php
function sqlsrv_connect($serverName, $connectionInfo) {}
function sqlsrv_errors() {}
function sqlsrv_begin_transaction($conn) {}
function sqlsrv_commit($conn) {}
function sqlsrv_rollback($conn) {}
function sqlsrv_query($conn, $sql, $params = [], $options = []) {}
function sqlsrv_fetch_array($stmt, $fetchType = null) {}
function sqlsrv_close($conn) {}
function sqlsrv_prepare($conn, $sql, $params = []) {}
function sqlsrv_execute($stmt) {}
function sqlsrv_free_stmt($stmt) {}SQLSRV_PARAM_IN;
define('SQLSRV_PARAM_IN', 1);
define('SQLSRV_FETCH_ASSOC', 2);