<?php
session_start();
// no mock functions
require 'c:/laragon/www/sma5/php/admin/db.php';
['admin_id'] = 1;
['role'] = 'admin';
ob_start();
include 'c:/laragon/www/sma5/php/admin/dashboard.php';
 = ob_get_clean();
if (preg_match('/(Fatal error|Parse error|Warning|Notice):.*/i', , )) {
    echo [0];
} else {
    echo 'NO PHP ERRORS';
}
