<?php
$file = @$_REQUEST['file'];
if (!$file) {
    $file = 'FRAN_IR_041106.xml';
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
    <link rel="stylesheet" href="lib/css/map.css" />
    <script type="text/javascript" src="lib/vendor/neooblaster/xhrquery/releases/v1.4.0/xhrQuery.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
    <script type="text/javascript" src="lib/vendor/uneminuteago/maps/Leaflet_with_GEOJSON/www/lib/js/Map.js"></script>
    <title>Leaflet Map</title>
    <script>
        function loadMap(file) {
            var oldMapElement = document.querySelector('#map');
            var newMapElement = document.createElement('div');
            newMapElement.id = "map";

            oldMapElement.parentNode.replaceChild(newMapElement, oldMapElement);

            new Map()
                .target('map')
                .make()
                .marker().icon('lib/img/icon-red.png', [64,64]).notary()
                .marker().icon('lib/img/icon-blue.png', [32,32]).customer()
                .loadNotary('lib/iface/getGEOJson.php?file=' + file);
        }
    </script>
</head>
<body onload="loadMap('<?=$file;?>')">
    <div id="map"></div>
</body>
</html>