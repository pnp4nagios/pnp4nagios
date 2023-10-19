<?php

defined('SYSPATH') or die('No direct access allowed.');
/**
 * @package  Cache:SQLite
 */
$config['schema'] =
'CREATE TABLE caches(
	id VARCHAR(127) PRIMARY KEY,
	tags VARCHAR(255),
	expiration INTEGER,
	cache TEXT);';
