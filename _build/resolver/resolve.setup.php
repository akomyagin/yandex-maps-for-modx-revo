<?php
/** @var modX $modx */
/** @var array $options */
$action = $options[xPDOTransport::PACKAGE_ACTION] ?? null;

if ($action === xPDOTransport::ACTION_INSTALL || $action === xPDOTransport::ACTION_UPGRADE) {
    $toSet = [
        'yandexmaps.api_key'        => trim((string)($options['api_key'] ?? '')),
        'yandexmaps.default_center' => trim((string)($options['default_center'] ?? '')),
        'yandexmaps.default_zoom'   => trim((string)($options['default_zoom'] ?? '')),
    ];

    foreach ($toSet as $key => $val) {
        if ($val === '') continue;
        $setting = $modx->getObject('modSystemSetting', ['key' => $key]);
        if (!$setting) {
            $setting = $modx->newObject('modSystemSetting');
            $setting->set('key', $key);
            $setting->set('namespace', 'yandexmaps');
            $xtype = ($key === 'yandexmaps.default_zoom') ? 'numberfield' : 'textfield';
            $setting->set('xtype', $xtype);
        }
        $setting->set('value', $val);
        $setting->save();
    }

    $modx->cacheManager->refresh(['system_settings' => []]);
}

return true;
