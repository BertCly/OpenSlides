<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
				<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
				<li class="sidebar-toggler-wrapper">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<!-- END SIDEBAR TOGGLER BUTTON -->
				</li>
				<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
				<li class="sidebar-search-wrapper hidden-xs">
					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
					<!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
					<!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                    <form class="sidebar-search" action="presentations.php" method="POST">
                        <a href="javascript:;" class="remove">
                            <i class="icon-close"></i>
                        </a>
                        <div class="input-group">
                            <input name="search_query" id="search_query" type="text" class="form-control" placeholder="Search...">
							<span class="input-group-btn">
							<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
							</span>
                        </div>
                    </form>
					<!-- END RESPONSIVE QUICK SEARCH FORM -->
				</li>
                <li class="start <?php if (strpos($_SERVER['PHP_SELF'], 'index.php')) echo 'active';?> ">
					<a href="index.php">
					<i class="icon-home"></i>
					<span class="title">Home</span>
					<span class="selected"></span>
					</a>
				</li>
                <li <?php if (strpos($_SERVER['PHP_SELF'], 'presentations.php')) echo 'class="active"';?>>
                    <a href="presentations.php">
                        <i class="icon-note"></i>
                        <span class="title">Presentations</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li <?php if (strpos($_SERVER['PHP_SELF'], 'attend.php')) echo 'class="active"';?>>
                    <a href="attend.php">
                        <i class="fa fa-caret-square-o-right"></i>
                        <span class="title">Attend presentation</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li <?php if (strpos($_SERVER['PHP_SELF'], 'profile.php')) echo 'class="active"';?>>
                    <a href="profile.php">
                        <i class="icon-user"></i>
                        <span class="title">My account</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <?php if($_SESSION['roleID'] == 'AD'){ ?>
                <li <?php if (strpos($_SERVER['PHP_SELF'], 'faq.php')) echo 'class="active"';?>>
                    <a href="faq.php">
                        <i class="icon-question"></i>
                        <span class="title">FAQ</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li <?php if (strpos($_SERVER['PHP_SELF'], 'users.php')) echo 'class="active"';?>>
                    <a href="users.php">
                        <i class="icon-users"></i>
                        <span class="title">UserData</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li <?php if (strpos($_SERVER['PHP_SELF'], 'presentations_admin.php')) echo 'class="active"';?>>
                    <a href="presentations_admin.php">
                        <i class="icon-note"></i>
                        <span class="title">Presentations for admin</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <?php } ?>
                <li <?php if (strpos($_SERVER['PHP_SELF'], 'contact.php')) echo 'class="active"';?>>
                    <a href="contact.php">
                        <i class="icon-envelope-open"></i>
                        <span class="title">Contact</span>
                        <span class="selected"></span>
                    </a>
                </li>
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>