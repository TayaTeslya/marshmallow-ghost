<?php
session_start();
include_once 'includeTemplate.php';
include_once 'loadConfig.php';
include_once 'database.php';
include_once 'authorization.php';

if (isAuthorized()) {
    rewriteCookie(currentUser()['email']);
}

if (isset($_GET['logout']) && $_GET['logout'] === 'yes') {
    logout();
}
