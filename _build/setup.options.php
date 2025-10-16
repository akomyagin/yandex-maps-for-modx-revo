<?php
// _build/setup.options.php
/** @var modX $modx */
$apiDefault = (string)$modx->getOption('yandexmaps.api_key', null, '');
$centerDefault = (string)$modx->getOption('yandexmaps.default_center', null, '55.751244,37.618423');
$zoomDefault = (string)$modx->getOption('yandexmaps.default_zoom', null, '12');

return '
  <div>
    <label>Yandex Maps API Key</label><br>
    <input type="text" name="api_key" value="'.htmlspecialchars($apiDefault, ENT_QUOTES).'" style="width:360px;"><br><br>

    <label>Default center (lat,lon)</label><br>
    <input type="text" name="default_center" value="'.htmlspecialchars($centerDefault, ENT_QUOTES).'" style="width:360px;"><br><br>

    <label>Default zoom</label><br>
    <input type="number" name="default_zoom" value="'.htmlspecialchars($zoomDefault, ENT_QUOTES).'" style="width:120px;">
  </div>
';