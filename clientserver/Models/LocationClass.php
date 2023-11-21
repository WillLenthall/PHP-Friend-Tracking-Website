<?php
require_once ('UserData.php');

class LocationClass
{


        public function getLocationData() {
            $code =
                '<script>
               
                navigator.geolocation.getCurrentPosition(recordPosition);
        
        
                function recordPosition(position) {
                let lat = position.coords.latitude;
                let long = position.coords.longitude;
                document.getElementById("latitude").value = lat.toString();
                document.getElementById("longitude").value = long.toString();           
        }

       
    
       </script>';

            return $code;
       }


       public function getModal() {
            $code = '<script>// Get the modal
            var modal = document.getElementById("myModal");

            // Get the button that opens the modal
            var btn = document.getElementById("openMap");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on the button, open the modal
            btn.onclick = function() {
                modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>';

            return $code;
       }

       public function mapBuild() {
            $code = '<script src="OpenLayers-2.13.1/OpenLayers.js"></script>

        <script>



            var lat = 53.5;                 //sets the zoom to around Manchester
            var long = -2.4;
            var zoom = 11;
            //document.getElementById("test").innerText = "a";
            var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
            var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection

            var position = new OpenLayers.LonLat(long, lat).transform(fromProjection, toProjection);
            //var markers = new OpenLayers.Layer.Markers("Markers");
            var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

                map = new OpenLayers.Map("Map");
                var mapnik = new OpenLayers.Layer.OSM();
                map.addLayer(mapnik);
            //sets the initial position for the map
                map.setCenter(position, zoom);

            //document.getElementById("Map").value = map;
            //document.getElementById("test").innerText = "a";             

            //map.addLayer(markers);
            
            



            
            //markers.addMarker(new OpenLayers.Marker(position));
            
            
        ';
            return $code;
       }

        public function addMarkers($friends) {
            $script = 'var locations = [';

            foreach ($friends as $FriendsData) {
                $script = $script . '["' . $FriendsData->getUsername() . '",' . $FriendsData->getLatitude() . ',' . $FriendsData->getLongitude() . ','. '"'.$FriendsData->getImgPath().'"],';
            }
            $script = rtrim($script, ',');   // remove the last unnecessary comma
            $script = $script . '];';  // add on the closing bracket
            //$script = $script.'document.getElementById("test").innerText = locations;';

            //var_dump($script);
            return $script;  // output to the browser

        }

        public function addMarkers2() {
            $script = "
            locations.forEach(createMarker);

            function createMarker(item,index)  {
                
                
                
                var feature = new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(item[2],item[1]).transform(fromProjection, toProjection),
                    { description: '<img class=\"center-block\" src=\"item[3]\" height=125 width=125>'}, 
                    {externalGraphic: '../'+item[3], graphicHeight: 30, graphicWidth: 30, graphicXOffset: -12, graphicYOffset: -25 }    //I was having a nightmare with the div in the popup so decided to use profile pictures
            );                                                                                                                           // to identify users on the map, it isn't ideal but it works

            vectorLayer.addFeatures(feature); 
            map.addLayer(vectorLayer);
    
            //Add a selector control to the vectorLayer with popup functions
            var controls = {
            selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
            };
            function createPopup(feature) {
            feature.popup = new OpenLayers.Popup.FramedCloud('pop',feature.geometry.getBounds().getCenterLonLat(),
                null,null,null,true, function(){controls['selector'].unselectAll(); });
            map.addPopup(feature.popup);
        }
        
        function destroyPopup(feature) {
            feature.popup.destroy();
            feature.popup = null;
        }
        
        map.addControl(controls['selector']);
        controls['selector'].activate();

                
                
                
                
        //document.getElementById('test').innerText = item[3];
                
                
                
                
                
                // show the data in the console
                //console.log(item[0],item[2], item[1]);
                 // create a marker from the data
                 //let mposition = new OpenLayers.LonLat(item[2], item[1]).transform( fromProjection, toProjection);
                // place marker on the map layer
                 //markers.addMarker(new OpenLayers.Marker(mposition));
            }
        </script>";

            return $script;
        }

        public function getAndSetLocation() {
            return '<script>

                         setInterval(record, 10000);
                         function record() {
                        navigator.geolocation.getCurrentPosition(recordPosition);


                        function recordPosition(position) {
                            let lat = position.coords.latitude;
                            let long = position.coords.longitude;
                            document.getElementById("lat").value = lat;
                            document.getElementById("long").value = long;

                        };}

                  

setInterval(sendLat, 20000);

    function sendLat() {
        var file = "location.php";
        var lat = document.getElementById("lat").value;
        var long = document.getElementById("long").value;
        fetch (
            file, {
                method: "POST",
                body : JSON.stringify({lat, long}),
                headers : {"Content-Type" : "application/json;charset=utf-8"}
            }
        ).then(res=>res.text()).then(function(resp) {
            document.getElementById("message").innerHTML = resp;
        });
    }
</script>

                    
                    ';
        }



}