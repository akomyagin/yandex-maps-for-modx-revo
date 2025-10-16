<?php
$snippet = $modx->newObject('modSnippet');

$code = file_get_contents(
    $modx->getOption('base_path').'core/components/yandexmaps/elements/snippets/yandexmap.snippet.php'
);
$code = preg_replace('#^\s*<\?php\s#', '', $code);
$code = preg_replace('#\?>\s*$#', '', $code);

$snippet->fromArray([
    'name' => 'YandexMap',
    'description' => 'Вывод карты Яндекс по координатам из TV',
    'snippet' => $code,
], '', true, true);

return $snippet;