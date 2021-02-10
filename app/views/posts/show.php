<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="columns">
    <div class="column is-half is-offset-one-quarter">
        <div class="card">
            <div class="card-header">
                <div class="columns">
                    <div class="column">
                        <figure class="image is-24x24">
                            <img class="is-rounded" src="https://www.cnam.ca/wp-content/uploads/2018/06/default-profile.gif">
                        </figure>
                    </div>
                    <div class="column is-11">
                        <a href="<?php echo URLROOT; ?>/users/profile/<?= $data['post']->user_id ?>">
                            <h4><?php echo $data['user']->username; ?></h4>
                        </a>
                    </div>
                    <div class="column is-4 is-offset-8">
                        <?php if ($data['post']->user_id === $_SESSION['user_id']) { ?> <a data-toggle="modal" id="modaldelete" class="modal-delete" href="#modal-delete"><button class="button  is-small is-danger">Delete post</button> </a><?php } ?>

                    </div>
                </div>

            </div>
            <div class="card-content">
                <img class="gallery-image" src="<?php echo URL . $data['post']->image; ?>">
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-half is-offset-one-quarter ">
        <span onclick="likeButton(<?= $data['post']->post_id ?>)" class="<?php if ($data['liked'] === true)  echo 'has-text-danger'; ?>">
            <span class="icon">
                <i class="fa fa-heart"></i>
            </span>
            <span id="like<?= $data['post']->post_id ?>"><?php echo $data['post']->likes ?> likes </span>
        </span>
        <?php if ($data['post']->likes) { ?> <a data-toggle="modal" id="modal_likes" class="modal-likes" href="#modal">(Show likes)</a> <?php } ?>
        <span class="icon">
            <i class="far fa-comments"></i>
        </span>
        <span><?php echo $data['post']->comments ?> Comments</span>
    </div>
</div>

<?php if ($data['post']->user_id === $_SESSION['user_id']) { ?>
    <div class="modal" id="modal-delete">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Delete post</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                Are you sure you want to delete this post ?
            </section>
            <footer class="modal-card-foot">
                <form action="<?= URLROOT ?>/posts/delete_post/<?= $data['post']->post_id ?>" method="POST">
                    <input type="hidden" name="token" id="" value="<?= $_SESSION['token'] ?>">
                    <button class="button is-danger" name="delete" type="submit">Delete</button>
                </form>
            </footer>
        </div>
    </div>
<?php } ?>
<div class="modal" id="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">People who like this post</p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <?php foreach ($data['likedby'] as $likedby) : ?>
                <div>
                    <!-- <figure class="image is-32x32">
                    <img class="is-rounded" src="https://www.cnam.ca/wp-content/uploads/2018/06/default-profile.gif">
                </figure> -->
                    <span><?php echo $likedby->uname; ?> ( @<?php echo $likedby->username; ?> )</span>

                </div>
            <?php endforeach; ?>
        </section>
        <footer class="modal-card-foot">
        </footer>
    </div>
</div>
<?php if ($data['comment_err']) { ?>
    <div class="notification is-danger">
        <?= $data['comment_err'] ?>
    </div>
<?php } ?>
<?php foreach ($data['comments'] as $comment) :
?>
    <div class="columns">
        <div class="column is-half is-offset-one-quarter">
            <div class="box">
                <article class="media">
                    <div class="media-left">
                        <figure class="image is-64x64">
                            <img src="<?php echo URLROOT; ?>/public/img/profile.gif" alt="Image">
                        </figure>
                    </div>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <strong><?php echo $comment->userName; ?></strong> <small>@<?php echo $comment->useruName; ?></small> <small>| <?php echo $comment->cmntCreated; ?></small>
                                <br>
                                <?php echo $comment->cmnt; ?>
                            </p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<div class="columns">
    <div class="column is-half is-offset-one-quarter">
        <form method="POST" action="<?php echo URLROOT; ?>/posts/show/<?php echo $data['post']->post_id; ?>">
            <div class="control">
                <textarea name="comment" class="textarea is-hovered" placeholder="Write a comment ..."></textarea>
            </div>
            <div class="control">
                <input type="hidden" name="token" id="" value="<?= $_SESSION['token'] ?>">
                <button type="submit" id="up" class="button  is-link is-fullwidth">Share comment</button>
            </div>
        </form>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>