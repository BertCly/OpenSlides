<?php include("header.php"); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/select2/select2.css"/>
<link href="../assets/admin/pages/css/search.css" rel="stylesheet" type="text/css">
<!-- END PAGE LEVEL STYLES -->
</head>
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
					Presentations <small> and all for free</small>
					</h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li class="btn-group">
                            <a style="color: white" href="presentation_builder.php?type=1" class="btn blue">
                                New Presentation<i class="fa fa-plus"></i>
                            </a>

                        </li>
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="index.php">Home</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a>Presentations</a>
                        </li>
                    </ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-8">
                    <div class="row search-form-default">
                        <div class="col-md-12">
                            <form action="#">
                                <div class="input-group">
                                    <div class="input-cont">
                                        <input id="SearchFieldPresentations" type="text" placeholder="Search..." class="form-control"/>
                                    </div>
												<span class="input-group-btn">
												<button id="IdButtonSearch" type="button" class="btn green-haze">
                                                    Search &nbsp; <i class="m-icon-swapright m-icon-white"></i>
                                                </button>
												</span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                    if(isset($_GET['page'])) $currentPage = $_GET['page'];
                    else $currentPage = 1;

                    $whereSearch = "";
                    $urlSearchParam = '';
                    if(isset($_GET['search_query'])){
                        $searchQuery = $_GET['search_query'];
                        $urlSearchParam= "&search_query=" . $searchQuery;
                        $whereSearch = " WHERE c.Name LIKE '%$searchQuery%'
                        OR c.Description LIKE '%$searchQuery%' OR u.Username LIKE '%$searchQuery%' ";
                    }
                    elseif(isset($_POST['search_query'])){
                        $searchQuery = $_POST['search_query'];
                        $urlSearchParam= "&search_query=$searchQuery";
                        $whereSearch = " WHERE c.Name LIKE '%$searchQuery%'
                        OR c.Description LIKE '%$searchQuery%' OR u.Username LIKE '%$searchQuery%' ";
                    }

                    $iTotalRecords = $db->get_var('Select count(*) AS iTotalRecords FROM tblPresentations c INNER JOIN tblUsers u ON c.CreatorID = u.ID '.$whereSearch);
                    $pages = ceil($iTotalRecords / 20);

                    $end = $currentPage * 20;
                    $start = $end - 20;

                    $presentations = $db->get_results(';WITH Results_CTE AS
                        (
                            SELECT c.ID, c.Name, u.Username, c.CreationDate, c.Description, c.FilePath, COUNT(v.ID) AS Views,

                                ROW_NUMBER() OVER (ORDER BY c.CreationDate DESC) AS RowNum
                            FROM tblPresentations c INNER JOIN tblUsers u ON c.CreatorID = u.ID left JOIN tblPresentationViews v ON c.ID = v.PresentationID
                            '.$whereSearch.'
                            GROUP BY c.ID, c.Name, u.Username, c.CreationDate, c.Description, c.FilePath
                        )
                        SELECT *
                        FROM Results_CTE
                        WHERE RowNum >= ' . $start . '
                        AND RowNum < ' . $end . '');

                    if(count($presentations) > 0){
                        foreach($presentations as $presentation){
                        $dateFormatted = date_format($presentation->CreationDate, "d/m/Y");
                        echo "<div class='search-classic'>
                            <div class='pic hidden-xs'>

                                <a href='presentation_page.php?presentationID=$presentation->ID'><img src='../assets/admin/pages/media/profile/preview_square.jpg' alt=''/></a>
                            </div>
                            <div class='desc'>
                                <h4>
                                    <a href='presentation_page.php?presentationID=$presentation->ID'>
                                        $presentation->Name </a>
                                </h4>
                                <span>by $presentation->Username - $dateFormatted - $presentation->Views views</span>
                                <p>
                                    $presentation->Description
                                </p>
                            </div>
                        </div>";

                    }
                        ?>
                        <div class="margin-top-20">
                            <ul class="pagination">
                                <?php

                                if($currentPage > 1 ){
                                    $currentPageMin = $currentPage - 1;
                                    echo '<li>
                                <a href="presentations.php?page='.$currentPageMin.$urlSearchParam.'">
                                    Vorige </a>
                            </li>';
                                }
                                for ($i = 1; $i <= $pages; $i++) {
                                    echo '<li';
                                    if($currentPage == $i) echo ' class="active"';
                                    echo '>
                                <a href="presentations.php?page='.$i.$urlSearchParam.'">
                                   '.$i.'  </a>
                            </li>';
                                }
                                if($currentPage != $pages) {
                                    $currentPagePlus = $currentPage +1;
                                    echo '<li>
                                <a href="presentations.php?page='.$currentPagePlus.$urlSearchParam.'">
                                    Volgende</a>
                            </li>';
                                }

                                ?>
                            </ul>
                        </div>
                    <?php
                    }
                    else echo 'No presentations found for ' . $searchQuery;
                    ?>
				</div>
			</div>
            <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Nieuwe Presentations. Kies hoe je ze maakt.</h4>
                        </div>
                        <div class="modal-body">
                            <div class="btn-group btn-group btn-group-justified chooseNewPresentationType">
                                <a href="presentation_builder.php?type=1" class="btn blue">
                                    EIGEN DOC </a>
                                <a href="#" class="btn yellow">
                                    INDEPEN BUILDER </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
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
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
        jQuery(document).ready(function() {    
           Metronic.init(); // init metronic core components
           Layout.init(); // init current layout
           QuickSidebar.init() // init quick sidebar

            $('#IdButtonSearch').click(function() {
                window.location = "presentations.php?search_query=" + $('#SearchFieldPresentations').val();
            });

            <?php
            //                    toPrintIn Jquery ready
                $jqueryPrint = '';
                foreach($presentations as $presentation){
                    if($presentation->FilePath != null){ $jqueryPrint .= "PDFJS.getDocument('../userData/presentations/$presentation->FilePath').then(function(pdf) {
                            // Using promise to fetch the page
                            pdf.getPage(1).then(function(page) {
                                var scale = 1.5;
                                var viewport = page.getViewport(scale);

                                //
                                // Prepare canvas using PDF page dimensions
                                //
                                var canvas = document.getElementById('the-canvas$presentation->ID');
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
                    }
                }
//echo $jqueryPrint;
 ?>


        });
    </script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>