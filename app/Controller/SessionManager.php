<?php

namespace App\Controller;

class SessionManager
{
    /**
     * Start the session if not already started
     */
    const SESSION_LIFETIME = 900; // 15 minutes in seconds

    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Set session cookie parameters
            session_set_cookie_params([
                'lifetime' => self::SESSION_LIFETIME,
                'path' => '/',
                'secure' => true,
                'httponly' => true
            ]);
            session_start();
            
            // Check if session has expired
            if (self::isSessionExpired()) {
                self::destroySession();
                return false;
            }
            
            // Update last activity time
            $_SESSION['LAST_ACTIVITY'] = time();
        }
        return true;
    }

    private static function isSessionExpired()
    {
        if (isset($_SESSION['LAST_ACTIVITY'])) {
            return (time() - $_SESSION['LAST_ACTIVITY'] > self::SESSION_LIFETIME);
        }
        return false;
    }

    /**
     * Set a session variable
     * 
     * @param string $key The session key
     * @param mixed $value The value to store
     */
    public static function set($key, $value)
    {
        self::startSession();
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable
     * 
     * @param string $key The session key
     * @return mixed|null The session value or null if not set
     */
    public static function get($key)
    {
        self::startSession();
        return $_SESSION[$key] ?? null;
    }

    /**
     * Check if a session variable is set
     * 
     * @param string $key The session key
     * @return bool
     */
    public static function has($key)
    {
        self::startSession();
        return isset($_SESSION[$key]);
    }

    /**
     * Unset a session variable
     * 
     * @param string $key The session key
     */
    public static function unset($key)
    {
        self::startSession();
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the session
     */
    public static function destroySession()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }

    /**
     * Check if a user is logged in
     * 
     * @return bool
     */
    public static function isLoggedIn()
    {
        self::startSession();
        return self::has('user_id') && !self::isSessionExpired();
    }
    public static function loginUser($userId)
    {
        self::startSession();
        self::set('user_id', $userId);
        self::set('LAST_ACTIVITY', time());
    }
    /**
     * Redirect to a given URL
     * 
     * @param string $url The URL to redirect to
     */
    public static function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Ensure the user is logged in, or redirect to the login page
     * 
     * @param string $loginPage Path to the login page
     */
    public static function requireLogin($loginPage = '/SWEphaseTwo/app/view/pages/Login_Signup.php')
    {
        if (!self::isLoggedIn()) {
            self::redirect($loginPage);
        }
    }

    /**
     * Get the current logged-in user ID
     * 
     * @return mixed|null The user ID or null if not logged in
     */
    public static function getUserId()
    {
        return self::get('user_id');
    }

    /**
     * Set user login session
     * 
     * @param int $userId The logged-in user's ID
     */
    // Update the loginUser  method to set the correct session variabl

    /**
     * Logout the user and destroy the session
     */
    public static function logoutUser()
    {
        self::destroySession();
    }
}
