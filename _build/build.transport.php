<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/build.config.php';

$root = dirname(__DIR__) . '/';

$includeCore = getenv('MODX_CORE_PATH') ?: $root . 'core/';
if (!is_dir($includeCore . 'model/modx')) {
    fwrite(STDERR, "MODX core not found at {$includeCore}\n");
    exit(1);
}
require_once $includeCore . 'model/modx/modx.class.php';

$modx = new modX();
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->setOption('use_db', false);

$repoCore = $root . 'core/';
$modx->setOption('core_path',   $repoCore);
$modx->setOption('assets_path', $root . 'assets/');
$modx->setOption('base_path',   $root);

if (!is_dir($repoCore . 'packages')) {
    if (!mkdir($concurrentDirectory = $repoCore . 'packages', 0777, true) && !is_dir($concurrentDirectory)) {
        throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }
}

$modx->loadClass('transport.modPackageBuilder','',false,true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/'.PKG_NAME_LOWER.'/');

$builder->setPackageAttributes([
    'setup-options' => [
        'source' => __DIR__ . '/setup-options.php',
        'properties' => [
            'yandexmaps.api_key' => '',
        ],
    ],
    'changelog' => file_get_contents($modx->getOption('core_path').'components/'.PKG_NAME_LOWER.'/docs/changelog.txt'),
    'readme'    => file_get_contents($modx->getOption('core_path').'components/'.PKG_NAME_LOWER.'/docs/readme.txt'),
    'license'   => 'MIT',
    ]
);

$categoryVehicle = include __DIR__ . '/data/transport.category.php';
include __DIR__ . '/data/transport.settings.php';
include __DIR__ . '/data/transport.plugins.php';
include __DIR__ . '/data/transport.snippets.php';

$categoryVehicle->resolve('php', ['source' => __DIR__ . '/resolvers/resolve.setup.php']);
$categoryVehicle->validate('php', ['source' => __DIR__ . '/validators/validate.php']);

$builder->pack();
$modx->log(modX::LOG_LEVEL_INFO, 'Package built: '.PKG_NAME_LOWER.'-'.PKG_VERSION.'-'.PKG_RELEASE.'.transport.zip');

return true;
