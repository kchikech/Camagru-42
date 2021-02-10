<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="columns">
    <div class="column">
        <a id="back-button" onclick="topFunction()">
            <span class="icon is-large has-text-white	">
                <i class="fas fa-angle-up "></i>
            </span>
        </a>

<div class="buttons" >
<a class="button is-primary is-fullwidth"  <?php if(!isLoggedIn()) {?> onclick="alert('You must be logged in to share a post ! ')"  <?php } else {?> href="<?php echo URLROOT ?>/posts/add" <?php } ?>>
                <strong>Add post</strong>
            </a>
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-half is-offset-one-quarter" id="container">


    </div>
</div>
<br><br>
<div class="loading">
    <div class="ball"></div>
    <div class="ball"></div>
    <div class="ball"></div>
</div>



<?php require APPROOT . '/views/inc/footer.php'; ?>
<script src="<?= URLROOT ?>/public/js/inf_scroll.js"></script>