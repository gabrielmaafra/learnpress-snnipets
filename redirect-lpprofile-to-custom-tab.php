<?php
/**
 * Plugin Name: LearnPress - Custom Default Tab
 * Description: Removes "My Courses and Courses" tab and redirects /lp-profile/{user} to /lp-profile/{user}/programs.
 * Author: Gabriel Mafra
 * Version: 1.0
 */

add_filter('learn-press/profile-tabs', function ($tabs) {
    unset($tabs['courses']);      // Main tab "My Courses"
    unset($tabs['my-courses']);   // Some installs use this slug (Main tab as admin user)
    return $tabs;
});

add_action('template_redirect', function () {
    // Ensure that LearnPress is active
    if (!function_exists('learn_press_get_profile_permalink')) {
        return;
    }

    if (!is_user_logged_in()) {
        return;
    }

    $user = wp_get_current_user();
    $profile_url = learn_press_get_profile_permalink($user->user_login);
    
    // Gets path of the current URL and compares it with the profile path
    $current_uri = untrailingslashit(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $expected_uri = untrailingslashit(parse_url($profile_url, PHP_URL_PATH));

    if ($current_uri === $expected_uri) {
        wp_redirect($profile_url . '/programs/'); //New main URL tab
        exit;
    }
});
