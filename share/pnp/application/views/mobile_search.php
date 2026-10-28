<?php if ($this->isAuthorizedFor('host_search')) { ?>
<!-- Search Box Start -->
<div class="content">
<form action="search" method="post" class="search">
    <input type="search" name="term" id="search" value="" />
    <button type="submit"><?php echo Kohana::lang('common.mobile-submit'); ?></button>
</form>
</div>
<!-- Search Box End -->
<?php } ?>

<div class="content">
<ul class="menu">
<?php
foreach ($this->result as $host) {
    $host = htmlspecialchars($host);
    printf('<li><a href="' . url::base(true) . 'mobile/host/%s">%s</a></li>', $host, $host);
}
?>
</ul>
</div>
