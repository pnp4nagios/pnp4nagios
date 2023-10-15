<?php
#
# Copyright (c) 2006-2010 Joerg Linge (http://www.pnp4nagios.org)
# Plugin: check_icmp [Multigraph]
#
# RTA
#
$ds_name[1] = "Round Trip Times";
$opt[1]  = "--lower=0 --vertical-label \"RTA\" --title \"Ping times\" ";
$def[1]  =  rrd::def("var1", $RRDFILE[1], $DS[1], "AVERAGE") ;
$def[1] .=  rrd::gradient('var1','f0f0f0','0000a0','Round Trip Times',20);
$def[1] .=  rrd::gprint("var1", array("LAST", "MAX", "AVERAGE"), "%6.2lf $UNIT[1]") ;

if($WARN[1] != ""){
    if($UNIT[1] == "%%"){ $UNIT[1] = "%"; };
    $def[1] .= rrd::hrule($WARN[1], "#FF8c00", "Warning  ".$WARN[1].$UNIT[1]."\\n");
}
if($CRIT[1] != ""){
    if($UNIT[1] == "%%"){ $UNIT[1] = "%"; };
    $def[1] .= rrd::hrule($CRIT[1], "#FF0000", "Critical  ".$CRIT[1].$UNIT[1]."\\n");
}

?>
