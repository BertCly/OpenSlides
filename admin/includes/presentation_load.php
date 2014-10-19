<?php

function loadPresentation($presentationID){
    require_once('presentation.php');
    global $db;
    $presentationRow = $db->get_row('SELECT * from tblPresentations WHERE ID = '.$presentationID);
    $presentation = new Presentation($presentationRow->ID, $presentationRow->Description, $presentationRow->Name, $presentationRow->StartPage, $presentationRow->EndPage, $presentationRow->FilePath, $presentationRow->CreatorID, $presentationRow->CreationDate, $presentationRow->Type );
    return $presentation;
}

?>