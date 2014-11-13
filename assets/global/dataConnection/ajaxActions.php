<?php

require_once( dirname(dirname(dirname(dirname(__FILE__)))) . '/load.php' );
include_once('session_functions.php');
sec_session_start();
if (login_check($db) == false) {
    $redirect = FRONTEND_HOME . '/login.php';
    header("Location: " . $redirect);
}
// get the action that needs to be performed

$action = $_POST['action'];

//queries
require_once(ABSPATH . "assets/global/dataConnection/queries.php");

//get product by ID
if ($action == "updatePresentation") {
    updatePresentation($_POST['presentationID'], $_POST['name'], $_POST['description'], $_POST['status'], $_POST['creatorID'], $_POST['shareStatus']);
    updatePresentationCategories($_POST['presentationID'], $_POST['categories']);
    exit();
}
else if ($action == "addPresentationReview") {
    addPresentationReview($_POST['presentationID'], $_POST['title'], $_POST['review'], $_POST['score'], $_POST['userID']);
    exit();
}
 else if ($action == "addFeedback") {
    addFeedback($_POST['feedback'], $_POST['userID']);
    exit();
}
//Remove product image
else if ($action == "deletePresentation") {
    deletePresentation($_POST['presentationID']);
    exit(true);
}
//Rem
// Set all notifications on read
else if ($action == "readNotifications") {
    readNotifications($_SESSION['user_id']);
    exit(true);
}
else if($action == 'updateStudent'){
    $result = addStudent($_POST['firstName'],$_POST['name'],$_POST['classID']);
    exit(json_encode($result));
}
else if($action == 'removeStudent'){
    disableStudent($_POST['userID']);
}
else if($action == 'goToSlide'){
    goToSlide($_POST['slide']);
}
// no valid method was found
else {
    exit("No valid method found");
}
