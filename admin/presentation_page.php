<?php include("header.php"); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/select2/select2.css"/>
<link href="../assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="../assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/>

<link rel="stylesheet" type="text/css" href="../assets/global/plugins/pdfjs/web/viewer.css"/>
<link href="../assets/global/plugins/pdfjs/web/locale/locale.properties" type="application/l10n" rel="resource"/>

<link rel="stylesheet" type="text/css" href="../assets/global/plugins/jstree/dist/themes/default/style.min.css"/>
<!-- END PAGE LEVEL STYLES -->
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <?php include("page_header.php"); ?>
</div>
<?php require_once(ABSPATH."assets/global/dataConnection/queries.php");
if(!isset($_GET['presentationID'])) exit;
$presentationID = $_GET['presentationID'];
$presentation = getPresentation($presentationID);
$reviews = getReviewsPresentation($presentationID);
$presentationCategories = getPresentationCategories($presentationID);
if(isset($_SESSION['user_id'])) addPresentationView($presentationID, $_SESSION['user_id']);
else addPresentationView($presentationID);
?>
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
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Write a review</h4>
						</div>
						<div class="modal-body">
                            <form action="#" id="form_sample_1" class="form-horizontal">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have filled in everything correctly.
                                    </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You're review has been added!
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Title <span class="required">
										* </span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" id="title" name="title" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Review <span class="required">
										* </span>
                                        </label>
                                        <div class="col-md-9">
                                            <textarea rows="10" id="review" type="text" name="review" data-required="1" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">rating <span class="required">
										* </span>
                                        </label>
                                        <div class="col-md-9">
                                            <span id="reviewRating">
                                              <span id="5" class="star" onclick="rate(this)"></span><span id="4" class="star" onclick="rate(this)"></span><span id="3" class="star" onclick="rate(this)"></span><span id="2" class="star" onclick="rate(this)"></span><span id="1" class="star" onclick="rate(this)"></span>
                                            </span>
                                            <input type="text" name="scoreReview" id="scoreReview" data-required="1" class="form-control" style="display: none"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn green">Save</button>
                                            <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN STYLE CUSTOMIZER -->
			<div class="theme-panel hidden-xs hidden-sm">
                <?php require_once('styleCustomizer.php'); ?>
			</div>
			<!-- END STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
					Presentation settings <small></small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="index.php">Home</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">Presentations</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#"><?php echo $presentation->name ?></a>
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
                    <form id="generalSettingsPresentation" class="form-horizontal" role="form">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-note"></i><?php echo $presentation->name ?>
								</div>
								<div class="actions btn-set">
                                    <?php if($_SESSION['user_id'] == $presentation->getUserID() || $_SESSION['roleID'] == 'AD'){ ?><a href="presentation_page_edit.php?presentationID=<?php echo $presentationID; ?>"  id="EditPresentation" class="btn green"><i class="fa fa-pencil"></i> Edit</a><?php } ?>
                                    <a href="presentation_viewer.php?presentationID=<?php echo $presentationID; ?>" id="WatchPresentation" class="btn green"><i class="fa fa-play"></i>Watch</a>

								</div>
							</div>
							<div class="form-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet grey-cascade box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>General
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Name:</label>
                                                            <div class="col-md-9">
                                                                <p class="form-control-static">
                                                                    <?php echo $presentation->name; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Description:</label>
                                                            <div class="col-md-9">
                                                                <p class="form-control-static">
                                                                    <?php echo $presentation->description; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Views:</label>
                                                            <div class="col-md-9">
                                                                <p class="form-control-static">
                                                                    <?php echo getPresentationsViews($presentationID); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Rating:</label>
                                                            <div class="col-md-9">
                                                                <p class="form-control-static">
                                                                    <?php
                                                                    $avgScore = getAvgReview($presentation->getId());
                                                                    for($i = 1; $i < 6; $i++){
                                                                        if($i <= $avgScore) echo '<i class="fa fa-star"></i>';
                                                                        else echo '<i class="fa fa-star-o"></i>';
                                                                    }
                                                                    ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Categories:</label>
                                                            <div class="col-md-9">
                                                                <ul class="list-unstyled">
                                                                    <?php
                                                                    foreach($presentationCategories as $category){
                                                                        echo "<li>$category->Name</li>";
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <?php if($_SESSION['roleID'] == 'AD'){ ?>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Status:</label>
                                                            <div class="col-md-9">
                                                                <p class="form-control-static">
                                                                    <span class="label label-success">
																 <?php if($presentation->status == 1) echo 'Published';
                                                                 elseif($presentation->status == 0) echo 'Not Published'; ?> </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    <!--/span-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
                    </form>
				</div>
			</div>
            <div class="row">
                <div class="col-md-6">
                    <!-- BEGIN ALERTS PORTLET-->
                    <div class="portlet purple box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-pencil"></i>Reviews
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if($_SESSION['roleID'] != 'LL'){ ?>
                            <a href="#portlet-config" data-toggle="modal" class="btn btn-lg purple">
                                Write a review <i class="fa fa-edit"></i>
                            </a><br/><br/>
                            <?php } ?>
                            <?php
                            if(count($reviews)==0) echo 'There are no reviews yet, be the first.';
                            else{
                                foreach($reviews as $review){
                                    echo '<div class="note';
                                    if($review->Score > 2) echo ' note-success';
                                    else echo ' note-danger';
                                    echo '">
                                    <h4 class="block"> <span class="reviewStars">';
                                    for($i = 0; $i < 5; $i++){
                                        if($i < $review->Score) echo '<i class="fa fa-star"></i>';
                                        else echo '<i class="fa fa-star-o"></i>';
                                    }
                                    echo '</span>'.$review->Title.'</h4>
                                    <p>
                                        '.$review->Message.'
                                     </p>
                                    <p>
                                        <i>'.$review->Username.'</i>, '.date_format($review->DateTime, "d M Y").'
                                    </p>
                                </div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- END ALERTS PORTLET-->
                </div>
                <div class="col-md-6">
                    <!-- BEGIN ALERTS PORTLET-->
                    <div class="portlet yellow box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa  fa-file-pdf-o"></i>Preview
                            </div>
                        </div>
                        <div id="pdfPreviewer" class="portlet-body">
                            <?php if($presentation->filePath != '') echo '<canvas id="the-canvas" style="border:1px solid black;"/>';
                            else echo 'There is no PDF attached to this presentation.';
                            ?>
                        </div>
                    </div>
                    <!-- END ALERTS PORTLET-->
                </div>
            </div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
	<!-- BEGIN QUICK SIDEBAR -->
	<a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
	<div class="page-quick-sidebar-wrapper">
        <?php require_once('styleCustomizer.php'); ?>
	</div>
	<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<?php include("footer.php"); ?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script src="../assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
<script type="text/javascript" src="../assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="../assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script>

<script src="../assets/global/plugins/pdfjs/web/compatibility.js" type="text/javascript" ></script>
<script src="../assets/global/plugins/pdfjs/web/l10n.js" type="text/javascript" ></script>
<script src="../assets/global/plugins/pdfjs/build/pdf.js" type="text/javascript"></script>
<script src="../assets/global/plugins/pdfjs/build/pdf.worker.js" type="text/javascript"></script>

<script type="text/javascript" src="../assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../assets/global/plugins/jstree/dist/jstree.min.js"></script>
<script src="../assets/admin/pages/scripts/ui-tree.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init Libaro core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
//        UITree.init();
//        handleShareTree();
        handleFormValidation();

        //
// Fetch the PDF document from the URL using promises
//
            <?php if($presentation->filePath != '') echo "PDFJS.getDocument('../userData/presentations/$presentation->filePath').then(function(pdf) {
            // Using promise to fetch the page
            pdf.getPage(1).then(function(page) {
                var scale = 1.5;
                var viewport = page.getViewport(scale);

                //
                // Prepare canvas using PDF page dimensions
                //
                var canvas = document.getElementById('the-canvas');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                //
                // Render PDF page into canvas context
                //
                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        });";
        ?>

        // advance validation
        function handleFormValidation() {
            // for more info visit the official plugin documentation:
            // http://docs.jquery.com/Plugins/Validation

            var form1 = $('#form_sample_1');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    title: {
                        minlength: 5,
                        required: true
                    },
                    review: {
                        minlength: 20,
                        required: true
                    },
                    scoreReview: {
                        required: true,
                        number: true
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit
                    success1.hide();
                    error1.show();
                    Metronic.scrollTo(error1, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    var presentationID = <?php echo $presentationID; ?>;
                    var score = $('#scoreReview').val();
                    var review = $('#review').val();
                    var title = $('#title').val();
                    var userID = <?php echo $_SESSION['user_id']; ?>;
                    $.ajax({
                        type: "POST",
                        url: "../assets/global/dataConnection/ajaxActions.php",
                        data: {action: "addPresentationReview",
                            presentationID: presentationID,
                            score: score,
                            review: review,
                            title: title,
                            userID: userID
                        },
                        success: function(result) {
                            window.location.href = 'presentation_page.php?presentationID=' + <?php echo $presentationID; ?>;
                        }
                    });
                }
            });


        }

    });

    function rate(obj) {
        var $obj = $(obj);
        $obj.addClass("rated")
            .prevAll().removeClass("rated").end()
            .nextAll().addClass("rated");

        $obj.prevAll().addClass("star");
        $('#scoreReview').val($obj.attr('id'));
    }
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>