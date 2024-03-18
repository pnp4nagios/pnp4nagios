<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 * Base path of the web site. If this includes a domain, eg: localhost/kohana/
 * then a full URL will be used, eg: http://localhost/kohana/. If it only includes
 * the path, and a site_protocol is specified, the domain will be auto-detected.
 */
$config['site_domain'] = PNP_URL;

/*
 * Force a default protocol to be used by the site. If no site_protocol is
 * specified, then the current protocol is used, or when possible, only an
 * absolute path (with no protocol/domain) is used.
 */
$config['site_protocol'] = '';

/*
 * Name of the front controller for this application. Default: index.php
 *
 * This can be removed by using URL rewriting.
 */
$config['index_page'] = '';

/*
 * Fake file extension that will be added to all generated URLs. Example: .html
 */
$config['url_suffix'] = '';

/*
 * Length of time of the internal cache in seconds. 0 or FALSE means no caching.
 * The internal cache stores file paths and config entries across requests and
 * can give significant speed improvements at the expense of delayed updating.
 */
$config['internal_cache'] = false;

/*
 * rrdtool graph uses pango to do graphics, and pango calls fontconfig to
 * deal with font stuff...but fontconfig needs a cache directory in
 * XDG_CACHE_HOME.  Automatically set up for interactive use, but not for
 * web servers.  It should be a 'cache' directory writable by the webserver
 * So for apache on linux, /var/cache/httpd  is already set up and owned by
 * apache...but for other systems/webservers this may need changed.
 * NOTE that this propagates down to application/models/rrdtool.php where the
 * environment variable is set for rrdtool to use.
 */

$config['fontconfig_cache'] = CACHE_DIR;


/*
 * Enable or disable gzip output compression. This can dramatically decrease
 * server bandwidth usage, at the cost of slightly higher CPU usage. Set to
 * the compression level (1-9) that you want to use, or FALSE to disable.
 *
 * Do not enable this option if you are using output compression in php.ini!
 */
$config['output_compression'] = false;

/*
 * Enable or disable global XSS filtering of GET, POST, and SERVER data. This
 * option also accepts a string to specify a specific XSS filtering tool.
 */
$config['global_xss_filtering'] = true;

/*
 * Enable or disable hooks.
 */
$config['enable_hooks'] = false;

/*
 * Log thresholds:
 *  0 - Disable logging
 *  1 - Errors and exceptions
 *  2 - Warnings
 *  3 - Notices
 *  4 - Debugging
 */
$config['log_threshold'] = 0;

/*
 * Message logging directory.
 */
$config['log_directory'] = PNP_LOG_PATH . '/kohana';

/*
 * Enable or disable displaying of Kohana error pages. This will not affect
 * logging. Turning this off will disable ALL error pages.
 */
$config['display_errors'] = true;

/*
 * Enable or disable statistics in the final output. Stats are replaced via
 * specific strings, such as {execution_time}.
 *
 * @see http://docs.kohanaphp.com/general/configuration
 */
$config['render_stats'] = true;

/*
 * Filename prefixed used to determine extensions. For example, an
 * extension to the Controller class would be named MY_Controller.php.
 */
$config['extension_prefix'] = 'MY_';

/*
 * Additional resource paths, or "modules". Each path can either be absolute
 * or relative to the docroot. Modules can include any resource that can exist
 * in your application directory, configuration files, controllers, views, etc.
 */
$config['modules'] = [
    // MODPATH.'auth',      // Authentication
    // MODPATH.'kodoc',     // Self-generating documentation
    // MODPATH.'gmaps',     // Google Maps integration
    // MODPATH.'archive',   // Archive utility
    // MODPATH.'payment',   // Online payments
    // MODPATH.'unit_test', // Unit testing
];

/*
 * PNP Config
 *
 * pnp_etc_path points to $sysconfdir
 */
$config['pnp_etc_path'] = PNP_ETC_PATH;
/*
 * Default Theme
 */
$config['theme'] = 'smoothness';
/*
 * Available Doc Languages
 */
$config['doc_language'] = [
    'en_US',
    'de_DE',
];
/*
 * Default template dirs
 */
$config['template_dirs'] = [
    DOCROOT . '/templates',
    DOCROOT . '/templates.dist',
];
/*
 * Default graph dimensions
 */
$config['graph_width']              = '500';
$config['graph_height']             = '100';
$config['zgraph_width']             = '500';
$config['zgraph_height']            = '100';
$config['pdf_width']                = '675';
$config['pdf_height']               = '100';
$config['right_zoom_offset']        = '30';
$config['mobile_devices']           = 'iPhone';
$config['pdf_page_size']            = 'A4';
$config['pdf_margin_left']          = '17.5';
$config['pdf_margin_right']         = '10';
$config['pdf_margin_top']           = '30';
$config['auth_multisite_enabled']   = '0';
$config['auth_multisite_htpasswd']  = '';
$config['auth_multisite_serials']   = '';
$config['auth_multisite_secret']    = '';
$config['auth_multisite_login_url'] = '/check_mk/login.py';
