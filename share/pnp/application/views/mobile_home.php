<div class="content">
<ul class="menu">
<li><a href="<?php echo url::base(true)?>mobile/overview"><?php echo Kohana::lang('common.mobile-all-hosts')?></a></li>
<li><a href="<?php echo url::base(true)?>mobile/search"><?php echo Kohana::lang('common.mobile-search-hosts')?></a></li>
<?php
if ($this->data->getFirstPage() && $this->isAuthorizedFor('pages')) {
    echo "<li><a href=\"" . url::base(true) . "mobile/pages\">" . Kohana::lang('common.mobile-pages') . "</a></li>";
}

if ($this->data->getFirstSpecialTemplate()) {
    echo "<li><a href=\"" . url::base(true) . "mobile/special\">" . Kohana::lang('common.mobile-special-templates') . "</a></li>";
}
?>
<li><a href="<?php echo url::base(true)?>mobile/graph/.pnp-internal/runtime"><?php echo Kohana::lang('common.mobile-statistics')?></a></li>
<li><a href="<?php echo url::base(true)?>mobile/go/classic"><?php echo Kohana::lang('common.mobile-go-classic')?></a></li>
</ul>
</div>
