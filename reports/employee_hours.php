<?php
/***************************************************************************
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
 * This module allows an employee to see his own hours report.
 */

session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$current_page = "employee_hours.php";

include '../config.inc.php';

// Determine who is wishing to see the report and has authenticated himself
if (! isset($_SESSION['valid_report_employee'])) {
    include '../admin/header.php';
    //     include '../admin/topmain.php';
    include 'topmain.php';
    include 'leftmain.php';

    echo "
      <!-- Invalid Employee -->
      <title>
         $title
      </title>

      <table width=100% border=0 cellpadding=7 cellspacing=1>
         <tr class=right_main_text>
            <td height=10 align=center valign=top scope=row class=title_underline>
               PHP Timeclock Reports
            </td>
         </tr>
         <tr class=right_main_text>
            <td align=center valign=top scope=row>
               <table width=200 border=0 cellpadding=5 cellspacing=0>
                  <tr class=right_main_text>
                     <td align=center>
                        You are not presently logged in, or do not have permission to view this page.
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td align=center>
                        Click
                        <a class=admin_headings href='../login.php?login_action=reports'>
                           <u>here</u>
                        </a> to login.
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </body>
</html>";
    exit;
}

include 'header_post_reports.php';
include 'topmain.php';
include 'leftmain.php';

/**
 * Validates the input from the user
 * @param $from_date is the date to start the report on.
 * @param $to_date is the date to finish the report on.
 * @param $round_time is the method to use to round the total users time.
 * @param $show_details sets whether the report is to show the details of the punch in the report.
 * @param $display_ip sets whether the report is to display the IP from the punch.
 * @param $displayEmptyHours sets whether the report is to display empty hours.
 * @return true if all input is valid, false if one of the inputs are invalid.
 */
function validInput($from_date, $to_date, $round_time, $show_details, $display_ip, $displayEmptyHours) {
    $input_is_valid = True;

    if ((! empty($round_time)) && ($round_time != '1') && ($round_time != '2') && ($round_time != '3') && ($round_time != '4') && ($round_time != '5')) {
        echo "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                  <!-- Rounding Method Missing Message -->
                  <tr>
                     <td class=table_rows_red>
                        No rounding method selected.
                     </td>
                  </tr>";
        $input_is_valid = False;
    }
    if (empty($show_details)) {
        if ($input_is_valid) {
            print "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>";
        }
        echo "
                  <!-- Show Details Missing Message -->
                  <tr>
                     <td class=table_rows_red>
                        Show details missing.
                     </td>
                  </tr>";
        $input_is_valid = False;
    }
    if (empty($display_ip) && ($show_details == "yes")) {
        if ($input_is_valid) {
            print "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>";
        }
        echo "
                  <!-- Display IP Missing Message -->
                  <tr>
                     <td class=table_rows_red>
                        Display IP details missing.
                     </td>
                  </tr>";
        $input_is_valid = False;
    }
    if (empty($from_date)) {
        if ($input_is_valid) {
            print "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>";
        }
        echo "
                  <!-- From Date Missing Message -->
                  <tr>
                     <td class=table_rows_red>
                        From date details missing.
                     </td>
                  </tr>";
        $input_is_valid = False;
//    } elseif (! eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $from_date, $date_regs)) {
    }  elseif (!preg_match('/' . "^([0-9]?[0-9])+[-|\/|.]+([0-9]?[0-9])+[-|\/|.]+(([0-9]{2})|([0-9]{4}))$" . '/i', $from_date, $date_regs)) {
        if ($input_is_valid) {
            print "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>";
        }
        echo "
                  <!-- From Date Missing Message -->
                  <tr>
                     <td class=table_rows_red>
                        From date invalid.
                     </td>
                  </tr>";
        $input_is_valid = False;
    }
    if ($calendar_style == "amer") {
        if (isset($date_regs)) {
            $from_month = $date_regs[1];
            $from_day = $date_regs[2];
            $from_year = $date_regs[3];
        }
    } elseif ($calendar_style == "euro") {
        if (isset($date_regs)) {
            $from_month = $date_regs[2];
            $from_day = $date_regs[1];
            $from_year = $date_regs[3];
        }
    }
    if ($from_month > 12 || $from_day > 31) {
        if ($input_is_valid) {
            print "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>";
        }
        echo "
                <!-- From Date Missing Message -->
                <tr>
                    <td class=table_rows_red>
                       From date invalid.
                    </td>
                </tr>";
        $input_is_valid = False;
    }
    if (empty($to_date)) {
        if ($input_is_valid) {
            print "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>";
        }
        echo "
                  <!-- To Date Missing Message -->
                  <tr>
                     <td class=table_rows_red>
                        To date details missing.
                     </td>
                  </tr>";
        $input_is_valid = False;
//    } elseif (! eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $to_date, $date_regs)) {
    }  elseif (!preg_match('/' . "^([0-9]?[0-9])+[-|\/|.]+([0-9]?[0-9])+[-|\/|.]+(([0-9]{2})|([0-9]{4}))$" . '/i', $from_date, $date_regs)) {
        if ($input_is_valid) {
            print "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>";
        }
        echo "
                  <!-- To Date Missing Message -->
                  <tr>
                     <td class=table_rows_red>
                        To date details invalid.
                     </td>
                  </tr>";
        $input_is_valid = False;
    }
    if ($calendar_style == "amer") {
        if (isset($date_regs)) {
            $to_month = $date_regs[1];
            $to_day = $date_regs[2];
            $to_year = $date_regs[3];
        }
    } elseif ($calendar_style == "euro") {
        if (isset($date_regs)) {
            $to_month = $date_regs[2];
            $to_day = $date_regs[1];
            $to_year = $date_regs[3];
        }
    }
    if ($to_month > 12 || $to_day > 31) {
        if ($input_is_valid) {
            print "
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>";
        }
        echo "
                  <!-- To Date Missing Message -->
                  <tr>
                     <td class=table_rows_red>
                        To date details invalid.
                     </td>
                  </tr>";
        $input_is_valid = False;
    }
        if (! $input_is_valid) {
            print "
               </table>
            </td>
         </tr>";
        }

    return $input_is_valid;
}

/**
 * Outputs the record of a punched item.
 * @param $new_date sets if the punch is the start of a new day.
 * @param $info_date is the date of the punch.
 * @param $row_color is the current colour to use for the row.
 * @param $punchlist_color is the colour of the punch status item.
 * @param $info_inout is the status of the punch.
 * @param $time_formatted is the time of the punch.
 * @param $display_ip sets if the IP is to be displayed.
 * @param $info_ipaddress is the IP of the punch.
 * @param $info_notes is the notes of the punch.
 * @param $show_details sets if the details of the punch should be displayed.
 */
function output_date_record($new_date, $info_date, $row_color, $punchlist_color, $info_inout, $time_formatted, $display_ip, $info_ipaddress, $info_notes, $show_details) {
    if ($new_date == True) {
        print "
         <tr>
            <td nowrap style='font-size:11px;color:#000000;border-style:solid;border-color:#888888; border-width:1px 0px 0px 0px;'>
               Punched Hours: $info_date
            </td>
            <td nowrap style='color:#000000;padding-left:25px;border-style:solid;border-color:#888888; border-width:1px 0px 0px 0px;'>
               &nbsp;
            </td>
         </tr>
         <tr>
            <td width=100% colspan=2>
               <table width=100% align=center class=misc_items border=0 cellpadding=0 cellspacing=0>";
    }
    if ($show_details == "yes") {
        print "
                    <tr align=\"left\">
                        <td align=left width=13% nowrap>
                            $info_inout
                        </td>
                        <td nowrap align=right width=10% style='padding-right:25px;'>
                            $time_formatted
                        </td>";
        if ($display_ip == "yes") {
            print "
                        <td nowrap align=left width=15% style='padding-right:25px; color:$punchlist_color;'>
                            $info_ipaddress
                        </td>";
        } else {
            print "
                        <td nowrap align=left width=15% style='padding-right:25px; color:$punchlist_color;'>
                            &nbsp;
                        </td>";
        }
        print "
                        <td width=77%>
                            $info_notes
                        </td>";
    }
    print "
                  </tr>";
}

// Determine if we need to validate post input
if ($request == 'POST') {
    // Begin creating post data
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $round_time = $_POST['round_time'];
    $show_details = $_POST['show_details'];
    $display_ip = $_POST['display_ip'];

    if ($show_details == "0") { // set display variable
        $show_details = "no";
    } else if ($show_details == "1") {
        $show_details = "yes";
    }
    if ($display_ip == "0") { // set display ip variable
        $display_ip = "no";
    } else if ($display_ip == "1") {
        $display_ip = "yes";
    }
    $is_valid_input = validInput($from_date, $to_date, $round_time, $show_details, $display_ip, $displayEmptyHours);
    if ($is_valid_input) {
     //   eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $from_date, $date_regs);
        preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date , $date_regs);
        if ($calendar_style == "amer") {
            $from_month = $date_regs[1];
            $from_day = $date_regs[2];
            $from_year = $date_regs[3];
        } elseif ($calendar_style == "euro") {
            $from_month = $date_regs[2];
            $from_day = $date_regs[1];
            $from_year = $date_regs[3];
        }
   //     eregi ("^([0-9]?[0-9])+[-|/|.]+([0-9]?[0-9])+[-|/|.]+(([0-9]{2})|([0-9]{4}))$", $to_date, $date_regs);
	preg_match("/^([0-9]{1,2})[\-\/\.]([0-9]{1,2})[\-\/\.](([0-9]{2})|([0-9]{4}))$/i", $post_date , $date_regs);
        if ($calendar_style == "amer") {
            $to_month = $date_regs[1];
            $to_day = $date_regs[2];
            $to_year = $date_regs[3];
        } elseif ($calendar_style == "euro") {
            $to_month = $date_regs[2];
            $to_day = $date_regs[1];
            $to_year = $date_regs[3];
        }
        $from_date = "$from_month/$from_day/$from_year";
		$from_dateeuro = "$from_day/$from_month/$from_year";
// funkar ej $from_date = "$from_day/$from_month/$from_year";
        $from_timestamp = strtotime($from_date . " " . $report_start_time) - $tzo;
        $to_date = "$to_month/$to_day/$to_year";
		$to_dateeuro = "$to_day/$to_month/$to_year";
// funkar ej $to_date = "$to_day/$to_month/$to_year";
        $to_timestamp = strtotime($to_date . " " . $report_end_time) - $tzo + 60;
    }

} else {
    $is_valid_input = True;
}

if ($request == 'GET' || (! $is_valid_input)) { // Get the employee's report selections

    if ($is_valid_input) { // still need the headers
    echo "
      <!-- Employee Hours Date Selection Interface -->";
    }
    echo "

               <table class='table' width=100% height=100% border=0 cellpadding=10 cellspacing=1>
                  <tr class=right_main_text>
                     <td valign=top>
                        <br />
                        <form name='form' action='$self' method='post' onsubmit=\"return isFromOrToDate();\">
                           <table align=center class=table_border width=60% border=0 cellpadding=3 cellspacing=0>
                              <tr>
                                 <th class=rightside_heading nowrap halign=left colspan=3>
                                    <img src='../images/icons/report.png' />&nbsp;&nbsp;&nbsp;
                                    Employee Hours Worked Report
                                 </th>
                              </tr>
                              <tr>
                                 <td height=15> </td>
                              </tr>
                              <input type='hidden' name='date_format' value='$js_datefmt'>
                              <tr>
                                 <td class=table_rows style='padding-left:32px;' width=20% nowrap>
                                    From Date: ($tmp_datefmt)
                                 </td>
                                 <td style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;' width=80% >
                                    <input id='datepicker' type='text' size='10' maxlength='10' name='from_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
                                    <a href=\"#\" onclick=\"form.from_date.value='';cal.select(document.forms['form'].from_date,'from_date_anchor','$js_datefmt'); return false;\" name=\"from_date_anchor\" id=\"from_date_anchor\" style='font-size:11px;color:#27408b;'>
                                       Pick Date
                                    </a>
                                 </td>
                              <tr>
                              <tr>
                                 <td class=table_rows style='padding-left:32px;' width=20% nowrap>
                                    To Date: ($tmp_datefmt)
                                 </td>
                                 <td style='color:red;font-family:Tahoma;font-size:10px;padding-left:20px;' width=80% >
                                    <input id='datepicker1' type='text' size='10' maxlength='10' name='to_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
                                    <a href=\"#\" onclick=\"form.to_date.value='';cal.select(document.forms['form'].to_date,'to_date_anchor','$js_datefmt'); return false;\" name=\"to_date_anchor\" id=\"to_date_anchor\" style='font-size:11px;color:#27408b;'>
                                       Pick Date
                                    </a>
                                 </td>
                              </tr>
                              <tr>
                                 <td class=table_rows align=right colspan=3 style='color:red;font-family:Tahoma;font-size:10px;'>
                                    *&nbsp;required&nbsp;
                                 </td>
                              </tr>
                           </table>
                           <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\" height=200>
                              &nbsp;
                           </div>
                           <table align=center width=60% border=0 cellpadding=0 cellspacing=3>
                              <tr>
                                 <td class=table_rows height=25 valign=bottom>
                                    1.&nbsp;&nbsp;&nbsp;Show status details?
                                 </td>
                              </tr>";
    if (strtolower($ip_logging) == "yes") {
        if ($show_details == 'yes') {
            echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='show_details' value='1' checked onFocus=\"javascript:form.display_ip[0].disabled=false;form.display_ip[1].disabled=false;\">&nbsp;Yes&nbsp;
                                    <input type='radio' name='show_details' value='0' onFocus=\"javascript:form.display_ip[0].disabled=true; form.display_ip[1].disabled=true;\">&nbsp;No
                                 </td>
                              </tr>";
        } else {
            echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='show_details' value='1' onFocus=\"javascript:form.display_ip[0].disabled=false;form.display_ip[1].disabled=false;\">&nbsp;Yes&nbsp;
                                    <input type='radio' name='show_details' value='0' checked onFocus=\"javascript:form.display_ip[0].disabled=true; form.display_ip[1].disabled=true;\">&nbsp;No
                                 </td>
                              </tr>";
        }
    } else {
        if ($show_details == 'yes') {
            echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='show_details' value='1' checked>&nbsp;Yes&nbsp;
                                    <input type='radio' name='show_details' value='0'>&nbsp;No
                                 </td>
                              </tr>";
        } else {
            echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='show_details' value='1'>&nbsp;Yes&nbsp;
                                    <input type='radio' name='show_details' value='0' checked>&nbsp;No
                                 </td>
                              </tr>";
        }
    }
    if (strtolower($ip_logging) == "yes") {
        echo "
                              <tr>
                                 <td class=table_rows height=25 valign=bottom>
                                    2.&nbsp;&nbsp;&nbsp;Display connecting ip address information? (only available if \"Show status details?\" is set to \"Yes\".)
                                 </td>
                              </tr>";
        if ($show_details == 'yes') {
            if ($display_ip == "yes") {
                echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='display_ip' value='1' checked>&nbsp;Yes&nbsp;
                                    <input type='radio' name='display_ip' value='0'>&nbsp;No
                                 </td>
                              </tr>";
            } else {
                echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='display_ip' value='1' >&nbsp;Yes
                                    <input type='radio' name='display_ip' value='0' checked>&nbsp;No
                                 </td>
                              </tr>";
            }
        } else {
            if ($display_ip == "yes") {
                echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='display_ip' value='1' checked disabled>&nbsp;Yes&nbsp;
                                    <input type='radio' name='display_ip' value='0' disabled>&nbsp;No
                                 </td>
                              </tr>";
            } else {
                echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='display_ip' value='1' disabled>&nbsp;Yes&nbsp;
                                    <input type='radio' name='display_ip' value='0' checked disabled>&nbsp;No
                                 </td>
                              </tr>";
            }
        }
    }
    if (strtolower($ip_logging) == "yes") {
        echo "
                              <tr>
                                 <td colspan=2 class=table_rows height=25 valign=bottom>
                                    3.&nbsp;&nbsp;&nbsp;Round each day's time?
                                 </td>
                              </tr>";
    } else {
        echo "
                              <tr>
                                 <td colspan=2 class=table_rows height=25 valign=bottom>
                                    2.&nbsp;&nbsp;&nbsp;Round each day's time?
                                 </td>
                              </tr>";
    }
    if ($round_time == '1') {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='1' checked>&nbsp;To the nearest 5 minutes (1/12th of an hour)
                                 </td>
                              </tr>";
    } else {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='1'>&nbsp;To the nearest 5 minutes (1/12th of an hour)
                                 </td>
                              </tr>";
    }
    if ($round_time == '2') {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='2' checked>&nbsp;To the nearest 10 minutes (1/6th of an hour)
                                 </td>
                              </tr>";
    } else {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='2'>&nbsp;To the nearest 10 minutes (1/6th of an hour)
                                 </td>
                              </tr>";
    }
    if ($round_time == '3') {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='3' checked>&nbsp;To the nearest 15 minutes (1/4th of an hour)
                                 </td>
                              </tr>";
    } else {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='3'>&nbsp;To the nearest 15 minutes (1/4th of an hour)
                                 </td>
                              </tr>";
    }
    if ($round_time == '4') {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='4' checked>&nbsp;To the nearest 20 minutes (1/3rd of an hour)
                                 </td>
                              </tr>";
    } else {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='4'>&nbsp;To the nearest 20 minutes (1/3rd of an hour)
                                 </td>
                              </tr>";
    }
    if ($round_time == '5') {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='5' checked>&nbsp;To the nearest 30 minutes (1/2 of an hour)
                                 </td>
                              </tr>";
    } else {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value='5'>&nbsp;To the nearest 30 minutes (1/2 of an hour)
                                 </td>
                              </tr>";
    }
    if (empty($round_time)) {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value=0 checked>&nbsp;Do not round
                                 </td>
                              </tr>";
    } else {
        echo "
                              <tr>
                                 <td class=table_rows align=left nowrap style='padding-left:15px;'>
                                    <input type='radio' name='round_time' value=0>&nbsp;Do not round
                                 </td>
                              </tr>";
    }
    echo "
                              <tr>
                                 <td height=10> </td>
                              </tr>
                           </table>
                           <table align=center width=60% border=0 cellpadding=0 cellspacing=3>
                              <tr>
                                 <td width=30>
                                    <input type='image' name='submit' value='Edit Time' align='middle' src='../images/buttons/next_button.png'>
                                 </td>
                                 <td>
                                    <a href='../index.php'>
                                       <img src='../images/buttons/cancel_button.png' border='0'>
                                    </a>
                                 </td>
                              </tr>
                           </table>
                        </form>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
} else if ($request == "POST") { // Generate the employee's report
    $time = time();
    $rpt_hour = gmdate('H',$time);
    $rpt_min = gmdate('i',$time);
    $rpt_sec = gmdate('s',$time);
    $rpt_month = gmdate('m',$time);
    $rpt_day = gmdate('d',$time);
    $rpt_year = gmdate('Y',$time);
    $rpt_stamp = time ($rpt_hour, $rpt_min, $rpt_sec, $rpt_month, $rpt_day, $rpt_year);

    $rpt_stamp = $rpt_stamp + @$tzo;
    $rpt_time = date($timefmt, $rpt_stamp);
    $rpt_date = date($datefmt, $rpt_stamp);

    $query = "SELECT empfullname, displayname FROM ".$db_prefix."employees WHERE tstamp IS NOT NULL AND empfullname <> 'admin' ORDER BY displayname ASC";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    while ($row = mysqli_fetch_array($result)) {
        $empfullname = stripslashes("".$row['empfullname']."");
        $displayname = stripslashes("".$row['displayname']."");
        if ($empfullname == $_SESSION['valid_report_employee']) {
            break;
        }
    }


    echo '<div class="col-md-12">
          <div class="box"><div class="box-body no-padding">';
    echo "
      <!-- Employee Hours Dates Selected Report -->
      <table class='table'>
         <tr>
            <td width=80% >
               Ran on: $rpt_date at $rpt_time
            </td>
            <td nowrap >
               Employee Total Hours Report for $empfullname
            </td>
         </tr>
         <tr>
            <td width=80%> </td>
            <td nowrap >";
	        if ($calendar_style == "amer") {
	              echo "Date Range: $from_date - $to_date";
	        } elseif ($calendar_style == "euro") {
	            echo "Date Range: $from_dateeuro - $to_dateeuro";
	        }
            echo "</td>
         </tr>";
    if (strtolower($user_or_display) == "display") {
        echo "
         <tr>
            <td width=100% colspan=2 >
               <b>$displayname</b>
            </td>
         </tr>";
    } else {
        echo "
         <tr>
            <td width=100% colspan=2 >
               <b>$empfullname</b>
            </td>
         </tr>";
    }
    echo "
         <tr>
            <td width=75% nowrap align=left >
               <b> <u>Date</u> </b>
            </td>
            <td width=25% nowrap align=left >
               <b> <u>Hours Worked</u> </b>
            </td>
         </tr>";
    $row_color = $color1; // Initial row color

    $query = "SELECT ".$db_prefix."info.`inout`, ".$db_prefix."info.timestamp, ".$db_prefix."info.notes, ".$db_prefix."info.ipaddress, ".$db_prefix."punchlist.in_or_out, ".$db_prefix."punchlist.punchitems, ".$db_prefix."punchlist.color from ".$db_prefix."info, ".$db_prefix."punchlist, ".$db_prefix."employees WHERE ".$db_prefix."info.fullname LIKE ('".$empfullname."') AND ".$db_prefix."info.timestamp >= '".$from_timestamp."' AND ".$db_prefix."info.timestamp < '".$to_timestamp."' AND ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems AND ".$db_prefix."employees.empfullname = '".$empfullname."' AND ".$db_prefix."employees.empfullname <> 'admin' ORDER BY ".$db_prefix."info.timestamp ASC";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

    $current_date = date($datefmt, "0000"); // Tracks what date we're currently displaying
    $total_hours = 0.0; // Tracks the hours worked in the report
    $daily_hour = $current_in_stamp = 0.0;
    while ($row = mysqli_fetch_array($result)) { // Process each punch
        $info_inout = "".$row['inout']."";
        $info_timestamp = "".$row['timestamp']."" + $tzo;
        $info_notes = "".$row['notes']."";
        $info_ipaddress = "".$row['ipaddress']."";
        $punchlist_in_or_out = "".$row['in_or_out']."";
        $punchlist_punchitems = "".$row['punchitems']."";
        $punchlist_color = "".$row['color']."";

        // Format the input according to the server settings
        $info_date = date($datefmt, $info_timestamp);
        $time_formatted = date($timefmt, $info_timestamp);

        if ($current_date != $info_date) { // Display the total hours for that day
            if ($current_date != date($datefmt, "0000")) { // Skip the first iteration
                if ($previous_status == "1") { // The ending status of the day was in
                    if ($daily_hour > 0) {
                        $daily_hour += (12 - date('H', $current_timestamp)) + (date('i', $current_timestamp) / 60);
                        $total_hours += (12 - date('H', $current_timestamp)) + (date('i', $current_timestamp) / 60);
                        $current_in_stamp = 0;
                    } else { // The ending status of the day was out and the end of the day
                        $daily_hour += (24 - date('H', $current_timestamp)) + (date('i', $current_timestamp) / 60);
                        $total_hours += (24 - date('H', $current_timestamp)) + (date('i', $current_timestamp) / 60);
                        $current_in_stamp = 0;
                    }
                }
                print "
                  <tr>
                     <td nowrap  align='left'>
                        ".date('l', $current_timestamp)."'s Total Hours
                     </td>
                     <td>
                        &nbsp;
                     </td>
                     <td>
                        &nbsp;
                     </td>
                     <td nowrap align='right'>
                        ".number_format($daily_hour, 2)."
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
            }
            $daily_hour = 0.0;
            $new_date = True;
            $current_date = $info_date;
            $current_timestamp = $info_timestamp;
        } else {
            $new_date = False;
        }

        // Calculate the hour tracking for each day
        if ($punchlist_in_or_out == "0") { // Is the current status an out status
            if ($current_in_stamp == 0) {
                $daily_hour += (date('H', $info_timestamp) * 3600) + (date('i', $info_timestamp) * 60);
            } else {
                $daily_hour += ($info_timestamp - $current_in_stamp);
            }
            $daily_hour = secsToHours($daily_hour, $round_time);
            $total_hours += $daily_hour;
            $current_in_stamp = 0;
        } else if ($punchlist_in_or_out == "1") { // Is the current status an in status
            $current_in_stamp = $info_timestamp;
        }

        output_date_record($new_date, $info_date, $row_color, $punchlist_color, $info_inout, $time_formatted, $display_ip, $info_ipaddress, $info_notes, $show_details);

        $row_color = ($row_color == $color1) ? $color2 : $color1;
        $previous_status = $punchlist_in_or_out;
    }
    if ($total_hours != 0){ // Print last days hours and tally up the total period hours.
        print "
                  <tr>
                     <td nowrap  align='left'>
                        ".date('l', $current_timestamp)."'s Total Hours
                     </td>
                     <td>
                        &nbsp;
                     </td>
                     <td>
                        &nbsp;
                     </td>
                     <td nowrap align='right'>
                        ".number_format($daily_hour, 2)."
                     </td>
                  </tr>
               </table>";

    }
    print "
               <tr align=\"left\">
                  <td nowrap style='border-style:solid;border-color:#888888; border-width:1px 0px 0px 0px;'>
                     <b>Total Hours</b>
                  </td>";
    $total_hours = number_format($total_hours, 2);
    if ($total_hours < 10) {
        print "
                  <td nowrap style='border-style:solid;border-color:#888888; border-width:1px 0px 0px 0px;padding-left:30px;'>";
    } elseif ($total_hours < 100) {
        print "
                  <td nowrap style='border-style:solid;border-color:#888888; border-width:1px 0px 0px 0px;padding-left:23px;'>";
    } else {
        print "
                  <td nowrap style='border-style:solid;border-color:#888888; border-width:1px 0px 0px 0px;padding-left:15px;'>";
    }
    print "
                     <b>$total_hours</b>
                  </td>
               </tr>
            </td>
         </tr></table>";
	       echo '</div></div></div>';
}
include '../footer.php';
include '../theme/templates/controlsidebar.inc'; 
include '../theme/templates/endmain.inc';
include '../theme/templates/reportsfooterscripts.inc';
exit;
?>
