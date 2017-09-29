<!DOCTYPE html >
  <head>
	<?php header('Access-Control-Allow-Origin: *'); ?>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>HelpMe! - Mapa del Delito</title>
	<link rel="shortcut icon" href="img/logo.png"/>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>

    <script>
      var customLabel = {
        "Zona oscura": {
          label: 'Zona oscura'
        },
        "Robo": {
          label: 'Robo'
        },
		"Accidente de tránsito": {
          label: 'Robo'
        },
		"Acoso": {
          label: 'Acoso'
        },
		"Otro": {
          label: 'Otro'
        },
		"Violacion": {
          label: 'Violacion'
        }
      };
	  
	  var customColor = {
        "Zona oscura": {
          label: 'img/markers/celeste2.png'
        },
        "Robo": {
          label: 'img/markers/rosa.png'
        },
		"Accidente de tránsito": {
          label: 'img/markers/amarillo.png'
        },
		"Acoso": {
          label: 'img/markers/violeta.png'
        },
		"Otro": {
          label: 'img/markers/celeste.png'
        },
		"Violacion": {
          label: 'img/markers/azul.png'
        }
      };


        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-34.6044226, -58.4246368),
          zoom: 16
        });
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
			initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			map.setCenter(initialLocation);
		});
 }
        var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          downloadUrl('http://helpmeayudame.azurewebsites.net/traerDenunciasWeb.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var name = markerElem.getAttribute('descripcion');
              var date = markerElem.getAttribute('fecha');
			  var res = date.split("-");
			  date = res[2]+"-"+res[1]+"-"+res[0];
              var type = markerElem.getAttribute('tipo');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('latitud')),
                  parseFloat(markerElem.getAttribute('longitud')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = date
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
			  var color = customColor[type] || {};
			  
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                //label: icon.label,
				icon: color.label
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }



      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwTCQp_tmPErwvb2LBl82BRnGhyQa7_ng&callback=initMap">
    </script>
  </body>
</html>