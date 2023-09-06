let map, lieuDepart = null, destination = null, polyline=null;

let lieuDepartMarker = L.marker([0,0],{
    draggable: false,
    icon : new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    }),
});

let destinationMarker = L.marker([0,1],{
    draggable: false,
    icon : new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    }),
});

$('#type_trajet').on('change', function () {
    let selectedValue = $(this).val();

    if (selectedValue === '1') {
        $('#lieu_depart_id, #destination_id')
            .removeClass('col-md-12')
            .addClass('col-md-6')
            .show();
    } else if (selectedValue === '2') {
        $('#lieu_depart_id')
            .removeClass('col-md-6')
            .addClass('col-md-12')
            .show();
        $('#destination_id').hide();
    }
});

function searchPlace(input_id, pt_lat, pt_lng, calculateKm = false,place_num){
    // if
    let pacContainerInitialized = false;
    let autocomplete;

    autocomplete = new google.maps.places.Autocomplete((document.getElementById(input_id)), {
        componentRestrictions: {
            country: "mr",
        },
    });

    $(`#${input_id}`).keypress(function () {
        if (!pacContainerInitialized) {
            $(".pac-container").css("z-index", "20");
            pacContainerInitialized = true;
        }
    });

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        const near_place = autocomplete.getPlace();

        $(pt_lat).val(near_place.geometry.location.lat());
        $(pt_lng).val(near_place.geometry.location.lng());

        lieuDepart = [parseFloat($('#latitudeLieuDepart').val()), parseFloat($('#longitudeLieuDepart').val())];
        destination = [parseFloat($('#latitudeDestination').val()),parseFloat($('#longitudeDestination').val())];

        if(place_num === 1){
            setLieuDepart(lieuDepart);
            setDestination(lieuDepart);
            initMap();
            lieuDepartMarker.addTo(map);
            $('#map').show();
        } else{
            setDestination(destination);
            initMap();
            lieuDepartMarker.addTo(map);
            destinationMarker.addTo(map);
            $('#map').show();
        }
    });
}

function setLieuDepart(lieuDepart)
{
    const latLng = new L.LatLng(lieuDepart[0], lieuDepart[1]);
    lieuDepartMarker.setLatLng(latLng)
}

function setDestination(destination) {
    const latLng = new L.LatLng(destination[0], destination[1]);

    destinationMarker.setLatLng(latLng);

    if (lieuDepart != null && destination != null && lieuDepart[0] !== destination[0] && lieuDepart[1] !== destination[1]) {
        drawDirection(lieuDepart, destination);
    }
    else {
        findNearestDriver(lieuDepart);
    }
}

function drawDirection(lieuDepart, destination){
    let directionsService = new google.maps.DirectionsService;
    let control = L.control();

    directionsService.route({
        origin: lieuDepart.join(','),
        destination: destination.join(','),
        travelMode: 'DRIVING'
    }, function (response, status) {

        if (status === 'OK') {

            let typeCourseId = $('#type_course_id').val();
            let kmCourse = parseFloat(response.routes[0].legs[0].distance.text.split(' ')[0].replace(/,/g, '.'));

            $.ajax({
                type: 'get',
                url: "courses/get-prix-km/" + typeCourseId + "/" + kmCourse,
                success: function (data) {
                    $('#km').text(kmCourse + " km");
                    $('#price_id').text(data.price + " mru");
                    $('#price').val(data.price);
                    findNearestDriver(lieuDepart);
                },
                error: function (error) {
                    console.log(error.toString());
                }
            });

            let pol = L.Polyline.fromEncoded(response.routes[0].overview_polyline).getLatLngs();

            polyline = L.polyline(pol, {color: 'red'}).addTo(map);

            $('.infoTrajet').remove();

            control.onAdd = function () {
                let div = L.DomUtil.create('div', 'infoTrajet');

                div.innerHTML = `<div class="card p-1" style="width: 150px;height: auto">
                       <p>
                            <strong><i class="fa fa-road" aria-hidden="true"></i> Distance : <span id="km"></span>
                            <input type="hidden" name="km" value="${parseFloat(response.routes[0].legs[0].distance.text.split(' ')[0].replace(/,/g, '.'))}">
                       </p>
                       <p> <strong><i class="fa fa-clock"></i> Temps : </strong> ${response.routes[0].legs[0].duration.text}</p>
                       <p>
                            <strong><i class="fas fa-money"></i> Prix : <span id="price_id"></span></strong>
                            <input type="hidden" name="price" id="price">
                       </p>
                    </div>
                `;
                return div;
            }
            control.addTo(map);
        } else {
            console.log(status);
        }
    });
}

function initMap(){
    map = L.map('map').setView([18.079021, -15.965662], 13);

    L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    }).addTo(map);

    setTimeout(function () { map.invalidateSize() }, 800);
}

function findNearestDriver(lieuDepart){
    const lieuDepartLatLng = {
        latitude: lieuDepart[0],
        longitude: lieuDepart[1]
    }

    $.ajax({
        type: 'get',
        url: "courses/get-drivers/",
        success: function (data) {
            let driversCoordinates = [];

            for (let i = 0; i < data.drivers.length; i++) {
                let driver = data.drivers[i];

                let latitude = driver.location.latitude;
                let longitude = driver.location.longitude;

                driversCoordinates.push({ latitude, longitude });
            }

            let nearestDriver = geolib.findNearest(lieuDepartLatLng, driversCoordinates);

            if (nearestDriver) {
                let driver = data.drivers
                    .filter(driver => driver.car.type_course == $('#type_course_id').val())
                    .find(driver => driver.location.latitude === nearestDriver.latitude && driver.location.longitude === nearestDriver.longitude)
                let time = geolib.getDistance(lieuDepartLatLng, nearestDriver);
                let timeInMinutes = time / 60;

                let driverLatLng = new L.LatLng(driver.location.latitude, driver.location.longitude);
                let driverMarker = L.marker(driverLatLng,{
                    draggable: false,
                    icon : new L.Icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/512/75/75800.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    }),
                }).addTo(map);

                driverMarker.bindPopup(`<div class="card" style="width: 200px;height: auto; padding: 5px">
                       <p>
                            <strong><i class="fas fa-user"></i> Nom complet : </strong> ${driver.nom} ${driver.prenom}
                            <input type="hidden" name="driver_id" value="${driver.id}">
                            <input type="hidden" id="time_waiting" name="time_waiting">
                       </p>
                       <p>
                            <strong><i class="fas fa-phone"></i> Téléphone : </strong> ${driver.tel}
                             <input type="hidden" name="driver_tel" value="${driver.tel}">
                       </p>
                      <p>
                            <strong><i class="fas fa-car"></i> Véhicule : </strong> ${driver.car.immatriculation}
                      </p>
                      <p>
                            <strong><i class="fas fa-car"></i> Type du véhicule : </strong> ${driver.car.type_car == 1 ? 'Economique' : 'VIP'}
                      </p>
                    </div>`).openPopup();

                $('#time_waiting').val(timeInMinutes);
                driverMarker.addTo(map);
                map.setView(driverLatLng, 13);
            }
        },
        error: function () {
            console.log("error");
        }
    });

}

