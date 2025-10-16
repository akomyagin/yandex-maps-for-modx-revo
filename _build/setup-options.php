<?php
/** @var modX $modx */
/** @var array $options */

$modx->lexicon->load('setup');

$apiKey = isset($options['yandexmaps.api_key']) ? $options['yandexmaps.api_key'] : '';

$form = '<form method="post">
    <label for="api_key">API Key для Яндекс.Карт:</label><br>
    <input type="text" name="api_key" value="' . $apiKey . '" style="width: 300px;"/><br>
    <input type="submit" value="Сохранить"/>
</form>';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['api_key'])) {
    $apiKey = trim($_POST['api_key']);

    // Сохраняем API-ключ как системную настройку
    $setting = $modx->getObject('modSystemSetting', array('key' => 'yandexmaps.api_key'));
    if (!$setting) {
        $setting = $modx->newObject('modSystemSetting');
        $setting->fromArray(array(
            'key' => 'yandexmaps.api_key',
            'value' => $apiKey,
            'namespace' => 'yandexmaps',
            'xtype' => 'text-field',
            'area' => 'API'
        ));
    }
    $setting->save();

    $form .= '<p>API-ключ успешно сохранен!</p>';
}

return $form;
