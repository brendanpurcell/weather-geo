/**
 * 
 * @param {*} markerSpecs marker object that contains info needed to graph to gMaps API
 */
function addMarker(markerSpecs){

    var marker = new google.maps.Marker({
        position:markerSpecs.coords,
        map:map,
        icon:'',

    });

    var infoWindow = new google.maps.InfoWindow({
        content:'<p>'+markerSpecs.info+'</p>'
    });
    marker.addListener('click', function(){
        infoWindow.open(map, marker);
    });
}

/**
 * 
 * @param {*} polygonCoords 
 * @param {*} severity 
 */
function addPolygon(polygonCoords, severity){

    function Polygon(lat,lng) {
        this.lat = lat;
        this.lng = lng;
    };

    var polygon = [];
    for (var i = 0; i < polygonCoords.length; i++) {
        var coordinate = polygonCoords[i];
        //api received writes in lng lat...
        polygon.push(new Polygon(parseFloat(coordinate[1]),parseFloat(coordinate[0])));
    }
 
    // Construct the polygon.
    var severityColor = '#FF0000';
    if (severity === "Severe") {
        severityColor = '#FF0000';
    } else if (severity === "Moderate") {
        severityColor = '#ff6600';
    } else if (severity === "Minor") {
        severityColor = '#FFD700';
    } else {
        severityColor = '#008000';
    }

    var region = new google.maps.Polygon({
      paths: polygon,
      strokeColor: severityColor,
      strokeOpacity: 0.8,
      strokeWeight: 3,
      fillColor: severityColor,
      fillOpacity: 0.35
    });
    region.setMap(map);
}