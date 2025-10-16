<?php
$snippet = $modx->newObject('modSnippet');
$snippet->fromArray([
    'name' => 'YandexMap',
    'description' => 'Вывод карты Яндекс по координатам из TV',
    'snippet' => file_get_contents(
        $modx->getOption('base_path').'core/components/yandexmaps/elements/snippets/yandexmap.snippet.php'
    ),
], '', true, true);
return $snippet;
