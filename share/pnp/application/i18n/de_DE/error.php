<?php

defined('SYSPATH') or die('kein direkter Zugang erlaubt.');
$lang = [
    'rrdtool-not-found'                 => '"rrdtool"-Binary nicht in %s gefunden.' .
    ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/1.txt">FAQ online lesen</a>',
    'config-not-found'                  => 'Config-Datei %s nicht gefunden. <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/2.txt">' .
        'FAQ online lesen</a>',
    'perfdata-dir-empty'                => 'Das perfdata-Verzeichnis "%s" ist leer.' .
        ' Bitte die Nagios-Konfiguration prüfen. <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/3.txt">FAQ online lesen</a>',
    'host-perfdata-dir-empty'           => 'Das perfdata-Verzeichnis "%s" ist leer.' .
        ' Bitte die Nagios-Konfiguration prüfen. <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/4.txt">FAQ online lesen</a>',
    'perfdata-dir-for-host'             => 'Das perfdata-Verzeichnis "%s" für Host "%s" existiert nicht.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/5.txt">FAQ online lesen</a>',
    'xml-not-found'                     => 'XML-Datei "%s" nicht gefunden. ' .
        '<a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/6.txt">FAQ online lesen</a>',
    'get-first-service'                 => 'Konnte ersten Service für Host "%s" nicht finden.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/7.txt">FAQ online lesen</a>',
    'get-first-host'                    => 'Keinen Host gefunden. <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/8.txt">FAQ online lesen</a>',
    'xml-structure-mismatch'            => 'Version der XML-Struktur ungültig. Fand Version "%d",' .
        ' sollte aber "%d" sein. <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/9.txt">FAQ online lesen</a>',
    'save-rrd-image'                    => 'Speichern des Graphen gescheitert. php fopen("%s") failed.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/10.txt">FAQ online lesen</a>',
    'xml-structure-without-version-tag' => 'Versionshinweis fehlt im XML-File.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/11.txt">FAQ online lesen</a>',
    'template-without-opt'              => 'Template %s übergibt Array $opt[] nicht.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/12.txt">FAQ online lesen</a>',
    'template-without-def'              => 'Template %s übergibt Array $def[] nicht.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/13.txt">FAQ online lesen</a>',
    'no-data-for-page'                  => 'Keine Daten für die Page "%s",' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/14.txt">FAQ online lesen</a>',
    'page-not-readable'                 => 'Konfigurationsdatei "%s" ist nicht lesbar' .
        ' oder existiert nicht. <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/15.txt">FAQ online lesen</a>',
    'auth-pages'                        => 'Sie sind nicht berechtigt, "Pages" anzusehen' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/16.txt">FAQ online lesen</a>',
    'page-config-dir'                   => 'Keine page-Konfigurationsdatei in "%s" gefunden' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/17.txt">FAQ online lesen</a>',
    'xport-host-service'                => 'Xport-Controller benötigt "host"- und "srv"-URL-Parameter.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/18.txt">FAQ online lesen</a>',
    'mod-rewrite'                       => 'Apache Rewrite Module ist nicht aktiviert.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/19.txt">Read FAQ online</a>',
    'tpl-no-services-found'             => 'Es wurden keine Services gefunden "%s".' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/20.txt">Read FAQ online</a>',
    'tpl-no-hosts-found'                => 'Es wurden keine Hosts gefunden "%s".' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/21.txt">Read FAQ online</a>',
    'no-templates-found'                => 'Es wurde kein passendes Template gefunden.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/22.txt">Read FAQ online</a>',
    'not_authorized'                    => 'You are not authorized to view this host/service',
    'remote_user_missing'               => 'Remote user is missing. Authentication check cancled.' .
        ' <a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/23.txt">Read FAQ online</a>',
    'livestatus_socket_error'           => 'Livestatus Socket error: %s (%s) ' .
        '<a href="https://raw.githubusercontent.com/pnp4nagios/docs/main/pages/faq/24.txt">Read FAQ online</a>',
    'not_authorized_for_host_overview'  => 'You are not authorized to access this host overview page.',
    'xml-generic_error'                 => 'XML file "%s" not parsable.<p><strong>XML Errors:</strong>%s</p>',
    'gd-missing'                        => 'PHP GD functions are missing. More on' .
        ' <a href="http://www.php.net/manual/en/book.image.php">www.php.net</a>',
];
