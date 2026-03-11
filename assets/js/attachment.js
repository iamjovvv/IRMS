// --- Lightbox (always runs, regardless of mode) ---
function openLightbox(el) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxVideo = document.getElementById('lightbox-video');
    if (!lightbox) return;

    if (el.tagName === 'VIDEO') {
        lightboxImg.style.display = 'none';
        lightboxVideo.style.display = 'block';
        lightboxVideo.src = el.src;
        lightboxVideo.play();
    } else {
        if (lightboxVideo) { lightboxVideo.style.display = 'none'; lightboxVideo.pause(); }
        lightboxImg.style.display = 'block';
        lightboxImg.src = el.src;
    }
    lightbox.classList.add('show');
}

document.addEventListener('click', function (e) {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox) return;
    if (e.target.classList.contains('lightbox__close') || e.target === lightbox) {
        lightbox.classList.remove('show');
        const vid = document.getElementById('lightbox-video');
        if (vid) { vid.pause(); vid.src = ''; }
    }
});

// --- Uploader (only runs if elements exist i.e. create/edit mode) ---
const preview = document.getElementById('preview-container');
const input = document.getElementById('incident_image');
const openCameraBtn = document.getElementById('openCameraBtn');
const openUploadBtn = document.getElementById('openUploadBtn');

if (openCameraBtn && openUploadBtn && input && preview) {
    const cameraModal = document.getElementById('cameraModal');
    const cameraVideo = document.getElementById('cameraVideo');
    const cameraCanvas = document.getElementById('cameraCanvas');
    const snapBtn = document.getElementById('snapBtn');
    const closeCamera = document.getElementById('closeCamera');
    const gpsInput = document.getElementById('gps_data_input');

    let filesList = [];
    let cameraStream = null;
    let currentLat = null;
    let currentLng = null;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            currentLat = pos.coords.latitude;
            currentLng = pos.coords.longitude;
        }, err => console.warn(err), { enableHighAccuracy: true });
    }

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

    closeCamera.addEventListener('click', () => { stopCamera(); cameraModal.style.display = 'none'; });

    function stopCamera() {
        if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }
    }

    snapBtn.addEventListener('click', () => {
        cameraCanvas.width = cameraVideo.videoWidth;
        cameraCanvas.height = cameraVideo.videoHeight;
        cameraCanvas.getContext('2d').drawImage(cameraVideo, 0, 0);
        cameraCanvas.toBlob(blob => {
            const file = new File([blob], `camera_${Date.now()}.jpeg`, { type: 'image/jpeg' });
            filesList.push({ file, latitude: currentLat, longitude: currentLng });
            renderPreviews(); syncInputFiles();
        }, 'image/jpeg', 0.95);
        stopCamera(); cameraModal.style.display = 'none';
    });

    openUploadBtn.addEventListener('click', () => input.click());

    input.addEventListener('change', (e) => {
        Array.from(e.target.files).forEach(file => filesList.push({ file, latitude: currentLat, longitude: currentLng }));
        renderPreviews(); syncInputFiles();
    });

    function renderPreviews() {
        preview.innerHTML = '';
        filesList.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'uploader__item';
            card.style.position = 'relative';
            let el = item.file.type?.startsWith('image/') ? document.createElement('img') : Object.assign(document.createElement('video'), { muted: true, controls: true });
            el.src = URL.createObjectURL(item.file);
            Object.assign(el.style, { width: '100%', height: '100%', objectFit: 'cover' });
            card.appendChild(el);
            const removeBtn = Object.assign(document.createElement('button'), { type: 'button', className: 'uploader__remove', innerHTML: '&times;' });
            removeBtn.addEventListener('click', e => { e.stopPropagation(); filesList.splice(index, 1); renderPreviews(); syncInputFiles(); });
            card.appendChild(removeBtn);
            preview.appendChild(card);
        });
    }

    function syncInputFiles() {
        const dt = new DataTransfer();
        filesList.forEach(item => dt.items.add(item.file));
        input.files = dt.files;
        if (gpsInput) gpsInput.value = JSON.stringify(filesList.map(f => ({ name: f.file.name, latitude: f.latitude, longitude: f.longitude })));
    }
}