<?php
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $path = $modx->getOption('core_path') . 'components/yandexmaps/tv/input/';
        if (is_dir($path)) {
            $modx->event->output($path);
        }
        break;

    case 'OnTVInputPropertiesList':
        $path = $modx->getOption('core_path') . 'components/yandexmaps/tv/properties/';
        if (is_dir($path)) {
            $modx->event->output($path);
        }
        break;
}
