<?php if ($this->config->conf['use_calendar']) {
    // datetime-local needs Y-m-d\TH:i; session values may be timestamps or free text
    $fmt = function ($value) {
        if ($value === '' || $value === false) {
            return '';
        }
        $ts = is_numeric($value) ? (int)$value : strtotime($value);
        return $ts ? date('Y-m-d\TH:i', $ts) : '';
    };
    $start = $fmt($this->session->get('start', ''));
    $end   = $fmt($this->session->get('end', ''));
    ?>
<script type="text/javascript">
jQuery(function() {
    jQuery("#button").click(function() {
        jQuery("#toggle-timerange").toggle("blind",500);
        return false;
    });
});
</script>

<div id="toggle-timerange" class="ui-widget" style="Display: none;">
    <div class="p4 ui-widget-header ui-corner-top"><?php echo Kohana::lang('common.timerange-selector-title'); ?></div>
    <div class="p4 ui-widget-content ui-corner-bottom">
    <form method="GET" action="<?php echo url::base(true) . Router::$current_uri; ?>">
        <fieldset>
            <legend><?php echo Kohana::lang('common.timerange-selector-legend'); ?></legend>
            <?php
            foreach ($_GET as $key => $val) {
                if ($key == 'start') {
                    continue;
                }
                if ($key == 'end') {
                    continue;
                }
                echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($val) . "\">\n";
            }
            ?>
            <label for="dpstart"><?php echo Kohana::lang('common.start'); ?>: </label>
<input id="dpstart" type="datetime-local" name="start" value="<?php echo htmlspecialchars($start); ?>">
            <label for="dpend"><?php echo Kohana::lang('common.end'); ?>: </label>
     <input id="dpend" type="datetime-local" name="end" value="<?php echo htmlspecialchars($end); ?>">
            <input type="submit" id="submit" class="ui-button ui-state-default ui-corner-all"
     value="<?php echo Kohana::lang('common.timerange-selector-submit-button'); ?>"></input>
            <input type="button" id="remove_timerange_session" class="ui-button ui-state-default ui-corner-all"
     value="<?php echo Kohana::lang('common.timerange-selector-clear-button'); ?>"></input>
        </fieldset>
    </form><p>
    </div>
</div>
    <?php
}//end if
