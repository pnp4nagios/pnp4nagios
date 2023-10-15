<?php

$ds_name[0] = "Heap usage / ".$servicedesc." ($hostname)";

$opt[0] = "--vertical-label \"Heap usage (MB)\" -l 0 --title \"".$ds_name[0]."\" ";

# Memory definition
$def[0]  = rrd::def("heapsize",   $RRDFILE[1], $DS[1]);
$def[0] .= rrd::def("usedmemory", $RRDFILE[2], $DS[2]);
$def[0] .= rrd::gradient("heapsize", "#ffffff", "#cceeee");
$def[0] .= rrd::line1("heapsize", "#ddcccc");
$def[0] .= rrd::gradient("usedmemory", "#ffffff", "#33cccc", 'Used memory');
$def[0] .= rrd::line1("usedmemory", "#339999");
$def[0] .= rrd::gprint("usedmemory", "LAST",    "last %4.lf");
$def[0] .= rrd::gprint("usedmemory", "MAX",     "max %4.lf");
$def[0] .= rrd::gprint("usedmemory", "AVERAGE", "average %4.2lf");

$next_def = 1;
for($i = 2; $i < count($this->DS, 2); $i++) {
 $label = $this->DS[$i]["LABEL"];
 if(preg_match("/^ThreadPoolSize$/", $label, $tmp)) {
   $source = "ThreadPoolSize";
   $label = "Thread pool / $servicedesc ($hostname)";
   $ds_name[$next_def] = $label;
   $opt[$next_def] = "--vertical-label \"Thread count\" -l 0 --title \"$label\" ";
   $def[$next_def]  = rrd::def($source,           $this->DS[$i]["RRDFILE"],   $this->DS[$i]["DS"]);
   $def[$next_def] .= rrd::def($source."_active",           $this->DS[$i+1]["RRDFILE"],   $this->DS[$i+1]["DS"]);
   $def[$next_def] .= rrd::gradient($source, "#ffffff", "#cceeee");
   $def[$next_def] .= rrd::line1($source."_active", "#7777ff", "Active thread count");
   $def[$next_def] .= rrd::gprint($source."_active", "LAST",    "last %3.lf ");
   $def[$next_def] .= rrd::gprint($source."_active", "MAX",     "max %3.lf ");
   $def[$next_def] .= rrd::gprint($source."_active", "AVERAGE", "average %3.2lf\\n");
   $i++;
 } elseif(preg_match("/^app-(.*)/", $label, $tmp)) {
   $source = $tmp[1];
   $label = "Application $source / $servicedesc ($hostname)";
   $ds_name[$next_def] = $label;
   $opt[$next_def] = "--vertical-label \"HTTP Sessions\" -l 0 --title \"$label\" ";
   $def[$next_def]  = rrd::def($source, $this->DS[$i]["RRDFILE"],   $this->DS[$i]["DS"]);
   $def[$next_def] .= rrd::gradient($source, "#ffefcf", "#ff9d00", "HTTP sessions");
   $def[$next_def] .= rrd::line1($source, "#ff7f00");
   $def[$next_def] .= rrd::gprint($source, "LAST",    "last %4.lf ");
   $def[$next_def] .= rrd::gprint($source, "MAX",     "max %4.lf ");
   $def[$next_def] .= rrd::gprint($source, "AVERAGE", "average %4.2lf\\n");
 } elseif(preg_match("/^jdbc-(.*)-capacity$/", $label, $jdbc)) {
   $jdbc_name = $jdbc[1];
   $label = "Datasource $jdbc_name";
   $ds_name[$next_def] = $label;
   $opt[$next_def] = "--vertical-label \"JDBC connection\" -l 0 --title \"".$ds_name[$next_def]."\" ";
   $def[$next_def]  = rrd::def($jdbc_name."_capacity", $this->DS[$i]["RRDFILE"],   $this->DS[$i]["DS"]);
   $def[$next_def] .= rrd::def($jdbc_name."_active",   $this->DS[$i+1]["RRDFILE"], $this->DS[$i+1]["DS"]);
   $def[$next_def] .= rrd::def($jdbc_name."_waiting",  $this->DS[$i+2]["RRDFILE"], $this->DS[$i+2]["DS"]);
   $def[$next_def] .= rrd::cdef("neg_".$jdbc_name."_waiting", "-1,".$jdbc_name."_waiting,*");
   $def[$next_def] .= rrd::gradient($jdbc_name."_capacity", "#ffffff", "#cceeee");
   $def[$next_def] .= rrd::line1($jdbc_name."_capacity", "#ddcccc");
   $def[$next_def] .= rrd::gradient($jdbc_name."_active", "#55eded", "#ccffff", 'Actives   ');
   $def[$next_def] .= rrd::gprint($jdbc_name."_active", "LAST",    "last %.0lf ");
   $def[$next_def] .= rrd::gprint($jdbc_name."_active", "MAX",     "max %.0lf ");
   $def[$next_def] .= rrd::gprint($jdbc_name."_active", "AVERAGE", "avg %.2lf\\n");
   $def[$next_def] .= rrd::line1($jdbc_name."_active", "#000000");
   $def[$next_def] .= rrd::gradient("neg_".$jdbc_name."_waiting", "#eecccc", "#ff9dcc", 'Waiting');
   $def[$next_def] .= rrd::gprint($jdbc_name."_waiting", "LAST",    "last %.0lf ");
   $def[$next_def] .= rrd::gprint($jdbc_name."_waiting", "MAX",     "max %.0lf ");
   $def[$next_def] .= rrd::gprint($jdbc_name."_waiting", "AVERAGE", "avg %.2lf\\n");
   $def[$next_def] .= rrd::line1("neg_".$jdbc_name."_waiting", "#000000");
   $i += 2;
 } elseif(preg_match("/^Throughput$/", $label, $jdbc)) {
   # CPU definition
   $ds_name[$next_def] = $label;
   $opt[$next_def] = "--vertical-label \"\" -l 0 --title \"".$ds_name[$next_def]."\" ";
   $def[$next_def]  = rrd::def("Throughput",      $this->DS[$i]["RRDFILE"], $this->DS[$i]["DS"]);
   $def[$next_def] .= rrd::gradient("Throughput", "FFAD00", "FF1D00");
   $def[$next_def] .= rrd::line1("Throughput", "#FF0000", "Throughput");
   $def[$next_def] .= rrd::gprint("Throughput", "LAST",    "last %2.lf");
   $def[$next_def] .= rrd::gprint("Throughput", "MAX",     "max %2.lf");
   $def[$next_def] .= rrd::gprint("Throughput", "AVERAGE", "average %2.2lf\\n");
 } else {
   $ds_name[$next_def] = $label;
   $opt[$next_def] = "--vertical-label \"\" -l 0 --title \"".$ds_name[$next_def]."\" ";
   $def[$next_def]  = rrd::def($label, $this->DS[$i]["RRDFILE"], $this->DS[$i]["DS"]);
   $def[$next_def] .= rrd::line1($label, "#000000");
 }
 $next_def++;
}
?>
