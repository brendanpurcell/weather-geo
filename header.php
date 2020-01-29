<?php
//start a logged in session if session variable is activated (see login.inc.php)
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="helps with search results">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--bootstrap import-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="css/styles.css" type="text/css">
  <link rel="shortcut icon" href="img/favicon.png" />
  <title>WeatherGeo</title>
  <style>
    #myCanvas {
      border: 2px solid gray;
    }

    #map {
      height: 100%;
      width: 100%;
    }

    main {
      width: 100%;
      height: 100%;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    .sidebar {
      width: 15%;
      height: 100%;
      z-index: 1;
      top: 80px;
      left: 10px;
      background-color: transparent;
      overflow-x: hidden;
      padding-top: 60px;
    }

    .sidebar:hover {
      width: 80%;
      height: 100%;
    }

    .sidebar a {
      padding: 6px 8px 6px 16px;
      text-decoration: none;
      font-size: 20px;
      color: #2196F3;
      display: block;
    }

    .Severe {
      background-color: red;
      border-style: solid;
      border-width: 2px;
    }

    .Severe:hover {
      background-color: white;
    }

    .Moderate {
      background-color: orange;
      border-style: solid;
      border-width: 2px;
    }

    .Moderate:hover {
      background-color: white;
    }

    .Minor {
      background-color: gold;
      border-style: solid;
      border-width: 2px;
    }

    .Minor:hover {
      background-color: white;
    }

    .Extreme {
      animation: pulse linear 2s infinite;
      background-color: red;
    }

    @-webkit-keyframes pulse {
      0%, 49% {
        border: 3px solid red;
      }
      50%, 100% {
        background-color: white;
        border: 3px solid black;
      }
    }

    .sidebar a:hover {
      color: #064579;
    }

    .tiptext {
      border: 1px #333 solid;
      padding: 5px;
      width: 200px;
    }

    .description {
      display: none;
      border: 1px solid;
      padding: 10px;
      box-shadow: 5px 10px 18px black;
      width: 80%;
      position: absolute;
      left: 20%;
      top: 20%;
      background-color: white;
    }

  .warningHeader{
    background: #ccc;
    border: 1px #333 solid;
    padding: 5px;
    width: 200px;
  }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZggH8fBXcBh-g0iVuXsg4Ol_i9ILtdMg&callback=initMap" async defer></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/headerLatLngQuery.js"></script>
    <script type="text/javascript" src="js/onLoad.js"></script>
    <script type="text/javascript" src="js/headerLatLngQuery.js"></script>
    <script type="text/javascript" src="js/gMapMethods.js"></script>
</head>
<body onLoad="alertDataForUserLocation()">
  <!--navbar-->
  <header>
    <!--lg= large screens-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="header-logo" href ="index.php">
          <canvas id="myCanvas" width="150" height="50"></canvas>
          <script>
            var canvas = document.getElementById("myCanvas");
            var dim = canvas.getContext("2d");
            base_image = new Image();
            base_image.src = 'img/favicon.png';
            base_image.onload = function(){
            dim.drawImage(base_image, 50, 0,40,40);
            dim.font = "20px Arial";
            dim.fillText("GeoWeather", 15, 48);
            }
          </script>
      </a>
      <!--hamburger icon collapse on smaller screens-->
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ml-auto">
          <?php
          //if user logged in OR just registered, the session is on
          if (isset($_SESSION['userId'])) {
            echo '<li class="form-inline">
            <form>
              <input type="text" id="lat" class="form-control" placeholder="Latitude" required/>
              <input type="text" id="lng" class="form-control" placeholder="Longitude" required/>
              <button type="button" class="btn btn-warning" onclick="updateGoogleMapCenter()">GeoReport</button>
            </form>
            </li>
            <li class="nav-item">
              <form action="includes/logout.inc.php" method="post">
              <button type="submit" name="logout-submit" class="btn btn-primary">Logout</button>
              </form>
            </li>';
          }
          else {
            echo '<li class="nav-item"><a href="index.php" class="nav-link"><button class="btn btn-primary">Login</button></a></li>
            <li class="nav-item"><a href="signup.php" class="nav-link"><button class="btn btn-primary">Register</button></a></li>';
          }
          ?>
        </ul>
    </div>
    </nav>
  </header>
