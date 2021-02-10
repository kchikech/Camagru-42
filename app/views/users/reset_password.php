<?php require APPROOT . '/views/inc/header.php'; ?>
<form action="<?php echo URLROOT; ?>/users/change_forgottenPassword/<?= $data['token'] ?>" method="POST">

    <br>
    <div>
        <h2 class="title">Create new password:<h2>
    </div>
    <br>

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
            <button class="button is-link" type="submit" name="change" value="change">Change Password</button>
        </div>
    </div>

</form>
<?php require APPROOT . '/views/inc/footer.php'; ?>