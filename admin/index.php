<?php include("header.php"); ?>
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="../assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link href="../assets/admin/layout/css/index.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE STYLES -->
</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<?php
    include("page_header.php");
    require_once("../assets/global/dataConnection/queries.php");
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
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
					Home <small>welkom op de Presentation community</small>
					</h3>

					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
            <div class="tiles">
                <a href="presentations.php">
                <div class="tile bg-blue-steel">
                    <div class="tile-body">
                        <i class="fa fa-file-pdf-o"></i>
                    </div>
                    <div class="tile-object">
                        <div class="name">
                            Presentations
                        </div>
                        <div class="number">
                            <?php echo $db->get_var('Select count(*) AS iTotalRecords FROM tblPresentations'); ?>
                        </div>
                    </div>
                </div>
                </a>
                <?php if($_SESSION['roleID'] != 'LL'){ ?>
                <a href="presentation_viewer.php">
                <div class="tile bg-blue-steel">
                    <div class="tile-body">
                        <i class="fa fa-plus"></i>
                    </div>
                    <div class="tile-object">
                        <div class="name">
                            New Presentation
                        </div>
                    </div>
                </div>
                </a>
                <?php } ?>
                <?php if($_SESSION['roleID'] != 'LL'){ ?>
                    <a href="profile.php#tab_1_4">
                        <div class="tile bg-blue-steel">
                            <div class="tile-body">
                                <i class="fa fa-folder-open-o"></i>
                            </div>
                            <div class="tile-object">
                                <div class="name">
                                    My Presentations
                                </div>
                            </div>
                        </div>
                    </a>
                <?php } ?>
                <?php if($_SESSION['roleID'] != 'AD'){ ?>
                <a href="users.php">
                <div class="tile bg-green">
                    <div class="corner">
                    </div>
                    <div class="tile-body">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="tile-object">
                        <div class="name">
                            Users
                        </div>
                    </div>
                </div>
                </a>
                <?php } ?>
                <div class="tile double bg-blue-madison">
                    <div class="tile-body">
                        <img src="../assets/admin/pages/media/profile/photo1.jpg" alt="">
                        <h4>Announcement</h4>
                        <p>
                            This is a test version. This project is begging for feedback. Please give him some.
                        </p>
                    </div>
                    <div class="tile-object">
                        <div class="name">
                            Bert Clybouw
                        </div>
                        <div class="number">
                            21 Oct 2014
                        </div>
                    </div>
                </div>
                <a href="profile.php">
                <div class="tile bg-purple-studio">
                    <div class="tile-body">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="tile-object">
                        <div class="name">
                            My account
                        </div>
                    </div>
                </div>
                </a>
                <a href="#getStarted" class="scroll">
                <div id="howItWorks" class="tile bg-yellow-saffron">
                    <div class="corner">
                    </div>
                    <div class="tile-body">
                        <i class="fa fa-lightbulb-o"></i>
                    </div>
                    <div class="tile-object">
                        <div class="name">
                            How?
                        </div>
                    </div>
                </div>
                </a>
                <a href="contact.php">
                <div class="tile bg-green-meadow">
                    <div class="tile-body">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div class="tile-object">
                        <div class="name">
                            Feedback
                        </div>
                        <div class="number">
                            <?php echo $db->get_var('Select count(*) AS CntFeedback FROM tblFeedbacks WHERE UserID = '.$_SESSION['user_id']); ?>
                        </div>
                    </div>
                </div>
                </a>
                <a target="_blank" href="https://twitter.com/bertclybouw">
                <div class="tile double bg-grey-cascade">
                    <div class="tile-body">
                        <img src="../assets/admin/pages/media/profile/photo2.jpg" width="50px" alt="" class="pull-right">
                        <h3>@BertClybouw</h3>
                        <p>
                            OpenSlides will be one great presentation platform. Open for anything, anywhere, anytime
                        </p>
                    </div>
                    <div class="tile-object">
                        <div class="name">
                            <i class="fa fa-twitter"></i>
                        </div>
                        <div class="number">
                            11:45, 18 Oct
                        </div>
                    </div>
                </div>
                </a>

            </div>

            <div id="getStarted">
                <div class="services-block content content-center" id="services">
                    <div class="container">
                        <h2><strong>What</strong> is it?</h2>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12 item">
                                <i class="fa fa-file-pdf-o"></i>
                                <a href="#solvePresentationManual" class="scroll"><h3>Online presentations</h3></a>
                                <p>Save your presentation as PDF<br/>
                                and upload it</p>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 item">
                                <i class="fa fa-users"></i>
                                <a href="#buildPresentationManual" class="scroll"><h3>Choose your public</h3></a>
                                <p>Just as Dropbox you decide<br/> who has access</p>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 item">
                                <i class="fa fa-refresh"></i>
                                <h3>Interactive presentations</h3>
                                <p>With your audience</p>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 item">
                                <i class="fa fa-bar-chart-o"></i>
                                <h3>Statistics</h3>
                                <p>Get a reputation <br/>with quality slides</p>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="OpenSlidesManual" id="buildPresentationManual">
                    <div class="container">
                        <div class="row">
                            <div class="title animate fadeIn animated">
                                <h2><strong>Upload</strong> a Presentation </h2>
                            </div>
                            <div class="space"></div>
                            <div class="com-sec">
                                <div class="left-text animate fadeIn animated"><span class="title">Press on 'New Presentation'</span>

                                    <div class="clear"></div>
                                    <p>All presentations are uploaded by our community.</p>
                                    <a href="presentation_builder.php">New Presentation</a></div>
                                <div class="divider"><img src="../assets/global/img/manual/timeline-divider.png" alt="">
                                </div>
                                <div class="press-img image"><a href="presentation_builder.php"><img class="animate fadeIn animated"
                                                                                                      src="../assets/global/img/manual/new_presentation.jpg"
                                                                                                      alt=""></a></div>
                            </div>
                            <div class="clear"></div>
                            <div class="com-sec">
                                <div class="press-img image">
                                    <img class="animate fadeIn animated"
                                         src="../assets/global/img/manual/select_file.JPG" alt="">
                                </div>
                                <div class="divider"><img src="../assets/global/img/manual/timeline-divider.png" alt="">
                                </div>
                                <div class="right-text  animate fadeIn animated"><span class="title">Select your presentation in PDF format.</span>

                                    <div class="clear"></div>
                                    <p>You can upload Keynote or Powerpoint presentations if you save them as PDF. </p>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="com-sec">
                                <div class="left-text animate fadeIn animated"><span class="title">
                                                Press 'Edit' and fill in properties of the presentation.
                                            </span>

                                    <div class="clear"></div>

                                    <p>The better you fill this in, the better your presentation can be found. </p>
                                </div>
                                <div class="divider"><img src="../assets/global/img/manual/timeline-divider.png" alt="">
                                </div>
                                <div class="press-img image"><img class="animate fadeIn animated"
                                                                  src="../assets/global/img/manual/presentation_settings.JPG"
                                                                  alt=""></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
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
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="../assets/global/plugins/jquery.scrollTo.min.js"></script>

<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   QuickSidebar.init() // init quick sidebar

    /* set variables locally for increased performance */
    $('#getStarted').on('click', '.goToSolvePresentation', function(e) {
        Metronic.scrollTo($('#solvePresentationManual'));
        e.preventDefault();
    });
    $('#howItWorks').click(function() {
        $('#getStarted').fadeIn();
    });
    $(".scroll").on("click", function(event) {
        event.preventDefault();//the default action of the event will not be triggered
        $("html, body").animate({scrollTop:($("#"+this.href.split("#")[1]).offset().top)}, 600);
    });
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>