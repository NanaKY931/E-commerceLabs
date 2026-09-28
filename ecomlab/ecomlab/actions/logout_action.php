<?php

// This action file is called when a logged-in user clicks "Logout".
// It destroys the session completely and redirects to the login page.
// It does not need to talk to the database at all.
require_once "../core/core.php";

// 1. Clear every value stored in $_SESSION for this user
$_SESSION = [];

// 2. Delete the session cookie from the browser so the old session ID
//    cannot be replayed (session fixation / hijacking protection)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destroy the server-side session data
session_destroy();

// 4. Send the user to the login page
header("Location: ../view/login.php");
exit;
