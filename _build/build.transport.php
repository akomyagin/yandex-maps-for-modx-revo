<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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

$repoCore = $root . 'core/';
$modx->setOption('core_path',   $repoCore);
$modx->setOption('assets_path', $root . 'assets/');
$modx->setOption('base_path',   $root);

if (!is_dir($repoCore . 'packages')) {
    if (!mkdir($concurrentDirectory = $repoCore . 'packages', 0777, true) && !is_dir($concurrentDirectory)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }
}

$modx->getService('lexicon','modLexicon');
$modx->lexicon->load('core:default');

$modx->loadClass('transport.modPackageBuilder','',false,true);
$builder = new modPackageBuilder($modx);
$builder->createPackage('yandexmaps','1.0.0','pl');
$builder->registerNamespace('yandexmaps', false, true, '{core_path}components/yandexmaps/');

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
