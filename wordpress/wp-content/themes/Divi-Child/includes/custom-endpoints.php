<?php
// custom-endpoints.php

// Custom endpoint for user creation
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/createUser', array(
        'methods' => 'POST',
        'callback' => 'custom_create_user_endpoint',
        'permission_callback' => '__return_true', // We will handle authentication manually
    ));
});

function custom_create_user_endpoint($request) {
    $secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v'; // Ensure this matches the one in your custom app

    // Check for the secret key in the request headers
    $auth_header = $request->get_header('authorization');
    if (!$auth_header || $auth_header !== 'Bearer ' . $secret_key) {
        return new WP_Error('unauthorized', 'Unauthorized', array('status' => 403));
    }

    $params = $request->get_json_params();
    $user_type = sanitize_text_field($params['userType']);
    $email = sanitize_email($params['email']);
    $password = sanitize_text_field($params['password']);

    // Validate required fields
    if (!$user_type || !$email || !$password) {
        return new WP_Error('missing_fields', 'Missing required fields', array('status' => 400));
    }

    // Create the user in WordPress
    $user_id = wp_create_user($email, $password, $email);
    if (is_wp_error($user_id)) {
        return new WP_Error('user_creation_failed', 'User creation failed', array('status' => 500));
    }

    // Set user type
    update_user_meta($user_id, 'user_type', $user_type);

    return rest_ensure_response(array(
        'success' => true,
        'user' => array(
            'ID' => $user_id,
            'user_email' => $email,
            'user_type' => $user_type
        )
    ));
}
