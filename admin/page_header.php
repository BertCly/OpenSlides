<div class="page-header-inner">
    <!-- BEGIN LOGO -->
    <div class="page-logo">
        <a href="index.php">
            <img src="../assets/admin/layout/img/openslides_logo.png" height="44px" alt="logo" class="logo-default"/>
        </a>
        <div class="menu-toggler sidebar-toggler hide">
            <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
        </div>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
    </a>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <!-- BEGIN TOP NAVIGATION MENU -->
    <div class="top-menu">
        <ul class="nav navbar-nav pull-right">
            <!-- BEGIN NOTIFICATION DROPDOWN -->
            <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                <?php
                $userID = $_SESSION['user_id'];
                require_once("../assets/global/dataConnection/queries.php");
                $notifications = getNotificationsUser($userID);
                $number = 0;
                foreach ($notifications as $not) {
                    if ($not->Read == 0)
                        $number++;
                }
                ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <i class="icon-bell"></i>
                    <span class="badge badge-default">
                        <?php echo $number; ?> </span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <p>
                            <?php
                            echo 'You have ' . $number . ' notifications';
                            ?>
                        </p>
                    </li>
                    <li>
                        <ul class="dropdown-menu-list scroller" style="height: 250px;">
                            <?php
                            foreach ($notifications as $not) {
                                echo '<li><a ';
                                if($not->Read == false) echo ' style="background-color: #f5f5f5 !important;"';
                                if ($not->Type == 'newPresentation')
                                    echo 'href="' . ADMIN_HOME . '/presentation_builder.php?presentationID=' . $not->Url . '"><span class="label label-sm label-icon label-success"><i class="fa fa-plus"></i></span>';
                                elseif ($not->Type == 'UserLoggedIn')
                                    echo 'href = "#"></a><span class="label label-sm label-icon label-danger"><i class="fa fa-bolt"></i></span>';
                                elseif ($not->Type == 'meta')
                                    echo 'href = "#"><span class="label label-sm label-icon label-warning"><i class="fa fa-bell-o"></i></span>';
                                elseif ($not->Type == 'meta')
                                    echo 'href = "' . ADMIN_HOME . '/page_calendar.php"><span class="label label-sm label-icon label-info"><i class="fa fa-bullhorn"></i></span>';
                                elseif ($not->Type == 'meta')
                                    echo 'href = "#"><div class="label label-danger"><i class="fa fa-trash-o"></i>';
                                else
                                    echo 'href = "#"><div class="label label-default"><i class="fa fa-bell-o"></i>';
                                echo $not->Message . '<span class="time">';
                                //<script type="text/javascript"> //timeAgo('. date_format($not->DateTime, 'D M d Y H:i:s').'); </script>
                                echo '</span>
                                        </a>
                                    </li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="external">
                        <a href="profile.php">
                            Show all notifications <i class="m-icon-swapright"></i>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- END NOTIFICATION DROPDOWN -->


            <!-- BEGIN USER LOGIN DROPDOWN -->
            <li class="dropdown dropdown-user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <img alt="" class="img-circle" src="../assets/admin/layout/img/avatar.png"/>
<!--                        <i class="icon-user img-circle"></i>-->
                    <span class="username">
                        <?php echo $_SESSION['username'] ?></span><span id="userID" class="hidden"><?php echo $userID; ?></span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="profile.php">
                            <i class="icon-user"></i> My account</a>
                    </li>
                    <li class="divider">
                    </li>
                    <li>
                        <a href="includes/logout.php">
                            <i class="icon-key"></i> Log Out </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- END TOP NAVIGATION MENU -->
</div>
<!-- END HEADER INNER -->