<?php
class tvYandexMapInputRender extends modTemplateVarInputRender {
    public function getTemplate() {
        return 'core/components/yandexmaps/tv/input/tvyandexmap.tpl';
    }
    public function process($value,array $params=array()) {
        $this->setPlaceholder('value', $value);
        $this->setPlaceholder('apiKey', $this->modx->getOption('yandexmaps.api_key'));
        $this->setPlaceholder('defaultCenter', $this->modx->getOption('yandexmaps.default_center', null, '55.751244,37.618423'));
        $this->setPlaceholder('defaultZoom', (int)$this->modx->getOption('yandexmaps.default_zoom', null, 12));
        $this->setPlaceholder('tvId', $this->tv->id);
    }
}
return 'tvYandexMapInputRender';
