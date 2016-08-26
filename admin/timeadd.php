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

session_start();

/**
 * This module will add time to an employee's record and place the add in the audit record as well.
 */

include '../config.inc.php';
include 'header_date.php';
include 'topmain.php';
include 'leftmain-time.php';

echo "<title>$title - Add Time</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (($timefmt == "G:i") || ($timefmt == "H:i")) {
  $timefmt_24hr = '1';
  $timefmt_24hr_text = '24 hr format';
  $timefmt_size = '5';
} else {
  $timefmt_24hr = '0';
  $timefmt_24hr_text = '12 hr format';
  $timefmt_size = '8';
}

// Make sure they are a valid user
if ((!isset($_SESSION['valid_user'])) && (!isset($_SESSION['time_admin_valid_user']))) {
    echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
    echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Administration</td></tr>\n";
    echo "  <tr class=right_main_text>\n";
    echo "    <td align=center valign=top scope=row>\n";
    echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
    echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
    echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
    echo "      </table><br /></td></tr></table>\n";
    exit;
}

if ($request == 'GET') { // Display employee add time interface
    if (!isset($_GET['username'])) { // Ensure someone is logged in.
        echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
        echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Error!</td></tr>\n";
        echo "  <tr class=right_main_text>\n";
        echo "    <td align=center valign=top scope=row>\n";
        echo "      <table width=300 border=0 cellpadding=5 cellspacing=0>\n";
        echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
        echo "        <tr class=right_main_text><td align=center>Go back to the <a class=admin_headings href='timeadmin.php'>Modify Time</a> page to add a time.</td></tr>\n";
        echo "      </table><br /></td></tr></table>\n";
        exit;
    }

	/*
    $get_user = stripslashes($_GET['username']);

    disabled_acct($get_user);



*/
    $get_user = addslashes($get_user);

    $query = "select * from ".$db_prefix."employees where empfullname = '".$get_user."' order by empfullname";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row=mysqli_fetch_array($result)) {
        $username = stripslashes("".$row['empfullname']."");
        $displayname = stripslashes("".$row['displayname']."");
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

    $get_user = stripslashes($_GET['username']);

	/*
    echo "    <td align=left class=right_main scope=col>\n";
    echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
    echo "        <tr class=right_main_text>\n";
    echo "          <td valign=top>\n";
    echo "            <br />\n";
	*/
	
	

	echo '<div class="row">
        <div class="col-md-6">
          <div class="box box-info"> ';
    echo '<div class="box-header with-border">
	                 <h3 class="box-title">Add Time</h3>
	               </div><div class="box-body">';
    echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
    echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
    echo '<div class="form-group"><label>Username:</label><div class="input-group">';
    echo "               <input type='hidden' name='post_username' value=\"$username\">$username\n";
    echo '</div></div>';
    echo '<div class="form-group"><label>Display Name:</label><div class="input-group">';
    echo "               <input type='hidden' name='post_displayname' value=\"$displayname\">$displayname\n";
        echo '</div></div>';
    echo '<div class="form-group"><label>Date: ('.$tmp_datefmt.')</label><div class="input-group">';
    echo "              <input class='form-control' type='text' size='12' maxlength='12' id='datepicker' name='post_date'>&nbsp;*&nbsp;&nbsp;&nbsp;\n";
        echo '</div></div>';

 
echo'    <div class="bootstrap-timepicker">
    	                   <div class="form-group">
    	                     <label>Time: ('.$timefmt_24hr_text.')</label>';

echo'    	                     <div class="input-group">
    	                       <input type="text" size="10" maxlength="10" class="form-control timepicker" name="post_time">';
echo'    	                       <div class="input-group-addon">
    	                         <i class="fa fa-clock-o"></i>
    	                       </div>
    	                     </div>
    	                     <!-- /.input group -->
    	                   </div>
    	                   <!-- /.form group -->
    	                 </div>
    	               ';


//    echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Time:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'> <div class='bootstrap-timepicker'><input id='timepicker1' type='text' class='form-control bootstrap-timepicker timepicker' size='10'  name='post_time'><i class='icon-time'></i>&nbsp;*&nbsp;&nbsp; <a style='text-decoration:none;font-size:11px;color:#27408b;'>($timefmt_24hr_text)</a></div></td></tr>\n";
    echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
    echo "                <input type='hidden' name='timefmt_24hr' value=\"$timefmt_24hr\">\n";
    echo "                <input type='hidden' name='timefmt_24hr_text' value=\"$timefmt_24hr_text\">\n";
    echo "                <input type='hidden' name='timefmt_size' value=\"$timefmt_size\">\n";

    // query to populate dropdown with statuses //

    $query2 = "select * from ".$db_prefix."punchlist order by punchitems asc";
    $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
    echo '<div class="form-group"><label>Status:</label>';
    echo "             <select class='form-control select2' name='post_statusname'>\n";
    echo "                      <option value ='1'>Choose One</option>\n";

    while ($row2=mysqli_fetch_array($result2)) {
        echo "                        <option>".$row2['punchitems']."</option>\n";
    }
    echo "                      </select>&nbsp;*\n";
    echo '</div>';
    ((mysqli_free_result($result2) || (is_object($result2) && (get_class($result2) == "mysqli_result"))) ? true : false);
    echo '<div class="form-group"><label>Notes:</label><div class="input-group">';
    echo "              <input type='text' size='25' maxlength='250' name='post_notes'>\n";
            echo '</div></div>';
    if ($require_time_admin_edit_reason == "yes") {
	    echo '<div class="form-group"><label>Reason For Addition:</label><div class="input-group">';
        echo "              <input type='text' size='25' maxlength='250' name='post_why'>&nbsp;*\n";
	echo '</div></div>';
    } else if ($require_time_admin_edit_reason == "no") {
	    echo '<div class="form-group"><label>Reason For Addition:</label><div class="input-group">';
        echo "     <input type='text' size='25' maxlength='250' name='post_why'>\n";
	echo '</div></div>';
    }
    echo "              *&nbsp;required&nbsp;\n";
    echo "            \n";

    echo "            \n";
    echo "              \n";
    echo '<div class="box-footer">
                <button type="submit" name="submit" value="Add Time" class="btn btn-info">Add time</button>
                <button type="submit" name="cancel" class="btn btn-default pull-right">Cancel</button>   
              </div></form>';

    echo '</div></div></div></div>';
    include '../theme/templates/endmaincontent.inc';
    include '../footer.php';
	include '../theme/templates/controlsidebar.inc'; 
	include '../theme/templates/endmain.inc';
	include '../theme/templates/reportsfooterscripts.inc';
    exit;
} elseif ($request == 'POST') { // Add the time for the employee
    $get_user = stripslashes($_POST['get_user']);
    $post_username = stripslashes($_POST['post_username']);
    $post_displayname = stripslashes($_POST['post_displayname']);
    $post_date = $_POST['post_date'];
    $post_time = $_POST['post_time'];
    $post_statusname = $_POST['post_statusname'];
    $post_notes = $_POST['post_notes'];
    $timefmt_24hr = $_POST['timefmt_24hr'];
    $timefmt_24hr_text = $_POST['timefmt_24hr_text'];
    $timefmt_size = $_POST['timefmt_size'];
    $date_format = $_POST['date_format'];
    $post_why = $_POST['post_why'];

    $get_user = addslashes($get_user);
    $post_username = addslashes($post_username);
    $post_displayname = addslashes($post_displayname);

    // begin post validation //
    if (!empty($get_user)) {
        $query = "select * from ".$db_prefix."employees where empfullname = '".$get_user."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
            $tmp_get_user = "".$row['empfullname']."";
        }
        if (!isset($tmp_get_user)) {
            echo "Something is fishy here.\n";
            exit;
        }
    }

    if (!empty($post_username)) {
        $query = "select * from ".$db_prefix."employees where empfullname = '".$post_username."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
            $tmp_username = "".$row['empfullname']."";
        }
        if (!isset($tmp_username)) {
            echo "Something is fishy here.\n";
            exit;
        }
    }

    if (!empty($post_displayname)) {
        $query = "select * from ".$db_prefix."employees where empfullname = '".$post_username."' and displayname = '".$post_displayname."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        while ($row=mysqli_fetch_array($result)) {
            $tmp_post_displayname = "".$row['displayname']."";
        }
        if (!isset($tmp_post_displayname)) {
            echo "Something is fishy here.\n";
            exit;
        }
    }

    if (!empty($post_statusname)) {
        if ($post_statusname != '1') {
            $query = "select * from ".$db_prefix."punchlist where punchitems = '".$post_statusname."'";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

            while ($row=mysqli_fetch_array($result)) {
                $punchitems = "".$row['punchitems']."";
                $color = "".$row['color']."";
            }
            ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
            if (!isset($punchitems)) {
                echo "Something is fishy here.\n";
            exit;
            }
        } else {
            $punchitems = '1';
        }
    }

    if (($timefmt == "G:i") || ($timefmt == "H:i")) {
        $tmp_timefmt_24hr = '1';
        $tmp_timefmt_24hr_text = '24 hr format';
        $tmp_timefmt_size = '5';
    } else {
        $tmp_timefmt_24hr = '0';
        $tmp_timefmt_24hr_text = '12 hr format';
        $tmp_timefmt_size = '8';
    }

    if (($timefmt_24hr != $tmp_timefmt_24hr) || ($timefmt_24hr_text != $tmp_timefmt_24hr_text) || ($timefmt_size != $tmp_timefmt_size)) {
        echo "Something is fishy here.\n";
        exit;
    }
    if ($date_format != $js_datefmt) {
        echo "Something is fishy here.\n";
        exit;
    }

    // Escape input
//    $post_notes = ereg_replace("[^[:alnum:] \,\.\?-]", "", $post_notes);
    $post_notes = preg_replace('/' . "[^[:alnum:] \,\.\?-]" . '/', "", $post_notes);
    if ($post_notes == "") {
        $post_notes = " ";
    }

    // Escape admin reason for SQL
    if (empty($post_why)) {
        $post_why = '';
    } else {
//        $post_why = ereg_replace("[^[:alnum:] \,\.\?-]", "", $post_why);
	$post_why = preg_replace('/' . "[^[:alnum:] \,\.\?-]". '/', "", $post_why);
    }

    // end post validation //

    /* This whole section is commented out, we need to look into it.
    if ($get_user != $post_username) {
        exit;
    }
    if (($timefmt_24hr !== '0') && ($timefmt_24hr !== '1')) {
        exit;
    }
    if (($timefmt_24hr_text !== '24 hr format') && ($timefmt_24hr_text !== '12 hr format')) {
        exit;
    }
    if (($timefmt_size != '5') && ($timefmt_size != '7')) {
        exit;
    }
    */

    $get_user = stripslashes($get_user);
    $post_username = stripslashes($post_username);
    $post_displayname = stripslashes($post_displayname);

    /*

*/
    
//    if ((empty($post_date)) || (empty($post_time)) || ($post_statusname == '1') || (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_statusname)) || (!eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date))) {
	
    if ((empty($post_date)) || (empty($post_time)) || ($post_statusname == '1') || (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_statusname)) ||
        (!preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date))
    ) {
	
        $evil_post = '1';
        if (empty($post_date)) {
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Date is required.</td></tr>\n";
            echo "            </table>\n";
        } elseif (empty($post_time)) {
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Time is required.</td></tr>\n";
            echo "            </table>\n";
        } elseif ($post_statusname == "1") {
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A Status must be chosen.</td></tr>\n";
            echo "            </table>\n";
//        } elseif (!eregi ("^([[:alnum:]]| |-|_|\.)+$", $post_statusname)) {
 } elseif (!preg_match('/' . "^([[:alnum:]]| |-|_|\.)+$" . '/i', $post_statusname)) {
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> Alphanumeric characters, hyphens, underscores, spaces, and periods are allowed in a Status Name.</td></tr>\n";
            echo "            </table>\n";
 //       } elseif (!eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date)) {
 } elseif  (!preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date)) {
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Date is required.</td></tr>\n";
            echo "            </table>\n";
        }
    } 
//	elseif ($timefmt_24hr == '0') {
//        if ((!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$", $post_time, $time_regs)) && (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$", $post_time, $time_regs))) {
	elseif ($timefmt_24hr == '0') {
	        if ((!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])+([a|p]+m)$" . '/i', $post_time, $time_regs)) && (!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])+( [a|p]+m)$" . '/i', $post_time,
	                                                                                                     $time_regs))
	        ) {
            $evil_time = '1';
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                                A valid Time is required.</td></tr>\n";
            echo "            </table>\n";
        } else {
            if (isset($time_regs)) {
                $h = $time_regs[1];
                $m = $time_regs[2];
            }
            $h = $time_regs[1]; $m = $time_regs[2];
            if (($h > 12) || ($m > 59)) {
                $evil_time = '1';
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr>\n";
                echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Time is required.</td></tr>\n";
                echo "            </table>\n";
            }
        }
    } 
	
//	elseif ($timefmt_24hr == '1') {
//        if (!eregi ("^([0-9]?[0-9])+:+([0-9]+[0-9])$", $post_time, $time_regs)) {
	elseif ($timefmt_24hr == '1') {
	        if (!preg_match('/' . "^([0-9]?[0-9])+:+([0-9]+[0-9])$" . '/i', $post_time, $time_regs)) {
            $evil_time = '1';
            echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
            echo "              <tr>\n";
            echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Time is required.</td></tr>\n";
            echo "            </table>\n";
        } else {
            if (isset($time_regs)) {
                $h = $time_regs[1];
                $m = $time_regs[2];
            }
            $h = $time_regs[1]; $m = $time_regs[2];
            if (($h > 24) || ($m > 59)) {
                $evil_time = '1';
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr>\n";
                echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Time is required.</td></tr>\n";
                echo "            </table>\n";
            }
        }
    }

//    if (eregi ("^([0-9]{1,2})[-,/,.]([0-9]{1,2})[-,/,.](([0-9]{2})|([0-9]{4}))$", $post_date, $date_regs)) {
if (preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date, $date_regs)) {
        if ($calendar_style == "amer") {
            if (isset($date_regs)) {
                $month = $date_regs[1];
                $day = $date_regs[2];
                $year = $date_regs[3];
            }
            if ($month > 12 || $day > 31) {
                $evil_date = '1';
                if (!isset($evil_post)) {
                    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                    echo "              <tr>\n";
                    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Date is required.</td></tr>\n";
                    echo "            </table>\n";
                }
            }
        } elseif ($calendar_style == "euro") {
            if (isset($date_regs)) {
                $month = $date_regs[2];
                $day = $date_regs[1];
                $year = $date_regs[3];
            }
            if ($month > 12 || $day > 31) {
                $evil_date = '1';
                if (!isset($evil_post)) {
                    echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
                    echo "              <tr>\n";
                    echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A valid Date is required.</td></tr>\n";
                    echo "            </table>\n";
                }
            }
        }
    }

    if (($require_time_admin_edit_reason == "yes") && empty($post_why)) { // Ensure that the admin gives a reason for the addition
        $evil_why = True;
        echo "            <table align=center class=table_border width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr>\n";
        echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> A reason for the addition is required.</td></tr>\n";
        echo "            </table>\n";
    }

    if ((isset($evil_post)) || (isset($evil_date)) || (isset($evil_time)) || (isset($evil_why))) { // Display error message
        echo "            <br />\n";
        echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
        echo "            <table align=center class=table width=60% border=0 cellpadding=3 cellspacing=0>\n";
        echo "              <tr>\n";
        echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/clock_add.png' />&nbsp;&nbsp;&nbsp;Add Time </th>\n";
        echo "              </tr>\n";
        echo "              <tr><td height=15></td></tr>\n";
        echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'> <input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'> <input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Date:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='10' maxlength='10' name='post_date' value='$post_date'>&nbsp;*&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"cal.select(document.forms['form'].post_date,'post_date_anchor','$js_datefmt'); return false;\" name=\"post_date_anchor\" id=\"post_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Time:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'> <input type='text' size='10' maxlength='$timefmt_size' name='post_time' value='$post_time'>&nbsp;*&nbsp;&nbsp; <a style='text-decoration:none;font-size:11px;color:#27408b;'>($timefmt_24hr_text)</a></td></tr>\n";
        echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
        echo "                <input type='hidden' name='timefmt_24hr' value=\"$timefmt_24hr\">\n";
        echo "                <input type='hidden' name='timefmt_24hr_text' value=\"$timefmt_24hr_text\">\n";
        echo "                <input type='hidden' name='timefmt_size' value=\"$timefmt_size\">\n";

        // query to populate dropdown with statuses //

        $query2 = "select * from ".$db_prefix."punchlist order by punchitems asc";
        $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);

        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Status:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'> <select name='post_statusname'>\n";
        echo "                        <option value ='1'>Choose One</option>\n";

        while ($row2=mysqli_fetch_array($result2)) {
            if ($post_statusname == "".$row2['punchitems']."") {
                echo "                        <option selected>".$row2['punchitems']."</option>\n";
            } else {
                echo "                        <option>".$row2['punchitems']."</option>\n";
            }
        }
        echo "                      </select>&nbsp;*</td></tr>\n";
        ((mysqli_free_result($result2) || (is_object($result2) && (get_class($result2) == "mysqli_result"))) ? true : false);

        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Notes:</td><td align=left colspan=2 width=80% style='padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_notes' value='$post_notes'></td></tr>\n";
        if ($require_time_admin_edit_reason == "yes") {
            echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Addition:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_why' value='$post_why'>&nbsp;*</td></tr>\n";
        } else if ($require_time_admin_edit_reason == "no") {
            echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Addition:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='25' maxlength='250' name='post_why' value='$post_why'> </td></tr>\n";
        }
        echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
        echo "            </table>\n";
        echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\" height=200>&nbsp;</div>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr><td height=40>&nbsp;</td></tr>\n";
        echo "              <tr><td width=30><input type='image' name='submit' value='Add Time' align='middle' src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form>\n";
	include '../theme/templates/endmaincontent.inc';
        include '../footer.php';
		include '../theme/templates/controlsidebar.inc'; 
		include '../theme/templates/endmain.inc';
		include '../theme/templates/reportsfooterscripts.inc';
		
        exit;
    } else { // Display add time interface
        $post_username = addslashes($post_username);
        $post_displayname = addslashes($post_displayname);

        // configure timestamp to insert/update

        if ($calendar_style == "euro") {
//            $post_date = "$day/$month/$year";
			$post_date = "$month/$day/$year";
        } elseif ($calendar_style == "amer") {
            $post_date = "$month/$day/$year";
        }

        $timestamp = strtotime($post_date . " " . $post_time) - $tzo;

        // check for duplicate time for $post_username
        $query = "select * from ".$db_prefix."info where fullname = '".$post_username."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        $post_username = stripslashes($post_username);
        $post_displayname = stripslashes($post_displayname);

        while ($row=mysqli_fetch_array($result)) {
            $info_table_timestamp = "".$row['timestamp']."";
            if ($timestamp == $info_table_timestamp) {
                echo "            <table align=center class=table width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr>\n";
                echo "                <td class=table_rows width=20 align=center><img src='../images/icons/cancel.png' /></td><td class=table_rows_red> Duplicate time exists for this user on this date. Time not added..</td></tr>\n";
                echo "            </table>\n";
                echo "            <br />\n";
                echo "            <form name='form' class='form-control' action='$self' method='post' onsubmit=\"return isDate()\">\n";
                echo "            <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>\n";
                echo "              <tr>\n";
                echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/clock_add.png' />&nbsp;&nbsp;&nbsp;Add Time </th>\n";
                echo "              </tr>\n";
                echo "              <tr><td height=15></td></tr>\n";
                echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'> <input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'> <input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname</td></tr>\n";
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Date:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'><input type='text' size='10' maxlength='10' name='post_date' value='$post_date'>&nbsp;*&nbsp;&nbsp;&nbsp;<a href=\"#\" onclick=\"cal.select(document.forms['form'].post_date,'post_date_anchor','$js_datefmt'); return false;\" name=\"post_date_anchor\" id=\"post_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Time:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'> <input type='text' size='10' maxlength='$timefmt_size' name='post_time' value='$post_time'>&nbsp;*&nbsp;&nbsp; <a style='text-decoration:none;font-size:11px;color:#27408b;'>($timefmt_24hr_text)</a></td></tr>\n";
                echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
                echo "                <input type='hidden' name='timefmt_24hr' value=\"$timefmt_24hr\">\n";
                echo "                <input type='hidden' name='timefmt_24hr_text' value=\"$timefmt_24hr_text\">\n";
                echo "                <input type='hidden' name='timefmt_size' value=\"$timefmt_size\">\n";

                // query to populate dropdown with statuses //
                $query2 = "select * from ".$db_prefix."punchlist order by punchitems asc";
                $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);

                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Status:</td><td colspan=2 width=80% style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;'> <select name='post_statusname'>\n";
                echo "                        <option value ='1'>Choose One</option>\n";

                while ($row2=mysqli_fetch_array($result2)) {
                    if ($post_statusname == "".$row2['punchitems']."") {
                        echo "                        <option selected>".$row2['punchitems']."</option>\n";
                    } else {
                        echo "                        <option>".$row2['punchitems']."</option>\n";
                    }
                }
                echo "                      </select>&nbsp;*</td></tr>\n";
                ((mysqli_free_result($result2) || (is_object($result2) && (get_class($result2) == "mysqli_result"))) ? true : false);

                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Notes:</td><td align=left colspan=2 width=80% style='padding-left:20px;'><input type='text' size='17' maxlength='250' name='post_notes' value='$post_notes'></td></tr>\n";
                echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Addition:</td><td align=left colspan=2 width=80% style='padding-left:20px;'><input type='text' size='17' maxlength='250' name='post_why' value='$post_why'></td></tr>\n";
                echo "              <tr><td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>*&nbsp;required&nbsp;</td></tr>\n";
                echo "            </table>\n";
                echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\" height=200>&nbsp;</div>\n";
                echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
                echo "              <tr><td height=40>&nbsp;</td></tr>\n";
                echo "              <tr><td width=30><input type='image' name='submit' value='Add Time' align='middle' src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form>\n";
		include '../theme/templates/endmaincontent.inc';
                include '../footer.php';
				include '../theme/templates/controlsidebar.inc'; 
				include '../theme/templates/endmain.inc';
				include '../theme/templates/reportsfooterscripts.inc';
                exit;
            }
        }
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

        // check to see if this would be the most recent time for $post_username. if so, run the update query for the employees table.
        $post_username = addslashes($post_username);
        $post_displayname = addslashes($post_displayname);

        $query = "select * from ".$db_prefix."employees where empfullname = '".$post_username."'";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

        while ($row=mysqli_fetch_array($result)) {
            $employees_table_timestamp = "".$row['tstamp']."";
        }
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

        if ($timestamp > $employees_table_timestamp) {
            $update_query = "update ".$db_prefix."employees set tstamp = '".$timestamp."' where empfullname = '".$post_username."'";
            $update_result = mysqli_query($GLOBALS["___mysqli_ston"], $update_query);
        }

        // determine who the authenticated user is for audit log
        if (isset($_SESSION['valid_user'])) {
            $user = $_SESSION['valid_user'];
        } elseif (isset($_SESSION['time_admin_valid_user'])) {
            $user = $_SESSION['time_admin_valid_user'];
        } else {
            $user = "";
        }

        // configure current time to insert for audit log
        $time = time();
        $time_hour = gmdate('H', $time);
        $time_min = gmdate('i', $time);
        $time_sec = gmdate('s', $time);
        $time_month = gmdate('m', $time);
        $time_day = gmdate('d', $time);
        $time_year = gmdate('Y', $time);
        $time_tz_stamp = time ($time_hour, $time_min, $time_sec, $time_month, $time_day, $time_year);

        // add the time to the info table for $post_username and audit log
        if (strtolower($ip_logging) == "yes") {
            $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes, ipaddress) values ('".$post_username."', '".$post_statusname."', '".$timestamp."', '".$post_notes."', '".$connecting_ip."')";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
            $query2 = "insert into ".$db_prefix."audit (modified_by_ip, modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values ('".$connecting_ip."', '".$user."', '".$time_tz_stamp."', '0', '".$timestamp."', '".$post_why."', '".$post_username."')";
            $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
        } else {
            $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes) values ('".$post_username."', '".$post_statusname."', '".$timestamp."', '".$post_notes."')";
            $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
            $query2 = "insert into ".$db_prefix."audit (modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values ('".$user."', '".$time_tz_stamp."', '0', '".$timestamp."', '".$post_why."', '".$post_username."')";
            $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
        }

        $post_username = stripslashes($post_username);
        $post_displayname = stripslashes($post_displayname);
        $post_date = date($datefmt, $timestamp + $tzo);

	echo '<div class="row">
    <div class="col-md-8">
      <div class="box box-info"> ';
echo '<div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-clock-o"></i> Time added successfully</h3>
               </div><div class="box-body">';
        echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate();\">\n";
        echo "            <table align=center class='table'>\n";
        echo "              <tr>\n";
        echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/clock_add.png' />&nbsp;&nbsp;&nbsp;Add Time </th>\n";
        echo "              </tr>\n";
        echo "              <tr><td height=15></td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_username</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_displayname</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Date:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_date</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Time:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_time</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Status:</td><td align=left class=table_rows colspan=2 width=80% style='color:$color;padding-left:20px;'>$post_statusname</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Notes:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_notes</td></tr>\n";
        echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reason For Addition:</td><td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$post_why</td></tr>\n";
        echo "              <tr><td height=15></td></tr>\n";
        echo "            </table>\n";
        echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
        echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
        echo "              <tr><td><a href='timeadmin.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table>\n";
	echo'</div></div></div></div>';
	include '../theme/templates/endmaincontent.inc';
        include '../footer.php';
		include '../theme/templates/controlsidebar.inc'; 
		include '../theme/templates/endmain.inc';
		include '../theme/templates/reportsfooterscripts.inc';
        exit;
    }
}
?>
