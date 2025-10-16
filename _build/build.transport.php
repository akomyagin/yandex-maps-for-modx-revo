<?php
require_once __DIR__ . '/build.config.php';

$root = dirname(__DIR__) . '/';

$modxCorePath = getenv('MODX_CORE_PATH');

if (!$modxCorePath && is_dir($root . 'core/model/modx')) {
    $modxCorePath = $root . 'core/';
}

if (!$modxCorePath && is_dir($root . 'vendor/modx/revolution/core/model/modx')) {
    $modxCorePath = $root . 'vendor/modx/revolution/core/';
}

if (!$modxCorePath) {
    fwrite(STDERR, "MODX core not found. Set MODX_CORE_PATH env or add core/ or require modx/revolution via Composer.\n");
    exit(1);
}

$packagesDir = $root . 'core/packages';
if (!is_dir($packagesDir)) {
    if (!mkdir($packagesDir, 0777, true) && !is_dir($packagesDir)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $packagesDir));
    }
}
require_once $modxCorePath . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/');

$builder->setPackageAttributes(array(
    'setup-options' => array(
        'source' => __DIR__ . '/setup-options.php',
        'properties' => array(
            'yandexmaps.api_key' => '',
        ),
    ),
));

$categoryVehicle = include __DIR__ . '/data/transport.category.php';
$settingsVehicles = include __DIR__ . '/data/transport.settings.php';
$pluginVehicle = include __DIR__ . '/data/transport.plugins.php';
$snippetVehicle = include __DIR__ . '/data/transport.snippets.php';

$categoryVehicle->resolve('php', [
    'source' => __DIR__ . '/resolvers/resolve.setup.php'
]);

$categoryVehicle->validate('php', [
    'source' => __DIR__ . '/validators/validate.php'
]);

$builder->setPackageAttributes([
    'changelog' => file_get_contents(MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/docs/changelog.txt'),
    'readme'    => file_get_contents(MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/docs/readme.txt'),
    'license'   => 'MIT',
]);

$builder->pack();
$modx->log(modX::LOG_LEVEL_INFO, 'Package built: ' . PKG_NAME_LOWER . '-' . PKG_VERSION . '-' . PKG_RELEASE . '.transport.zip');
return true;
