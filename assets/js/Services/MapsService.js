export default (() => {
    let Public = {},
        Private = {};

    Public.renderMapWithPath = tripId => {
        $.get(`/api/get-trip-data/${tripId}`)
            .then(function (response) {
                let path = response.route;
                let map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: path[1],
                    mapTypeId: 'terrain'
                });

                let elevator = new google.maps.ElevationService;

                Private.displayPath(path, elevator, map);

                google.maps.event.trigger(map, "resize");
                map.setCenter(new google.maps.LatLng(path[1].lat, path[1].lng) );
            });
    };

    Private.displayPath = (path, elevator, map) => {
        new google.maps.Polyline({
            path: path,
            strokeColor: '#0000CC',
            strokeOpacity: 0.4,
            map: map
        });
    }

    return Public;
})