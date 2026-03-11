<?php
$isReadOnly = $isReadOnly ?? false;
$media = $media ?? [];
?>

<div class="form__field">
    <label class="form__label"><?= $isReadOnly ? 'Uploaded Media' : 'Upload / Capture Media' ?></label>

    <?php if (!$isReadOnly): ?>
        <div class="uploader">

            <!-- Source toggle -->
            <!-- <div class="form__control form__control--radio">
                <label>
                    <input type="radio" name="media_source" value="camera" checked> Camera
                </label>
                <label>
                    <input type="radio" name="media_source" value="upload"> Upload
                </label>
            </div> -->

            <!-- Camera Modal -->
            <div id="cameraModal" class="camera-modal" style="display:none;">
                <video id="cameraVideo" autoplay playsinline style="width:100%; max-width:400px;"></video>
                <div class="camera-buttons">
                    <button type="button" id="snapBtn" class="btn btn--primary">📸 Capture</button>
                    <button type="button" id="closeCamera" class="btn btn--secondary">Cancel</button>
                </div>
            </div>

            <!-- Hidden canvas to hold captured image -->
            <canvas id="cameraCanvas" hidden></canvas>

            <!-- Hidden inputs for captured image and GPS -->
            <input type="hidden" name="captured_image_data" id="captured_image_data">
            <!-- <input type="hidden" name="latitude" id="incident_latitude">
            <input type="hidden" name="longitude" id="incident_longitude"> -->


            <!-- File upload fallback -->
            <input type="file"
                   id="incident_image"
                   name="incident_image[]"
                   accept="image/*,video/*"
                   multiple
                   hidden>

            <div id="preview-container" class="uploader__preview">
                <?php if (!empty($media)): ?>
                    <?php foreach ($media as $item): ?>
                        <div class="uploader__item uploader__item--readonly">
                            <?php if ($item['file_type'] === 'image'): ?>
                                <img src="/RMS/public/uploads/<?= htmlspecialchars($item['file_path']) ?>" 
                                     alt="Uploaded image" 
                                     onclick="openLightbox(this)">
                            <?php else: ?>
                                <video src="/RMS/public/uploads/<?= htmlspecialchars($item['file_path']) ?>" 
                                       muted 
                                       onclick="openLightbox(this)"
                                       width="200"></video>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Action buttons -->
            <div class="form__buttons">
                <button type="button" id="openCameraBtn" class="btn btn--secondary">📷 Open Camera</button>
                <button type="button" id="openUploadBtn" class="btn btn--secondary">📁 Upload Files</button>
            </div>

        </div>
    <?php else: ?>
        <!-- Read-only previews -->
        <div class="uploader__preview readonly-preview">
            <?php foreach ($media as $item): ?>
                <div class="uploader__item uploader__item--readonly">
                    <?php if ($item['file_type'] === 'image'): ?>
                        <img src="/RMS/public/uploads/<?= htmlspecialchars($item['file_path']) ?>" 
                             alt="Uploaded image" 
                             onclick="openLightbox(this)">
                    <?php else: ?>
                        <video src="/RMS/public/uploads/<?= htmlspecialchars($item['file_path']) ?>" 
                               muted 
                               onclick="openLightbox(this)"
                               width="200"></video>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>




