<?php
    $error_msg = "";
    require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/load.php');
    global $db;

    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
        // Sanitize and validate the data passed in
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Not a valid email
            $error_msg .= 'Gelieve een geldig Emailadres in te geven';
        }
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        //TODO: check strenth password &  hash
    //    if (strlen($password) != 128) {
    //        // The hashed pwd should be 128 characters long.
    //        // If it's not, something really odd has happened
    //        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    //    }

        // Username validity and password validity have been checked client side.
        // This should should be adequate as nobody gains any advantage from
        // breaking these rules.
        //

        $prep_stmt = "SELECT TOP 1 ID FROM tblUsers WHERE Email = '" . $email . "'";
        $stmt = $db->get_results($prep_stmt);

        // check existing email
        if (isset($db->num_rows)) {
            if ($db->num_rows == 1) {
                // A user with this email address already exists
                $error_msg .= 'Er bestaat reeds een gebruiker met dit email. <br/> ';
            }
        } else {
            $error_msg .= 'Database error/';
        }

        // check existing username
        $prep_stmt = "SELECT TOP 1 ID FROM tblUsers WHERE Username = '" . $username . "'";
        $stmt = $db->get_results($prep_stmt);

        if (isset($db->num_rows)) {
            if ($db->num_rows == 1) {
                // A user with this username already exists
                $error_msg .= 'Er bestaat reeds een gebruiker met deze username';
            }
        } else {
            $error_msg .= 'Database error line 55 /';
        }

        // TODO:
        // We'll also have to account for the situation where the user doesn't have
        // rights to do registration, by checking what type of user is attempting to
        // perform the operation.

        if (empty($error_msg)) {
            // Create a random salt
            //$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
            $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

            // Create salted password
            $password2 = hash('sha512', $password . $random_salt);

            // Insert the new user into the database
            if ($insert_stmt = $db->insert('tblUsers', array('username' => $username, 'email' => $email, 'password' => $password2, 'FirstName'  => $firstname, 'Name' => $lastname, 'RoleID' => 'US', 'salt' => $random_salt, 'Creationdate' => date('Y-m-d H:i:s')))) {
                $db->insert('tblUserMeta', array('UserID' =>$insert_stmt, 'MetaKey' => 'city', 'MetaValue' => $city));
                include_once 'session_functions.php';
                sec_session_start();
                $loggedin = login($username, $password);
                echo '{ "message": "registerOK" }';
                $db->logEvent('UserRegistered', 'User ' . $username .' is registered');
            }

        }
        else{
            echo '{ "message": "'.$error_msg.'" }';
        }


    }
