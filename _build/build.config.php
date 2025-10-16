<?php
define('PKG_NAME', 'yandexmaps');
define('PKG_NAME_LOWER', 'yandexmaps');
define('PKG_VERSION', '1.0.1');
define('PKG_RELEASE', 'pl');

$root = dirname(__DIR__) . '/';
define('MODX_CORE_PATH', getenv('MODX_CORE_PATH') ?: $root.'core/');
define('MODX_BASE_PATH', getenv('MODX_BASE_PATH') ?: $root);