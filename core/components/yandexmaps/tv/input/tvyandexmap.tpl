<div id="ymap-tv-[[+tvId]]" style="width:100%;height:360px;border:1px solid #e3e3e3;border-radius:6px;"></div>
<input type="text" id="tv[[+tvId]]" name="tv[[+tvId]]" value="[[+value]]" style="margin-top:8px;width:100%;" placeholder="lat,lon">
<script>
    (function(){
        var apiKey = '[[+apiKey]]';
        var initial = '[[+value]]'.trim();
        var center = '[[+defaultCenter]]'.trim();
        var zoom = parseInt('[[+defaultZoom]]',10) || 12;
        function parseCoords(str){
            var p = (str||'').split(',');
            if(p.length===2){
                var lat = parseFloat(p[0]), lon = parseFloat(p[1]);
                if(!isNaN(lat) && !isNaN(lon)) return [lat, lon];
            }
            return null;
        }
        var initialCoords = parseCoords(initial) || parseCoords(center) || [55.751244,37.618423];
        function loadYMAPS(cb){
            if(window.ymaps){ cb(); return; }
            var s = document.createElement('script');
            s.src = 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=' + encodeURIComponent(apiKey);
            s.onload = cb;
            document.head.appendChild(s);
        }
        loadYMAPS(function(){
            ymaps.ready(function(){
                var map = new ymaps.Map('ymap-tv-[[+tvId]]', {center: initialCoords, zoom: zoom});
                var placemark = new ymaps.Placemark(initialCoords, {}, { draggable: true });
                map.geoObjects.add(placemark);
                var input = document.getElementById('tv[[+tvId]]');
                function write(coords){ input.value = coords[0].toFixed(6) + ',' + coords[1].toFixed(6); }
                if(initialCoords) write(initialCoords);
                map.events.add('click', function(e){
                    var c = e.get('coords');
                    placemark.geometry.setCoordinates(c); write(c);
                });
                placemark.events.add('dragend', function(){
                    var c = placemark.geometry.getCoordinates(); write(c);
                });
                input.addEventListener('change', function(){
                    var c = parseCoords(input.value);
                    if(c){ placemark.geometry.setCoordinates(c); map.setCenter(c); }
                });
            });
        });
    })();
</script>