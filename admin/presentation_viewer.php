<?php include("header.php"); ?>
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/pdfjs/web/viewer.css"/>
<link href="../assets/global/plugins/pdfjs/web/locale/locale.properties" type="application/l10n" rel="resource"/>
<link href="../assets/global/plugins/jquery-live-menu/css/jquery.liveMenu.css" rel="stylesheet" />

<style>
    #latDraggable{display: none; z-index: 10000; }
    #protractor{display: none; z-index: 10000; }
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <?php
    include("page_header.php");
    include("includes/presentation_build.php");

    $userID = $_SESSION['user_id'];

    ?>
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
        <?php include("menu.php"); ?>
	</div>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN STYLE CUSTOMIZER -->
			<div class="theme-panel hidden-xs hidden-sm">
                <?php require_once('styleCustomizer.php'); ?>
			</div>
			<!-- END STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
			<div class="row">
				<div class="col-md-12 headEditor">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
                        <span id="presentationName"><?php echo $presentation->name; ?></span> <small>Viewer</small><span id="solutionID" class="hidden"><?php echo $solution->ID; ?></span><?php if(isset($assignment)) echo "<span id='assignmentID' class='hidden'> $assignment->ID </span>"; ?>
					</h3>
                    <div class="actions btn-set">
                        <?php if(!(isset($assignment) && $assignment->DateSubmitted != '')) echo '<a class="btn green" href="presentation_page_edit.php"><i class="fa fa-cogs"></i> Edit</a>' ?>
                    </div>
                    <div id="messageBox"></div>
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
                <?php
                if(is_null($presentation->filePath)){ ?>
                <div class="selectFileUpload">
                    <form action="includes/presentation_upload.php" method="post" enctype="multipart/form-data">
                        <div class="fileUploadButton fileinput-new" data-provides="fileinput">
                                <span class="btn default btn-file">
                                <span class="fileinput-new">
                                Select file </span>
                                <span class="fileinput-exists">
                                Change </span>
                                <input onchange="this.form.submit()"  type="file" name="file" id="file" accept="application/pdf">
                                </span>
                                <span class="fileinput-filename">
                                </span>
                            &nbsp; <a href="#" class="close fileinput-exists" data-dismiss="fileinput">
                            </a>
                        </div>
                        <input type="hidden" name="pk" value="<?php echo $presentation->id; ?>">
                    </form>
                </div>
                <?php }
                else {
                    echo '
  <img src="../assets/global/img/tools/centimeter_and_inch_ruler.png" alt="lat" title="lat" class="tool" id="latDraggable"/>
  <img src="../assets/global/img/tools/protractor.png" alt="geodriehoek" title="geodriehoek" class="tool" id="protractor"/>
';
                    echo '<div id="maskLayer"></div>';
                    echo '<div id="pdfViewer">';

                    include('pdf_viewer.php');

                    echo '</div>';
                }?>
			</div>

			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
	<!-- BEGIN QUICK SIDEBAR -->

    <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
    <div class="page-quick-sidebar-wrapper">
        <?php include("quick_sidebar.php"); ?>
    </div>
<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<?php include("footer.php");
?>
<!-- END CORE PLUGINS -->
<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../assets/global/plugins/pdfjs/web/compatibility.js" type="text/javascript" ></script>
<script src="../assets/global/plugins/pdfjs/web/l10n.js" type="text/javascript" ></script>
<script src="../assets/global/plugins/pdfjs/build/pdf.js" type="text/javascript"></script>

<script src="../assets/global/plugins/pdfjs/web/debugger.js" type="text/javascript"></script>
<script src="../assets/global/plugins/pdfjs/web/viewer.js" type="text/javascript"></script>

<!--<script src="../assets/global/plugins/jquery-live-menu/js/jquery-1.6.4.min.js"></script>-->
<script src="../assets/global/plugins/jquery-live-menu/_source/jquery.liveMenu.source.js"></script>
<script>
jQuery(document).ready(function() {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
    QuickSidebar.init(); // init quick sidebar

    <?php if($presentation->filePath != '') echo "PDFView.open('../userData/presentations/".$presentation->filePath."', 0);";
   else echo "PDFView.open('helloworld.pdf', 0);"; ?>

    function alertMessage(message){
        Metronic.alert({
            container: "#messageBox", // alerts parent container(by default placed after the page breadcrumbs)
            place: "append", // append or prepent in container
            type: 'success',  // alert's type
            message: message,  // alert's message
            close: true, // make alert closable
            reset: true, // close all previous alerts first
            focus: true, // auto scroll to the alert after shown
            closeInSeconds: 5, // auto close after defined seconds
            icon: "check" // put icon before the message
        });
    }

});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>