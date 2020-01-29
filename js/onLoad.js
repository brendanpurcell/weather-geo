var alerts = [];
var currentLoc;
var map;

/**
 * 
 * @param {*} title alert main name
 * @param {*} location city, state
 * @param {*} start time
 * @param {*} end time
 * @param {*} severity minor, moderate, severe, extreme severities
 * @param {*} headline
 * @param {*} body description of event
 * @param {*} poly coordinates of area to map
 */
function Warning(title, location, start, end, severity, headline, body, poly) {
  this.title = title;
  this.location = location;
  this.start = start;
  this.end = end;
  this.severity = severity;
  this.headline = headline;
  this.body = body;
  this.poly = poly;
};

/**
 * when the user loads up the page, we render the map and query any weather alerts for the person's location
 */
function alertDataForUserLocation() {
  var lat = "";
  var long = "";
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function showPosition(position) {
      lat = parseFloat(position.coords.latitude);
      long = parseFloat(position.coords.longitude);
      currentLoc = [lat,long];
      function initMap() {
        var options = {
            zoom: 10,
            center:{lat:currentLoc[0],lng:currentLoc[1]}
        }
        //new map
        map = new google.maps.Map(document.getElementById('map'), options);
      }
      initMap();
      addMarker({coords:{lat:currentLoc[0],lng:currentLoc[1]},info: ""+currentLoc[0]+","+currentLoc[1]+""});

      let url = " https://api.weather.gov/alerts/active?point=" + lat + "," + long;
      fetch(url)
        .then(res => res.json())
        .then((out) => {
          // if there are no warnings, display "None" in sidebar
          if (out.features == 0) {
            var div = document.createElement("div");
            div.id = "alert0";
            div.innerHTML = "None";
            div.className = "tiptext";
            div.style = "background-color: white";
            document.getElementById("sidebar").appendChild(div);
          } else {
            // generate array of warnings
            for (var f in out.features) {
              var newWarn = new Warning(out.features[f].properties.event,
                out.features[f].properties.areaDesc,
                out.features[f].properties.effective,
                out.features[f].properties.expires,
                out.features[f].properties.severity,
                out.features[f].properties.headline,
                out.features[f].properties.description,
                out.features[f].geometry);
              alerts.push(newWarn);
            }
            var i = 0;
            for (var x in alerts) {
              var alert = document.createElement("div");
              var alertData = document.createElement("div");
              alert.id = "alert" + i;
              alert.innerHTML = alerts[x].title;
              alert.className = "tiptext";
              alert.className += " " + alerts[x].severity;
              alertData.className = "description";
              alertData.innerHTML = alerts[x].body;
              alertData.innerHTML = "<b>" + alerts[x].title + "</b>" + " (" + alerts[x].severity + ")" + "<br><br>Starts: " + alerts[x].start + " <br>Ends: " + alerts[x].end + "<br><br>" + alerts[x].body + "<br><br>" + alerts[x].headline;
              alert.appendChild(alertData);
              document.getElementById("sidebar").appendChild(alert);
              i++;
            }
          }
        }).catch(err => console.error(err)); 
      }, (error) => {
      alert("Geolocation needs to be enabled for this site.");
      console.log(error)
    });
  }
}

/**
 * query a new location on the map, and query new information from the weather API related to the area
 * @param {*} lat 
 * @param {*} long 
 */
function queryAlert(lat, long) {
  //refreshes alert current warnings
 var i = 0;
 alerts = [];
 while (document.getElementById("alert"+i)){
  document.getElementById("alert"+i).remove();
  i++;
 }
  let url = "https://api.weather.gov/alerts/active?point=" + lat + "," + long;
  fetch(url)
        .then(res => res.json())
        .then((out) => {
          // if there are no warnings, display "None" in sidebar
          if (out.features == 0) {
            var div = document.createElement("div");
            div.id = "alert0";
            div.innerHTML = "None";
            div.className = "tiptext";
            div.style = "background-color: white";
            document.getElementById("sidebar").appendChild(div);
          } else {
            // generate array of warnings
            for (var f in out.features) {
              var newWarn = new Warning(out.features[f].properties.event,
                out.features[f].properties.areaDesc,
                out.features[f].properties.effective,
                out.features[f].properties.expires,
                out.features[f].properties.severity,
                out.features[f].properties.headline,
                out.features[f].properties.description,
                out.features[f].geometry);
              alerts.push(newWarn);
            }
            var i = 0;
            for (var x in alerts) {
              var alert = document.createElement("div");
              var alertData = document.createElement("div");
              alert.id = "alert" + i;
              alert.innerHTML = alerts[x].title;
              alert.className = "tiptext";
              alert.className += " " + alerts[x].severity;
              alertData.className = "description";
              alertData.innerHTML = alerts[x].body;
              alertData.innerHTML = "<b>" + alerts[x].title + "</b>" + " (" + alerts[x].severity + ")" + "<br><br>Starts: " + alerts[x].start + " <br>Ends: " + alerts[x].end + "<br><br>" + alerts[x].body + "<br><br>" + alerts[x].headline;
              alert.appendChild(alertData);
              document.getElementById("sidebar").appendChild(alert);
              i++;
            }
            //renders markers/polygons depicting the affected areas
            generateMapVisual();
          }
        } , (error) => {
          console.log(error)
        });
    }


    /**
     * takes each Warning object in alert array and put them on map
     */
    function generateMapVisual() {
      //for each alert in alerts, print out to map
      for (var i = 0; i < alerts.length; i++) {
        var x = alerts[i];
        if (x.poly == null || x.poly.length == 0) {
          if (x.severity != null && x.title != null) {
            addMarker({coords:{lat:currentLoc[0],lng:currentLoc[1]},info: ""+x.title+": "+x.severity});
          } else {
            addMarker({coords:{lat:currentLoc[0],lng:currentLoc[1]},info: ""+currentLoc[0]+","+currentLoc[1]+""});
          }
        } else {
          addPolygon(x.poly.coordinates[0],x.severity);
        }
      }
    }
