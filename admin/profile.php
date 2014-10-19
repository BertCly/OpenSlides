<?php include("header.php"); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<link href="../assets/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
    <?php
    include("page_header.php");
    ?>
	<!-- END HEADER INNER -->
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
            <?php
            global $db;
            require_once("../assets/global/dataConnection/queries.php");
            $user_id = $_SESSION['user_id'];
            $userValues = $db->get_row('SELECT * FROM tblUsers WHERE tblUsers.ID = ' . $user_id);
            ?>
			<!-- BEGIN PAGE HEADER-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
					<?php echo $userValues->Username .' <small>' . $userValues->FirstName . ' ' . $userValues->Name .'</small>';
                    ?>
					</h3>
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="index.html">Home</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">Profiel</a>
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row profile">
				<div class="col-md-12">
					<!--BEGIN TABS-->
					<div class="tabbable tabbable-custom tabbable-full-width">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab_1_1" data-toggle="tab">
								Overview</a>
							</li>
							<li>
								<a href="#tab_1_3" data-toggle="tab">
								Account </a>
							</li>
							<li>
								<a href="#tab_1_4" data-toggle="tab">
								My Presentations </a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_1_1">
								<div class="row">
                                    <?php
                                    $image = getMetaUser($user_id, 'avatar');
                                    if($image != null){
                                    echo '<div class="col-md-3">
										<ul class="list-unstyled profile-nav">
                                            <li><img src="../userData/profile/'. $image . '" class="img-responsive" alt=""/></li>

										</ul>
									</div>';
                                    }
                                    $column = ($image != null ? 'class="col-md-9"' : 'class="col-md-12"');
                                    ?>
									<div <?php echo $column; ?>>
										<div class="row">
											<div class="col-md-8 profile-info">
												<h1><?php echo $userValues->FirstName . ' ' . $userValues->Name; ?></h1>
												<ul class="list-inline">
													<?php $city = getMetaUser($user_id, 'city');
                                                        if($city != null)echo '<li> <i class="fa fa-map-marker"></i>' . $city  . '</li>';
                                                    echo '<li>
														<i class="fa fa-calendar"></i>Member since ' . date_format($userValues->Creationdate, 'd/m/Y') . '
													</li>
													<li>
														<i class="fa fa-briefcase"></i> ' . getRoleDescription($userValues->RoleID) . '
													</li>';
//													echo '<li>
//														<i class="fa fa-star"></i> Top auteur
//													</li>' ?>
												</ul>
											</div>
											<!--end col-md-8-->
											<div class="col-md-4">
												<div class="portlet sale-summary">
													<div class="portlet-title">
														<div class="caption">
															 Summary
														</div>
														<div class="tools">
															<a class="reload" href="javascript:;">
															</a>
														</div>
													</div>
													<div class="portlet-body">
														<ul class="list-unstyled">
                                                            <?php if($_SESSION['roleID'] != 'LL'){ ?>
                                                            <li>
																<span class="sale-info">
																Number of PRESENTATIONS<i class="fa fa-img-up"></i>
																</span>
																<span class="sale-num">
																<?php echo $db->get_var("SELECT count(*) as atlPresentations FROM tblPresentations WHERE CreatorID = " . $user_id) ?> </span>
															</li>
                                                            <li>
																<span class="sale-info">
																Views of you uploads </span>
																<span class="sale-num">
																<?php echo $db->get_var("SELECT count(*) as atlViews FROM tblPresentationViews INNER JOIN tblPresentations ON tblPresentationViews.PresentationID = tblPresentations.ID WHERE tblPresentations.CreatorID = " . $user_id) ?> </span>
                                                            </li>
                                                            <?php } ?>
															<li>
																<span class="sale-info">
																PRESENTATIONS watched by you <i class="fa fa-img-down"></i>
																</span>
																<span class="sale-num">
																<?php echo $db->get_var("SELECT count(DISTINCT PresentationID) as atlViews FROM tblPresentationViews WHERE UserID = " . $user_id) ?> </span>
															</li>
															<li>
																<span class="sale-info">
																YOUR REVIEWS </span>
																<span class="sale-num">
																<?php echo $db->get_var("SELECT count(*) as atlReviews FROM tblPresentationReviews WHERE UserID = " . $user_id) ?> </span>
															</li>
														</ul>
													</div>
												</div>
											</div>
											<!--end col-md-4-->
										</div>
                                        <?php
                                        require_once("../assets/global/dataConnection/queries.php");
                                        $notifications = getNotificationsUser($userID);
                                        $number = 0;
                                        foreach($notifications as $not){
                                            if($not->Read == 0) $number++;
                                        }
                                        ?>
										<!--end row-->
										<div class="tabbable tabbable-custom tabbable-custom-profile">
											<ul class="nav nav-tabs">
												<li class="active">
													<a href="#tab_1_11" data-toggle="tab">
													Notifications (<?php echo $number; ?>)</a>
												</li>
                                                <li>
                                                    <a href="#tab_1_22" data-toggle="tab">
                                                     Activity</a>
                                                </li>
											</ul>
											<div class="tab-content">
												<!--tab-pane-->
												<div class="tab-pane active" id="tab_1_11">
													<div class="tab-pane active" id="tab_1_1_1">
														<div class="scroller" data-height="290px" data-always-visible="1" data-rail-visible1="1">
															<ul class="feeds">
                                                                <?php
                                                                foreach($notifications as $not){
                                                                    ?>
                                                                    <li>
                                                                        <div class="col1">
                                                                            <div class="cont">
                                                                                <div class="cont-col1">
                                                                                    <?php
                                                                                    if($not->Type == 'addPresentation') echo '<div class="label label-success"><i class="fa fa-plus"></i></div>';
                                                                                    elseif($not->Type == 'UserLoggedIn') echo '<div class="label label-default"><i class="icon-user"></i></div>';
                                                                                    elseif($not->Type == 'PresentationOpened') echo '<div class="label label-default"><i class="fa fa-folder-open-o"></i></div>';
                                                                                    elseif($not->Type == 'PresentationCreated') echo '<div class="label label-success"><i class="fa fa-pencil"></i></div>';
                                                                                    elseif($not->Type == 'PresentationDeleted') echo '<div class="label label-danger"><i class="fa fa-trash-o"></i></div>';
                                                                                    else echo '<div class="label label-default"><i class="fa fa-bell-o"></i></div>';
                                                                                    ?>
                                                                                </div>
                                                                                <div class="cont-col2">
                                                                                    <div class="desc">
                                                                                        <?php echo $not->Message;
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col2">
                                                                            <div class="date">
                                                                                <?php echo date_format($not->DateTime, 'd/m/Y'); ?>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
															</ul>
														</div>
													</div>
											</div>
											<!--tab-pane-->
                                            <div class="tab-pane" id="tab_1_22">
                                                <div class="tab-pane active" id="tab_1_1_1">
                                                    <div class="scroller" data-height="290px" data-always-visible="1" data-rail-visible1="1">
                                                        <ul class="feeds">
                                                            <?php
                                                            $feeds = $db->get_results("SELECT TOP(20) * FROM tblChangeLogs WHERE UserID = " . $user_id . " order by 'DateTime' desc");
                                                            foreach($feeds as $feed){
                                                            ?>
                                                            <li>
                                                                <div class="col1">
                                                                    <div class="cont">
                                                                        <div class="cont-col1">
                                                                            <?php
                                                                            if($feed->Type == 'UserRegistered') echo '<div class="label label-success"><i class="fa fa-plus"></i></div>';
                                                                            elseif($feed->Type == 'UserLoggedIn') echo '<div class="label label-default"><i class="icon-user"></i></div>';
                                                                            elseif($feed->Type == 'PresentationOpened') echo '<div class="label label-default"><i class="fa fa-folder-open-o"></i></div>';
                                                                            elseif($feed->Type == 'PresentationCreated') echo '<div class="label label-success"><i class="fa fa-pencil"></i></div>';
                                                                            elseif($feed->Type == 'PresentationDeleted') echo '<div class="label label-danger"><i class="fa fa-trash-o"></i></div>';
                                                                            else echo '<div class="label label-default"><i class="fa fa-bell-o"></i></div>';
                                                                            ?>
                                                                        </div>
                                                                        <div class="cont-col2">
                                                                            <div class="desc">
                                                                                <?php echo $feed->Description;
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col2">
                                                                    <div class="date">
                                                                        <?php echo date_format($feed->DateTime, 'd/m/Y'); ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab_1_3">
								<div class="row profile-account">
									<div class="col-md-3">
										<ul class="ver-inline-menu tabbable margin-bottom-10">
											<li class="active">
												<a data-toggle="tab" href="#tab_1-1">
												<i class="fa fa-cog"></i> Edit profile</a>
												<span class="after">
												</span>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_2-2">
												<i class="fa fa-picture-o"></i> Edit avatar</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_3-3">
												<i class="fa fa-lock"></i> Edit password </a>
											</li>
                                            <?php if($_SESSION['roleID'] == 'AD'){ ?>
											<li>
												<a data-toggle="tab" href="#tab_4-4">
												<i class="fa fa-eye"></i> Privacy settings </a>
											</li>
                                            <?php } ?>
										</ul>
									</div>
									<div class="col-md-9">
										<div class="tab-content">
											<div id="tab_1-1" class="tab-pane active">
                                                <?php
                                                echo '<form role="form" method="post" action="includes/userSettingsForm.php">';

                                                    echo '<div class="form-group">
														<label class="control-label">Firstname</label>
														<input type="text" name="firstName" placeholder="Vincent" class="form-control" value="' . $userValues->FirstName . '"/>
													</div>
													<div class="form-group">
														<label class="control-label">Name</label>
														<input type="text" name="name" placeholder="Kompany" class="form-control" value="' . $userValues->Name . '"/>
													</div>
                                                    <div class="form-group">
                                                        <label class="control-label">City</label>
                                                        <input type="text" name="city" placeholder="Leuven" class="form-control" value="' . getMetaUser($user_id, 'city') . '"/>
                                                    </div>
													<div class="form-group">
														<label class="control-label">About myself</label>
														<textarea class="form-control" name="about" rows="3" placeholder="Describe yourself.">' . getMetaUser($user_id, 'about') . '</textarea>
													</div>
													<div class="margiv-top-10">
														<a  onclick="$(this).closest(\'form\').submit()" class="btn green">
														Save </a>
														<a href="#" class="btn default">
														Cancel </a>
													</div>
													'; ?>
												</form>
											</div>
											<div id="tab_2-2" class="tab-pane">
												<form action="includes/upload_processor.php" method="post" role="form" enctype="multipart/form-data">
													<div class="form-group">
														<div class="fileinput fileinput-new" data-provides="fileinput">
															<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
																<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>
															</div>
															<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
															</div>
															<div>
																<span class="btn default btn-file">
																<span class="fileinput-new">
																Select image </span>
																<span class="fileinput-exists">
																Change </span>
                                                               <input type="hidden" name="MAX_FILE_SIZE" value="20000000"><!-- =20MB-->
																<input type="file" name="avatar" id="avatar">
																</span>
																<a href="#" class="btn default fileinput-exists" data-dismiss="fileinput">
																Remove </a>
															</div>
														</div>
													</div>
													<div class="margin-top-10">
														<a  onclick="$(this).closest('form').submit()" class="btn green">
														Save </a>
														<a href="#" class="btn default">
														Cancel </a>
													</div>
												</form>
											</div>
											<div id="tab_3-3" class="tab-pane">
												<form action="#">
													<div class="form-group">
														<label class="control-label">Current Password</label>
														<input type="password" class="form-control"/>
													</div>
													<div class="form-group">
														<label class="control-label">New Password</label>
														<input type="password" class="form-control"/>
													</div>
													<div class="form-group">
														<label class="control-label">Re-type New Password</label>
														<input type="password" class="form-control"/>
													</div>
													<div class="margin-top-10">
														<a href="#" class="btn green">
														Change Password </a>
														<a href="#" class="btn default">
														Cancel </a>
													</div>
												</form>
											</div>
											<div id="tab_4-4" class="tab-pane">
												<form action="#">
													<table class="table table-bordered table-striped">
													<tr>
														<td>
															 Subscribe to the newsletter.
														</td>
														<td>
															<label class="uniform-inline">
															<input type="checkbox" value=""/> Yes </label>
														</td>
													</tr>
													</table>
													<!--end profile-settings-->
													<div class="margin-top-10">
														<a href="#" class="btn green">
														Save Changes </a>
														<a href="#" class="btn default">
														Cancel </a>
													</div>
												</form>
											</div>
										</div>
									</div>
									<!--end col-md-9-->
								</div>
							</div>
							<div class="tab-pane" id="tab_1_4">
								<div class="row">
									<div class="col-md-12">
										<div class="add-portfolio">
											<span>
											Your items are viewed <?php echo getTotalViewsUserPresentations($user_id); ?> times. </span>
											<a href="presentations.php#basic" class="btn icn-only green">
											New presentation <i class="m-icon-swapright m-icon-white"></i>
											</a>
										</div>
									</div>
								</div>
								<!--end add-portfolio-->
                                <?php
                                $userPresentations = getPresentationsUser($user_id);

                                foreach($userPresentations as $userPresentation){
                                    echo '<div class="row portfolio-block">
									<div class="col-md-5">
										<div class="portfolio-text">
											<img src="../assets/admin/pages/media/profile/preview_square.jpg" alt=""/>
											<div class="portfolio-text-info">
												<h4>' . $userPresentation->name . '</h4>
												<p>
													 ' . $userPresentation->description . '
												</p>
											</div>
										</div>
									</div>
									<div class="col-md-5 portfolio-stat">
										<div class="portfolio-info">
											 Rating <span>';
                                                $avgScore = getAvgReview($userPresentation->getId());
                                                if($avgScore == 0) echo '<span title="Geen reviews">/</span>';
                                                else {
                                                    for($i = 1; $i < 6; $i++){
                                                        if($i <= $avgScore) echo '<i class="fa fa-star"></i>';
                                                        else echo '<i class="fa fa-star-o"></i>';
                                                    }
                                                }
                                            echo '</span>
										</div>
										<div class="portfolio-info">
											 Views <span>
											'. getPresentationsViews($userPresentation->id) .' </span>
										</div>
									</div>
									<div class="col-md-2">
										<div class="portfolio-btn">
											<a href="presentation_viewer.php?presentationID='. $userPresentation->id .'" class="btn bigicn-only">
											<span>
											View</span>
											</a>
										</div>
									</div>
								</div>';
                                }

                                ?>
							<!--end tab-pane-->
						</div>

					</div>
					<!--END TABS-->
				</div>
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
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init() // init quick sidebar
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>