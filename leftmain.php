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

echo "
      <!-- Left Side Interface For Employee's To Punch -->
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td class=left_main width=170 align=left scope=col>
               <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>";

// display links in top left of each page //
if ($links == "none") { // Display any links listed
    echo "
                  <tr>
                  </tr>";
} else {
    echo "
                  <tr>
                     <td class=left_rows height=7 align=left valign=middle> </td>
                  </tr>";
    for ($x = 0; $x < count($display_links); $x++) {
        echo "
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <a class=admin_headings href='$links[$x]'>
                           $display_links[$x]
                        </a>
                     </td>
                  </tr>";
    }
}

// display form to submit signin/signout information //
echo "
                  <form name='timeclock' action='$self' method='post'>";

if ($links == "none") {
    echo "
                     <tr>
                        <td height=7> </td>
                     </tr>";
} else {
    echo "
                     <tr>
                        <td height=20> </td>
                     </tr>";
}

echo "
                     <tr>
                        <td class=title_underline height=4 align=left valign=middle style='padding-left:10px;'>
                           Please punch in below:
                        </td>
                     </tr>
                     <tr>
                        <td height=7> </td>
                     </tr>
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>
                           Name:
                        </td>
                     </tr>
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>";

// query to populate dropdown with employee names //
if ($show_display_name == "yes") {
    $query = "select displayname from ".$db_prefix."employees where disabled <> '1'  and empfullname <> 'admin' order by displayname";
    $emp_name_result = mysql_query($query);
    echo "
                           <select name='left_displayname' tabindex=1>
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
                        </td>
                     </tr>
                     <tr>
                        <td height=7> </td>
                     </tr>";
    mysql_free_result($emp_name_result);
} else { // Display full employee names
    $query = "select empfullname from ".$db_prefix."employees where disabled <> '1'  and empfullname <> 'admin' order by empfullname";
    $emp_name_result = mysql_query($query);
    echo "
                           <select name='left_fullname' tabindex=1>
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
                        </td>
                     </tr>
                     <tr>
                        <td height=7> </td>
                     </tr>";
    mysql_free_result($emp_name_result);
}

// determine whether to use encrypted passwords or not //
if ($use_passwd == "yes") {
    echo "
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>
                           Password:
                        </td>
                     </tr>
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>
                           <input type='password' name='employee_passwd' maxlength='25' size='17' tabindex=2>
                        </td>
                     </tr>
                     <tr>
                        <td height=7> </td>
                     </tr>";
}

echo "
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>
                           Status:
                        </td>
                     </tr>
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>";

// query to populate dropdown with punchlist items //
$query = "select punchitems from ".$db_prefix."punchlist";
$punchlist_result = mysql_query($query);

echo "
                           <select name='left_inout' tabindex=3>
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
                        </td>
                     </tr>";
mysql_free_result( $punchlist_result );

echo "
                     <tr>
                        <td height=7> </td>
                     </tr>
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>
                           Notes:
                        </td>
                     </tr>
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>
                           <input type='text' name='left_notes' maxlength='250' size='17' tabindex=4>
                        </td>
                     </tr>";

if (! isset($_COOKIE['remember_me'])) {
    echo "
                     <tr>
                        <td width=100%>
                           <table width=100% border=0 cellpadding=0 cellspacing=0>
                              <tr>
                                 <td nowrap height=4 align=left valign=middle class=misc_items width=10%>
                                    Remember Me?
                                 </td>
                                 <td width=90% align=left class=misc_items style='padding-left:0px;padding-right:0px;' tabindex=5>
                                    <input type='checkbox' name='remember_me' value='1'>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     <tr>";
} elseif (isset($_COOKIE['remember_me'])) {
    echo "
                     <tr>
                        <td width=100%>
                           <table width=100% border=0 cellpadding=0 cellspacing=0>
                              <tr>
                                 <td nowrap height=4 align=left valign=middle class=misc_items width=10%>
                                    Reset Cookie?
                                 </td>
                                 <td width=90% align=left class=misc_items style='padding-left:0px;padding-right:0px;' tabindex=5>
                                    <input type='checkbox' name='reset_cookie' value='1'>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     <tr>";
}

echo "
                     <tr>
                        <td height=7> </td>
                     </tr>
                     <tr>
                        <td height=4 align=left valign=middle class=misc_items>
                           <input type='submit' name='submit_button' value='Punch Status' align='center' tabindex=6>
                        </td>
                     </tr>
                  </form>";

if ($display_weather == "yes") { // Display the weather information.
    echo "
                  <tr>
                     <td height=25 align=left valign=bottom class=misc_items>
                        <font color='00589C'>
                           <b> <u>Weather Conditions:</u> </b>
                        </font>
                     </td>
                  </tr>
                  <tr>
                     <td height=7> </td>
                  </tr>
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        <b>$city</b>
                     </td>
                  </tr>
                  <tr>
                     <td height=4> </td>
                  </tr>
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        Currently: $temp&#176;
                     </td>
                  </tr>
                  <tr>
                     <td height=4> </td>
                  </tr>
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        Feels Like: $feelslike&#176;
                     </td>
                  </tr>
                  <tr>
                     <td height=4> </td>
                  </tr>
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        Skies: $cloud_cover
                     </td>
                  </tr>
                  <tr>
                     <td height=4> </td>
                  </tr>
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        Wind: $wind_dir $wind$mph
                     </td>
                  </tr>
                  <tr>
                     <td height=4> </td>
                  </tr>";

    if ($humidity == 'None') {
        echo "
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        Humidity: $humidity
                     </td>
                  </tr>";
    } else {
        echo "
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        Humidity: $humidity%
                     </td>
                  </tr>";
    }

    echo "
                  <tr>
                     <td height=4> </td>
                  </tr>
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        Visibility: $visibility miles
                     </td>
                  </tr>
                  <tr>
                     <td height=4> </td>
                  </tr>
                  <tr>
                     <td align=left valign=middle class=misc_items>
                        <font color='FF0000'>
                           Last Updated: $time
                        </font>
                     </td>
                  </tr>";
}

echo "
                  <tr>
                     <td height=90%> </td>
                  </tr>
               </table>
            </td>";

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
        echo "Status is not in the database.";
        exit;
    }
    // end post validation //

    if ($show_display_name == "yes") {
        if (! $displayname && ! $inout) {
            echo "
            <td align=left class=right_main scope=col>
               <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        You have not chosen a username or a status. Please try again.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
            include 'footer.php';
            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }

        if (! $displayname) {
            echo "
            <td align=left class=right_main scope=col>
               <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        You have not chosen a username. Please try again.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
            include 'footer.php';
            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }
    } elseif ($show_display_name == "no") {
        if (! $fullname && ! $inout) {
            echo "
            <td align=left class=right_main scope=col>
               <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        You have not chosen a username or a status. Please try again.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
            include 'footer.php';
            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }

        if (! $fullname) {
            echo "
            <td align=left class=right_main scope=col>
               <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        You have not chosen a username. Please try again.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
            include 'footer.php';
            // Return the employee back to the punch interface after 5 seconds
            echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
            exit;
        }
    }

    if (! $inout) {
        echo "
            <td align=left class=right_main scope=col>
               <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        You have not chosen a status. Please try again.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
        include 'footer.php';
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
        echo "
            <td align=left class=right_main scope=col>
               <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        The current punch status for ".$fullname." is ".$currentPunchName.". Please use a different status.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
        include 'footer.php';
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
            echo "
            <td align=left class=right_main scope=col>
               <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        You have entered the wrong password for $fullname. Please try again.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
            include 'footer.php';
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
    $tz_stamp = mktime ($hour, $min, $sec, $month, $day, $year);

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

    echo "
            <td align=left class=right_main scope=col>
               <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        Status changed successfully for $fullname to a status of $inout. <br>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
    include 'footer.php';
    // Return the employee back to the punch interface after 5 seconds
    echo "
   <head>
      <meta http-equiv='refresh' content=5;url=index.php>
   </head>";
}
?>
