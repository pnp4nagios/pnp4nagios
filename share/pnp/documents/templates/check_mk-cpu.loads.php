<?php
# +------------------------------------------------------------------+
# |             ____ _               _        __  __ _  __           |
# |            / ___| |__   ___  ___| | __   |  \/  | |/ /           |
# |           | |   | '_ \ / _ \/ __| |/ /   | |\/| | ' /            |
# |           | |___| | | |  __/ (__|   <    | |  | | . \            |
# |            \____|_| |_|\___|\___|_|\_\___|_|  |_|_|\_\           |
# |                                                                  |
# | Copyright Mathias Kettner 2009             mk@mathias-kettner.de |
# +------------------------------------------------------------------+
#
# This file is part of Check_MK.
# The official homepage is at http://mathias-kettner.de/check_mk.
#
# check_mk is free software;  you can redistribute it and/or modify it
# under the  terms of the  GNU General Public License  as published by
# the Free Software Foundation in version 2.  check_mk is  distributed
# in the hope that it will be useful, but WITHOUT ANY WARRANTY;  with-
# out even the implied warranty of  MERCHANTABILITY  or  FITNESS FOR A
# PARTICULAR PURPOSE. See the  GNU General Public License for more de-
# ails.  You should have  received  a copy of the  GNU  General Public
# License along with GNU Make; see the file  COPYING.  If  not,  write
# to the Free Software Foundation, Inc., 51 Franklin St,  Fifth Floor,
# Boston, MA 02110-1301 USA.

# Modified by Romuald FRONTEAU

$opt[1] = "--vertical-label Load --title \"CPU Load pour $hostname / $servicedesc\" --color=BACK#000000 --color=FONT#F7F7F7 --color=SHADEA#ffffff --color=SHADEB#ffffff --color=CANVAS#000000 --color=GRID#00991A --color=MGRID#00991A --color=ARROW#FF0000 ";

$def[1] =  "DEF:var1=$rrdfile:$DS[1]:AVERAGE " ;
$def[1] .= "DEF:var2=$rrdfile:$DS[2]:AVERAGE " ;
$def[1] .= "DEF:var3=$rrdfile:$DS[3]:AVERAGE " ;
$def[1] .= "AREA:var1#F8D605:\"1 min \" " ;
$def[1] .= "GPRINT:var1:LAST:\"%6.2lf last\" " ;
$def[1] .= "GPRINT:var1:AVERAGE:\"%6.2lf avg\" " ;
$def[1] .= "GPRINT:var1:MAX:\"%6.2lf max\\n\" ";

$def[1] .=  "CDEF:sp1=var2,100,/,10,* " ;
$def[1] .=  "CDEF:sp2=var2,100,/,20,* " ;
$def[1] .=  "CDEF:sp3=var2,100,/,30,* " ;
$def[1] .=  "CDEF:sp4=var2,100,/,40,* " ;
$def[1] .=  "CDEF:sp5=var2,100,/,50,* " ;
$def[1] .=  "CDEF:sp6=var2,100,/,60,* " ;
$def[1] .=  "CDEF:sp7=var2,100,/,70,* " ;
$def[1] .=  "CDEF:sp8=var2,100,/,80,* " ;
$def[1] .=  "CDEF:sp9=var2,100,/,90,* " ;

$def[1] .= "AREA:var2#F85B05:\"5 min \" ";
$def[1] .= "AREA:sp9#F85B05: " ;
$def[1] .= "AREA:sp8#FB7328: " ;
$def[1] .= "AREA:sp7#FD8645: " ;
$def[1] .= "AREA:sp6#FD9862: " ;
$def[1] .= "AREA:sp5#FDA97D: " ;
$def[1] .= "AREA:sp4#C5D760: " ;
$def[1] .= "AREA:sp3#FBBE9E: " ;
$def[1] .= "AREA:sp2#FFD9C5: " ;
$def[1] .= "AREA:sp1#FFFFFF: " ;

$def[1] .= "GPRINT:var2:LAST:\"%6.2lf last\" " ;
$def[1] .= "GPRINT:var2:AVERAGE:\"%6.2lf avg\" " ;
$def[1] .= "GPRINT:var2:MAX:\"%6.2lf max\\n\" ";

$def[1] .= "LINE2:var3#FC2505:\"15 min \" " ;
$def[1] .= "GPRINT:var3:LAST:\"%6.2lf last\" " ;
$def[1] .= "GPRINT:var3:AVERAGE:\"%6.2lf avg\" " ;
$def[1] .= "GPRINT:var3:MAX:\"%6.2lf max\\n\" " ;
?>
