<div class="content">
<input type="search" class="filter" placeholder="Filter">
<ul class="menu">
<?php
$l = '';
foreach ($hosts as $host) {
    if (substr($host['name'], 0, 1) != $l) {
        printf("<li class=\"divider\">%s</li>\n", htmlspecialchars(strtoupper(substr($host['name'], 0, 1))));
    }
    $name = htmlspecialchars($host['name']);
    printf("<li><a href=\"" . url::base(true) . "mobile/host/%s\">%s</a></li>", $name, $name);
    $l = substr($host['name'], 0, 1);
}
?>
</ul>
</div>
