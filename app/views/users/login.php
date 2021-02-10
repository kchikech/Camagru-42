<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container is-widescreen">
    <div class="column is-half is-offset-one-fifth">

    </div>
    <div class="container is-fluid column is-half is-offset-one-fifth">
        <div class="column is-center">
            <?php flash('register_success'); ?>
            <?php flash('account_verified'); ?>
            <?php flash('already_verified'); ?>
            <?php  flash('reset_success');?>
        </div>
        <!-- <h6 class="subtitle is-6">Please fill thi form to login</h6> -->
        <form action="<?php echo URLROOT; ?>/users/login" method="post">
            <div class="field">
                <label class="label" for="email">Username:</label>
                <div class="control">
                    <input name="email" class="input <?php echo (!empty($data['email_err'])) ? 'is-danger' : ''; ?>" type="text" placeholder="" value="<?php echo $data['email']; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['email_err']; ?></span>
            </div>
            <div class="field">
                <label class="label" for="password">Password:</label>
                <div class="control">
                    <input name="password" class="input <?php echo (!empty($data['password_err'])) ? 'is-danger' : ''; ?>" type="password" placeholder="" value="<?php echo $data['password']; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['password_err']; ?></span>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link">Login</button>
                </div>
                <div class="control">
                    <a href="<?php echo URLROOT; ?>/users/register"><sub>don't have an account? Register</sub></a>
                </div>
            </div>
            <div class="control">
                <a href="<?php echo URLROOT; ?>/users/forget_password"><sub>Froget password.</sub></a>
            </div>

            <div>
                <?php flash('verify_email'); ?>
            </div>

        </form>
    </div>
</div>