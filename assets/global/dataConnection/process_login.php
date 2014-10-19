<?php
//include_once 'load.php';
include_once 'session_functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // The hashed password.

    if (login($username, $password) == true) {
        // Login success
//        $path = 'http://' . $_SERVER["HTTP_HOST"] . '/indepen/admin/index.php';
//       header('Location: ' . $path);
        //exit(0);
        echo '{ "message": "loginOK"}';
       global $db;
        $db->logEvent('UserLoggedIn', 'User ' . $_SESSION['user_id'] .' is logged in');
    } else {
        // Login failed
        echo '{ "message": "Voer het wachtwoord en gebruikersnaam opnieuw in" }';

    }
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}

