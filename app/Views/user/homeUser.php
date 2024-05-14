<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>


<div class="overflow overflow-x-auto mt-5 mr-4 ml-4 bg-white shadow-xl rounded-lg text-gray-900">
    <div class="rounded-t-lg h-40 overflow-hidden">
        <img class="object-cover object-top w-full" src="/img/bg-profile.png">
    </div>
    <div class="mx-auto w-48 h-48 relative -mt-20 border-4 border-white rounded-full overflow-hidden">
        <img class="object-cover object-center h-48" src="<?php echo session('user_details')['foto'] ?>" alt="Image Description">>
    </div>
    <div class="flex justify-center mt-2 px-4">

        <div class="border-b border-gray-200 dark:border-neutral-700">
            <nav class="flex space-x-1" aria-label="Tabs" role="tablist">
                <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-gray-800 hs-tab-active:text-gray-800 py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-gray-500 active" id="tabs-with-icons-item-1" data-hs-tab="#tabs-with-icons-1" aria-controls="tabs-with-icons-1" role="tab">
                    <svg class="flex-shrink-0 size-4" fill="#00000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">

                            <path d="M9.6,3.32a3.86,3.86,0,1,0,3.86,3.85A3.85,3.85,0,0,0,9.6,3.32M16.35,11a.26.26,0,0,0-.25.21l-.18,1.27a4.63,4.63,0,0,0-.82.45l-1.2-.48a.3.3,0,0,0-.3.13l-1,1.66a.24.24,0,0,0,.06.31l1,.79a3.94,3.94,0,0,0,0,1l-1,.79a.23.23,0,0,0-.06.3l1,1.67c.06.13.19.13.3.13l1.2-.49a3.85,3.85,0,0,0,.82.46l.18,1.27a.24.24,0,0,0,.25.2h1.93a.24.24,0,0,0,.23-.2l.18-1.27a5,5,0,0,0,.81-.46l1.19.49c.12,0,.25,0,.32-.13l1-1.67a.23.23,0,0,0-.06-.3l-1-.79a4,4,0,0,0,0-.49,2.67,2.67,0,0,0,0-.48l1-.79a.25.25,0,0,0,.06-.31l-1-1.66c-.06-.13-.19-.13-.31-.13L19.5,13a4.07,4.07,0,0,0-.82-.45l-.18-1.27a.23.23,0,0,0-.22-.21H16.46M9.71,13C5.45,13,2,14.7,2,16.83v1.92h9.33a6.65,6.65,0,0,1,0-5.69A13.56,13.56,0,0,0,9.71,13m7.6,1.43a1.45,1.45,0,1,1,0,2.89,1.45,1.45,0,0,1,0-2.89Z"></path>
                        </g>
                    </svg>
                    Detail Profil
                </button>
                <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-gray-800 hs-tab-active:text-gray-800 py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-gray-500" id="tabs-with-icons-item-2" data-hs-tab="#tabs-with-icons-2" aria-controls="tabs-with-icons-2" role="tab">
                    <svg class="flex-shrink-0 size-4" fill="#00000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_iconCarrier">
                            <path d="M2,21H8a1,1,0,0,0,0-2H3.071A7.011,7.011,0,0,1,10,13a5.044,5.044,0,1,0-3.377-1.337A9.01,9.01,0,0,0,1,20,1,1,0,0,0,2,21ZM10,5A3,3,0,1,1,7,8,3,3,0,0,1,10,5ZM20.207,9.293a1,1,0,0,0-1.414,0l-6.25,6.25a1.011,1.011,0,0,0-.241.391l-1.25,3.75A1,1,0,0,0,12,21a1.014,1.014,0,0,0,.316-.051l3.75-1.25a1,1,0,0,0,.391-.242l6.25-6.25a1,1,0,0,0,0-1.414Zm-5,8.583-1.629.543.543-1.629L19.5,11.414,20.586,12.5Z"></path>
                        </g>
                    </svg>
                    Ubah Profil
                </button>
            </nav>
        </div>


    </div>

</div>


<div class="mt-3">
    <div id="tabs-with-icons-1" role="tabpanel" aria-labelledby="tabs-with-icons-item-1">

        <div class="mt-5 pt-5 mr-4 ml-4 bg-white shadow-xl rounded-xl text-gray-900 sm:p-7 dark:bg-slate-900">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    Biodata diri
                </h2>
            </div>

            <form action="/submiteditprofil" method="post">

                <div class="sm:flex sm:items-center py-4">
                    <!-- Grid -->
                    <div class="sm:w-1/6">
                        <label for="af-account-id-akun" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Email
                        </label>
                    </div>

                    <!-- End Col -->

                    <div class="sm:col-span-9">
                        <input id="af-id-akun" name="id_akun" type="text" class="mx-28 py-5 px-3  block w-full border-gray-900 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" readonly placeholder="36 characters uuid" value="<?= $akun_data['email'] ?? '' ?>">
                    </div>
                    <!-- End Col -->
                </div>


                <div class="sm:flex sm:items-center py-4">
                    <!-- Grid -->
                    <div class="sm:w-1/6">
                        <label for="af-account-id-akun" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Role
                        </label>
                    </div>

                    <!-- End Col -->

                    <div class="sm:col-span-9">
                        <input id="af-id-akun" name="id_akun" type="text" class="mx-28 py-5 px-3  block w-full border-gray-900 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" readonly placeholder="36 characters uuid" value="<?= $akun_data['nip'] ?? '' ?>">
                    </div>
                    <!-- End Col -->
                </div>

                <div class="sm:flex sm:items-center py-4">
                    <!-- Grid -->
                    <div class="sm:w-1/6">
                        <label for="af-account-id-akun" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Alamat Lengkap
                        </label>
                    </div>

                    <!-- End Col -->

                    <div class="sm:col-span-9">
                        <input id="af-id-akun" name="id_akun" type="text" class="mx-28 py-5 px-3  block w-full border-gray-900 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" readonly placeholder="36 characters uuid" value="<?= $akun_data['alamat'] ?? '' ?>">
                    </div>
                    <!-- End Col -->
                </div>

                <div class="sm:flex sm:items-center py-4">
                    <!-- Grid -->
                    <div class="sm:w-1/6">
                        <label for="location-input" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Denah Lokasi
                        </label>
                    </div>

                    <!-- End Col -->
                    <div class="sm:w-5/6">
                        <!-- Update input fields with latitude and longitude values from API -->
                        <input id="location-input" name="location-input" type="text" class="mx-28 py-5 px-3 block w-full border-gray-900 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" readonly placeholder="Latitude, Longitude" value="<?= ($akun_data['alamat_lat'] ?? '') . ', ' . ($akun_data['alamat_lon'] ?? '') ?>">
                        <!-- Map container -->
                        <div id="location-map" class="h-96 w-96 mx-28 py-5 mt-5 px-3"></div>
                    </div>
                </div>


            </form>

        </div>
    </div>
    <div id="tabs-with-icons-2" class="hidden" role="tabpanel" aria-labelledby="tabs-with-icons-item-2">
        <div class="mt-5 pt-5 mr-4 ml-4 bg-white shadow-xl rounded-xl text-gray-900 sm:p-7 dark:bg-slate-900">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    Ubah Profil
                </h2>
            </div>

            <form action="" method="post">

                <div class="sm:flex sm:items-center py-4">
                    <!-- Grid -->
                    <div class="sm:w-1/6">
                        <label for="af-account-id-akun" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Email
                        </label>
                    </div>

                    <!-- End Col -->

                    <div class="sm:col-span-9">
                        <input id="af-id-akun" name="id_akun" type="text" class="mx-28 py-5 px-3  block w-full border-gray-900 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="36 characters uuid" value="<?= $akun_data['email'] ?? '' ?>">
                    </div>
                    <!-- End Col -->
                </div>


                <div class="sm:flex sm:items-center py-4">
                    <!-- Grid -->
                    <div class="sm:w-1/6">
                        <label for="af-account-id-akun" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Role
                        </label>
                    </div>

                    <!-- End Col -->

                    <div class="sm:col-span-9">
                        <input id="af-id-akun" name="id_akun" type="text" class="mx-28 py-5 px-3  block w-full border-gray-900 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="36 characters uuid" value="<?= $akun_data['nip'] ?? '' ?>">
                    </div>
                    <!-- End Col -->
                </div>

                <div class="sm:flex sm:items-center py-4">
                    <!-- Grid -->
                    <div class="sm:w-1/6">
                        <label for="af-account-id-akun" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Alamat Lengkap
                        </label>
                    </div>

                    <!-- End Col -->

                    <div class="sm:col-span-9">
                        <input id="af-id-akun" name="id_akun" type="text" class="mx-28 py-5 px-3  block w-full border-gray-900 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="36 characters uuid" value="<?= $akun_data['alamat'] ?? '' ?>">
                    </div>
                </div>
                <!-- End Col -->

                <div class=" sm:flex sm:items-center py-4">
                    <!-- Grid -->
                    <div class="sm:w-1/6">
                        <label for="af-account-id-akun" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Denah Lokasi
                        </label>
                    </div>

                    <!-- End Col -->

                    <div class="sm:col-span-9">
                        <!-- Update input fields with latitude and longitude values from API -->
                        <input id="loc2" name="loc2" type="text" class="mx-28 py-5 px-3 block w-full border-gray-900 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" readonly placeholder="Latitude" value="<?= ($akun_data['alamat_lat'] ?? '') . ', ' . ($akun_data['alamat_lon'] ?? '') ?>">
                        <!-- Map container -->
                        <div id="map2" class="h-96 w-96 mx-28 py-5 mt-8 px-3"></div>

                    </div>
                    <button type="button" id="edit-location-btn" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Edit lokasi
                    </button>



                </div>

                <div class="mt-5 flex justify-center gap-x-2">
                    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Submit your project
                    </button>
                </div>


            </form>
        </div>
    </div>

</div>


<script>
    var map1Initialized = false;
    var map2Initialized = false;

    function initMap() {
        if (!map1Initialized) {
            // Initialize the first map
            var coordinates1 = '<?= ($akun_data['alamat_lat'] ?? '') . ', ' . ($akun_data['alamat_lon'] ?? '') ?>';
            var coordinatesArray1 = coordinates1.split(',').map(function(item) {
                return parseFloat(item);
            });

            if (!isNaN(coordinatesArray1[0]) && !isNaN(coordinatesArray1[1])) {
                var location1 = {
                    lat: coordinatesArray1[0],
                    lng: coordinatesArray1[1]
                };

                var map1 = new google.maps.Map(document.getElementById('location-map'), {
                    zoom: 12,
                    center: location1
                });

                var marker1 = new google.maps.Marker({
                    position: location1,
                    map: map1,
                    title: 'Your Location'
                });

                map1Initialized = true;
            }
        }

        if (!map2Initialized) {
    // Get latitude and longitude from PHP variable
    var latLng = '<?= ($akun_data['alamat_lat'] ?? '') . ', ' . ($akun_data['alamat_lon'] ?? '') ?>';
    var latLngArray = latLng.split(',').map(function(item) {
        return parseFloat(item);
    });

    // If latitude and longitude are valid
    if (!isNaN(latLngArray[0]) && !isNaN(latLngArray[1])) {
        // Create a LatLng object
        var myLatLng = {
            lat: latLngArray[0],
            lng: latLngArray[1]
        };

        // Create a new map object
        var map = new google.maps.Map(document.getElementById('map2'), {
            zoom: 12, // Set the initial zoom level
            center: myLatLng // Center the map on the specified location
        });

        // Add a marker to the map
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Your Location'
        });

        // Add click event listener to the "Edit Location" button
        document.getElementById('edit-location-btn').addEventListener('click', function() {
            // Request the user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLatLng = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Center the map to the user's current location
                    map.setCenter(userLatLng);
                    map.setZoom(15);

                    // Remove previous marker
                    if (marker) {
                        marker.setMap(null);
                    }

                    // Add a new marker to the map
                    marker = new google.maps.Marker({
                        position: userLatLng,
                        map: map,
                        title: 'Your Location'
                    });

                    // Update the input field with the new coordinates
                    document.getElementById('loc2').value = userLatLng.lat + ', ' + userLatLng.lng;
                }, function() {
                    alert('Error: The Geolocation service failed.');
                });
            } else {
                alert('Error: Your browser doesn\'t support Geolocation.');
            }
        });
    }

    map2Initialized = true;
}

    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('api_map_key') ?>&callback=initMap" async defer></script>



<?= $this->endSection(); ?>