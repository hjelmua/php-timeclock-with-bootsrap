<script language="JavaScript">
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

  var time = new Date()
  var cookieexpire = new Date(time.getTime() + 90 * 24 * 60 * 60 * 1000); //cookie expires in 90 days
  var timeclock = document.cookie;
  var timezone = (-(time.getTimezoneOffset()))

  function getthecookie(name) {
    var index = timeclock.indexOf(name + "=");
    if (index == -1) return null;
    index = timeclock.indexOf("=", index) + 1;
    var endstr = timeclock.indexOf(";", index);
    if (endstr == -1) endstr = timeclock.length;
    return unescape(timeclock.substring(index, endstr));
  }

  function setthecookie(timeclock, value) {
    if (value != null && value != "")
      document.cookie=timeclock + "=" + escape(value) + "; expires=" + cookieexpire.toGMTString();
    timeclock = document.cookie;
  }

  var tzoffset = getthecookie("tzoffset") || timezone;
  if (tzoffset == null || tzoffset == "")
    tzoffset="0";
  setthecookie("tzoffset", tzoffset);
</script>
