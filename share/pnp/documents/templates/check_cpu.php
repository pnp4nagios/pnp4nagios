<?php
#
# Copyright (c)  2010 Yannig Perre (http://lesaventuresdeyannigdanslemondeit.blogspot.com)
# Plugin: check_cpu
#
$ds_name[1] = "CPU activity";

$opt[1] = "--upper-limit 100 --vertical-label CPU -l0  --title \"CPU activity on $hostname\" ";

$trend_array = array(
  "one_month"    => array(strtotime("-1 month", $this->TIMERANGE['end']), $this->TIMERANGE['end'], "1 month trend:dashes=10", "#FF007F", "line3"),
  "global_trend" => array($this->TIMERANGE['start'], $this->TIMERANGE['end'], "Global trend\\n:dashes=20", "#707070", "line2"),
);

$def[1] =  rrd::def("var1", $RRDFILE[1], $DS[1]);
$def[1] .= rrd::def("var2", $RRDFILE[2], $DS[2]);
$def[1] .= rrd::cdef("user", "var2,var1,+");
$def[1] .= rrd::def("var3", $RRDFILE[3], $DS[3]);
$def[1] .= rrd::cdef("io", "var3,var2,+,var1,+");

$trends_graphic = "";

foreach(array_keys($trend_array) as $trend) {
  $def[1] .= rrd::def("var1$trend", $RRDFILE[1], $DS[1], "AVERAGE:start=".$trend_array[$trend][0]);
  $def[1] .= rrd::def("var2$trend", $RRDFILE[2], $DS[2], "AVERAGE:start=".$trend_array[$trend][0]);
  $def[1] .= rrd::cdef("user$trend", "var2$trend,var1$trend,+");

  $def[1] .= rrd::vdef("dtrend$trend", "user$trend,LSLSLOPE");
  $def[1] .= rrd::vdef("htrend$trend", "user$trend,LSLINT");
  $def[1] .= rrd::cdef("curve_user$trend", "user$trend,POP,dtrend$trend,COUNT,*,htrend$trend,+");
  $trends_graphic .= rrd::$trend_array[$trend][4]("curve_user$trend", $trend_array[$trend][3], $trend_array[$trend][2]);
}

if ($WARN[1] != "") { $def[1] .= rrd::hrule($WARN[1], "#FFFF00"); }
if ($CRIT[1] != "") { $def[1] .= rrd::hrule($CRIT[1], "#FF0000"); }

$def[1] .= rrd::area("io", "#00FF00", "iowait");
$def[1] .= rrd::gprint("var3", "LAST", "%6.2lf");
$def[1] .= rrd::gprint("var3", "AVERAGE", "avg %6.2lf");
$def[1] .= rrd::gprint("var3", "MAX", "max %6.2lf\\n");
$def[1] .= rrd::area("user", "#005CFF", "user  ");
$def[1] .= rrd::gprint("var1", "LAST", "%6.2lf");
$def[1] .= rrd::gprint("var1", "AVERAGE", "avg %6.2lf");
$def[1] .= rrd::gprint("var1", "MAX", "max %6.2lf\\n");
$def[1] .= rrd::area("var2", "#FF5C00", "sys   ");
$def[1] .= rrd::gprint("var2", "LAST", "%6.2lf");
$def[1] .= rrd::gprint("var2", "AVERAGE", "avg %6.2lf");
$def[1] .= rrd::gprint("var2", "MAX", "max %6.2lf\\n");
$def[1] .= rrd::line1("io", "#000000");
$def[1] .= rrd::line1("user", "#000000", "Total");
$def[1] .= rrd::gprint("user", "LAST", " %6.2lf");
$def[1] .= rrd::gprint("user", "AVERAGE", "moy %6.2lf");
$def[1] .= rrd::gprint("user", "MAX", "max %6.2lf\\n");
$def[1] .= rrd::line1("var2", "#000000");
$def[1] .= $trends_graphic;
?>
