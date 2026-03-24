<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>#map { height: 600px; }</style>
</head>
<body>
    <div id="map"></div>

    <script>
        // Inisialisasi peta fokus ke Iran
        var map = L.map('map').setView([32.4279, 53.6880], 5);

        // Tambahkan layer peta OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Ambil data dari API kita
        fetch('/api/map-events')
            .then(res => res.json())
            .then(data => {
                L.geoJSON(data, {
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup(feature.properties.description);
                    }
                }).addTo(map);
            });
    </script>
</body>
</html>