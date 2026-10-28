<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>PNP4Nagios</title>
<?php echo html::link('media/images/home.png', 'apple-touch-icon', ''); ?>
<?php echo html::stylesheet('media/css/mobile.css'); ?>
<?php echo html::script('media/js/pnp-mobile.js'); ?>
</head>
<body>

<div class="header">
<h1>PNP4Nagios</h1>
<a href="<?php echo url::base(true); ?>mobile" class="home">Home</a>
</div>
<?php
foreach (array('home', 'about', 'overview', 'host', 'graph', 'search', 'query', 'pages', 'special') as $part) {
    if (!empty($$part)) {
        echo $$part;
    }
}
?>
</body>
</html>
