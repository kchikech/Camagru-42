<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="columns is-mobile is-centered">
    <div class="column is-narrow">
        <header class="bd-index-header"></header>
        <h3 class="title">Add Image</h3>
        </header>
    </div>
</div>
<?php echo $data['image_err']; ?>
<div class="tabs is-centered is-boxed">
    <ul>
        <li class="tablinks is-active" onclick="switch_type(event, 'camera')">
            <a>
                <span>Camera</span>
            </a>
        </li>
        <li class="tablinks" onclick="switch_type(event, 'upload')">
            <a>
                <span>Upload image</span>
            </a>
        </li>
    </ul>
</div>

<div class="is-mobile is-centered">
    <form action="<?php echo URLROOT ?>/posts/add" method="post" enctype="multipart/form-data">
        <div class="columns is-mobile">
            <div class="column">
                <input type="radio" value="<?php echo URLROOT; ?>/public/img/1.png" class="form-check-input hidden sup" name="super" id="super1">
                <label for="super1" class="form-check-label">
                    <img src="<?php echo URLROOT; ?>/public/img/1.png" alt="" class="image is-32x32 sup">
                </label>
            </div>
            <div class="column">
                <input type="radio" value="<?php echo URLROOT; ?>/public/img/2.png" class="form-check-input hidden sup" name="super" id="super2">
                <label for="super2" class="form-check-label">
                    <img src="<?php echo URLROOT; ?>/public/img/2.png" alt="" class="image is-32x32 sup">
                </label>
            </div>
            <div class="column">
                <input type="radio" value="<?php echo URLROOT; ?>/public/img/3.png" class="form-check-input hidden sup" name="super" id="super3">
                <label for="super3" class="form-check-label">
                    <img src="<?php echo URLROOT; ?>/public/img/3.png" alt="" class="image is-32x32 sup">
                </label>
            </div>
            <div class="column">
                <input type="radio" value="<?php echo URLROOT; ?>/public/img/4.png" class="form-check-input hidden sup" name="super" id="super4">
                <label for="super4" class="form-check-label">
                    <img src="<?php echo URLROOT; ?>/public/img/4.png" alt="" class="image is-32x32 sup">
                </label>
            </div>
            <div class="column">
                <input type="radio" value="<?php echo URLROOT; ?>/public/img/5.png" class="form-check-input hidden sup" name="super" id="super5">
                <label for="super5" class="form-check-label">
                    <img src="<?php echo URLROOT; ?>/public/img/5.png" alt="" class="image is-32x32 sup">
                </label>
            </div>
        </div>

        <div class="columns is-mobile tabcontent" id="camera">
            <input name="type" type="hidden" id="type" value="camera" />
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column">
                        <h3 class="title is-3">Camera</h3>
                        <div class="canvas_container">
                            <video id="vid-show" autoplay></video>
                            <img class="canvas_sup" draggable="true">
                        </div>
                        <input id="vid-take" type="button" class="button is-primary is-fullwidth" value="Take Photo" />

                    </div>
                    <div class="column" id="vid-canvas">
                        <h3 class="title is-3">Preview</h3>
                        <div class="preview">
                            <canvas class="photo"> </canvas>
                            <img class="canvas_sup1" draggable="true">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="control">
            <input type="hidden" name="x" id="x" value="">
            <input type="hidden" name="y" id="y" value="">
            <input type="hidden" name="token" id="token" value="<?= $_SESSION['token'] ?>">
            <input name="imageData" type="hidden" class="imgInputData" id="imgInputData" value="" />
        </div>

        <div class="tabcontent" id="upload" style="display: none;">
            <div class="columns  is-mobile ">
                <div class="column">

                </div>
                <div>
                    <div class="column" id="preview">

                    </div>
                </div>
            </div>
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column">
                        <div class="file has-name">
                            <label class="file-label">
                                <span class="file-cta">
                                    <span class="file-label">Choose an image...</span>
                                    <input accept="image/*" name="image" type="file" class="is-fullwidth imgInput" value="" onchange="preview_image(event)" />
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="column">
                        <div class="control canvas_container" id="vid-canvas">
                            <img class="image" id="previmg" />
                            <img class="canvas_sup2" draggable="true">
                        </div>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>

<button type="submit" id="up" class="button  is-link is-fullwidth" name="share">Share post</button>

</form>
</div>

</div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>