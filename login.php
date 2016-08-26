<?php
/***************************************************************************
 *   Copyright (C) 2006 by Ken Papizan                                     *
 *   Copyright (C) 2008 by phpTimeClock Team                               *
 *   http://sourceforge.net/projects/phptimeclock                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.             *
 ***************************************************************************/

/**
 *  This module will authenticate a user to verify that they have the permissions to acceess either
 *  the reports, time editing, system options, or all of the above. Only one log-in is required
 *  as all permissions a user has access to is given to him.
 */

session_start();

include 'config.inc.php';
include 'header.php';
include 'theme/templates/mainstart.inc';
include 'topmain.php';
include './theme/templates/leftnavstart.inc';
include './theme/templates/leftnavend.inc';
include './theme/templates/beginmaincontent.inc';

// Determine if the user is trying to login-in for administration privleges.
if ($_REQUEST["login_action"] == "admin") {
    echo "
      <!-- Admin Login Interface -->
      <title>
         $title - Admin Login
      </title>";

    $self = $_SERVER['PHP_SELF'];

    // Determine if the user has entered his authentication credentials
    if (isset($_POST['login_userid']) && (isset($_POST['login_password']))) {
        $login_userid = $_POST['login_userid'];
        $login_password = crypt($_POST['login_password'], 'xy');

        // Determine if the user has sys or time access rights.
        $query = "select empfullname, employee_passwd, admin, time_admin from ".$db_prefix."employees where empfullname = '".$login_userid."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        while ($row = mysqli_fetch_array($result)) {
            $admin_username = "".$row['empfullname']."";
            $admin_password = "".$row['employee_passwd']."";
            $admin_auth = "".$row['admin']."";
            $time_admin_auth = "".$row['time_admin']."";
        }

        // Setup user permissions
        if (($login_userid == @$admin_username) && ($login_password == @$admin_password) && ($admin_auth == "1")) {
            $_SESSION['valid_user'] = $login_userid;
        } elseif (($login_userid == @$admin_username) && ($login_password == @$admin_password) && ($time_admin_auth == "1")) {
            $_SESSION['time_admin_valid_user'] = $login_userid;
        }

        // Determine if the user has report access rights.
        $query = "select empfullname, employee_passwd, reports from ".$db_prefix."employees where empfullname = '".$login_userid."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        while ($row=mysqli_fetch_array($result)) {
            $reports_username = "".$row['empfullname']."";
            $reports_password = "".$row['employee_passwd']."";
            $reports_auth = "".$row['reports']."";
        }

        if (($login_userid == @$reports_username) && ($login_password == @$reports_password) && ($reports_auth == "1")) {
            $_SESSION['valid_reports_user'] = $login_userid;
        }
    }

    // If the user is authorised send the user to the correct location
    if (isset($_SESSION['valid_user'])) {
        echo "
      <script type='text/javascript' language='javascript'>
         window.location.href = 'admin/index.php';
      </script>";
        exit;
    } elseif (isset($_SESSION['time_admin_valid_user'])) {
        echo "
      <script type='text/javascript' language='javascript'>
         window.location.href = 'admin/timeadmin.php';
      </script>";
        exit;
    } elseif (isset($_SESSION['valid_reports_user'])) {
            echo "
      
            You do not have administration access permission.
";
            exit;
    } else { // The user is either not valid or has not entered in his credentials.
	    echo '<div class="col-md-12"><div class="login-box">
  <div class="login-logo">
    <a href="index.php"><b>PHP TIMECLOCK <i class="fa fa-clock-o"></i></b>Admin Login</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    

    <form name="auth" method="post" action=" '.$self.' ">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="login_userid" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="login_password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-4">
	  <button type="submit" class="btn btn-primary btn-block btn-flat" onClick="admin.php">Log In</button>
	   <input type="hidden" name="login_action" value="admin">
        </div>
        <!-- /.col -->
      </div>
    </form>
    
    </div>
      <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
    </div>

';


        // Did the user enter invalid credentials
        if (isset($login_userid)) {
            echo "
            
                  Could not log you in. Either your username or password is incorrect.
           ";
        }
        echo "
         
      
      <script language=\"javascript\">
         document.forms['auth'].login_userid.focus();
      </script>";
    }
} else if (($use_reports_password == "yes") && ($_REQUEST["login_action"] == "reports")) { // Determine if the user is trying to log-in to reports
    echo "
      <!-- Reports Login Interface -->
      <title>
         $title - Reports Login
      </title>";

    $self = $_SERVER['PHP_SELF'];

    // Determine if the user has entered his authentication credentials
    if (isset($_POST['login_userid']) && (isset($_POST['login_password']))) {
        $login_userid = $_POST['login_userid'];
        $login_password = crypt($_POST['login_password'], 'xy');

        // Determine if the user has report access rights.
        $query = "select empfullname, employee_passwd, reports from ".$db_prefix."employees where empfullname = '".$login_userid."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        while ($row = mysqli_fetch_array($result)) {
            $reports_username = "".$row['empfullname']."";
            $reports_password = "".$row['employee_passwd']."";
            $reports_auth = "".$row['reports']."";
        }

        // Determine if the user is authorised to view reports
        if (($login_userid == @$reports_username) && ($login_password == @$reports_password) && ($reports_auth == "1")) {
            $_SESSION['valid_reports_user'] = $login_userid;
        } else if (($login_userid == @$reports_username) && ($login_password == @$reports_password)) { // User can view his own hours
            $_SESSION['valid_report_employee'] = $login_userid;
        }

        // Determine if the user has time or sys access rights.
        $query = "select empfullname, employee_passwd, admin, time_admin from ".$db_prefix."employees where empfullname = '".$login_userid."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        while ($row = mysqli_fetch_array($result)) {
            $admin_username = "".$row['empfullname']."";
            $admin_password = "".$row['employee_passwd']."";
            $admin_auth = "".$row['admin']."";
            $time_admin_auth = "".$row['time_admin']."";
        }

        if (($login_userid == @$admin_username) && ($login_password == @$admin_password) && ($admin_auth == "1")) {
            $_SESSION['valid_user'] = $login_userid;
        } elseif (($login_userid == @$admin_username) && ($login_password == @$admin_password) && ($time_admin_auth == "1")) {
            $_SESSION['time_admin_valid_user'] = $login_userid;
        }
    }

    // If the user supplied the proper credentials, send them to the proper location
    if (isset($_SESSION['valid_reports_user'])) {
        echo "
      <script type='text/javascript' language='javascript'>
         window.location.href = 'reports/index.php';
      </script>";
        exit;
    } else if (isset($_SESSION['valid_report_employee'])) {
        echo "
      <script type='text/javascript' language='javascript'>
         window.location.href = 'reports/employee_hours.php';
      </script>";
        exit;
    } else if ((isset($_SESSION['valid_user'])) || (isset($_SESSION['time_admin_valid_user']))) {
        echo "
      <br>

            You do not have report access permission.
";
        exit;
    } else { // The user is either not valid or has not entered in his credentials.

	    echo '<div class="col-md-12"><div class="login-box">
      <div class="login-logo">
        <a href="index.php"><b>PHP TIMECLOCK <i class="fa fa-clock-o"></i></b>Reports Login</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    

    <form name="auth" method="post" action=" '.$self.' ">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="login_userid" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="login_password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-4">
	  <button type="submit" class="btn btn-primary btn-block btn-flat" onClick="admin.php">Log In</button>
	   <input type="hidden" name="login_action" value="reports">
        </div>
        <!-- /.col -->
      </div>
    </form>
    
    </div>
      <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
    </div>

';
        // Determine if the user has supplied incorrect credentials.
        if (isset($login_userid)) {
            echo "
            
                  Could not log you in. Either your username or password is incorrect.
";
        }
        echo "

      <script language=\"javascript\">
         document.forms['auth'].login_userid.focus();
      </script>";
    }
} else { // The user has entered the URL directly, send to appropriate page
    if (($use_reports_password == "no") && ($_REQUEST["login_action"] == "reports")) {
        echo "
      <script type='text/javascript' language='javascript'>
         window.location.href = 'reports/index.php';
      </script>";
    } else {
        echo "
      <script type='text/javascript' language='javascript'>
         window.location.href = 'index.php';
      </script>";
    }
}

include 'footer.php';
include 'theme/templates/endmain.inc';
include 'theme/templates/footerscripts.inc';
?>
