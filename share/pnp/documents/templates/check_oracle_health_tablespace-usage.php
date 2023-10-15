<?php
#
# Colors are simply doubled, because some DB have ridiculous count of performancedata. It should be o.k. in 
# the graphs though
$colors=array("CC3300","CC3333","CC3366","CC3399","CC33CC","CC33FF","336600","336633","336666","336699","3366CC","3366FF","33CC33","33CC66","609978","922A99","997D6D","174099","1E9920","E88854","AFC5E8","57FA44","FA6FF6","008080","D77038","272B26","70E0D9","0A19EB","E5E29D","930526","26FF4A","ABC2FF","E2A3FF","808000","000000","00FAFA","E5FA79","F8A6FF","FF36CA","B8FFE7","CD36FF",
"CC3300","CC3333","CC3366","CC3399","CC33CC","CC33FF","336600","336633","336666","336699","3366CC","3366FF","33CC33","33CC66","609978","922A99","997D6D","174099","1E9920","E88854","AFC5E8","57FA44","FA6FF6","008080","D77038","272B26","70E0D9","0A19EB","E5E29D","930526","26FF4A","ABC2FF","E2A3FF","808000","000000","00FAFA","E5FA79","F8A6FF"
);

foreach($DS as $i => $VAL){
# Graph for tablespace percentage
    if(preg_match('/^tbs_.*_usage_pct$/',$NAME[$i], $matches)){
        $ds_name[1] = "TBS usage %";
        $short_name = $matches[0];
        $short_name = substr($short_name, 4,-10);
        $opt[1] = "--vertical-label \"TBS usage %\" -u102 -X0 -l0 --title \"Tablespace usage percent $servicedesc\" ";
        if(!isset($def[1])){
            $def[1] = "";
        }
        $def[1] .= "DEF:var$i=$RRDFILE[$i]:$DS[$i]:AVERAGE " ;
        $def[1] .= "LINE:var$i#".$colors[$i].":\"$short_name\" " ;
        $def[1] .= "GPRINT:var$i:LAST:\"%6.0lf PERC LAST \" ";
        $def[1] .= "GPRINT:var$i:MAX:\"%6.0lf PERC MAX \" ";
        $def[1] .= "GPRINT:var$i:AVERAGE:\"%6.2lf PERC AVERAGE \\n\" ";
    }

# Graph for tablespace size
    if(preg_match('/^tbs_.*_usage$/',$NAME[$i], $matches)){
        $ds_name[2] = "TBS usage MB/GB";
        $short_name = $matches[0];
        $short_name = substr($short_name, 4,-6);
        $opt[2] = " -X 0 --vertical-label \"TBS usage MB/GB\" --title \"Tablespace usage MB/GB $servicedesc\" ";
        if(!isset($def[2])){
            $def[2] = "";
        }
        $def[2] .= "DEF:var$i=$RRDFILE[$i]:$DS[$i]:AVERAGE " ;
        $def[2] .= "LINE:var$i#".$colors[$i].":\"$short_name\" " ;
        $def[2] .= "GPRINT:var$i:LAST:\"%6.0lf $UNIT[$i] LAST \" ";
        $def[2] .= "GPRINT:var$i:MAX:\"%6.0lf  $UNIT[$i] MAX \" ";
        $def[2] .= "GPRINT:var$i:AVERAGE:\"%6.2lf  $UNIT[$i] AVERAGE \\n\" ";
    }
}

?>

