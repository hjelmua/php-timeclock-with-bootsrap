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
 * This module creates the employee current/previous status table.
 */

$row_count = 0;
$page_count = 0;

// Add the Message of the day
echo "
                        <!-- Current Display Messages -->
                        <table class=misc_items width=100% border=0 cellpadding=2 cellspacing=0>";

// Determine if we should add the message of the day
if (! isset($_GET['printer_friendly']) && ($message_of_the_day != "none")) {
    echo "
                           <!-- Message Of The Day Display -->
                           <tr>
                              <td class=motd colspan=5>
                                 <strong> Message Of The Day: </strong> <br>
                                 ".htmlspecialchars($message_of_the_day)."
                              </td>";
} else if (! isset($_GET['printer_friendly']) && ($message_of_the_day == "none")) {
    echo "
                           <!-- Message Of The Day Display -->
                           <tr>
                              <td colspan=3 >
                                 &nbsp;
                              </td>";
}

// Parse the employee info in the result array
while ($row = mysql_fetch_array($result)) {
    $display_stamp = "".$row["timestamp"]."";
    $time = date($timefmt, $display_stamp);
    $date = date($datefmt, $display_stamp);

    if ($row_count == 0) {
        if ($page_count == 0) {
            // display sortable column headings for main page //
            if (! isset($_GET['printer_friendly'])) {
                echo "
                              <td align=right colspan=2>
                                 <a style='font-size:11px;color:#853d27;' href='timeclock.php?printer_friendly=true'>
                                    Printer Friendly Page
                                 </a>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 &nbsp;
                              </td>
                           </tr>";
            }

            echo "
                           <!-- Current Employee Status' -->
                           <tr class=notprint>";

            if ($display_name == "yes") {
                echo "
                              <td nowrap width=20% align=left style='padding-left:10px;padding-right:10px;'>
                                 <a style='font-size:11px;color:#27408b;' href='$current_page?sortcolumn=empfullname&sortdirection=$sortnewdirection'>
                                    Name
                                 </a>
                              </td> ";
            }

            if ($display_status == "yes") {
                echo "
                              <td nowrap width=10% align=left style='padding-left:10px;'>
                                 <a style='font-size:11px;color:#27408b;' href='$current_page?sortcolumn=inout&sortdirection=$sortnewdirection'>
                                    Status
                                 </a>
                              </td>";
            }

            if ($display_date == "yes") {
                echo "
                              <td nowrap width=5% align=left style='padding-left:10px;'>
                                 <a style='font-size:11px;color:#27408b;' href='$current_page?sortcolumn=tstamp&sortdirection=$sortnewdirection'>
                                    Date
                                 </a>
                              </td>";
            }

            if ($display_time == "yes") {
                echo "
                              <td nowrap width=5% align=left style='padding-right:10px;'>
                                 <a style='font-size:11px;color:#27408b;' href='$current_page?sortcolumn=tstamp&sortdirection=$sortnewdirection'>
                                    Time
                                 </a>
                              </td>";
            }

            if ($display_office_name == "yes") {
                echo "
                              <td nowrap width=10% align=left style='padding-left:10px;'>
                                 <a style='font-size:11px;color:#27408b;' href='$current_page?sortcolumn=office&sortdirection=$sortnewdirection'>
                                    Office
                                 </a>
                              </td>";
            }

            if ($display_group_name == "yes") {
                echo "
                              <td nowrap width=10% align=left style='padding-left:10px;'>
                                 <a style='font-size:11px;color:#27408b;' href='$current_page?sortcolumn=groups&sortdirection=$sortnewdirection'>
                                    Group
                                 </a>
                              </td>";
            }

            if ($display_notes == "yes") {
                echo "
                              <td style='padding-left:10px;'>
                                 <a style='font-size:11px;color:#27408b;' href='$current_page?sortcolumn=notes&sortdirection=$sortnewdirection'>
                                    <u>Notes</u>
                                 </a>
                              </td>";
            }

            echo "
                           </tr>";
        } else {
            // display report name and page number of printed report above the column headings of each printed page //
            $temp_page_count = $page_count + 1;
        }

        echo "
                           <tr class=notdisplay>";

        if ($display_name == "yes") {
            echo "
                              <td nowrap width=20% align=left style='padding-left:10px;padding-right:10px;font-size:11px;color:#27408b; text-decoration:underline;'>
                                 Name
                              </td>";
        }

        if ($display_status == "yes") {
            echo "
                              <td nowrap width=10% align=left style='padding-left:10px;font-size:11px;color:#27408b; text-decoration:underline;'>
                                 Status
                              </td>";
        }

        if ($display_date == "yes") {
            echo "
                              <td nowrap width=5% align=left style='padding-left:10px;font-size:11px;color:#27408b; text-decoration:underline;'>
                                 Date
                              </td>";
        }

        if ($display_time == "yes") {
            echo "
                              <td nowrap width=5% align=left style='padding-right:10px;font-size:11px;color:#27408b; text-decoration:underline;'>
                                 Time
                              </td>";
        }

        if ($display_office_name == "yes") {
            echo "
                              <td nowrap width=10% align=left style='padding-left:10px;font-size:11px;color:#27408b; text-decoration:underline;'>
                                 Office
                              </td>";
        }

        if ($display_group_name == "yes") {
            echo "
                              <td nowrap width=10% align=left style='padding-left:10px;font-size:11px;color:#27408b; text-decoration:underline;'>
                                 Group
                              </td>";
        }

        if ($display_notes == "yes") {
            echo "
                              <td style='padding-left:10px;'>
                                 <a style='font-size:11px;color:#27408b;text-decoration:underline;'>
                                    Notes
                                 </a>
                              </td>";
        }

        echo "
                           </tr>";
    }

    // begin alternating row colors //
    $row_color = ($row_count % 2) ? $color1 : $color2;

    // display the query results //
    $display_stamp = $display_stamp + @$tzo;
    $time = date($timefmt, $display_stamp);
    $date = date($datefmt, $display_stamp);

    echo "
                           <tr class=display_row>";

    if ($display_name == "yes") {
        if ($show_display_name == "yes") {
            echo stripslashes("
                              <td nowrap width=20% bgcolor='$row_color' style='padding-left:10px; padding-right:10px;'>
                                 ".$row["displayname"]."
                              </td>");
        } elseif ($show_display_name == "no") {
            echo stripslashes("
                              <td nowrap width=20% bgcolor='$row_color' style='padding-left:10px; padding-right:10px;'>
                                 ".$row["empfullname"]."
                              </td>");
        }
    }

    if ($display_status == "yes") {
        // Get in or out status of the current status
        $status_query = "SELECT * FROM ".$db_prefix."punchlist ORDER BY punchitems ASC";
        $status = mysql_query($status_query);

        while ($status_row = mysql_fetch_array($status)) {
            if ($status_row['punchitems'] == $row["inout"]) {
                echo "
                              <td nowrap align=left width=10% style='background-color:$row_color;color:".$row["color"]."; padding-left:10px;'>";

                if ((($display_status_option == "icon") || ($display_status_option == "both")) && $status_row['in_or_out'] == 0) { // An out status icon
                    echo "
                                 <img src='images/icons/status_out.gif' alt='Employee Is Out' />";
                } else if ((($display_status_option == "icon") || ($display_status_option == "both")) && $status_row['in_or_out'] == 1) { // An in status icon
                    echo "
                                 <img src='images/icons/status_in.gif' alt='Employee Is In' />";
                }

                if (($display_status_option == "text") || ($display_status_option == "both")) { // Add the status.
                    echo "
                                 ".$row["inout"];
                }
                echo "
                              </td> ";
                break;
            }
        }
        mysql_free_result($status);
    }

    if ($display_date == "yes") {
        echo "
                              <td nowrap align=right width=5% bgcolor='$row_color' style='padding-left:10px;'>
                                 ".$date."
                              </td>";
        }

    if ($display_time == "yes") {
        echo "
                              <td nowrap align=right width=5% bgcolor='$row_color' style='padding-right:10px;'>
                                 ".$time."
                              </td>";
    }

    if ($display_office_name == "yes") {
        echo "
                              <td nowrap align=left width=10% bgcolor='$row_color' style='padding-left:10px;'>
                                 ".$row["office"]."
                              </td>";
    }

    if ($display_group_name == "yes") {
        echo "
                              <td nowrap align=left width=10% bgcolor='$row_color' style='padding-left:10px;'>
                                 ".$row["groups"]."
                              </td>";
    }

    if ($display_notes == "yes") {
        echo stripslashes("
                              <td bgcolor='$row_color' style='padding-left:10px;'>
                                 ".$row["notes"]."
                              </td>");
    }

    echo "
                           </tr>";
    $row_count++;

    // output 40 rows per printed page //
    if ($row_count == 40) {
        echo "
                           <tr style=\"page-break-before:always;\">
                           </tr>";
        $row_count = 0;
        $page_count++;
    }
}
echo "
                        </table>";

if (! isset($_GET['printer_friendly'])) {
    echo "
                     </td>
                  </tr>";
}
mysql_free_result($result);
?>
