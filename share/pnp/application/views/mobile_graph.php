<?php
if ($this->is_authorized == false) {
    ?>
<div class="content">
<div class="alert"><strong>Alert:&nbsp;</strong><?php echo Kohana::lang('error.not_authorized'); ?></div>
</div>
    <?php
    return;
}
?>
<div class="content">

<?php
$last_view = -1;
foreach ($this->data->STRUCT as $d) {
    if ($d['VIEW'] > $last_view) {
// a new header begins
        if ($last_view != -1) {
// close last div
            print "</div>\n";
        }
        printf("<div class=\"timerange\">%s</div>\n", $d['TIMERANGE']['title']);
        printf("<div class=\"datasource\">%s\n", $d['ds_name']);
        printf(
            '<div><img style="max-width: 100%%" src="' . url::base(true) . "image?host=%s&srv=%s&view=%s&source=%s\"></div>\n",
            $d['MACRO']['HOSTNAME'],
            $d['MACRO']['SERVICEDESC'],
            $d['VIEW'],
            $d['SOURCE']
        );
        $last_view++;
    } else {
        printf("<div>%s</div>\n", $d['ds_name']);
        printf(
            '<div><img style="max-width: 100%%" src="' . url::base(true) . "image?host=%s&srv=%s&view=%s&source=%s\"></div>\n",
            $d['MACRO']['HOSTNAME'],
            $d['MACRO']['SERVICEDESC'],
            $d['VIEW'],
            $d['SOURCE']
        );
    }//end if
}//end foreach
?>
</div>
</div>
