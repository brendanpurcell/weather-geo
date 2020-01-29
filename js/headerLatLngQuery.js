
/**
 * recenter map to new place
 */
function updateGoogleMapCenter() {
    if (document.getElementById("lat").value === "" || document.getElementById("lng").value === "") {
        //break out with no input
       return;
    }
    var newLat =  parseFloat(document.getElementById("lat").value);
    var newLng =  parseFloat(document.getElementById("lng").value);
    // using global variable:
    var latlng = new google.maps.LatLng(newLat, newLng);
    var specs = {

        zoom: 9,

        center: latlng,

    }
    //note making a new map will erase all marker from the original page
    map = new google.maps.Map(document.getElementById('map'), specs);
    map.setCenter(latlng);
    currentLoc = [newLat,newLng];
    //check if valid (in US location), to get alerts if any
    var top = 49.3457868; //north lat
    var left = -124.7844079; //west long
    var right = -66.9513812; //east long
    var bottom =  24.7433195; //south lat
    if ((newLat < top && newLat > bottom) && (newLng < right && newLng > left)) {
        queryAlert(newLat, newLng);
    } else {
        alert("Outside of the US. No weather alerts available.")
    }
}