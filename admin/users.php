<?php include("header.php"); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link href="../assets/admin/pages/css/search.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
</head>
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
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
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							 Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
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
					Users <small>data</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a href="index.html">Home</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">Users</a>
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
                    <div class="row search-form-default">
                        <div class="col-md-12">
                            <form class="form-inline" action="#">
                                <div class="input-group">
                                    <div class="input-cont">
                                        <input type="text" placeholder="Search..." class="form-control"/>
                                    </div>
                                    <span class="input-group-btn">
                                    <button type="button" class="btn green">
                                    Search &nbsp; <i class="m-icon-swapright m-icon-white"></i>
                                    </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th>
                                 Photo
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                 Username
                            </th>
                            <th>
                                 Email
                            </th>
                            <th>
                                 Role
                            </th>
                            <th>
                                 Member Since
                            </th>
                            <th>
                                actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        global $db;
                        $users = $db->get_results('SELECT * FROM tblUsers WHERE Active = 1');
                        foreach ($users as $user) {
                            echo '<tr><td><img src="../assets/admin/pages/media/profile/avatar1.jpg" alt=""/></td>';
                            echo '<td>' . $user->FirstName . ' ' . $user->Name . '</td>';
                            echo '<td>' . $user->Username . '</td>';
                            echo '<td>' . $user->Email . '</td>';
                            echo '<td>' . $user->RoleID . '</td>';
                            $date1 = $user->Creationdate->format('Y-m-d');
                            //days member
                            $date2 = date('Y-m-d');
                            $diff = abs(strtotime($date2) - strtotime($date1));
                            echo '<td>' .  floor($diff/(60*60*24)) . ' Day(s)</td>';
                            echo '<td><a class="btn default btn-xs red-stripe" href="user.php?userID='.$user->ID.'">View </a></td>
                                </tr>';
                        }
                        ?>

                        </tbody>
                        </table>
                    </div>
                    <div class="margin-top-20">
                        <ul class="pagination">
                            <li>
                                <a href="#">
                                Prev </a>
                            </li>
                            <li class="active">
                                <a href="#">
                                1 </a>
                            </li>
                            <li>
                                <a href="#">
                                Next </a>
                            </li>
                        </ul>
                    </div>
				</div>
				<!--end tabbable-->
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
<script type="text/javascript" src="../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="../assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../assets/admin/pages/scripts/search.js"></script>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init() // init quick sidebar
   Search.init();
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>