<?php
/** @var modX $modx */
/** @var modPackageBuilder $builder */

$category = $modx->newObject('modCategory');
$category->set('category','Yandex Maps');

$attr = [
    xPDOTransport::UNIQUE_KEY   => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
        'Snippets' => [
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ],
        'Plugins' => [
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
            xPDOTransport::RELATED_OBJECTS => true,
            xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
                'PluginEvents' => [
                    xPDOTransport::PRESERVE_KEYS => true,
                    xPDOTransport::UPDATE_OBJECT => false,
                    xPDOTransport::UNIQUE_KEY => ['pluginid','event'],
                ],
            ],
        ],
    ],
];

$snippetObj = include __DIR__.'/transport.snippets.php.return.php';
$pluginObj  = include __DIR__.'/transport.plugins.php.return.php';

$snippets = [$snippetObj];
$plugins  = [$pluginObj];

$category->addMany($snippets, 'Snippets');
$category->addMany($plugins,  'Plugins');

$vehicle = $builder->createVehicle($category, $attr);
$builder->putVehicle($vehicle);

return $vehicle;
