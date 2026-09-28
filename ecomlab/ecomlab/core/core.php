<?php

// ============================================================
// core/core.php
// ------------------------------------------------------------
// This file is included at the top of every protected page.
// Including it is enough to start the session and gain access
// to all the helper functions below — no extra calls needed.
// ============================================================

// Start (or resume) the session before anything else reads or
// writes $_SESSION. Calling session_start() more than once on
// the same request is harmless because PHP ignores the second call.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------------------------------
// AUTHENTICATION HELPERS
// ------------------------------------------------------------------

// Returns true when a customer has logged in during this session.
// The presence of 'customer_id' in $_SESSION is the single source
// of truth — it is set in actions/login_action.php on success and
// removed entirely by actions/logout_action.php.
function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

// Returns true when the logged-in user has the admin role (role = 1).
// The role value comes from the database's user_role column and is
// stored in the session at login time — it is never taken from the
// browser directly, so a user cannot escalate their own privileges
// by editing a cookie or form field.
function is_admin()
{
    return is_logged_in() && $_SESSION['user_role'] === 1;
}

// ------------------------------------------------------------------
// ACCESS-CONTROL GUARDS
// ------------------------------------------------------------------

// Call require_login() at the top of any page that needs a session.
// If the user is not logged in, they are sent to the login page and
// the current script stops running immediately (exit).
function require_login()
{
    if (!is_logged_in()) {
        header("Location: " . get_root() . "view/login.php");
        exit;
    }
}

// Call require_admin() at the top of any admin-only page.
// Non-admins are sent back to the homepage with a query-string error
// rather than a dedicated error page, which keeps the structure simple.
function require_admin()
{
    if (!is_admin()) {
        header("Location: " . get_root() . "index.php?error=unauthorised");
        exit;
    }
}

// ------------------------------------------------------------------
// PATH HELPER
// ------------------------------------------------------------------

// Returns the relative path from the current file back to the project
// root (the folder that contains index.php). This makes redirects work
// correctly from both view/ and actions/ without hardcoding paths.
function get_root()
{
    // Walk up one level from wherever this file is included from.
    // __DIR__ here is always the core/ folder; "/../" goes to project root.
    $root = str_replace('\\', '/', dirname(__DIR__)) . '/';
    return $root;
}
