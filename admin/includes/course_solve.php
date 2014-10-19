<?php
require_once("../../load.php");
include_once(ABSPATH . 'assets/global/dataConnection/session_functions.php');
sec_session_start();
require_once("../../assets/global/dataConnection/queries.php");


//if new solution, create one and open it
if(isset($_GET['presentationID'])) {
    $presentationID = htmlspecialchars($_GET["presentationID"]);
    $solutionID = createSolution($presentationID, $_SESSION['user_id']);
    header('Location: ../presentation_solver.php?solutionID= ' . $solutionID);
}
else echo 'Fout bij het openen';

?>