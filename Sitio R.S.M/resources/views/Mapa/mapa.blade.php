<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <style>
        .map {
            height: 400px;
            width: 100%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <title>OpenLayers example</title>
</head>
<body>
<div id="map" class="map" style="width: 100%; height: 98vh"></div>
<script type="text/javascript">
    var map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([-54.93, -34.92]),
            zoom: 13
        })
    });
    var layer = new ol.layer.Vector({
        source: new ol.source.Vector({
            features: [
                new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.fromLonLat([-54.95, -34.9]))
                }),
                new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.fromLonLat([-56.21417, -34.09556]))
                }),
                new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.fromLonLat([-56.46667, -30.4]))
                }),
                new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.fromLonLat([-55.98111, -31.71694]))
                }),
                new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.fromLonLat([-57.96667, -31.38333]))
                })

            ]
        })
    });
    map.addLayer(layer);
</script>
</body>
</html>
