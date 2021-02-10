<nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item" href="<?php echo URLROOT ?>">
        <span class="icon">
          <i class="fas fa-home">&nbsp;&nbsp;</i>
        </span>

        Home
      </a>
      <?php if (isset($_SESSION['user_id'])) : ?>

      <a class="navbar-item " href="<?php echo URLROOT; ?>/users/profile/<?= $_SESSION['user_id'] ?>">
        <i class="fa fa-user" aria-hidden="true"></i> &nbsp;Profile
      </a>

      <div class="navbar-item">
        <a class="navbar-item" href="<?php echo URLROOT; ?>/users/settings">
        <i class="fa fa-cogs" aria-hidden="true"></i> &nbsp;Settings
        </a>
      </div>
      <?php endif; ?>
    </div>

    <div class="navbar-end">
      <?php if (isset($_SESSION['user_id'])) : ?>
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-light" href="<?php echo URLROOT ?>/users/logout">
              <strong>Logout</strong>
            </a>
          </div>
        </div>
      <?php else : ?>
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-primary" href="<?php echo URLROOT ?>/users/register">
              <strong>Sign up</strong>
            </a>
            <a class="button is-light" href="<?php echo URLROOT ?>/users/login">
              Log in
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>
<script>
  document.addEventListener('DOMContentLoaded', () => {

    // Get all "navbar-burger" elements
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {

      // Add a click event on each of them
      $navbarBurgers.forEach(el => {
        el.addEventListener('click', () => {

          // Get the target from the "data-target" attribute
          const target = el.dataset.target;
          const $target = document.getElementById(target);

          // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
          el.classList.toggle('is-active');
          $target.classList.toggle('is-active');

        });
      });
    }

  });
</script>