<?php
// Load Helpers
require_once 'helpers/mail_helper.php';
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';
require_once 'helpers/image_helper.php';



// Load config
require_once 'config/config.php';
require_once 'config/database.php';

//
spl_autoload_register(function ($className) {
    require_once 'libraries/' . $className . '.php';
});
