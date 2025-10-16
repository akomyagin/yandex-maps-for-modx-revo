<?php
$plugin = $modx->newObject('modPlugin');
$plugin->fromArray([
    'name' => 'YandexMapTV',
    'description' => 'Регистрирует кастомный TV-тип tvYandexMap',
    'plugincode' => file_get_contents(
        $modx->getOption('base_path').'core/components/yandexmaps/elements/plugins/yandexmapstv.plugin.php'
    ),
], '', true, true);

$events = [];
foreach (['OnTVInputRenderList','OnTVInputPropertiesList'] as $ev) {
    $e = $modx->newObject('modPluginEvent');
    $e->fromArray(['event' => $ev, 'priority' => 0, 'propertyset' => 0], '', true, true);
    $events[] = $e;
}
$plugin->addMany($events);

return $plugin;
