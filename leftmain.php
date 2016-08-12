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
* This module creates the interface for an employee to punch their status.
*/

include 'config.inc.php';

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

// set cookie if 'Remember Me?' checkbox is checked, or reset cookie if 'Reset Cookie?' is checked //
if ($request == 'POST') {
    @$remember_me = $_POST['remember_me'];
    @$reset_cookie = $_POST['reset_cookie'];
    @$fullname = stripslashes($_POST['left_fullname']);
    @$displayname = stripslashes($_POST['left_displayname']);
    if ((isset($remember_me)) && ($remember_me != '1')) {
        echo "Something is fishy here.";
        exit;
    }
    if ((isset($reset_cookie)) && ($reset_cookie != '1')) {
        echo "Something is fishy here.";
        exit;
    }

    // begin post validation //
    if ($show_display_name == "yes") {
        if (isset($displayname)) {
            $displayname = addslashes($displayname);
            $query = "select displayname from ".$db_prefix."employees where displayname = '".$displayname."'";
            $emp_name_result = mysql_query($query);

            while ($row = mysql_fetch_array($emp_name_result)) {
                $tmp_displayname = "".$row['displayname']."";
            }
            if ((!isset($tmp_displayname)) && (!empty($displayname))) {
                echo "Username is not in the database.";
                exit;
            }
            $displayname = stripslashes($displayname);
        }
    } elseif ($show_display_name == "no") {
        if (isset($fullname)) {
            $fullname = addslashes($fullname);
            $query = "select empfullname from ".$db_prefix."employees where empfullname = '".$fullname."'";
            $emp_name_result = mysql_query($query);

            while ($row = mysql_fetch_array($emp_name_result)) {
                $tmp_empfullname = "".$row['empfullname']."";
            }
            if ((!isset($tmp_empfullname)) && (!empty($fullname))) {
                echo "Username is not in the database.";
                exit;
            }
            $fullname = stripslashes($fullname);
        }
    }
    // end post validation //

    if (isset($remember_me)) {
        if ($show_display_name == "yes") {
            setcookie("remember_me", stripslashes($displayname), time() + (60 * 60 * 24 * 365 * 2));
        } elseif ($show_display_name == "no") {
            setcookie("remember_me", stripslashes($fullname), time() + (60 * 60 * 24* 365 * 2));
        }
    } elseif (isset($reset_cookie)) {
        setcookie("remember_me", "", time() - 3600);
    }
    ob_end_flush();
}

/*
if ($display_weather == 'yes') { // Retrieve weather information
    include 'phpweather.php';
    $metar = get_metar($metar);
    $data = process_metar($metar);
    $mph = "mph";

    // weather info //
    if (! isset($data['temp_f'])) {
        $temp = '';
    } else {
        $temp = $data['temp_f'];
    }

    if (! isset($data['windchill_f'])) {
        $windchill = '';
    } else {
        $windchill = $data['windchill_f'];
    }

    if (! isset($data['wind_dir_text_short'])) {
        $wind_dir = '';
    } else {
        $wind_dir = $data['wind_dir_text_short'];
    }

    if (! isset($data['wind_miles_per_hour'])) {
        $wind = '';
    } else {
        $wind = round($data['wind_miles_per_hour']);
    }

    if ($wind == 0) {
        $wind_dir = 'None';
        $mph = '';
        $wind = '';
    } else {
        $wind_dir = $wind_dir;
    }

    if (! isset($data['visibility_miles'])) {
        $visibility = '';
    } else {
        $visibility = $data['visibility_miles'];
    }

    if (! isset($data['rel_humidity'])) {
        $humidity = 'None';
    } else {
        $humidity = round($data['rel_humidity'], 0);
    }

    if (! isset($data['time'])) {
        $time = '';
    } else {
        $time = date($timefmt, $data['time']);
    }

    if (! isset($data['cloud_layer1_condition'])) {
        $cloud_cover = '';
    } else {
        $cloud_cover = $data['cloud_layer1_condition'];
    }

    if (($temp <> '') && ($temp >= '70') && ($humidity <> '')) {
        $heatindex = number_format(-42.379 + (2.04901523 * $temp) + (10.1433312 * $humidity) - (0.22475541 * $temp * $humidity) - (0.00683783 * ($temp * $temp)) - (0.05481717 * ($humidity * $humidity)) + (0.00122874 * ($temp * $temp) * $humidity) + (0.00085282 * $temp * ($humidity * $humidity)) - (0.00000199 * ($temp * $temp) * ($humidity * $humidity)));
    }

    if ((isset($heatindex)) || ($windchill <> '')) {
        if (!isset($heatindex)) {
            $feelslike = $windchill;
        } else {
            $feelslike = $heatindex;
        }
    } else {
        $feelslike = $temp;
    }
}

*/

/* nya */
if ($display_weather == 'yes') {

    include 'phpweather.php';
    $metar = get_metar($metar);
    $data = process_metar($metar);

    if ($weather_units == "f") {
        $mph = " mph";
        $miles = " miles";

        // weather info //

        if (!isset($data['temp_f'])) {
            $temp = '';
        } else {
            $temp = $data['temp_f'];
        }
        if (!isset($data['windchill_f'])) {
            $windchill = '';
        } else {
            $windchill = $data['windchill_f'];
        }
        if (!isset($data['wind_dir_text_short'])) {
            $wind_dir = '';
        } else {
            $wind_dir = $data['wind_dir_text_short'];
        }
        if (!isset($data['wind_miles_per_hour'])) {
            $wind = '';
        } else {
            $wind = round($data['wind_miles_per_hour']);
        }
        if ($wind == 0) {
            $wind_dir = 'None';
            $mph = '';
            $wind = '';
        } else {
            $wind_dir = $wind_dir;
        }
        if (!isset($data['visibility_miles'])) {
            $visibility = '';
        } else {
            $visibility = $data['visibility_miles'] . $miles;
        }
        if (!isset($data['rel_humidity'])) {
            $humidity = 'None';
        } else {
            $humidity = round($data['rel_humidity'], 0);
        }
        if (!isset($data['time'])) {
            $time = '';
        } else {
            $time = date($timefmt, $data['time']);
        }
        if (!isset($data['cloud_layer1_condition'])) {
            $cloud_cover = '';
        } else {
            $cloud_cover = $data['cloud_layer1_condition'];
        }
        if (($temp <> '') && ($temp >= '70') && ($humidity <> '')) {
            $heatindex = number_format(-42.379 + (2.04901523 * $temp) + (10.1433312 * $humidity) - (0.22475541 * $temp * $humidity)
                                       - (0.00683783 * ($temp * $temp)) - (0.05481717 * ($humidity * $humidity))
                                       + (0.00122874 * ($temp * $temp) * $humidity) + (0.00085282 * $temp * ($humidity * $humidity))
                                       - (0.00000199 * ($temp * $temp) * ($humidity * $humidity)));
        }
    } else {
        $mph = " kmh";
        $miles = " km";

        // weather info //

        if (!isset($data['temp_c'])) {
            $temp = '';
        } else {
            $temp = $data['temp_c'];
        }
        if (!isset($data['temp_f'])) {
            $tempF = '';
        } else {
            $tempF = $data['temp_f'];
        }
        if (!isset($data['windchill_c'])) {
            $windchill = '';
        } else {
            $windchill = $data['windchill_c'];
        }
        if (!isset($data['wind_dir_text_short'])) {
            $wind_dir = '';
        } else {
            $wind_dir = $data['wind_dir_text_short'];
        }
        if (!isset($data['wind_meters_per_second'])) {
            $wind = '';
        } else {
            $wind = round($data['wind_meters_per_second'] / 1000 * 60 * 60);
        }
        if ($wind == 0) {
            $wind_dir = 'None';
            $mph = '';
            $wind = '';
        } else {
            $wind_dir = $wind_dir;
        }
        if (!isset($data['visibility_km'])) {
            $visibility = '';
        } else {
            $visibility = $data['visibility_km'] . $miles;
        }
        if (!isset($data['rel_humidity'])) {
            $humidity = 'None';
        } else {
            $humidity = round($data['rel_humidity'], 0);
        }
        if (!isset($data['time'])) {
            $time = '';
        } else {
            $time = date($timefmt, $data['time']);
        }
        if (!isset($data['cloud_layer1_condition'])) {
            $cloud_cover = '';
        } else {
            $cloud_cover = $data['cloud_layer1_condition'];
        }
        if (($tempF <> '') && ($tempF >= '70') && ($humidity <> '')) {
            $heatindexF = number_format(-42.379 + (2.04901523 * $tempF) + (10.1433312 * $humidity) - (0.22475541 * $tempF * $humidity)
                                        - (0.00683783 * ($tempF * $tempF)) - (0.05481717 * ($humidity * $humidity))
                                        + (0.00122874 * ($tempF * $tempF) * $humidity) + (0.00085282 * $tempF * ($humidity * $humidity))
                                        - (0.00000199 * ($tempF * $tempF) * ($humidity * $humidity)));
            $heatindex = round(($heatindexF - 32) * 5 / 9);
        }
    }

    if ((isset($heatindex)) || ($windchill <> '')) {
        if (!isset($heatindex)) {
            $feelslike = $windchill;
        } else {
            $feelslike = $heatindex;
        }
    } else {
        $feelslike = $temp;
    }
}


/* slut nya */

include './theme/templates/leftnavstart.inc';



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



echo "
      <!-- Left Side Interface For Employee's To Punch -->";

// display form to submit signin/signout information //


echo "<form role='form' name='timeclock' action='$self' method='post'>";

echo '
        <div class="box box-primary">
               <div class="box-header with-border">
                 <h3 class="box-title">Please punch in below:</h3>
               </div>
               <!-- /.box-header -->';
echo "<div class='box-body'>
	
	<div class='form-group'>
 
                           
                        
                           <label>Name:</label>
			   
                        ";

// query to populate dropdown with employee names //
if ($show_display_name == "yes") {
    $query = "select displayname from ".$db_prefix."employees where disabled <> '1'  and empfullname <> 'admin' order by displayname";
    $emp_name_result = mysql_query($query);
    echo "
                           <select multiple class='form-control' name='left_displayname' size='6' tabindex=1>
                              <option value =''>
                                 ...
                              </option>";

    while ($row = mysql_fetch_array($emp_name_result)) {
        $abc = stripslashes("".$row['displayname']."");

        if ((isset($_COOKIE['remember_me'])) && (stripslashes($_COOKIE['remember_me']) == $abc)) {
            echo "
                              <option selected>
                                 $abc
                              </option>";
        } else {
            echo "
                              <option>
                                 $abc
                              </option>";
        }
    }

    echo "
                           </select>
    </div>
                        ";
    mysql_free_result($emp_name_result);
} else { // Display full employee names
    $query = "select empfullname from ".$db_prefix."employees where disabled <> '1'  and empfullname <> 'admin' order by empfullname";
    $emp_name_result = mysql_query($query);
    echo "
                           <select multiple class='form-control' name='left_fullname'>
                              <option value =''>
                                 ...
                              </option>";

    while ($row = mysql_fetch_array($emp_name_result)) {
        $def = stripslashes("".$row['empfullname']."");
        if ((isset($_COOKIE['remember_me'])) && (stripslashes($_COOKIE['remember_me']) == $def)) {
            echo "
                              <option selected>
                                 $def
                              </option>";
        } else {
            echo "
                              <option>
                                 $def
                              </option>";
        }
    }

    echo "
                           </select>
    </div>
                        ";
    mysql_free_result($emp_name_result);
}

// determine whether to use encrypted passwords or not //
if ($use_passwd == "yes") {
    echo "
                     <div class='form-group'>
                           <label>Password:</label>
                        
                           <input type='password' name='employee_passwd' maxlength='25' class='form-control' placeholder='Password'>
			   </div>
";
}

echo "
                     <div class='form-group'>
                           Status:
                        ";

// query to populate dropdown with punchlist items //
$query = "select punchitems from ".$db_prefix."punchlist";
$punchlist_result = mysql_query($query);

echo "
                           <select class='form-control' name='left_inout'>
                              <option value =''>
                                 ...
                              </option>";

while ($row = mysql_fetch_array($punchlist_result)) {
    echo "
                              <option>
                                 ".$row['punchitems']."
                              </option>";
}

echo "
                           </select>
</div>
";
mysql_free_result( $punchlist_result );

echo "
                     <div class='form-group'>
                           <label>Notes:</label>
                        
                           <input type='text' name='left_notes' maxlength='250' class='form-control'>
</div>";

if (! isset($_COOKIE['remember_me'])) {
    echo "
                     <div class='checkbox'>
     
		                      <label>
		                        <input type='checkbox' name='remember_me' value='1'> Remember Me?
		                      </label>
                                    
                 </div>      
                    ";
} elseif (isset($_COOKIE['remember_me'])) {
    echo "
                     <div class='checkbox'>
                                   
                                    <label><input type='checkbox' name='reset_cookie' value='1'> Reset Cookie? </label>
                               </div>   ";
}

echo "
                      <div class='form-group'>
<button type='submit' class='btn btn-lg btn-primary'>Punch Status</button>
                         </div>
                  </div></form>";

// End leftnav here and put the rest in main.	

// display links in top left of each page //
if ($links == "none") { // Display any links listed

} else {
    echo "<ul class='sidebar-menu'><li class='header'>LINKS</li>";
    for ($x = 0; $x < count($display_links); $x++) {
        echo "
<li><a href='$links[$x]'><i class='fa fa-link'></i>$display_links[$x]</a></li>";
    }
    echo '
	    </ul>';
}
	  
include './theme/templates/leftnavend.inc';
include './theme/templates/beginmaincontent.inc';

echo '
	<div class="row">
	<!-- extra messages -->
	';

if ($display_weather == "yes") { // Display the weather information.
	echo '       <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
              <h3 class="widget-user-username">Weather Conditions</h3>
	      <h5 class="widget-user-desc">'.$city.'</h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
              <li><a href="#"><i class="fa fa-sun-o fa-fw"></i> Currently: <span class="pull-right badge bg-red">'.$temp.' &#176;</span></a></li>
              <li><a href="#"><i class="fa fa-umbrella fa-fw"></i> Feels Like: <span class="pull-right badge bg-aqua">'.$feelslike.' &#176;</span></a></li>
              <li><a href="#"><i class="glyphicon glyphicon-cloud fa-fw"></i> Skies: <span class="pull-right badge bg-blue">'.$cloud_cover.'</span></a></li>
              <li><a href="#"><i class="fa fa-refresh fa-fw"></i> Wind: <span class="pull-right badge bg-orange">'.$wind_dir.' '.$wind.' '.$mph.'</span></a></li>
	      <li><a href="#"><i class="fa fa-bolt fa-fw"></i> Humidity: <span class="pull-right badge bg-yellow">'.$humidity.''; if ($humidity == 'None') {echo '';} else {echo '%';} echo '</span></a></li>
	      <li><a href="#"><i class="fa fa-eye fa-fw"></i> Visibility: <span class="pull-right badge bg-grey">'.$visibility.'</span></a></li>
	      <li><a href="#"><i class="glyphicon glyphicon-time"></i> Last Updated: <span class="pull-right badge bg-green">'.$time.'</span></a></li>
              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <!-- /.col -->';
}

echo "

";

if ($request == 'POST') { // Process employee's punch information
    // signin/signout data passed over from timeclock.php //
    $inout = $_POST['left_inout'];
    $notes = ereg_replace("[^[:alnum:] \,\.\?-]","",strtolower($_POST['left_notes']));

    // begin post validation //
    if ($use_passwd == "yes") {
        $employee_passwd = crypt($_POST['employee_passwd'], 'xy');
    }

    $query = "select punchitems from ".$db_prefix."punchlist";
    $punchlist_result = mysql_query($query);

    while ($row = mysql_fetch_array($punchlist_result)) {
        $tmp_inout = "".$row['punchitems']."";
    }

    if (! isset($tmp_inout)) {
	    echo '<div class="col-md-4">
 <div class="callout callout-danger">
                <h4><i class="fa fa-bullhorn"></i> Error</h4>
                <p>Status is not in the database.</p>
</div>
</div>';

        exit;
    }
    // end post validation //

    if ($show_display_name == "yes") {
        if (! $displayname && ! $inout) {
    	    echo '<div class="col-md-4">
     <div class="callout callout-danger">
                    <h4><i class="fa fa-bullhorn"></i> Error</h4>
                    <p>You have not chosen a username or a status. Please try again..</p>
    </div>
    </div>';
            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }

        if (! $displayname) {
        	    echo '<div class="col-md-4">
         <div class="callout callout-danger">
                        <h4><i class="fa fa-bullhorn"></i> Error</h4>
                        <p>You have not chosen a username. Please try again.</p>
        </div>
        </div>';


            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }
    } elseif ($show_display_name == "no") {
        if (! $fullname && ! $inout) {
    	    echo '<div class="col-md-4">
     <div class="callout callout-danger">
                    <h4><i class="fa fa-bullhorn"></i> Error</h4>
                    <p>You have not chosen a username or a status. Please try again.</p>
    </div>
    </div>';


            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }

        if (! $fullname) {
        	    echo '<div class="col-md-4">
         <div class="callout callout-danger">
                        <h4><i class="fa fa-bullhorn"></i> Error</h4>
                        <p>You have not chosen a username. Please try again.</p>
        </div>
        </div>';


            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }
    }

    if (! $inout) {
	    echo '<div class="col-md-4">
 <div class="callout callout-danger">
                <h4><i class="fa fa-bullhorn"></i> Error</h4>
                <p>You have not chosen a status. Please try again.</p>
</div>
</div>';


        // Return the employee back to the punch interface after 5 seconds
        echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
        exit;
    }

    // Get all the possible punch status names
    $query = "select punchitems from ".$db_prefix."punchlist";
    $punchlist_result = mysql_query($query);
    // We need to get the full name if we're only displaying the display name
    if ($show_display_name == "yes") {
        $query = "select empfullname from ".$db_prefix."employees where displayname = '".$displayname."'";
        $sel_result = mysql_query($query);
        while ($row = mysql_fetch_array($sel_result)) {
            $fullname = stripslashes("".$row["empfullname"]."");
            $fullname = addslashes($fullname);
        }
    }
    // Get the current punch name of that employee
    $query = "select * from ".$db_prefix."info where fullname = '".$fullname."'";
    $query = mysql_query($query);
    // Find the last entry for the employee
    $largestStamp = 0;
    while ($row = mysql_fetch_array($query)) {
        if ($row['timestamp'] > $largestStamp) {
            $currentPunchName = $row['inout'];
            $largestStamp = $row['timestamp'];
        }
    }
    // Get the selected status
    $query = "SELECT `in_or_out` FROM ".$db_prefix."punchlist WHERE punchitems = '".$inout."'";
    $query = mysql_query($query);
    $row = mysql_fetch_array($query);
    $selectedStatus = $row['in_or_out']; // The first one should the be the current status code.
    if ($currentPunchName == "") {
        $currentStatus = "NEVER CLOCKED IN YET";
    } else { // Iterate through to find the current status of individual logging in
        while ($punchName = mysql_fetch_array($punchlist_result)) {
            if ($currentPunchName == $punchName['punchitems']) {
                $query = "SELECT `in_or_out` FROM ".$db_prefix."punchlist WHERE punchitems = '".$currentPunchName."'";
                $query = mysql_query($query);
                $row = mysql_fetch_array($query);
                $currentStatus = $row['in_or_out']; // The first one should the be the current status code.
                break;
            }
        }
    }

    // Verify that the employee is not selecting the same status as his current status
    if ($selectedStatus == $currentStatus) {
	    echo '<div class="col-md-4">
 <div class="callout callout-danger">
                <h4><i class="fa fa-bullhorn"></i> Error</h4>
                <p>The current punch status for '.$fullname.' is '.$currentPunchName.' . Please use a different status.</p>
</div>
</div>';


        // Return the employee back to the punch interface after 5 seconds
        echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
        exit;
    }

    if ($use_passwd == "yes") { // Verify that the employee password is correct, if required
        $sel_query = "select empfullname, employee_passwd from ".$db_prefix."employees where empfullname = '".$fullname."'";
        $sel_result = mysql_query($sel_query);

        while ($row=mysql_fetch_array($sel_result)) {
            $tmp_password = "".$row["employee_passwd"]."";
        }

        if ($employee_passwd != $tmp_password) {
    	    echo '<div class="col-md-4">
     <div class="callout callout-danger">
                    <h4><i class="fa fa-bullhorn"></i> Error</h4>
                    <p>You have entered the wrong password for '.$fullname.'. Please try again.</p>
    </div>
    </div>';


            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
<meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }
    }

    @$fullname = addslashes($fullname);
    @$displayname = addslashes($displayname);

    // configure timestamp to insert/update //
    $time = time();
    $hour = gmdate('H',$time);
    $min = gmdate('i',$time);
    $sec = gmdate('s',$time);
    $month = gmdate('m',$time);
    $day = gmdate('d',$time);
    $year = gmdate('Y',$time);
  //  $tz_stamp = mktime ($hour, $min, $sec, $month, $day, $year);
  // testing better ways 
  $tz_stamp = time ($hour, $min, $sec, $month, $day, $year);

    if ($show_display_name == "yes") {
        $sel_query = "select empfullname from ".$db_prefix."employees where displayname = '".$displayname."'";
        $sel_result = mysql_query($sel_query);

        while ($row=mysql_fetch_array($sel_result)) {
            $fullname = stripslashes("".$row["empfullname"]."");
            $fullname = addslashes($fullname);
        }
    }

    if (strtolower($ip_logging) == "yes") {
        $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes, ipaddress) values ('".$fullname."', '".$inout."', '".$tz_stamp."', '".$notes."', '".$connecting_ip."')";
    } else {
        $query = "insert into ".$db_prefix."info (fullname, `inout`, timestamp, notes) values ('".$fullname."', '".$inout."', '".$tz_stamp."', '".$notes."')";
    }

    $result = mysql_query($query);

    $update_query = "update ".$db_prefix."employees set tstamp = '".$tz_stamp."' where empfullname = '".$fullname."'";
    $other_result = mysql_query($update_query);
	    echo '<div class="col-md-4">
 <div class="callout callout-success">
                <h4><i class="fa fa-bullhorn"></i> </h4>
                <p> Status changed successfully for '.$fullname.' to a status of '.$inout.'.</p>
</div>
</div>';


    // Return the employee back to the punch interface after 5 seconds
    echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
}

// Determine if we should add the message of the day
if (! isset($_GET['printer_friendly']) && ($message_of_the_day != "none")) {
	echo '
		<!-- Message Of The Day Display -->
	        <div class="col-md-4">
		<div class="callout callout-success">
                <h4>Message Of The Day:</h4>

                <p>'.htmlspecialchars($message_of_the_day).'</p>
              </div>
	      </div>
	      ';
	      

} else if (! isset($_GET['printer_friendly']) && ($message_of_the_day == "none")) {
    echo " ";
}

      if (! isset($_GET['printer_friendly'])) {
      	echo ' <a href="timeclock.php?printer_friendly=true" class="btn btn-app">
                      <i class="glyphicon glyphicon-print"></i> Printer Friendly Page
                    </a>';
      }


echo '
	</div>
	<!-- /.extra messages -->
	';
?>
