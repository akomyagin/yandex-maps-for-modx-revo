<?php
/** @var modX $modx */
/** @var array $options */
$modx->log(modX::LOG_LEVEL_INFO, 'Running resolver: setup');

$coreSrc   = realpath($modx->getOption('source_core', $options, $modx->getOption('core_path').'components/yandexmaps/'));
$assetsSrc = realpath($modx->getOption('source_assets', $options, $modx->getOption('assets_path').'components/yandexmaps/'));

$coreDst   = $modx->getOption('core_path').'components/yandexmaps/';
$assetsDst = $modx->getOption('assets_path').'components/yandexmaps/';

$transport = $options[xPDOTransport::PACKAGE_ACTION] ?? null;

// Копируем core/ и assets/ из дистрибутива пакета (если не скопированы)
if ($transport == xPDOTransport::ACTION_INSTALL || $transport == xPDOTransport::ACTION_UPGRADE) {
    $modx->log(modX::LOG_LEVEL_INFO, 'Copying component files...');
    $modx->getService('file','modFileHandler');

    $modx->file->copyTree($coreSrc, $coreDst, ['deleteTop' => false]);
    if (is_dir($assetsSrc)) {
        $modx->file->copyTree($assetsSrc, $assetsDst, ['deleteTop' => false]);
    }

    // Регистрируем namespace
    if (!$modx->getObject('modNamespace', ['name' => 'yandexmaps'])) {
        $ns = $modx->newObject('modNamespace');
        $ns->fromArray([
            'name' => 'yandexmaps',
            'path' => $coreDst,
            'assets_path' => $assetsDst,
        ], '', true, true);
        $ns->save();
    }
}
$modx->log(modX::LOG_LEVEL_INFO, 'Resolver complete.');
return true;
