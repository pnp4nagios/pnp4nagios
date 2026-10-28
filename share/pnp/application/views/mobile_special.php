<div class="content">
<input type="search" class="filter" placeholder="Filter">
<ul class="menu">
<?php
foreach ($templates as $template) {
    $template = htmlspecialchars($template);
    printf('<li><a href="' . url::base(true) . 'mobile/special/%s">%s</a></li>', $template, $template);
}
?>
</ul>
</div>
