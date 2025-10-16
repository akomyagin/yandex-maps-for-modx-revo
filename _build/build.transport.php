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

$modx->getService('lexicon', 'modLexicon', '', ['use_db' => false]);

$modx->setOption('core_path',   $includeCore);
$modx->setOption('assets_path', $root . 'assets/');
$modx->setOption('base_path',   $root);

$repoPackages = $root . 'core/packages/';
if (!is_dir($repoPackages) && !mkdir($repoPackages, 0777, true) && !is_dir($repoPackages)) {
    throw new RuntimeException('Cannot create ' . $repoPackages);
}

$modx->loadClass('transport.modPackageBuilder','',false,true);
$builder = new modPackageBuilder($modx);

$builder->directory = $repoPackages;

$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/'.PKG_NAME_LOWER.'/');

$builder->setPackageAttributes([
    'setup-options' => ['source' => __DIR__ . '/setup.options.php'],
    'changelog' => file_get_contents($root.'core/components/'.PKG_NAME_LOWER.'/docs/changelog.txt'),
    'readme'    => file_get_contents($root.'core/components/'.PKG_NAME_LOWER.'/docs/readme.txt'),
    'license'   => 'MIT',
]);

$categoryVehicle = include __DIR__ . '/data/transport.category.php';

include __DIR__ . '/data/transport.settings.php';

$categoryVehicle->resolve('php', ['source' => __DIR__ . '/resolvers/resolve.setup.php']);
$categoryVehicle->validate('php', ['source' => __DIR__ . '/validators/validate.php']);

$builder->pack();
$modx->log(modX::LOG_LEVEL_INFO, 'Package built: '.PKG_NAME_LOWER.'-'.PKG_VERSION.'-'.PKG_RELEASE.'.transport.zip');

return true;
