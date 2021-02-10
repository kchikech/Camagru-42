<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container is-widescreen">
    <div class="column is-half is-offset-one-fifth">

    </div>
    <div class="container is-fluid column is-half is-offset-one-fifth">
        <h2 class="title">Edit you information here :</h2> <br>
        <form action="<?php echo URLROOT; ?>/users/settings" method="post">
            <div class="field">
                <label class="label" for="email">Email :</label>
                <div class="control">
                    <input name="email" class="input <?php echo (!empty($data['email_err'])) ? 'is-danger' : ''; ?>" type="email" placeholder="" value="<?php echo $data['user']->email; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['email_err']; ?></span>
            </div>
            <div class="field">
                <label class="label" for="username">Username :</label>
                <div class="control">
                    <input name="username" class="input <?php echo (!empty($data['username_err'])) ? 'is-danger' : ''; ?>" type="text" placeholder="" value="<?php echo $data['user']->username; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['username_err']; ?></span>
            </div>
            <div class="field">
                <label class="label" for="fname">Full name :</label>
                <div class="control">
                    <input name="fname" class="input <?php echo (!empty($data['fullname_err'])) ? 'is-danger' : ''; ?>" type="text" placeholder="" value="<?php echo $data['user']->name; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['fullname_err']; ?></span>
            </div>
            <div class="field">
                <label class="checkbox">
                    <input name="notif" type="checkbox" value="yes" <?php if($data['user']->notif == 1) echo ' checked';?>>
                    Recieve notifications 
                </label>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <input name="token" class="input" type="hidden" placeholder="" value="<?php echo $_SESSION['token']; ?>">

                    <button type="submit" class="button is-link" name="update" value="update">Update</button>
                </div>
            </div>
        </form>
        <form action="<?php echo URLROOT; ?>/users/settings" method="post">

            <br>
            <?php flash('password_changed'); ?>

            <div>
                <h2 class="title">Change password :<h2>
            </div>
            <br>
            <div class="field">
                <label class="label" for="old_password">Old password:</label>
                <div class="control">
                    <input name="old_password" class="input <?php echo (!empty($data['old_password_err'])) ? 'is-danger' : ''; ?>" type="password" placeholder="" value="<?php $data['old_password']; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['old_password_err']; ?></span>
            </div>

            <div class="field">
                <label class="label" for="new_password">new password:</label>
                <div class="control">
                    <input name="new_password" class="input <?php echo (!empty($data['new_password_err'])) ? 'is-danger' : ''; ?>" type="password" placeholder="" value="<?php $data['new_password']; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['new_password_err']; ?></span>
            </div>
            <div class="field">
                <label class="label" for="conf_new_password">Confirm new password:</label>
                <div class="control">
                    <input name="conf_new_password" class="input <?php echo (!empty($data['conf_new_password_err'])) ? 'is-danger' : ''; ?>" type="password" placeholder="" value="<?php $data['conf_new_password']; ?>">
                </div>
                <span class="help is-danger"><?php echo $data['conf_new_password_err']; ?></span>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <input name="token" class="input" type="hidden" placeholder="" value="<?php echo $_SESSION['token']; ?>">
                    <button class="button is-link" type="submit" name="change" value="change">Change Password</button>
                </div>
            </div>

        </form>



        <?php require APPROOT . '/views/inc/footer.php'; ?>