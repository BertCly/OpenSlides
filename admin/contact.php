<?php include("header.php"); ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <?php include("page_header.php"); ?>
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
					Contact<small> me</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a href="index.html">Home</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">Contact</a>
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- Google Map -->
					<div class="row">
						<div id="map" class="gmaps margin-bottom-40" style="height:400px;">
						</div>
					</div>
					<div class="row margin-bottom-20">
						<div class="col-md-6">
							<div class="space20">
							</div>
							<h3 class="form-section">Contact</h3>
							<p>
								 Questions and no answers? Contact me.
							</p>
							<div class="well">
								<h4>Adress</h4>
								<address>
								<strong>Bert Clybouw, student of KU Leuven</strong><br>
                                    Celestijnenlaan 200A<br>
                                    3001 Leuven<br>
								<abbr title="Phone">P:</abbr> 0494 20 70 25</address>
								<address>
								<strong>Email</strong><br>
								<a href="mailto:bert.clybouw@student.kuleuven.be">
								bert.clybouw@student.kuleuven.be </a>
								</address>
								<ul class="social-icons margin-bottom-10">
									<li>
										<a href="https://www.linkedin.com/pub/bert-clybouw/65/6b2/a54" data-original-title="linkedin" class="linkedin">
										</a>
									</li>
                                    <li>
                                        <a href="https://twitter.com/BertClybouw" data-original-title="twitter" class="twitter">
                                        </a>
									</li>
								</ul>
							</div>
						</div>
                        <div class="col-md-6">
                            <div class="space20">
                            </div>
                            <!-- BEGIN FORM-->
                            <h3 class="form-section">Feedback</h3>
                            <p>
                                Found something? Having an idea?
                            </p>
                            <form id="feedbackFormContact" action="#">

                                <div class="form-group">
                                    <textarea id="feedbackInput" class="form-control" rows="3=6" placeholder="Feedback"></textarea>
                                </div>
                                <button type="button" id="addFeedbackContact" class="btn green">Send</button>
                            </form>
                            <div  id="sentContact" class="alert alert-success"  style="display: none">
                                <strong>Sent!</strong> You're awesome
                            </div>
                            <h3>Sent feedback</h3>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Message
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                global $db;
                                $feedbacks = getFeedbacks($_SESSION['user_id']);
                                $cnt = 1;
                                foreach($feedbacks as $feedback){
                                    echo '<tr>
                                    <td class="hidden-480">
                                        '.$cnt.'
                                    </td>
                                    <td class="hidden-480">
                                        '.date_format($feedback->CreationDate, "d/m/Y").'
                                    </td>
                                    <td>
                                        '.$feedback->Feedback.'
                                    </td>
                                </tr>';
                                    $cnt++;
                                }
                                ?>

                                </tbody>
                            </table>
                            <!-- END FORM-->
                        </div>
					</div>
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
<?php include("footer.php"); ?>
<script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
<script src="../assets/global/plugins/gmaps/gmaps.min.js" type="text/javascript"></script>
<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../assets/admin/pages/scripts/contact-us.js"></script>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init() // init quick sidebar
   ContactUs.init();

    $('#addFeedbackContact').click(function() {
        $.ajax({
            type: "POST",
            url: "../assets/global/dataConnection/ajaxActions.php",
            data: {action: "addFeedback",
                feedback: $('#feedbackInput').val(),
                userID: $('#userID').text(),
                url: document.location.href.match(/[^\/]+$/)[0]
            },
            success: function(result) {
                $('#feedbackFormContact').slideUp();
                $('#sentContact').show();
            }
        });
    });
});


</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>