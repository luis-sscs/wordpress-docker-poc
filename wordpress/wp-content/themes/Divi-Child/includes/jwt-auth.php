<?php
if (!class_exists('Firebase\JWT\JWT')) {
    require_once get_stylesheet_directory() . '/includes/JWT.php';
    require_once get_stylesheet_directory() . '/includes/ExpiredException.php';
    require_once get_stylesheet_directory() . '/includes/SignatureInvalidException.php';
    require_once get_stylesheet_directory() . '/includes/BeforeValidException.php';
}

use \Firebase\JWT\JWT;

// Generate JWT Token
function generate_jwt($user) {
    $secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v'; // Ensure this key is kept secure and consistent between WordPress and your React app
    $issued_at = time();
    $expire = $issued_at + 36000; // Token expiration (10 hours)

    $account_id = get_user_meta($user->ID, 'account_id', true);
    $user_type = get_user_meta($user->ID, 'user_type', true) ?: 'WEB'; // Use 'WEB' as default if not set

    $payload = array(
        'iss' => get_bloginfo('url'),
        'iat' => $issued_at,
        'exp' => $expire,
        'data' => array(
            'user_id' => $user->ID,
            'user_email' => $user->user_email,
            'account_id' => $account_id,
            'user_type' => $user_type, // Include user type in JWT
        ),
    );

    return JWT::encode($payload, $secret_key, 'HS256', null, []);
}

// Add Launch Button
function add_launch_button() {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $jwt = generate_jwt($user);
        echo '<a href="http://localhost:3000?token=' . $jwt . '">Launch App</a>'; // Change this when website is hosted
    }
}
add_action('wp_footer', 'add_launch_button');