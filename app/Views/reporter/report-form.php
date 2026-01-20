
<?php
$mode     = $mode ?? 'create';
$incident = $incident ?? [];

$isReadOnly = ($mode === 'view');
$readonly   = $isReadOnly ? 'readonly' : '';
$disabled   = $isReadOnly ? 'disabled' : '';

?>


<main class="page page--incident-form">

    <h3 class="page__title">

        <?= $isReadOnly ? 'Incident Summary' : 'Create Report' ?>

    </h3>

    <section class = "form-wrapper"> 

        <form 
            method="POST" 
            action="/RMS/public/index.php?url=reporter/saveIncident"
            enctype="multipart/form-data" 
            class="form form--incident">

            <h2 class = "form__title">Incident Form</h2>

            <!-- Subject -->

            <div class="form__field">

                <label class="form__label" for="subject">Subject</label>
                
                <input 
                    id="subject"
                    name="subject" 
                    class="form__input" 
                    type="text"

                    value="<?= htmlspecialchars($incident['subject'] ?? '') ?>"
                <?= $readonly ?>

                    required >

            </div>

            <!-- Date & Time -->
        <div class="form__row form__row--two">

            <div class="form__field">

                <label          class="form__label">Date of Incident</label>

                <input
                    class="form__input"
                    type="date" 
                    name="date_of_incident"

                     value="<?= $incident['date_of_incident'] ?? '' ?>"
                    <?= $readonly ?>

                    required >

            </div>

                
                <div class="form__field">

                    <label class="form__label">Time of Incident</label>

                    <input
                        class="form__input" 
                        type="time" 
                        name="time_of_incident" 
                        value="<?= $incident['time_of_incident'] ?? '' ?>"
                    <?= $readonly ?>
                        >

                </div>

        </div>


         <label class="form__label">Location</label>
            <!-- Location & Category -->
        <div class="form__row form__row--three">

                <div class="form__field">
                    
                    <label class="form__label">Department/Office</label>

                    <input
                        class="form__input" 
                        type="text" name="location_department" 

                        value="<?= htmlspecialchars($incident['location_department'] ?? '') ?>"
                        <?= $readonly ?>


                        required>

                </div>


                <div class="form__field">
                    
                    <label class="form__label">Building</label>

                    <input
                        class="form__input" 
                        type="text" name="location_building" 

                        value="<?= htmlspecialchars($incident['location_building'] ?? '') ?>"
                        <?= $readonly ?>

                        required>

                </div>



                <div class="form__field">
                    
                    <label class="form__label">Landmark</label>

                    <input
                        class="form__input" 
                        type="text" name="location_landmark" 
                        placeholder="optional"

                        value="<?= htmlspecialchars($incident['location_landmark'] ?? '') ?>"
                        <?= $readonly ?>

                        >

                </div>

            
        </div>

            <div class="form__field">

            <label class="form__label">Category of Incident</label>

                <select class="form__select" name="category_of_incident" required  <?= $disabled ?>>

                        <option value="">Category of Incidents</option>


                <?php
                $categories = ['theft', 'accident', 'harassment', 'other'];
                foreach ($categories as $cat):
                    $selected = (($incident['category'] ?? '') === $cat) ? 'selected' : '';
                ?>
                    <option value="<?= $cat ?>" <?= $selected ?>>
                        <?= ucfirst($cat) ?>
                    </option>
                <?php endforeach; ?>

                </select>

            </div>


            <!-- Description -->
            <div class="form__field">

                <label class="form__label">Description</label>

                <textarea
                    name="description"
                    class="form__textarea"  
                    placeholder="Enter a short description here..."
                    

                     <?= $readonly ?>><?= htmlspecialchars($incident['description'] ?? '') ?>
                    

                </textarea>

            </div>


            <!-- Upload & Type -->

        <div class="form__row form__row--two">

                <!-- Upload -->

        <?php if ($isReadOnly && !empty($media)): ?>

            <div class="form__field">
                <label class="form__label">Attached Evidence</label>

                <div class="uploader__preview">
                    <?php foreach ($media as $item): ?>
                        <?php
                            $path = '/RMS/public/uploads/' . $item['file_path'];
                        ?>

                        <div class="uploader__item">
                            <?php if ($item['file_type'] === 'image'): ?>
                                <img src="<?= $path ?>" alt="Evidence">

                            <?php elseif ($item['file_type'] === 'video'): ?>
                                <video controls>
                                    <source src="<?= $path ?>">
                                </video>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>

        <?php endif; ?>

            


        <?php if (!$isReadOnly): ?>

            <div class="form__field">

                <label class="form__label">Upload Image / Video</label>

                <div class="uploader">

                    <label class="uploader__dropzone" for="incident_image">
                        
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Click or drag files here</span>
                        <small>(Images & Videos, max 500MB)</small>

                    </label>

                    <input

                        type="file"
                        id="incident_image"
                        name="incident_image[]"
                        accept="image/*,video/*"
                        multiple
                        hidden
                    >

                    <div id="preview-container" class="uploader__preview"></div>
                </div>
            </div>
        <?php endif; ?>

                
           


        
                
            
            <!-- Type -->

            <div class="form__field">
                    
                <label class="form__label">Type of Incident</label>

                <div class="form__control form__control--radio" >

                    <div class="form__radio-group"
                        >


                       <?php
                            $type = $incident['incident_type'] ?? ''; // get the submitted value
                            ?>
                            <label class="form__radio">
                                <input type="radio" name="incident_type" value="fatal"
                                    <?= $type === 'fatal' ? 'checked' : '' ?>
                                    <?= $isReadOnly ? 'disabled' : '' ?>>
                                Fatal
                            </label>

                            <label class="form__radio">
                                <input type="radio" name="incident_type" value="non-fatal"
                                    <?= $type === 'non-fatal' ? 'checked' : '' ?>
                                    <?= $isReadOnly ? 'disabled' : '' ?>>
                                Non-fatal
                            </label>

                    </div>
                </div>

            </div>
            
        </div>


       

            
        <?php if(!$isReadOnly): ?>

            <button type="submit" class="btn btn--primary btn--block">SAVE & CONTINUE</button>

        <?php endif; ?>

        </form>
    </section>

    <!-- Lightbox for enlarging images/videos -->
<div id="lightbox" class="lightbox">
    <span class="lightbox__close">&times;</span>
    <img id="lightbox-img" class="lightbox__media" src="" alt="Preview">
    <video id="lightbox-video" class="lightbox__media" controls style="display:none;"></video>
</div>


</main>






<script>
    
const input = document.getElementById('incident_image');
const preview = document.getElementById('preview-container');


let filesList = [];

input?.addEventListener('change', (e) => {
    for (const file of e.target.files) {
        filesList.push(file);
    }
    renderPreviews();
    syncInputFiles();
});



function renderPreviews() {
    preview.innerHTML = '';

    filesList.forEach((file, index) => {
        const card = document.createElement('div');
        card.className = 'uploader__item';

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'uploader__remove';
        removeBtn.innerHTML = '&times;';

        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // ⬅️ VERY IMPORTANT
            filesList.splice(index, 1);
            renderPreviews();
            syncInputFiles();
        });

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.cursor = 'pointer';

            img.addEventListener('click', () => {
                openLightbox('image', img.src);
            });

            card.appendChild(img);
        }

        if (file.type.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(file);
            video.muted = true;
            video.style.cursor = 'pointer';

            video.addEventListener('click', () => {
                openLightbox('video', video.src);
            });

            card.appendChild(video);
        }

        card.appendChild(removeBtn);
        preview.appendChild(card);
    });
}




function syncInputFiles() {
    const dt = new DataTransfer();
    filesList.forEach(file => dt.items.add(file));
    input.files = dt.files;
}





// Lightbox elements
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');
const lightboxVideo = document.getElementById('lightbox-video');
const lightboxClose = document.querySelector('.lightbox__close');

// Function to open lightbox
function openLightbox(fileType, src) {
    if (fileType === 'image') {
        lightboxImg.src = src;
        lightboxImg.style.display = 'block';
        lightboxVideo.style.display = 'none';
    } else if (fileType === 'video') {
        lightboxVideo.src = src;
        lightboxVideo.style.display = 'block';
        lightboxImg.style.display = 'none';
    }


    lightbox.classList.add('show');
}

// Close lightbox
lightboxClose.addEventListener('click', () => {
    lightbox.classList.remove('show');
    lightboxImg.src = '';
    lightboxVideo.src = '';
});

// // Click on any thumbnail
// document.querySelectorAll('.uploader__item img, .uploader__item video').forEach(item => {
//     item.style.cursor = 'pointer';
//     item.addEventListener('click', () => {
//         const type = item.tagName.toLowerCase() === 'img' ? 'image' : 'video';
//         openLightbox(type, item.src);
//     });
// });


document.addEventListener('click', e => {
    if (e.target.matches('.uploader__item img')) {
        openLightbox('image', e.target.src);
    }

    if (e.target.matches('.uploader__item video')) {
        openLightbox('video', e.target.src);
    }
});


lightbox.addEventListener('click', e => {
    if (e.target === lightbox) {
        lightbox.classList.remove('show');
    }
});


document.querySelector('.uploader__preview')?.addEventListener('click', e => {
    e.stopPropagation();
});

</script>




