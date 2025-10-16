<?php
$snippet = $modx->newObject('modSnippet');
$snippet->fromArray([
    'name' => 'YandexMap',
    'description' => 'Вывод карты Яндекс по координатам из TV',
    'snippet' => file_get_contents(MODX_CORE_PATH.'components/yandexmaps/elements/snippets/yandexmap.snippet.php'),
], '', true, true);
return $snippet;
