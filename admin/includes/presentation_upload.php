<?php
/**
 * Created by Libaro.
 * User: Bert
 * Date: 20/07/14
 * Time: 12:39
 */
require_once("../../load.php");

$allowedExts = array("pdf");
$filename = str_replace(" ","_",$_FILES["file"]["name"]);
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

if (($_FILES["file"]["type"] == "application/pdf")
    && ($_FILES["file"]["size"] < 1000000000)
    && in_array($extension, $allowedExts)) {
    if ($_FILES["file"]["error"] > 0) {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    } else {
//        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
//        echo "Type: " . $_FILES["file"]["type"] . "<br>";
//        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
        while (file_exists("../../userData/presentations/" . $filename)) {
            $filename = file_newname("../../userData/presentations/", $filename);
        }
        if (file_exists("../../userData/presentations/" . $filename)) {
            echo $filename . " already exists. ";
        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"],
                "../../userData/presentations/" . $filename);
//            echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
            $db->update(
                'tblPresentations',
                array(
                    'FilePath' => $filename
                ),
                array( 'ID' => $_POST["pk"] ));
            header('Location: ../presentation_viewer.php?presentationID=' . $_POST["pk"]);
            exit;
        }
    }
} else {
    echo "Invalid file";
}

function file_newname($path, $filename){
    if ($pos = strrpos($filename, '.')) {
        $name = substr($filename, 0, $pos);
        $ext = substr($filename, $pos);
    } else {
        $name = $filename;
    }

    $newpath = $path.'/'.$filename;
    $newname = $filename;
    $counter = 0;
    while (file_exists($newpath)) {
        $newname = $name .'_'. $counter . $ext;
        $newpath = $path.'/'.$newname;
        $counter++;
    }

    return $newname;
}