<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="columns">
</div><br><br>
<div class="columns">
    <div class="column is-two-fifths">
        <div class="card" id="card">
            <div class="card-image">
                <figure class="image is-4by3">
                    <img src="<?php echo URLROOT;?>/public/img/profile.gif" alt="Placeholder image">
                </figure>
            </div>
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="title is-6"><?php echo $data['user']->name;?></p>
                        <p class="subtitle is-6">@<?php echo $data['user']->username?></p>
                    </div>
                </div>

                <div class="content">
                    <p class="title is-6">Posts shared : <?= $data['count_posts']; ?> </p>
                    <br>
                </div>
            </div>
        </div>

    </div>
    <div class="column">
        <?php foreach ($data['posts'] as $post) : ?>
            <div class="card">
                <div class="card-content">
                    <img class="gallery-image" src="<?php echo URL . $post->image; ?>">
                </div>
                <div class="card-footer">
                    <div class="columns">
                        <div class="column is-6 <?php if($data['liked'][$post->post_id]) echo 'has-text-danger';?>" onclick="likeButton(<?= $post->post_id ?>)">
                            <span class="icon">
                                <i class="fa fa-heart"></i>
                            </span>
                            <span id="like<?= $post->post_id ?>"><?php echo $post->likes ?> likes &nbsp&nbsp</span>
                        </div>

                        <div class="column is-6">
                            <span class="icon">
                                <i class="far fa-comments"></i>
                            </span>
                            <span><?php echo $post->comments ?> Comments</span>
                        </div>
                        <div class="column ">
                            <div class="buttons are-small">
                                <a href="<?php echo URLROOT . '/posts/show/' . $post->post_id; ?>" class="href"><button class="button is-info is-inverted" class="btncmd" data-dismiss="modal">Show details</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <br>
        <?php endforeach; ?>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>