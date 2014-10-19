<?php
  /* 
   * Paging
   */


if ( defined('ABSPATH') ){
    require_once(ABSPATH . 'load.php');
    require_once(ABSPATH .  '/assets/global/dataConnection/session_functions.php');
}
else{
    require_once( dirname(dirname( dirname( __FILE__ ) )) . '/load.php' );
    require_once( dirname(dirname( dirname( __FILE__ ) )) . '/assets/global/dataConnection/session_functions.php' );
}
global $db;
sec_session_start();

$records = array();
$records["data"] = array();
$iDisplayStart = intval($_REQUEST['start']);
$sEcho = intval($_REQUEST['draw']);
$iDisplayLength = intval($_REQUEST['length']);
$sort_col = $_POST['order'][0]['column'];
$sort_dir = $_POST['order'][0]['dir'];

if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    if($_REQUEST["customActionName"] == "Delete"){
        require_once(dirname(dirname(dirname( __FILE__ ))) ."/assets/global/dataConnection/queries.php");
        foreach($_REQUEST["id"] as $exercise){
            deletePresentationAsAdmin($exercise);
        }
    }
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
}


/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
//Er moet AS bij staan!
  $iTotalRecords = $db->get_var('Select count(*) AS iTotalRecords FROM tblPresentations');
$columns = array('tblPresentations.ID', 'tblPresentations.Name AS PresentationName','tblUsers.FirstName', 'tblUsers.Name AS UserName', 'tblPresentations.CreationDate', 'tblPresentations.Status');
$columnsNoAs = array('tblPresentations.ID', 'tblPresentations.Name','tblUsers.FirstName', 'tblUsers.Name', 'tblPresentations.CreationDate', 'tblPresentations.Status');


  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 

  if(is_null($sort_col)) $sort_col = $columns[0];
  if(is_null($sort_dir)) $sort_dir = 'ASC';
  

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;

  $status_list = array(
    array("danger" => "Hidden"),
    array("success" => "Visible")
//    array("warning" => "On Hold"),
//    array("info" => "Fraud")
  );

$presentations = $db->get_results(';WITH Results_CTE AS
(
    SELECT
        '. $columns[0] .', '. $columns[1] .', '. $columns[2] .', '. $columns[3] .', '. $columns[4] .', '. $columns[5] .',

        ROW_NUMBER() OVER (ORDER BY ' . $columnsNoAs[$sort_col - 1] . ' ' . $sort_dir .') AS RowNum
    FROM tblPresentations INNER JOIN tblUsers ON tblPresentations.CreatorID = tblUsers.ID
)
SELECT *
FROM Results_CTE
WHERE RowNum >= ' . $iDisplayStart . '
AND RowNum < ' . $end . '');


//$presentations = $db->get_results('SELECT *, tblPresentations.Name AS PresentationName, tblUsers.Name AS UserName FROM tblPresentations INNER JOIN tblUsers ON tblPresentations.CreatorID = tblUsers.ID LIMIT ' . $iDisplayStart . ', '. $iDisplayLength);
if(is_null($presentations)) $records["customActionMessage"] = "No presentations found :(";
else{
    foreach ($presentations as $presentation) {
    $status = $status_list[$presentation->Status];
        if($_SESSION['roleID'] == 'AD'){
            $records["data"][] = array(
                '<input type="checkbox" name="id[]" value="'.$presentation->ID.'">',
                $presentation->ID,
                $presentation->PresentationName,
                $presentation->FirstName .' '. $presentation->UserName,
                date_format($presentation->CreationDate, 'd/m/Y'),
                '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                '<a href="presentation_page.php?presentationID=' . $presentation->ID . '" class="btn btn-xs default"><i class="fa fa-search"></i> Show</a>',
            );
        }
        else{
            $records["data"][] = array(
                $presentation->PresentationName,
                $presentation->FirstName .' '. $presentation->UserName,
                date_format($presentation->CreationDate, 'd/m/Y'),
                '<a href="presentation_page.php?presentationID=' . $presentation->ID . '" class="btn btn-xs default"><i class="fa fa-search"></i> Show</a>',
            );
        }
    }
}

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);

?>