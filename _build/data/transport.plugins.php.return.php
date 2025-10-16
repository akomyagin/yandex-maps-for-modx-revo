<?php
$plugin = $modx->newObject('modPlugin');

$code = file_get_contents(
    $modx->getOption('base_path').'core/components/yandexmaps/elements/plugins/yandexmapstv.plugin.php'
);
$code = preg_replace('#^\s*<\?php\s#', '', $code);
$code = preg_replace('#\?>\s*$#', '', $code);

$plugin->fromArray([
    'name' => 'YandexMapTV',
    'description' => 'Регистрирует кастомный TV-тип tvyandexmap',
    'plugincode' => $code,
    'static' => 0,
], '', true, true);

$events = [];
foreach (['OnTVInputRenderList','OnTVInputPropertiesList'] as $ev) {
    $e = $modx->newObject('modPluginEvent');
    $e->fromArray(['event' => $ev, 'priority' => 0, 'propertyset' => 0], '', true, true);
    $events[] = $e;
}
$plugin->addMany($events);

return $plugin;
