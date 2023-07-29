<?php
session_start();
include_once 'includeTemplate.php';
include_once 'loadConfig.php';
include_once 'database.php';
include_once 'authorization.php';

// if (isAuthorized()) {
//     setCookieEmail(currentUser()['email']);
// }

if (isset($_GET['logout']) && $_GET['logout'] === 'yes') {
    // if (isset($_SERVER['HTTP_COOKIE'])) {
    //     $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    //     foreach($cookies as $cookie) {
    //         $parts = explode('=', $cookie);
    //         $name = trim($parts[0]);
    //         setcookie($name, '', time()-1000);
    //         setcookie($name, '', time()-1000, '/');
    //     }
    // }
    logout();
}
