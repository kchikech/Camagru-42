<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container is-widescreen">
<div class="column is-half is-offset-one-fifth">
    
</div>
<div class="container is-fluid column is-half is-offset-one-fifth">
    <!-- <h6 class="subtitle is-6">Fill this form to register</h6> -->
    <form action="<?php echo URLROOT;?>/users/register" method="post">
        <div class="field">
            <label class="label" for="name">Name:</label>
            <div class="control">
                <input name="name" class="input <?php echo (!empty($data['name_err'])) ? 'is-danger' : '' ;?>" type="text" placeholder="" value="<?php echo $data['name']; ?>">
            </div>
            <span class="help is-danger"><?php echo $data['name_err']; ?></span>
        </div>
        <div class="field">
            <label class="label" for="username">Username:</label>
            <div class="control">
                <input name="username" class="input <?php echo (!empty($data['username_err'])) ? 'is-danger' : '' ;?>" type="text" placeholder="" value="<?php echo $data['username']; ?>">
            </div>
            <span class="help is-danger"><?php echo $data['username_err']; ?></span>
        </div>
        <div class="field">
            <label class="label" for="email">Email:</label>
            <div class="control">
                <input name="email" class="input <?php echo (!empty($data['email_err'])) ? 'is-danger' : '' ;?>" type="email" placeholder="" value="<?php echo $data['email']; ?>">
            </div>
            <span class="help is-danger"><?php echo $data['email_err']; ?></span>
        </div>
        <div class="field">
            <label class="label" for="password">Password:</label>
            <div class="control">
                <input name="password" class="input <?php echo (!empty($data['password_err'])) ? 'is-danger' : '' ;?>" type="password" placeholder="" value="<?php echo $data['password']; ?>">
            </div>
            <span class="help is-danger"><?php echo $data['password_err']; ?></span>
        </div>
        <div class="field">
            <label class="label" for="confirm_password">Confirm password:</label>
            <div class="control">
                <input name="confirm_password" class="input <?php echo (!empty($data['confirm_password_err'])) ? 'is-danger' : '' ;?>" type="password" placeholder="" value="<?php echo $data['confirm_password']; ?>">
            </div>
            <span class="help is-danger"><?php echo $data['confirm_password_err']; ?></span>
        </div>
        <div class="field is-grouped">
            <div class="control">
                <!-- <button class="button is-link">Register</button> -->
                <input type="submit" class="button is-link" value="Register">
            </div>
            <div class="control">
                <a href="<?php echo URLROOT;?>/users/login"><sub>Have an account? Login</sub></a>
            </div>
            
        </div>

    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

