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
 * This module will display the technology information about PhpTimeClock.
 * This module will also add the ending HTML tags to make it valid HTML.
 */

echo '
        </div>
        <!-- /.content-wrapper -->
	
	<!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">';


// Determine if we should add the contact E-mail to the footer
if (! empty($email) && ($email != "none")) {
    echo "
               <a class=footer_links href='mailto:$email'> Contact Management </a> &nbsp; &#8226;";
}

// Determine if the application information is set
if (empty($company_name) || empty($app_version)) {
    echo "
               <a class=footer_links href='https://github.com/hjelmua/php-timeclock-with-bootsrap' target='_blank'>Powered by PhpTimeClock</a>";
} else {
    echo "
               <a class=footer_links href='https://github.com/hjelmua/php-timeclock-with-bootsrap' target='_blank'> $company_name is Powered by PhpTimeClock $app_version</a>";
}

echo '
	</div>
<!-- Default to the left -->

Powered by <a class="footer_links" href="http://nginx.org/">NGINX</a> &nbsp;&#177 <a class="footer_links" href="http://mysql.org">mysql</a>&nbsp;&#177
<a class="footer_links" href="http://php.net">&nbsp;PHP</a> &nbsp;&#8226; <a class="footer_links" href="https://almsaeedstudio.com">AdminLTE theme</a> &nbsp;&#177 <a class="footer_links" href="http://getbootstrap.com">Bootstrap</a>

 </footer>
';

// Finish up the HTML to make it valid

?>
