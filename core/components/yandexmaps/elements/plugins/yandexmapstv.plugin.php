<?php
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($modx->getOption('core_path').'components/yandexmaps/tv/input/');
        break;
    case 'OnTVInputPropertiesList':
        $modx->event->output($modx->getOption('core_path').'components/yandexmaps/tv/properties/');
        break;
}
