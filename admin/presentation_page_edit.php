<?php include("header.php"); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>
<link href="../assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="../assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/>

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
$presentationID = $_GET['presentationID'];
$presentation = getPresentation($presentationID);
if($presentation->getUserID() != $_SESSION['user_id'] && $_SESSION['roleID'] != 'AD') exit;
$presentationCategories = getPresentationCategoriesIDs($presentationID);
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
					<form class="form-horizontal form-row-seperated" action="#">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-note"></i><?php echo $presentation->name ?>
								</div>
								<div class="actions btn-set">
									<button type="button" id="back" name="back" class="btn default"><i class="fa fa-angle-left"></i> Back</button>
                                    <button class="btn green save" id="saveContinue"><i class="fa fa-check-circle"></i> Save</button>
									<div class="btn-group">
										<a class="btn yellow" href="#" data-toggle="dropdown">
										<i class="fa fa-share"></i> More <i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="#" id="delete">
												Delete </a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="portlet-body">
								<div class="tabbable">
									<ul class="nav nav-tabs">
										<li class="active">
											<a href="#tab_general" data-toggle="tab">
											General </a>
										</li>
										<li>
											<a href="#tab_reviews" data-toggle="tab">
											Reviews <span class="badge badge-success">
											<?php echo sizeof(getReviewsPresentation($presentationID)); ?> </span>
											</a>
										</li>
                                        <li>
                                            <a href="#tab_share" data-toggle="tab">
                                                Share
                                            </a>
                                        </li>
									</ul>
									<div class="tab-content no-space">
										<div class="tab-pane active" id="tab_general">
											<div class="form-body">
                                                <div class="form-group" style="display: none;">
                                                    <label class="col-md-2 control-label">ID: <span class="required">
													* </span>
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input id="presentationID" type="text" class="form-control" name="presentationID" placeholder="" value="<?php echo $presentation->id; ?>" readonly>
                                                    </div>
                                                </div>
												<div class="form-group">
													<label class="col-md-2 control-label">Name: <span class="required">
													* </span>
													</label>
													<div class="col-md-10">
														<input id="presentationName" type="text" class="form-control" name="product[name]" placeholder="" value="<?php echo $presentation->name; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-2 control-label">Description: <span class="required">
													* </span>
													</label>
													<div class="col-md-10">
														<textarea id="presentationDescription" class="form-control" name="product[description]"><?php echo $presentation->description; ?></textarea>
													</div>
												</div>
												<div class="form-group" id="categoriesGroup">
													<label class="col-md-2 control-label">Categories: <span class="required">
													* </span>
													</label>
													<div class="col-md-10">
														<div class="form-control height-auto">
															<div class="scroller" style="height:275px;" data-always-visible="1">
																<ul class="list-unstyled">
																	<?php
                                                                        $categories = getCategories(1);
                                                                    foreach($categories as $category){
                                                                        echo '<li><label><input type="checkbox" name="product[categories][]" value="'.$category->ID.'" '. (in_array($category->ID, $presentationCategories) ? 'checked' : '') .'>'.$category->Name.'</label></li>';
                                                                    }
                                                                    ?>
																</ul>
															</div>
														</div>
														<span class="help-block">
														Select one or more categories </span>
													</div>
												</div>
                                                <?php if($_SESSION['roleID'] == 'AD'){ ?>
												<div class="form-group">
													<label class="col-md-2 control-label">Status: <span class="required">
													* </span>
													</label>
													<div class="col-md-10">
														<select id="presentationStatus" class="table-group-action-input form-control input-medium" name="product[status]">
															<option value="">Select...</option>
															<option value="1" <?php if($presentation->status == 1) echo 'selected="selected"' ?>>Published</option>
															<option value="0" <?php if($presentation->status == 0) echo 'selected="selected"' ?>>Unpublished</option>
														</select>
													</div>
												</div>
                                                <?php } ?>
											</div>
										</div>
										<div class="tab-pane" id="tab_reviews">
											<div class="table-container">
												<table class="table table-striped table-bordered table-hover" id="datatable_reviews">
												<thead>
												<tr role="row" class="heading">
													<th width="5%">
														 Review&nbsp;#
													</th>
                                                    <th width="5%">
                                                        Rating
                                                    </th>
                                                    <th width="20%">
                                                        Message
                                                    </th>
													<th width="10%">
														 Date
													</th>
													<th width="10%">
														 User
													</th>
													<th width="5%">
														 Status
													</th>
												</tr>
												</thead>
												<tbody>
												</tbody>
												</table>
											</div>
										</div>
                                        <div class="tab-pane" id="tab_share">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <h3>Kies het privacyniveau</h3>
                                                    <div>
                                                        <div id="privacy-slider" class="slider bg-green ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
                                                            <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 20%;"></a></div>
                                                        <ul class="states span8">
                                                            <li data-type="private"><a href="#" class="icon"><i
                                                                        class="icon-lock medium"></i><strong>Private</strong></a>
                                                            </li>
                                                            <li data-type="hidden"><a href="#"
                                                                                      class="icon"><i
                                                                        class="icon-link medium"></i><strong>Hidden</strong></a>
                                                            </li>
                                                            <li class="last-child" data-type="public"><a href="#"
                                                                                                         class="icon"><i
                                                                        class="icon-globe medium"></i><strong>Public</strong></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div id="linkShared" class="row">
                                                    <div class="portlet green-meadow box">
                                                        <div class="portlet-title">
                                                            <div class="caption">
                                                                <i class="fa fa-cogs"></i>URL to share
                                                            </div>
                                                            <div class="tools">
                                                                <a href="javascript:;" class="collapse">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="row">
                                                                <label class="col-md-2 control-label">Link to presentation:
                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input id="presentationName" type="text" class="form-control" name="product[name]" placeholder="" value="<?php echo ADMIN_HOME ."/presentation_viewer.phpu?presentationID=$presentation->id" ?>">
                                                                    <span class="help-block"><i class="fa fa-globe"></i> Anyone with the link can see it. </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="../assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="../assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
<script type="text/javascript" src="../assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="../assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../assets/global/scripts/datatable.js"></script>
<script src="../assets/admin/pages/scripts/ecommerce-products-edit.js"></script>
<script src="../assets/admin/pages/scripts/components-jqueryui-sliders.js"></script>
<script src="../assets/global/plugins/jstree/dist/jstree.min.js"></script>
<script src="../assets/admin/pages/scripts/ui-tree.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init Libaro core components
        Layout.init(); // init current layout
        QuickSidebar.init() // init quick sidebar
        EcommerceProductsEdit.init();
        ComponentsjQueryUISliders.init();

        <?php
            if($presentation->shareStatus == 'Private') $shareStatus = 0;
            elseif($presentation->shareStatus == 'Shared'){
                $shareStatus = 1;
                echo '$("#linkShared").show("fast");';
            }
            else $shareStatus = 2;
        ?>
        // snap inc
        $("#privacy-slider").slider({
            isRTL: Metronic.isRTL(),
            value: <?php echo $shareStatus; ?>,
            min: 0,
            max: 2,
            step: 1,
            slide: function (event, ui) {
//                $("#privacy-slider-amount").text("$" + ui.value);
                if(ui.value != 1){
                    $("#linkShared").hide( "fast" );
                }
                else $("#linkShared").show("fast");
            }
        });

        $('#back').click(function() {
            window.location.href = 'presentations.php';
        });

        $('#delete').click(function() {
            var presentationID = $('#presentationID').val();
            $.ajax({
                type: "POST",
                url: "../assets/global/dataConnection/ajaxActions.php",
                data: {action: "deletePresentation",
                    presentationID: presentationID
                },
                success: function(result) {
                    window.location.href = 'presentations.php';
                }
            });
        });

        $('#saveContinue').click(function() {
            var presentationID = $('#presentationID').val();
            name =  $('#presentationName').val();
            description = $('#presentationDescription').val();
            statusobject = document.getElementById("presentationStatus");
            status = statusobject.options[statusobject.selectedIndex].value;
            buttonID = this.id;
            searchIDsCat = $("#categoriesGroup input:checkbox:checked").map(function(){
                    return $(this).val();
                }).get(); // <----
            var shareStatus = $('#privacy-slider').slider("option", "value");
            if(shareStatus == 0) shareStatus = 'Private';
            else if(shareStatus == 1) shareStatus = 'Shared';
            else if(shareStatus == 2) shareStatus = 'Public';
            $.ajax({
                type: "POST",
                url: "../assets/global/dataConnection/ajaxActions.php",
//                processData: false,
                data: {action: "updatePresentation",
                    presentationID: presentationID,
                    name: name,
                    description: description,
                    status: status,
                    categories: searchIDsCat,
                    shareStatus: shareStatus
                },
                success: function(result) {
                        Metronic.alert({
                            container: "", // alerts parent container(by default placed after the page breadcrumbs)
                            place: "append", // append or prepent in container
                            type: 'success',  // alert's type
                            message: "Presentation opgeslagen",  // alert's message
                            close: true, // make alert closable
                            reset: true, // close all previous alerts first
                            focus: true, // auto scroll to the alert after shown
                            closeInSeconds: 5, // auto close after defined seconds
                            icon: "check" // put icon before the message
                        });
                }
                });
        });


    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>