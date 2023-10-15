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

# RRDtool Options
#$servicedes=$NAGIOS_SERVICEDESC

$fsname = str_replace("_", "/", substr($servicedesc, 3));
$fstitle = $fsname;

# Hack for windows: replace C// with C:\
if (strlen($fsname) == 3 && substr($fsname, 1, 2) == '//') {
    $fsname = $fsname[0] . "\:\\\\";
    $fstitle = $fsname[0] . ":\\";
}

$sizegb = sprintf("%.1f", $MAX[1] / 1024.0);
$opt[1] = "--vertical-label Pourcentage --slope-mode -l 0 -u 100 --title '$hostname: Filesystem $fstitle (Total --> $sizegb GB)' ";
#
#
# Graphen Definitions
$def[1] = "DEF:fs=$rrdfile:$DS[1]:MAX ";
$def[1] .= "CDEF:var1=fs,$MAX[1],/,100,* ";

$def[1] .=  "CDEF:sp1=var1,100,/,10,* " ;
$def[1] .=  "CDEF:sp2=var1,100,/,20,* " ;
$def[1] .=  "CDEF:sp3=var1,100,/,30,* " ;
$def[1] .=  "CDEF:sp4=var1,100,/,40,* " ;
$def[1] .=  "CDEF:sp5=var1,100,/,50,* " ;
$def[1] .=  "CDEF:sp6=var1,100,/,60,* " ;
$def[1] .=  "CDEF:sp7=var1,100,/,70,* " ;
$def[1] .=  "CDEF:sp8=var1,100,/,80,* " ;
$def[1] .=  "CDEF:sp9=var1,100,/,90,* " ;

$def[1] .= "VDEF:slope=var1,LSLSLOPE " ;
$def[1] .= "VDEF:int=var1,LSLINT " ;
$def[1] .= "CDEF:proj=var1,POP,slope,COUNT,*,int,+ " ;

$def[1] .= "AREA:var1#84C21F:\"% utilise sur $fsname\" ";
$def[1] .= "AREA:sp9#84C21F: " ;
$def[1] .= "AREA:sp8#8CC427: " ;
$def[1] .= "AREA:sp7#9CCA37: " ;
$def[1] .= "AREA:sp6#A5CD3F: " ;
$def[1] .= "AREA:sp5#B4D14F: " ;
$def[1] .= "AREA:sp4#C5D760: " ;
$def[1] .= "AREA:sp3#D5DC70: " ;
$def[1] .= "AREA:sp2#E6E280: " ;
$def[1] .= "AREA:sp1#F6E790: " ;

$def[1] .= "LINE1:var1#226600: ";
$def[1] .= "GPRINT:var1:LAST:\"current\: %6.2lf %%\" ";
$def[1] .= "GPRINT:var1:MAX:\"max\: %6.2lf %%\" ";
$def[1] .= "GPRINT:var1:AVERAGE:\"avg\: %6.2lf %%\\n\" ";
$def[1] .= "LINE2:proj#F83F05:\"Projection \" " ;
?>
