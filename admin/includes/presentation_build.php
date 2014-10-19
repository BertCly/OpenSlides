<?php

require_once("../assets/global/dataConnection/queries.php");


if(!isset($_GET['presentationID'])) {
    $presentation = saveNewPresentation();
}
else {
    $presentationID = $_GET['presentationID'];
    $presentation = getPresentation($presentationID);
    if(isset($_SESSION['user_id'])) addPresentationView($presentationID, $_SESSION['user_id']);
    else addPresentationView($presentationID);
}

function saveNewPresentation(){
    require_once("presentation.php");
    $presentation = new Presentation(array(
        'name' => 'untitled',
        'userID' => $_SESSION['user_id'],
        'creationDate' => date('Y-m-d H:i:s'),
        'shareStatus' => 'Public'
    ));
    if(is_null($presentation->id)) {
        $presentation = createPresentation($presentation);
        global $db;
        addNotification($presentation->userID, 'newPresentation', 'Your presenation has been created.', $presentation->id);
    }
    return $presentation;
}


?>