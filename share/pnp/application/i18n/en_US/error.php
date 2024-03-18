<?php

defined('SYSPATH') or die('No direct access allowed.');
$lang = [
    'rrdtool-not-found'                 => 'RRDTool not found in %s.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/1.txt">Read FAQ online</a>',
    'config-not-found'                  => 'Config file %s not found.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/2.txt">Read FAQ online</a>',
    'perfdata-dir-empty'                => 'perfdata directory "%s" is empty.' .
        ' Please check your Nagios config. <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/3.txt">Read FAQ online</a>',
    'host-perfdata-dir-empty'           => 'perfdata directory "%s" is empty.' .
        ' Please check your Nagios config. <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/4.txt">Read FAQ online</a>',
    'perfdata-dir-for-host'             => 'perfdata directory "%s" for host "%s" does not exist.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/5.txt">Read FAQ online</a>',
    'xml-not-found'                     => 'XML file "%s" not found.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/6.txt">Read FAQ online</a>',
    'get-first-service'                 => 'Can´t find first service for host "%s".' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/7.txt">Read FAQ online</a>',
    'get-first-host'                    => 'Can´t find any Host.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/8.txt">Read FAQ online</a>',
    'xml-structure-mismatch'            => 'XML structure mismatch.' .
        ' Found version "%d" but should be "%d". <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/9.txt">Read FAQ online</a>',
    'save-rrd-image'                    => 'php fopen("%s") failed.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/10.txt">Read FAQ online</a>',
    'xml-structure-without-version-tag' => 'XML structure mismatch.' .
        ' Version tag not found in "%s". <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/11.txt">Read FAQ online</a>',
    'template-without-opt'              => 'Template %s does not provide array $opt[].' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/12.txt">Read FAQ online</a>',
    'template-without-def'              => 'Template %s does not provide array $def[].' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/13.txt">Read FAQ online</a>',
    'no-data-for-page'                  => 'Sorry, but we can´t find any data using config file "%s",' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/14.txt">Read FAQ online</a>',
    'page-not-readable'                 => 'Config file "%s" is not readable or does not exist.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/15.txt">Read FAQ online</a>',
    'auth-pages'                        => 'You are not authorized to view "pages"' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/16.txt">Read FAQ online</a>',
    'page-config-dir'                   => 'No page config file found in "%s"' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/17.txt">Read FAQ online</a>',
    'xport-host-service'                => 'Xport controller needs "host" and "srv" URL parameters.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/18.txt">Read FAQ online</a>',
    'mod-rewrite'                       => 'Apache Rewrite Module is not enabled.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/19.txt">Read FAQ online</a>',
    'tpl-no-services-found'             => 'No services could be found "%s".' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/20.txt">Read FAQ online</a>',
    'tpl-no-hosts-found'                => 'No hosts could be found "%s".' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/21.txt">Read FAQ online</a>',
    'no-templates-found'                => 'No templates could be found.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/22.txt">Read FAQ online</a>',
    'not_authorized'                    => 'You are not authorized to view this host/service',
    'remote_user_missing'               => 'Remote user is missing. Authentication check canceled.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/23.txt">Read FAQ online</a>',
    'livestatus_socket_error'           => 'Livestatus Socket error: %s (%s)' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/24.txt">Read FAQ online</a>',
    'not_authorized_for_host_overview'  => 'You are not authorized to access this host overview page.',
    'xml-generic_error'                 => 'XML file "%s" not parsable.<p><strong>XML Errors:</strong>%s</p>',
    'gd-missing'                        => 'PHP GD functions are missing. More on <a href="http://www.php.net/manual/en/book.image.php">www.php.net</a>',
];
