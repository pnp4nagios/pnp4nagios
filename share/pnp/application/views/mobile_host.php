<?php
if ($this->is_authorized == false) {
    ?>
<div class="content">
<div class="alert"><strong>Alert:&nbsp;</strong><?php echo Kohana::lang('error.not_authorized')?></div>
</div>
    <?php
    return;
}
?>

<div class="content">
<ul class="menu">
<?php
foreach ($services as $key => $service) {
    if ($key == 0) {
        printf("<li class=\"divider\">%s</li>\n", htmlspecialchars($service['hostname']));
    }

     printf(
         "<li><a href=\"" . url::base(true) .
             "mobile/graph/%s/%s\"><img src=\"" .
             url::base(true) .
             "image?host=%s&srv=%s&h=80&w=80&view=1\">%s</a></li>",
         urlencode($service['hostname']),
         urlencode($service['name']),
         urlencode($service['hostname']),
         urlencode($service['name']),
         htmlspecialchars($service['servicedesc'])
     );
}
?>
</ul>
</div>
