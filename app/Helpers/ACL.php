<?php

use Illuminate\Support\Facades\Auth;

if(!function_exists('can')){
    /**
     * Chack user permissions
     *
     * @param string|array $abilities
     *
     * @return bool
     */
    function can(string|array $abilities):bool {
        return Auth::user()->hasAnyAccess($abilities);
    }
}


if(!function_exists('anyRole')){
    /**
     * Check user role
     *
     * @param string|array $roles
     *
     * @return bool
     */
    function anyRole(string|array $roles): bool {
        $userRoles = Auth::user()->getRoles()->pluck('slug');

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($userRoles->contains($role)) {
                    return true; // User has at least one of the specified roles
                }
            }
        } else {
            // If $roles is a string, check if the user has that role
            return $userRoles->contains($roles);
        }

        return false; // No matching roles found
    }

}
