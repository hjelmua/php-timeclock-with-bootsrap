<?php

include '../theme/templates/leftnavstart.inc';

//user moved here from topmain
if (isset($_SESSION['valid_user'])) {
$logged_in_user = $_SESSION['valid_user'];
echo '
      <div class="user-panel">
        <div class="pull-left image">
          <h3><i class="fa fa-user-secret text-orange"></i></h3>
        </div>
        <div class="pull-left info">
          <p>'.$logged_in_user.'</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
        </div>
      </div>';
}

else if (isset($_SESSION['time_admin_valid_user'])) {
    $logged_in_user = $_SESSION['time_admin_valid_user'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user-secret text-red"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';

} else if (isset($_SESSION['valid_reports_user'])) {
    $logged_in_user = $_SESSION['valid_reports_user'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user-plus"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';
} else if (isset($_SESSION['valid_report_employee'])) {
    $logged_in_user = $_SESSION['valid_report_employee'];
    echo '
          <div class="user-panel">
            <div class="pull-left image">
              <h3><i class="fa fa-user"></i></h3>
            </div>
            <div class="pull-left info">
              <p>'.$logged_in_user.'</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Logged in</a>
            </div>
          </div>';
}

// end user moved here from topmain


echo '     <ul class="sidebar-menu">
        <li class="header">ADMIN NAVIGATION</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i> <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="useradmin.php"><i class="fa fa-circle-o"></i> User Summary</a></li>
	    <li><a href="usercreate.php"><i class="fa fa-user-plus"></i> Create New User</a></li>
	    <li><a href="usersearch.php"><i class="fa fa-search"></i> User Search</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-building"></i> <span>Offices</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="officeadmin.php"><i class="fa fa-circle-o"></i> Office Summary</a></li>
	    <li><a href="officecreate.php"><i class="fa fa-circle-o"></i> Create New Office</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i> <span>Groups</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="groupadmin.php"><i class="fa fa-circle-o"></i> Group Summary</a></li>
	    <li><a href="groupcreate.php"><i class="fa fa-circle-o"></i> Create New Group</a></li>
          </ul>
        </li>	
        <li class="treeview">
          <a href="#">
            <i class="fa fa-sign-out"></i> <span>In/Out Status</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="statusadmin.php"><i class="fa fa-circle-o"></i> Status Summary</a></li>
	    <li><a href="statuscreate.php"><i class="fa fa-circle-o"></i> Create Status</a></li>
          </ul>
        </li>	
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Miscellaneous</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="timeadmin.php"><i class="fa fa-clock-o"></i> Modify Time</a></li>
            <li><a href="sysedit.php"><i class="fa fa-code"></i> Edit System Settings</a></li>
	    <li><a href="database_management.php"><i class="fa fa-database"></i> Manage Database</a></li>
          </ul>
        </li>
      </ul>';

include '../theme/templates/leftnavend.inc';
include '../theme/templates/beginmaincontent.inc';

?>
