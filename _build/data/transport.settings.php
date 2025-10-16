<?php
$settings = [
    [
        'key' => 'yandexmaps.api_key',
        'value' => '',
        'xtype' => 'textfield',
        'namespace' => 'yandexmaps',
        'area' => 'API',
    ],
    [
        'key' => 'yandexmaps.default_center',
        'value' => '55.751244,37.618423',
        'xtype' => 'textfield',
        'namespace' => 'yandexmaps',
        'area' => 'Map',
    ],
    [
        'key' => 'yandexmaps.default_zoom',
        'value' => '12',
        'xtype' => 'numberfield',
        'namespace' => 'yandexmaps',
        'area' => 'Map',
    ],
];

foreach ($settings as $s) {
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge([
        'editedon' => null,
    ], $s), '', true, true);

    $vehicle = $builder->createVehicle(
        $setting,
        [
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'key',
        ]
    );
    $builder->putVehicle($vehicle);
}
return true;
