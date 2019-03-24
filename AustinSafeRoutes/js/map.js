

// build map
var map;
var myLatLng = {lat: 30.4001631, lng: -97.68025269999998};
function initMap() {
  var options = {
    zoom:10,
    center: myLatLng
  }
  map = new google.maps.Map(document.getElementById('map'), options);

}
var markers = [];
// // // Retrieve our data and plot it
function filterData() {
     //Loop through all the markers and remove
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }

    // construct the query string
    //var url = 'https://data.austintexas.gov/resource/r3af-2r8x.json?traffic_report_status=ACTIVE';
    var url;
    var endPoint = 'https://data.austintexas.gov/resource/r3af-2r8x.json?';
    var incidentStatus = document.getElementById("incidentStatus").value;
    var incidentType = document.getElementById("incidentType").value;
    if (incidentType === "") {
      url = endPoint + "traffic_report_status=" + incidentStatus;
    }
    else {
      url = endPoint + "traffic_report_status=" + incidentStatus + "&" + "issue_reported=" + incidentType;
    }
    $.getJSON(url, function(data) {
      $.each(data, function(i, entry) {
        markers[i] = new google.maps.Marker({
          position: new google.maps.LatLng(entry.latitude,
                                          entry.longitude),
          map: map,
          title: "INCIDENT TYPE: " + entry.issue_reported
                + ' \n' + "ADDRESS: " + entry.address
                + '\n' + "DATE & TIME: " + entry.published_date
                + '\n' + "STATUS: " + entry.traffic_report_status
        });
      });
    });
}