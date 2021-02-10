<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container is-widescreen">
    <div class="column is-half is-offset-one-fifth">

    </div>
    <div class="container is-fluid column is-half is-offset-one-fifth">
        <!-- <h6 class="subtitle is-6">Please fill thi form to login</h6> -->
        <form action="<?php echo URLROOT; ?>/users/forget_password" method="post">
            <div class="field">
                <label class="label" for="email">Email:</label>
                <div class="control">
                    <input name="email" class="input <?php echo (!empty($data['email_err'])) ? 'is-danger' : ''; ?>" type="email" placeholder="" value="<?php echo $data['email']; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['email_err']; ?></span>
            </div>
            
            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link">Reset password</button>
                </div>
            </div>
            <div class="control">
                <a href="<?php echo URLROOT; ?>/users/login"><sub>Back to Login page.</sub></a>
            </div>

            <div>
                <?php flash('forget_email_send'); ?>
            </div>

        </form>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
