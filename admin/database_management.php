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
 * This module will allow a sys admin to select to backup, restore, or
 * upgrade the database.
 */

session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

// Ensure that the curent user has system access
if (! isset($_SESSION['valid_user'])) {
    write_admin_interface($title);
    echo "
            <!-- Display User Is Not Logged In -->
            <td align=left class=right_main scope=col>
               <table width=100% border=0 cellpadding=7 cellspacing=1>
                  <tr class=right_main_text>
                     <td height=10 align=center valign=top scope=row class=title_underline>
                        PHP Timeclock Administration
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
                                 <a class=admin_headings href='../login.php?login_action=admin'>
                                    <u>here</u>
                                 </a> to login.
                              </td>
                           </tr>
                        </table>
                        <br />
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
    include "../footer.php";
    exit;
}

/**
 * Writes the administration interface on the left of the browser
 * @param $title adds the bookmark title.
 */
function write_admin_interface($title) {
    echo "
      <title>
         $title - Manage Database
      </title>
      <!-- Administration Interface -->
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td class=left_main width=180 align=left scope=col>
               <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>
                  <tr>
                     <td class=left_rows height=11> </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle>
                        Users
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
                        <a class=admin_headings href='useradmin.php'>
                           User Summary
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/user_add.png' alt='Create New User' />&nbsp;&nbsp;
                        <a class=admin_headings href='usercreate.php'>
                           Create New User
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/magnifier.png' alt='User Search' />&nbsp;&nbsp;
                        <a class=admin_headings href='usersearch.php'>
                           User Search
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=33 > </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle>
                        Offices
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/brick.png' alt='Office Summary' />&nbsp;&nbsp;
                        <a class=admin_headings href='officeadmin.php'>
                           Office Summary
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/brick_add.png' alt='Create New Office' />&nbsp;&nbsp;
                        <a class=admin_headings href='officecreate.php'>
                           Create New Office
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=33> </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle>
                        Groups
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;
                        <a class=admin_headings href='groupadmin.php'>
                           Group Summary
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp;
                        <a class=admin_headings href='groupcreate.php'>
                           Create New Group
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=33> </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle colspan=2>
                        In/Out Status
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/application.png' alt='Status Summary' />&nbsp;&nbsp;
                        <a class=admin_headings href='statusadmin.php'>
                           Status Summary
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp;
                        <a class=admin_headings href='statuscreate.php'>
                           Create Status
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=33> </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle colspan=2>
                        Miscellaneous
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/clock.png' alt='Modify Time' />&nbsp;&nbsp;
                        <a class=admin_headings href='timeadmin.php'>
                           Modify Time
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/application_edit.png' alt='Edit System Settings' />&nbsp;&nbsp;
                        <a class=admin_headings href='sysedit.php'>
                           Edit System Settings
                        </a>
                    </td>
                  </tr>
                  <tr>
                     <td class=current_left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/database_go.png' alt='Manage Database' />&nbsp;&nbsp;&nbsp;
                        <a class=admin_headings href='database_management.php'>
                           Manage Database
                        </a>
                     </td>
                  </tr>
               </table>
            </td>";
}

// Create Database Management Interface
write_admin_interface($title);
echo "
            <!-- Database Management Interface -->
            <td align=left class=right_main scope=col>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                  <tr>
                     <th class=table_heading_no_color nowrap width=100% align=left>
                        Manage Database
                     </th>
                  </tr>
               </table>
               <table class=table_border width=90% align=center border=0 cellpadding=0 cellspacing=0>
                  <tr class=right_main_text>
                     <td nowrap class=table_rows width=17%>
                        <img src='../images/icons/database_go.png' alt='Backup Database' />
                        <a href='database_backup.php'>
                           Backup Database
                        </a>
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td nowrap class=table_rows width=17%>
                        <img src='../images/icons/database_go.png' alt='Restore Database' />
                        <a href='database_restore.php'>
                           Restore Saved Database
                        </a>
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td nowrap class=table_rows width=17%>
                        <img src='../images/icons/database_go.png' alt='Upgrade Database' />
                        <a href='dbupgrade.php'>
                           Upgrade Database
                        </a>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
// Add HTML clean up and footer
include '../footer.php';
exit;
?>