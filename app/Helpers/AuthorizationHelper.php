<?php

use Illuminate\Support\Facades\Auth;

class AuthorizationHelper
{
    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public static function isUser()
    {
        return Auth::check() && Auth::user()->role === 'user';
    }

    /**
     * Check if user can manipulate data (admin only)
     */
    public static function canManipulateData()
    {
        return self::isAdmin();
    }

    /**
     * Check if user can view data
     */
    public static function canViewData()
    {
        return Auth::check();
    }

    /**
     * Get user role
     */
    public static function getUserRole()
    {
        return Auth::check() ? Auth::user()->role : null;
    }
}
