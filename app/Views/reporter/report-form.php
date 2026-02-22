<?php

$userRole = $_SESSION['user']['role'] ?? null;
$mode        = $mode ?? 'create';
$incident    = $incident ?? [];
$media       = $media ?? [];
$currentStep = $currentStep ?? 1;
$steps       = $steps ?? [];

$isReadOnly = ($mode === 'view');
$readonly   = $isReadOnly ? 'readonly' : '';
$disabled   = $isReadOnly ? 'disabled' : '';

$code   = $incident['tracking_code'] ?? null; 
$status = strtolower(trim($incident['status'] ?? ''));
$currentUserId = $_SESSION['user']['id'] ?? null;
$reporterId    = $incident['reporter_id'] ?? null;

?>


<main class="page page--incident-form">

    <?php if ($currentUserId !== $reporterId): ?>
        <?php if ($userRole === 'staff' && $status !== 'closed'): ?>
            <section class="action">
                <div class="action--btn">
                    <a href="/RMS/public/index.php?url=staff/actionForm&code=<?= htmlspecialchars($code) ?>"
                       class="btn btn--primary btn--block">Take Action</a>
                </div>
            </section>
        <?php endif; ?>
        <?php if ($userRole === 'responder' && $status !== 'closed'): ?>
            <section class="action">
                <div class="action--btn">
                    <a href="/RMS/public/index.php?url=responder/actionForm&code=<?= htmlspecialchars($code) ?>" 
                       class="btn btn--secondary btn--block">Take Action</a>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <h3 class="page__title"><?= $isReadOnly ? 'Incident Summary' : 'Create Report' ?></h3>

    <section class="form-wrapper">

        <form method="POST" 
              action="/RMS/public/index.php?url=reporter/saveIncident"
              enctype="multipart/form-data" 
              class="form form--incident">

            <h2 class="form__title">Incident Form</h2>

            <!-- Hidden fields -->
            <input type="hidden" name="tracking_code" value="<?= htmlspecialchars($incident['tracking_code'] ?? '') ?>">
            

            <!-- Subject -->
            <div class="form__field">
                <label class="form__label" for="subject">Subject</label>
                <input id="subject"
                       name="subject" 
                       class="form__input" 
                       type="text"
                       value="<?= htmlspecialchars($incident['subject'] ?? '') ?>"
                       <?= $readonly ?>
                       required>
            </div>

            <!-- Date & Time -->
            <div class="form__row form__row--two">
                <div class="form__field">
                    <label class="form__label">Date of Incident</label>
                    <input class="form__input" type="date" name="date_of_incident"
                           value="<?= $incident['date_of_incident'] ?? '' ?>" <?= $readonly ?> required>
                </div>
                <div class="form__field">
                    <label class="form__label">Time of Incident</label>
                    <input class="form__input" type="time" name="time_of_incident"
                           value="<?= $incident['time_of_incident'] ?? '' ?>" <?= $readonly ?>>
                </div>
            </div>

            <!-- Location -->
            <label class="form__label">Location of Incident</label>
            <div class="form__row form__row--three">

                <!-- Department -->
                <div class="form__field">
                    <label class="form__label" for="location_department">Department</label>
                    <?php
                    $departments = [
                        '(CAFNR) College of Agriculture, Fisheries and Natural Resources',
                        '(CAC) College of Arts and Communication',
                        '(CBA) College of Business Administration',
                        '(CE) College of Engineering',
                        '(CNAH) College of Nursing and Allied Health Sciences',
                        '(CS) College of Science',
                        '(CVM) College of Veterinary Medicine',
                        '(CL) College of Law',
                    ];
                    $currentDepartment = $incident['location_department'] ?? '';
                    ?>
                    <select class="form__select" name="location_department" id="location_department"
                            <?= $disabled ?> required>
                        <option value="" disabled <?= empty($currentDepartment) ? 'selected' : '' ?>>-- Select Department --</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept) ?>"
                                <?= $dept === $currentDepartment ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Building -->
                <div class="form__field">
                    <label class="form__label">Building</label>
                    <input class="form__input" type="text" name="location_building"
                           value="<?= htmlspecialchars($incident['location_building'] ?? '') ?>"
                           <?= $readonly ?> required>
                </div>

                <!-- Landmark -->
                <div class="form__field">
                    <label class="form__label">Landmark</label>
                    <input class="form__input" type="text" name="location_landmark"
                           placeholder="optional"
                           value="<?= htmlspecialchars($incident['location_landmark'] ?? '') ?>"
                           <?= $readonly ?>>
                </div>

                <!-- Map -->
                 <?php
                 $readable_address = $incident['readable_address'] ?? '';
                 ?>

                 
                <div class="form__field">
                    <label class="form__label">Location Preview</label>
                    <div id="map" style="height: 250px; width: 100%; border-radius: 8px; border: 1px solid #ddd;"></div>
                    <small style="color: #666">📍 Based on your current location (if permitted)</small>
                </div>  

                <div id="map-address" style="margin-top: 8px; color: #333; font-size: 0.9rem;">
                    Loading address...
                </div>

                <input type="hidden" name="latitude" id="latitude" 
                    value="<?= htmlspecialchars($incident['latitude'] ?? '') ?>">
                <input type="hidden" name="longitude" id="longitude" 
                    value="<?= htmlspecialchars($incident['longitude'] ?? '') ?>">


                <input type="hidden" name="readable_address" id="readable_address" 
                    value="<?= htmlspecialchars($incident['readable_address'] ?? '') ?>">


            </div>

            <!-- Category -->
            <div class="form__field">
                <label class="form__label">Category of Incident</label>
                <select class="form__select" name="category_of_incident" required <?= $disabled ?>>
                    <option value="">Category of Incidents</option>
                    <?php
                    $categories = ['theft', 'accident', 'harassment', 'other'];
                    foreach ($categories as $cat):
                        $selected = (($incident['category'] ?? '') === $cat) ? 'selected' : '';
                        ?>
                        <option value="<?= $cat ?>" <?= $selected ?>><?= ucfirst($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Description -->
            <div class="form__field">
                <label class="form__label">Description</label>
                <textarea name="description" class="form__textarea" placeholder="Enter a short description here..."
                          <?= $readonly ?>><?= htmlspecialchars($incident['description'] ?? '') ?></textarea>
            </div>

            <!-- Upload & Type -->
            <div class="form__row form__row--two">
                <?php
                $media = $media ?? [];
                require BASE_PATH . '/app/Views/layouts/media-uploader.php';
                ?>

                <!-- Type -->
                <div class="form__field">
                    <label class="form__label">Type of Incident</label>
                    <div class="form__control form__control--radio">
                        <div class="form__radio-group">
                            <?php $type = $incident['incident_type'] ?? ''; ?>
                            <label class="form__radio">
                                <input type="radio" name="incident_type" value="fatal"
                                       <?= $type === 'fatal' ? 'checked' : '' ?>
                                       <?= $isReadOnly ? 'disabled' : '' ?>> Fatal
                                <p><i>(ex. life threatening)</i></p>
                            </label>
                            <label class="form__radio">
                                <input type="radio" name="incident_type" value="non-fatal"
                                       <?= $type === 'non-fatal' ? 'checked' : '' ?>
                                       <?= $isReadOnly ? 'disabled' : '' ?>> Non-fatal
                                <p><i>(ex. injury, fear, or property damage)</i></p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="currentStep" value="<?= $currentStep ?>">

            <?php if (!$isReadOnly): ?>
                <button type="submit" class="btn btn--primary btn--block" id="submitBtn" disabled>
                    Waiting for GPS…
                </button>
            <?php endif; ?>

        </form>
    </section>

    <!-- Lightbox for enlarging images/videos -->
    <?php if (!$isReadOnly): ?>
        <div id="lightbox" class="lightbox">
            <span class="lightbox__close">&times;</span>
            <img id="lightbox-img" class="lightbox__media" src="" alt="Preview">
            <video id="lightbox-video" class="lightbox__media" controls style="display:none;"></video>
        </div>
    <?php endif ?>





    <script>

        document.addEventListener("DOMContentLoaded", () => {
            const latInput = document.getElementById("latitude");
            const lngInput = document.getElementById("longitude");
            const form = document.querySelector(".form--incident");
            const submitBtn = document.getElementById("submitBtn");
            const isReadOnly = <?= $isReadOnly ? 'true' : 'false' ?>;
            const API_KEY = "pk.b430d43ae67ac8dcaca5c45fe0060e69";

            let map;
            window.marker = null;

            function enableSubmit(lat, lng) {
                latInput.value = lat.toFixed(8);
                lngInput.value = lng.toFixed(8);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = "SAVE & CONTINUE";
                }
            }




            function initMap(lat, lng) 
            {
                map = L.map("map", {
                    dragging: false,
                    zoomControl: true,
                    scrollWheelZoom: false,
                    doubleClickZoom: false
                }).setView([lat, lng], 18);

                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: "© OpenStreetMap"
                }).addTo(map);

                window.marker = L.marker([lat, lng], { draggable: !isReadOnly }).addTo(map);

                // Update address immediately
                if (!isReadOnly) 
                {
                    updateAddress(lat, lng);
                }

                // If marker is draggable, update address when moved
                if (!isReadOnly) {
                    window.marker.on('dragend', function(e) {
                        const pos = e.target.getLatLng();
                        latInput.value = pos.lat.toFixed(8);
                        lngInput.value = pos.lng.toFixed(8);
                        updateAddress(pos.lat, pos.lng);
                    });
                }

                setTimeout(() => map.invalidateSize(), 300);
                window.addEventListener("resize", () => map.invalidateSize());

                enableSubmit(lat, lng);





                function updateAddress(lat, lng, attempt = 1) {
                    const API_KEY = "pk.b430d43ae67ac8dcaca5c45fe0060e69";
                    const addressDiv  = document.getElementById("map-address");
                    const hiddenField = document.getElementById("readable_address");

                    addressDiv.textContent = "Fetching location…";

                

                    fetch(
                        `https://us1.locationiq.com/v1/reverse.php` +
                        `?key=${API_KEY}` +
                        `&lat=${lat}` +
                        `&lon=${lng}` +
                        `&format=json&zoom=19`
                    )
                    .then(res => res.json())
                    .then(data => {
                        if (!data || !data.address) throw new Error("No address");

                        const a = data.address;
                        const parts = [];

                        // 1️⃣ Landmark / POI (VERY important)
                        const landmark =
                            a.pitch ||
                            a.building ||
                            a.amenity ||
                            a.school ||
                            a.university ||
                            a.tourism;

                        // 2️⃣ Street
                        const road = a.road || a.street || a.path;

                        // 3️⃣ Barangay (ADMIN only)
                        const barangay =
                            a.barangay ||
                            a.quarter ||
                            a.suburb ||
                            a.neighbourhood ||
                            a.hamlet;

                        // 4️⃣ City / Municipality
                        const city =
                            a.city ||
                            a.town ||
                            a.municipality ||
                            a.village;

                        // 5️⃣ Province
                        const province = a.state || a.region;

                        // Build address
                        if (landmark) parts.push(landmark);
                        if (road) parts.push(road);
                        if (barangay) parts.push(`Brgy. ${barangay}`);
                        if (city) parts.push(city);
                        if (province) parts.push(province);




                                if (!a.house_number && !a.building) {
                                parts.push(`(${lat.toFixed(6)}, ${lng.toFixed(6)})`);
                        }

                                // Retry if result is too vague
                                if (parts.length < 3 && attempt < 3) {
                                    setTimeout(() => updateAddress(lat, lng, attempt + 1), 1000);
                                    return;
                                }

                                const finalLabel = parts.join(", ");

                                addressDiv.textContent = finalLabel;
                                hiddenField.value = finalLabel;
                            })
                            .catch(() => {
                                if (attempt < 3) {
                                    setTimeout(() => updateAddress(lat, lng, attempt + 1), 1000);
                                } else if (!hiddenField.value) {
                                    addressDiv.textContent = "Nearby location detected";
                                }
                            });
                }

            }


        if (latInput.value && lngInput.value) {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);

        initMap(lat, lng);

        if (isReadOnly) {
            const savedAddress =
                document.getElementById("readable_address")?.value;

            document.getElementById("map-address").textContent =
                savedAddress && savedAddress.trim() !== ""
                    ? savedAddress
                    : "Address not available";
        }

        } else if (isReadOnly) {
            // In read-only mode but no coordinates
            document.getElementById("map-address").textContent = "No coordinates available";
            // Optional: show an empty map or a placeholder
            const mapDiv = document.getElementById("map");
            mapDiv.innerHTML = "<div style='text-align:center; padding:50px; color:#666'>No map data</div>";

        } else {
            // Only ask for GPS in create/edit mode
            if (!navigator.geolocation) {
                alert("Geolocation is required to submit an incident.");
            } else {
                navigator.geolocation.getCurrentPosition(
                    pos => initMap(pos.coords.latitude, pos.coords.longitude),
                    err => alert("Location access is required to report an incident. Please allow location."),
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            }
        }




            if (!isReadOnly) 
            {
                form.addEventListener("submit", function (e) {
                    if (!latInput.value || !lngInput.value) {
                        e.preventDefault();
                        alert("GPS location is required.");
                        return;
                    }
                    if (window.marker) {
                        const pos = window.marker.getLatLng();
                        latInput.value = pos.lat.toFixed(6);
                        lngInput.value = pos.lng.toFixed(6);
                    }
                });
            }
});

    </script>

</main>
