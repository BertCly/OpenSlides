<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/load.php');

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = FALSE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
//        echo '{ "message": "Could not initiate a safe session" }';
//        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    session_regenerate_id();    // regenerated the session, delete the old one.
}
function login($username, $password) {
    global $db;

    // Using prepared statements means that SQL injection is not possible.
    if ($stmt = $db->get_row("SELECT TOP 1 ID, Email, Password, Salt, RoleID
        FROM tblUsers
       WHERE Username = '". $username ."'")) {

        // get variables from result.
        $user_id = $stmt->ID;
        $email = $stmt->Email;
        $db_password = $stmt->Password;
        $salt = $stmt->Salt;
        $roleID = $stmt->RoleID;


        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        if ($db->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts

            if (checkbrute($user_id, $db) == true) {
                // Account is locked
                // Send an email to user saying their account is locked
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/",
                        "",
                        $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['roleID'] = $roleID;
                    $_SESSION['login_string'] = hash('sha512',
                        $password . $user_browser);
                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $db->query("INSERT INTO tblLoginAttempts(UserID, Time)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            // No user exists.
            return false;
        }
    }
}

function checkbrute($user_id, $dbPar) {
    // Get timestamp of current time
    $now = time();

    // All login attempts are counted from the past 2 hours.
    $valid_attempts = $now - (2 * 60 * 60);

    if ($attempts = $dbPar->get_results("SELECT time
                             FROM tblLoginAttempts
                             WHERE UserID = '$user_id'
                            AND Time > '$valid_attempts'")) {

        // If there have been more than 5 failed logins
        if ($dbPar->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function login_check($dbpar) {
    // Check if all session variables are set
    if (isset($_SESSION['user_id'],
    $_SESSION['username'],
    $_SESSION['login_string'])) {

        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $dbpar->get_row("SELECT TOP 1 Password
                                      FROM tblUsers
                                      WHERE id = '$user_id'")) {

            if ($dbpar->num_rows == 1) {
                // If the user exists get variables from result.
                $password = $stmt->Password;
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string) {
                    // Logged In!!!!
                    return true;
                } else {
                    // Not logged in
                    return false;
                }
            } else {
                // Not logged in
                return false;
            }
        } else {
            // Not logged in
            return false;
        }
    } else {
        // Not logged in
        return false;
    }
}
function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}?>