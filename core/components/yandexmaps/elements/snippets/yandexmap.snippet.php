<?php
/**
 * [[YandexMap? &tv=`coords` &zoom=`15` &width=`100%` &height=`360px` &class=`map` ]]
 */
$tv = $modx->getOption('tv',$scriptProperties,'coords',true);
$zoom = (int)$modx->getOption('zoom',$scriptProperties,14);
$width = $modx->getOption('width',$scriptProperties,'100%');
$height = $modx->getOption('height',$scriptProperties,'400px');
$cls = $modx->getOption('class',$scriptProperties,'ymap');
$apiKey = $modx->getOption('yandexmaps.api_key');

$value = $modx->resource->getTVValue($tv);
if (!$value) return '';
$parts = array_map('trim', explode(',',$value));
if (count($parts) !== 2) return '';
$lat = (float)$parts[0]; $lon = (float)$parts[1];
$mapId = 'ymap-'.$modx->resource->get('id').'-'.$tv.'-'.mt_rand(1000,9999);

$out = [];
$out[] = '<div id="'.$mapId.'" class="'.$cls.'" style="width:'.$width.';height:'.$height.';"></div>';
$out[] = '<script>
(function(){
  var apiKey = '.json_encode($apiKey).';
  function boot(){
    ymaps.ready(function(){
      var map = new ymaps.Map("'.$mapId.'", {center:['.$lat.','.$lon.'], zoom:'.$zoom.'});
      var placemark = new ymaps.Placemark(['.$lat.','.$lon.']);
      map.geoObjects.add(placemark);
    });
  }
  if(window.ymaps){ boot(); }
  else {
    var s = document.createElement("script");
    s.src = "https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey="+encodeURIComponent(apiKey);
    s.onload = boot;
    document.head.appendChild(s);
  }
})();
</script>';
return implode("\n",$out);
