<?php

if (!defined('ABSPATH'))
    require_once("../../../load.php");

//if ( !defined('ABSPATH')) require_once("../load.php");
//user queries
function getMetaUser($userID, $metaKey) {
    global $db;
    $value = $db->get_var('SELECT MetaValue FROM tblUserMeta WHERE MetaKey = \'' . $metaKey . '\' and UserID = ' . $userID);
    return $value;
}

function getRoleDescription($roleCode) {
    global $db;
    return $db->get_var('SELECT Description FROM tblRoles WHERE Code = \'' . $roleCode . '\'');
}

function searchUsers($search_text) {
    global $db;
    $search_text = ltrim($search_text);
    $search_text = rtrim($search_text);
    $users = $db->get_results("SELECT * FROM tblUsers WHERE Name LIKE '%" . $search_text . "%'
            OR FirstName LIKE '%" . $search_text . "%'
            OR concat(FirstName , ' ', Name) LIKE '%" . $search_text . "%'
            OR Username LIKE '%" . $search_text . "%'
            OR Email LIKE '%" . $search_text . "%'");
    return $users;
}

function getUser($userID) {
    global $db;
    return $db->get_row("SELECT * FROM tblUsers WHERE ID = $userID");
}

//notifications
function getNotificationsUser($userID) {
    global $db;
    $nots = $db->get_results("SELECT TOP(20) * FROM tblNotifications WHERE UserID = $userID AND Type <> 'newAssignment' order by 'DateTime' desc");
    return $nots;
}

function getTaskNotifications($userID) {
    global $db;
    $nots = $db->get_results("SELECT TOP(20) an.*, a.*, c.Name, c.ID FROM tblNotifications an INNER JOIN tblAssignments a ON an.Url = a.ID INNER JOIN tblPresentations c ON a.PresentationID = c.ID WHERE an.UserID = $userID AND an.Type = 'newAssignment' order by 'DateTime' desc");
    return $nots;
}

function addNotification($userID, $type, $message, $url = '') {
    global $db;
    $success = $db->insert(
            'tblNotifications', array(
        'DateTime' => date('Y-m-d H:i:s'),
        'Type' => $type,
        'UserID' => $userID,
        'Message' => $message,
        'Url' => $url
            )
    );
    return $success;
}

//presentation queries
function deletePresentation($presentationID) {
    global $db;

    $deleted = $db->delete("tblPresentation_UserRights", array('PresentationID' => $presentationID));
    $deleted = $db->delete("tblPresentation_Categories", array('PresentationID' => $presentationID));
    $deleted = $db->delete("tblPresentationReviews", array('PresentationID' => $presentationID));
    $deleted = $db->delete("tblPresentationViews", array('PresentationID' => $presentationID));
    $deleted = $db->delete("tblPresentations", array('ID' => $presentationID));
    if (isset($_SESSION['user_id']))
        $userID = $_SESSION['user_id'];
    else
        $userID = -1;
    $db->logEvent('PresentationDeleted', 'Indepen ' . $presentationID . ' deleted', $userID);
}


function updatePresentation($presentationID, $name, $description = '', $status = '', $creatorID = '', $shareStatus = '') {
    global $db;
    $updateClause = array();
    $updateClause['Name'] = $name;
    if ($description != '')
        $updateClause['Description'] = $description;
    if ($status != '')
        $updateClause['Status'] = $status;
    if ($creatorID != '')
        $updateClause['CreatorID'] = $creatorID;
    if ($shareStatus != '')
        $updateClause['ShareStatus'] = $shareStatus;
    $db->update('tblPresentations', $updateClause, array('ID' => $presentationID));
    if (isset($_SESSION['user_id']))
        $userID = $_SESSION['user_id'];
    else
        $userID = -1;
    $db->logEvent('PresentationUpdated', 'Presentation ' . $presentationID . ' updated ', $userID);
}

function updatePresentationCategories($presentationID, $categories) {
    global $db;
    $currentRelations = getPresentationCategoriesIDs($presentationID);
    foreach ($currentRelations as $currentRel) {
        if (!in_array($currentRel, $categories)) {
            $db->delete("tblPresentation_Categories", array('PresentationID' => $presentationID, 'CategoryID' => $currentRel));
        }
    }
    foreach ($categories as $category) {
        $relation = $db->get_row('SELECT * FROM tblPresentation_Categories WHERE PresentationID = ' . $presentationID . ' AND CategoryID = ' . $category);
        if ($relation == null) {
            $db->insert(
                    'tblPresentation_Categories', array(
                'PresentationID' => $presentationID,
                'CategoryID' => $category
                    )
            );
        }
    }
}

function readNotifications($userID) {
    global $db;
    $nots = $db->get_results('SELECT  * FROM tblNotifications WHERE UserID = ' . $userID . ' AND "Read" = 0');
    if (count($nots) > 0) {
        $db->query('UPDATE tblNotifications SET "Read" =  1 WHERE UserID = ' . $userID);
        $notsRead = '';
        foreach ($nots as $not)
            $notsRead .= $not->ID . ' ';
        if (isset($_SESSION['user_id']))
            $userID = $_SESSION['user_id'];
        else
            $userID = -1;
        $db->logEvent('NotificationsRead', "Notification(s) $notsRead read", $userID);
    }
}

function getUserLogs($userID, $max = '', $from = '', $till = '') {
    global $db;
    $top = '';
    if ($max != null)
        $top = " TOP($max) ";
    $between = null;
    if ($from != null && $till != null)
        $between = ' and DateTime > \'' . $from . '\' and DateTime <  \'' . $till . '\'';
    return $db->get_results(
                    "SELECT $top * FROM tblChangeLogs WHERE UserID = $userID " . $between . " order by 'DateTime' desc");
}

function getLogs($max = '', $from = '', $till = '') {
    global $db;
    $top = '';
    if ($max != null)
        $top = " TOP($max) ";
    $between = null;
    if ($from != null && $till != null)
        $between = ' and DateTime > \'' . $from . '\' and DateTime <  \'' . $till . '\'';
    return $db->get_results(
                    "SELECT $top * FROM tblChangeLogs " . $between . " order by 'DateTime' desc");
}

function createPresentation($presentation) {
    global $db;
    $success = $db->insert(
            'tblPresentations', array(
        'Description' => $presentation->description,
        'Name' => $presentation->name,
        'FilePath' => $presentation->filePath,
        'CreatorID' => $presentation->userID,
        'CreationDate' => $presentation->creationDate,
        'Status' => 1,
        'ShareStatus' => $presentation->shareStatus
            )
    );
    $presentation->setId($db->insert_id);
    $db->logEvent('PresentationCreated', 'Indepen ' . $db->insert_id . ', "' . $presentation->name . '" created', $presentation->userID);
    return $presentation;
}

function addPresentationReview($presentationID, $title, $review, $score, $userID) {
    global $db;
    $reviewID = $db->insert(
            'tblPresentationReviews', array(
        'UserID' => $userID,
        'Score' => $score,
        'PresentationID' => $presentationID,
        'DateTime' => date('Y-m-d H:i:s'),
        'Message' => $review,
        'Status' => 'Pending',
        'Title' => $title
            )
    );
    $db->logEvent('ReviewAdded', 'Review ' . $reviewID . ' added', $userID, $reviewID);
    return $reviewID;
}

function getAvgReview($presentationID) {
    $avgScore = 0;
    $reviews = getReviewsPresentation($presentationID);
    if (count($reviews) > 0) {
        foreach ($reviews as $review)
            $avgScore += $review->Score;
        $avgScore = round($avgScore / (count($reviews)), 0);
    }
    return $avgScore;
}

function searchPresentations($search_text) {
    global $db;
    $search_text = ltrim($search_text);
    $search_text = rtrim($search_text);
    $products = $db->get_results("SELECT *, FROM tblPresentations WHERE Name LIKE '%" . $search_text . "%'
    OR Description LIKE '%" . $search_text . "%'");
    return $products;
}

function getPresentation($presentationID) {
    require_once(ABSPATH . '/admin/includes/presentation.php');
    global $db;
    $dbPresentation = $db->get_row(
            'SELECT * FROM tblPresentations WHERE ID = ' . $presentationID);
    $presentation = dbPresentationToObject($dbPresentation);
    return $presentation;
}

function dbPresentationToObject($dbPresentation) {
    $presentation = new Presentation(array(
        'description' => $dbPresentation->Description,
        'name' => $dbPresentation->Name,
        'filePath' => $dbPresentation->FilePath,
        'userID' => $dbPresentation->CreatorID,
        'creationDate' => $dbPresentation->CreationDate,
        'status' => $dbPresentation->Status,
        'shareStatus' => $dbPresentation->ShareStatus
    ));
    $presentation->setId($dbPresentation->ID);
    return $presentation;
}

function getPresentationsUser($userID) {
    require_once('includes/presentation.php');
    global $db;
    $dbPresentations = $db->get_results(
            'SELECT * FROM tblPresentations WHERE CreatorID = ' . $userID);
    $presentations = array();
    foreach ($dbPresentations as $dbPresentation) {
        $presentations[] = dbPresentationToObject($dbPresentation);
    }
    return $presentations;
}

function getPresentationCategoriesIDs($presentationID) {
    global $db;
    $categories = $db->get_results(
            'SELECT CategoryID FROM tblPresentation_Categories WHERE PresentationID = ' . $presentationID);
    $cats = array();
    foreach ($categories as $cat) {
        $cats[] = reset($cat);
    }
    return $cats;
}

function getPresentationCategories($presentationID) {
    global $db;
    $categories = $db->get_results(
            'SELECT c.* FROM tblPresentation_Categories cc INNER JOIN tblCategories c ON c.ID = cc.CategoryID WHERE PresentationID = ' . $presentationID);
    return $categories;
}

//get total views for one presentation
function getPresentationsViews($presentationID, $from = null, $till = null) {
    global $db;
    $between = null;
    if ($from != null && $till != null)
        $between = ' and DateTime > \'' . $from . '\' and DateTime <  \'' . $till . '\'';
    $views = $db->get_var(
            'SELECT count(*) as views FROM tblPresentationViews WHERE PresentationID = ' . $presentationID . $between);
    return $views;
}

//get the top 10 of presentations with the most views
function getPresentationsMostViews($aantal, $from = null, $till = null) {
    global $db;
    $between = null;
    if ($from != null && $till != null)
        $between = ' where DateTime > \'' . $from . '\' and DateTime <  \'' . $till . '\'';
    $views = $db->get_results(
            'SELECT TOP(' . $aantal . ') cv.PresentationID, Count(cv.PresentationID) AS views FROM tblPresentationViews cv INNER JOIN tblPresentations c ON cv.PresentationID = c.ID ' . $between . ' GROUP BY PresentationID ORDER BY views desc');
    return $views;
}

//krijg het totaal aantal views van alle presentations
function getTotalViewsUserPresentations($userID, $from = null, $till = null) {
    global $db;
    $between = null;
    if ($from != null && $till != null)
        $between = ' and DateTime > \'' . $from . '\' and DateTime <  \'' . $till . '\'';
    $presentations = getPresentationsUser($userID);
    $views = 0;
    foreach ($presentations as $presentation) {
        $views += $db->get_var('SELECT count(*) as views FROM tblPresentationViews WHERE PresentationID = ' . $presentation->id . $between);
    }
    return $views;
}

function addPresentationView($presentationID, $userID = null) {
    global $db;
    $success = $db->insert(
            'tblPresentationViews', array(
        'UserID' => $userID,
        'DateTime' => date('Y-m-d H:i:s'),
        'PresentationID' => $presentationID,
            )
    );
}

//presentation reviews
function getReviewsPresentation($presentationID) {
    global $db;
    return $db->get_results("select * from tblPresentationReviews cv INNER JOIN tblUsers u on cv.UserID = u.ID where cv.PresentationID = " . $presentationID);
}

function deleteReview($reviewID) {
    global $db;
    $deleted = $db->delete("tblPresentationReviews", array('ID' => $reviewID));
    if (isset($_SESSION['user_id']))
        $userID = $_SESSION['user_id'];
    else
        $userID = -1;
    $db->logEvent('ReviewDeleted', 'Review ' . $reviewID . ' deleted', $userID);
}

function setStatusReview($reviewID, $status) {
    global $db;
    $db->update(
            'tblPresentationReviews', array(
        'Status' => $status
            ), array('ID' => $reviewID));
    if (isset($_SESSION['user_id']))
        $userID = $_SESSION['user_id'];
    else
        $userID = -1;
    $db->logEvent('ReviewStatusChanged', 'Status of review ' . $reviewID . ' set to ' . $status, $userID);
}

function getCategories() {
    global $db;
    return $db->get_results("select * from tblCategories");
}

function addFeedback($feedback, $userID) {
    global $db;
    $feedbackID = $db->insert(
            'tblFeedbacks', array(
        'UserID' => $userID,
        'CreationDate' => date('Y-m-d H:i:s'),
        'Feedback' => $feedback,
            )
    );
    $db->logEvent('FeedbackAdded', 'Feedback sent', $userID, $feedbackID);
}


?>