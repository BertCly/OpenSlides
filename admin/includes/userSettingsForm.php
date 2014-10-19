<?php
/**
 * Created by PhpStorm.
 * User: Bert
 * Date: 13/08/14
 * Time: 21:38
 */

require_once('../../load.php' );
include_once(ABSPATH . 'assets/global/dataConnection/session_functions.php');
require_once("../../assets/global/dataConnection/queries.php");
sec_session_start();
if (login_check($db) == false) {
    header ("Location: login.php");
}

$firstName = $_POST["firstName"];
$name = $_POST["name"];
$city = $_POST["city"];
$about = $_POST["about"];

global $db;
$db->update(
    'tblUsers',
    array(
        'FirstName' => $firstName,	// string
        'Name' => $name
    ),
    array( 'ID' => $_SESSION['user_id']));
if($city != null){
    if(getMetaUser($_SESSION['user_id'], 'city') != null)
    $db->update(
        'tblUserMeta',
        array(
            'MetaValue' => $city
        ),
        array( 'UserID' => $_SESSION['user_id'],
               'MetaKey' => 'city'));
    else $success = $db->insert(
        'tblUserMeta', array(
            'MetaKey' => 'city',
            'UserID' => $_SESSION['user_id'],
            'MetaValue' => $city
        )
    );
}
if($about != null){
    if(getMetaUser($_SESSION['user_id'], 'about') != null)
        $db->update(
            'tblUserMeta',
            array(
                'MetaValue' => $about
            ),
            array('UserID' => $_SESSION['user_id'],
                  'MetaKey' => 'about'));
    else $success = $db->insert(
            'tblUserMeta', array(
            'MetaKey' => 'about',
            'UserID' => $_SESSION['user_id'],
            'MetaValue' => $about
            )
        );
}
$db->logEvent('ProfileEdited', 'Profile of ' . $firstName . ' ' . $name . ' edited', $_SESSION['user_id']);

// MySQL stuff goes here
header("Location: ../profile.php#tab_1_3");
exit;