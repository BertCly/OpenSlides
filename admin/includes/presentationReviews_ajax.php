<?php
  /* 
   * Paging
   */

if ( defined('ABSPATH') )
    require_once(ABSPATH . 'load.php');
else
    require_once( dirname(dirname( dirname( __FILE__ ) )) . '/load.php' );
global $db;

    //var_dump($_POST["selected"]);
$records = array();
$records["data"] = array();
$iDisplayStart = intval($_REQUEST['start']);
$sEcho = intval($_REQUEST['draw']);
$iDisplayLength = intval($_REQUEST['length']);
$sort_col = $_POST['order'][0]['column'];
$sort_dir = $_POST['order'][0]['dir'];
$presentationID = intval($_POST['presentationid']);

if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    require_once(dirname(dirname(dirname( __FILE__ ))) ."/assets/global/dataConnection/queries.php");
    if($_REQUEST["customActionName"] == "Delete"){
        foreach($_REQUEST["id"] as $review){
            deleteReview($review);
        }
    }
    elseif($_REQUEST["customActionName"] == "SetStatus"){
        foreach($_REQUEST["id"] as $review){
            setStatusReview($review, $status);
        }
    }
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
}


/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
//Er moet AS bij staan!
$iTotalRecords = $db->get_var('Select count(*) AS iTotalRecords FROM tblPresentationReviews WHERE PresentationID = ' . $presentationID);
$columns = array('tblPresentationReviews.ID', 'tblPresentationReviews.Score AS Score','tblPresentationReviews.Message', 'tblUsers.Name AS UserName', 'tblPresentationReviews.DateTime',  'tblPresentationReviews.Status');
$columnsNoAs = array('tblPresentationReviews.ID', 'tblPresentationReviews.Score AS Score','tblPresentationReviews.Message', 'tblUsers.Name AS UserName', 'tblPresentationReviews.DateTime',  'tblPresentationReviews.Status');


$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;

if(is_null($sort_col)) $sort_col = $columns[0];
if(is_null($sort_dir)) $sort_dir = 'ASC';


  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;

//  $status_list = array(
//    array("info" => "Pending"),
//    array("success" => "Approved"),
//    array("danger" => "Rejected")
//  );
$status_list2 = array();
$status_list2['Approved'] = array("success" => "Approved");
$status_list2['Pending'] = array("success" => "Approved");
$status_list2['Rejected'] = array("danger" => "Rejected");

$reviews = $db->get_results(';WITH Results_CTE AS
(
    SELECT
        '. $columns[0] .', '. $columns[1] .', '. $columns[2] .', '. $columns[3] .', '. $columns[4] .', '. $columns[5] .',

        ROW_NUMBER() OVER (ORDER BY ' . $columnsNoAs[$sort_col] . ' ' . $sort_dir .') AS RowNum
    FROM tblPresentationReviews INNER JOIN tblUsers ON tblPresentationReviews.UserID = tblUsers.ID
)
SELECT *
FROM Results_CTE
WHERE RowNum >= ' . $iDisplayStart . '
AND RowNum <= ' . $end . '');

if(is_null($reviews)) $records["customActionMessage"] = "No reviews yet";
else{
  foreach($reviews as $review) {
    $status = $status_list2[$review->Status];
    $records["data"][] = array(
      $review->ID,
        $review->Score.'/5',
        $review->Message,
      date_format($review->DateTime, 'd/m/Y'),
        $review->UserName,
      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
      '<a href="javascript:;" class="btn btn-xs default btn-editable"><i class="fa fa-share"></i> View</a>',
    );
  }
}

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>