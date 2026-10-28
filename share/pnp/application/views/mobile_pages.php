<div class="content">
<input type="search" class="filter" placeholder="Filter">
<ul class="menu">
<?php
foreach ($pages as $page) {
    $this->data->getPageDetails($page);
    printf('<li><a href="' . url::base(true) . 'mobile/pages/%s">%s</a></li>', htmlspecialchars($page), htmlspecialchars($this->data->PAGE_DEF['page_name']));
}
?>
</ul>
</div>
