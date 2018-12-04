function initMap() {
    var directionsService = new google.maps.DirectionsService();
    var directionsDisplay = new google.maps.DirectionsRenderer();
    var map;
    var myLatLng = {lat: 30.4001631, lng: -97.68025269999998};
    var options = {
        zoom:10,
        center: myLatLng
    }
    map = new google.maps.Map(document.getElementById('map'), options);

}
  
function calcRoute() {
    // var start = document.getElementById('start').value;
    // var end = document.getElementById('end').value;
    // var request = {
    //   origin: start,
    //   destination: end,
    //   travelMode: 'DRIVING'
    // };
    // directionsService.route(request, function(result, status) {
    //   if (status == 'OK') {
    //     directionsDisplay.setDirections(result);
    //   }
    // });
    if (timerHandle) { clearTimeout(timerHandle); }
    if (marker) { marker.setMap(null);}
    polyline.setMap(null);
    poly2.setMap(null);
    directionsDisplay.setMap(null);
    polyline = new google.maps.Polyline({
        path: [],
        strokeColor: '#FF0000',
        strokeWeight: 3
    });
    poly2 = new google.maps.Polyline({
        path: [],
        strokeColor: '#FF0000',
        strokeWeight: 3
    });
    // Create a renderer for directions and bind it to the map.
    var rendererOptions = {
        map: map
    }
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);


    // get 'start' and 'end' locations from form
    var start = document.getElementById("start").value;
    var end = document.getElementById("end").value;
    var travelMode = google.maps.DirectionsTravelMode.DRIVING

    var request = {
        origin: {lat: 30.274666, lng: -97.740349},

        destination: end,
        waypoints: [
            {
                location: start,
                stopover: true
            }],
        travelMode: travelMode
    };

    // Route the directions and pass the response to a
    // function to create markers for each step.
    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK){
            directionsDisplay.setDirections(response);

            var bounds = new google.maps.LatLngBounds();
            var route = response.routes[0];
            startLocation = new Object();
            endLocation = new Object();

            // For each route, display summary information.
            var path = response.routes[0].overview_path;
            var legs = response.routes[0].legs;
            for (i=0;i<legs.length;i++) {
                if (i == 0) {
                    startLocation.latlng = legs[i].start_location;
                    startLocation.address = legs[i].start_address;
                    // marker = google.maps.Marker({map:map,position: startLocation.latlng});
                    marker = createMarker(legs[i].start_location,"start",legs[i].start_address,"green");
                }
                endLocation.latlng = legs[i].end_location;
                endLocation.address = legs[i].end_address;
                var steps = legs[i].steps;
                for (j=0;j<steps.length;j++) {
                    var nextSegment = steps[j].path;
                    for (k=0;k<nextSegment.length;k++) {
                        polyline.getPath().push(nextSegment[k]);
                        bounds.extend(nextSegment[k]);
                    }
                }
            }

            polyline.setMap(map);
            map.fitBounds(bounds);
            // createMarker(endLocation.latlng,"end",endLocation.address,"red");
            map.setZoom(18);
        }
    });
}
    
  