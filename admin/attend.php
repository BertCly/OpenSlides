<?php
include("header.php");
require ABSPATH . '/vendor/autoload.php';
?>
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/pdfjs/web/viewer.css"/>
<link href="../assets/global/plugins/pdfjs/web/locale/en-GB/viewer.properties" type="application/l10n" rel="resource"/>
<link href="../assets/global/plugins/jquery-live-menu/css/jquery.liveMenu.css" rel="stylesheet" />

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <?php
    include("page_header.php");
    $userID = $_SESSION['user_id'];
    if(isset($_GET['sessionID'])){
        $sessionID = $_GET['sessionID'];
        $session = getLiveSession($sessionID);
        $presentation = getPresentation($session->PresentationID);
    }

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
            <?php if(!isset($presentation)){ ?>
            <div class="form" id="enterSessioncode">
                <form action="attend.php" class="form-horizontal" method="get" enctype="multipart/form-data">
                    <div class="form-body">
                        <div class="input-group">
											<span class="input-group-addon">
											<i class="fa fa-caret-square-o-right"></i>
											</span>
                            <input name="sessionID" type="text" class="form-control" placeholder="Live session code">
                        </div>
                        <button id="attend" type="submit" class="btn blue"><i class="fa fa-caret-square-o-right"></i> Attend
                        </button>

                    </div>

                </form>
                <button id="sendButton" class="btn blue"><i class="fa fa-caret-square-o-right"></i> Send
                </button>
            </div>
            <?php }
            else { ?>
			<div class="row">
				<div class="col-md-12 headEditor">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
                        <span id="presentationName"><?php echo $presentation->name; ?></span> <small> Attending </small>
					</h3>
                    <div id="messageBox"></div>
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
                <div id="pdfViewer">
                        <?php include('pdf_viewer_attend.php'); ?>
                </div>
			</div>
            <?php }?>

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
<script src="../assets/global/plugins/pdfjs/build/pdf.js#zoom=page-fit" type="text/javascript"></script>

<script src="../assets/global/plugins/pdfjs/web/debugger.js" type="text/javascript"></script>
<?php
if (isset($presentation)){
echo '<script src="../assets/global/plugins/pdfjs/web/viewer.js" type="text/javascript"></script>';
}
?>

<!--<script src="../assets/global/plugins/jquery-live-menu/js/jquery-1.6.4.min.js"></script>-->
<script src="../assets/global/plugins/jquery-live-menu/_source/jquery.liveMenu.source.js"></script>
<script>
jQuery(document).ready(function() {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
    QuickSidebar.init(); // init quick sidebar

    <?php if(isset($presentation)) echo "PDFView.open('../userData/presentations/".$presentation->filePath."', 0);";
    ?>

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

    var conn = new WebSocket('ws://localhost:8081');
    conn.onopen = function(e) {
        conn.send('newAttender');
    };
//    conn.onclose = function(e) {
//        conn.send('lostAttender');
//    };
    conn.onmessage = function(e) {
        if (e.data.indexOf("page") > -1) {
            console.log(e.data);
            var pieces = e.data.split(":");
            PDFView.page = pieces[1];
        }
    };

});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>