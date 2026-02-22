const preview = document.getElementById('preview-container');
const input = document.getElementById('incident_image');
const cameraModal = document.getElementById('cameraModal');
const cameraVideo = document.getElementById('cameraVideo');
const cameraCanvas = document.getElementById('cameraCanvas');
const snapBtn = document.getElementById('snapBtn');
const closeCamera = document.getElementById('closeCamera');
const openCameraBtn = document.getElementById('openCameraBtn');
const openUploadBtn = document.getElementById('openUploadBtn');
const gpsInput = document.getElementById('gps_data_input');

let filesList = [];
let cameraStream = null;
let currentLat = null;
let currentLng = null;

// --- Get user location on page load ---
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
        currentLat = pos.coords.latitude;
        currentLng = pos.coords.longitude;
    }, err => console.warn(err), { enableHighAccuracy: true });
}

// --- Open camera ---
openCameraBtn.addEventListener('click', async () => {
    cameraModal.style.display = 'flex';
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        cameraVideo.srcObject = cameraStream;
    } catch (err) {
        alert('Camera not available.');
        cameraModal.style.display = 'none';
    }
});

// --- Close camera ---
closeCamera.addEventListener('click', () => {
    stopCamera();
    cameraModal.style.display = 'none';
});

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
}

// --- Snap photo ---
snapBtn.addEventListener('click', () => {
    cameraCanvas.width = cameraVideo.videoWidth;
    cameraCanvas.height = cameraVideo.videoHeight;
    const ctx = cameraCanvas.getContext('2d');
    ctx.drawImage(cameraVideo, 0, 0);

    cameraCanvas.toBlob(blob => {
        const file = new File([blob], `camera_${Date.now()}.jpeg`, { type: 'image/jpeg' });
        filesList.push({
            file: file,
            latitude: currentLat,
            longitude: currentLng
        });
        renderPreviews();
        syncInputFiles();
    }, 'image/jpeg', 0.95);

    stopCamera();
    cameraModal.style.display = 'none';
});

// --- Open file upload ---
openUploadBtn.addEventListener('click', () => input.click());

// --- File input ---
input.addEventListener('change', (e) => {
    Array.from(e.target.files).forEach(file => {
        filesList.push({
            file: file,
            latitude: currentLat,
            longitude: currentLng
        });
    });
    renderPreviews();
    syncInputFiles();
});

// --- Render all previews with remove button ---
function renderPreviews() {
    preview.innerHTML = '';
    filesList.forEach((item, index) => {
        const card = document.createElement('div');
        card.className = 'uploader__item';
        card.style.position = 'relative';

        let el;
        if (item.file.type?.startsWith('image/')) {
            el = document.createElement('img');
        } else {
            el = document.createElement('video');
            el.muted = true;
            el.controls = true;
        }
        el.src = URL.createObjectURL(item.file);
        el.style.width = '100%';
        el.style.height = '100%';
        el.style.objectFit = 'cover';
        card.appendChild(el);

        // Remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'uploader__remove';
        removeBtn.innerHTML = '&times;';
        removeBtn.addEventListener('click', e => {
            e.stopPropagation();
            filesList.splice(index, 1);
            renderPreviews();
            syncInputFiles();
        });
        card.appendChild(removeBtn);

        preview.appendChild(card);
    });
}

// --- Sync files for form submit ---
function syncInputFiles() {
    const dt = new DataTransfer();
    filesList.forEach(item => dt.items.add(item.file));
    input.files = dt.files;

    // Save GPS per file
    gpsInput.value = JSON.stringify(filesList.map(f => ({
        name: f.file.name,
        latitude: f.latitude,
        longitude: f.longitude
    })));
}
