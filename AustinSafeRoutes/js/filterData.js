function filterData() {
  const href = {
    host: 'data.austintexas.gov',
    pathname: '/resource/r3af-2r8x.json',
    getIncident: function(domId = '', domVal = '', domEl) {
      domEl = domId.length ? document.getElementById('incident' + domId) : {};
      if (domEl && domEl.value && domEl.value.length) {
        domVal = domEl.value;
      }

      return domVal;
    },
    get: function(basename, status, type) {
      basename = `${this.host}` + this.pathname;
      status = `traffic_report_status=${this.getIncident('Status')}`;
      type = this.getIncident('Type');
      if (type && type.length) {
        type = 'issue_reported=' + type;
      }
      return `//${basename}?${status}&${type}`;
    }
  };

  // build map
  let map;
  let getMapElement = () => document.getElementById('map');

  function initMap() {
    map = new google.maps.Map(getMapElement(), {
      zoom: 10,
      center: { lat: 30.4001631, lng: -97.68025269999998 }
    });
  }

  // Retrieve our data and plot it
  $.getJSON(href.get(), function(data) {
    $.each(data, function(i, entry) {
      let marker = new google.maps.Marker({
        position: new google.maps.LatLng(entry.latitude, entry.longitude),
        map: map,
        title: `INCIDENT TYPE: \n${entry.issue_reported}
                ADDRESS: \n${entry.address}
                DATE & TIME: \n${entry.published_date}
                STATUS: \n${entry.traffic_report_status}`
      });
    });
  });
}
