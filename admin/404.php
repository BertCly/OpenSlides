<?php include("header.php"); ?>
<title>Presentation | 404</title>

<!-- BEGIN PAGE LEVEL STYLES -->
<link href="../assets/admin/pages/css/error.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
</head>
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
    <?php include("page_header.php"); ?>
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
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12 page-404">
					<div class="number">
						 404
					</div>
					<div class="details">
						<h3>Oops! You're lost.</h3>
						<p>
                            We can not find the page you're looking for.<br/>
                        <p>
                            We can not find the page you're looking for.<br>
                            <a href="index.html">
                                Return home </a>
                            or try the search bar below.
                        </p>
						</p>
						<form action="search.php" method="POST">
							<div class="input-group input-medium">
								<input name="search_text" type="text" class="form-control" placeholder="Keywords..">
								<span class="input-group-btn">
								<button type="submit" class="btn blue"><i class="fa fa-search"></i></button>
								</span>
							</div>
							<!-- /input-group -->
						</form>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
		<!-- BEGIN CONTENT -->
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
	<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
	<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
	<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
	<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init() // init quick sidebar
});
	</script>
	<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->
	</html>