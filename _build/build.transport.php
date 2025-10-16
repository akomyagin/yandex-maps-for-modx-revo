<?php
require_once __DIR__ . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/');

// Установим setup-options
$builder->setPackageAttributes(array(
    'setup-options' => array(
        'source' => __DIR__ . '/setup-options.php',
        'properties' => array(
            'yandexmaps.api_key' => '',
        ),
    ),
));

// Добавляем остальные объекты
$categoryVehicle = include __DIR__ . '/data/transport.category.php';
$settingsVehicles = include __DIR__ . '/data/transport.settings.php';
$pluginVehicle = include __DIR__ . '/data/transport.plugins.php';
$snippetVehicle = include __DIR__ . '/data/transport.snippets.php';

// Упаковываем файлы и документы
$categoryVehicle->resolve('php', [
    'source' => __DIR__ . '/resolvers/resolve.setup.php'
]);

$categoryVehicle->validate('php', [
    'source' => __DIR__ . '/validators/validate.php'
]);

// Добавляем документацию
$builder->setPackageAttributes([
    'changelog' => file_get_contents(MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/docs/changelog.txt'),
    'readme'    => file_get_contents(MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/docs/readme.txt'),
    'license'   => 'MIT',
]);

$builder->pack();
$modx->log(modX::LOG_LEVEL_INFO, 'Package built: ' . PKG_NAME_LOWER . '-' . PKG_VERSION . '-' . PKG_RELEASE . '.transport.zip');
return true;
