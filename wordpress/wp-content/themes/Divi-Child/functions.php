<?php

if (!class_exists('Firebase\JWT\JWT')) {
    require_once get_stylesheet_directory() . '/includes/JWT.php';
    require_once get_stylesheet_directory() . '/includes/ExpiredException.php';
    require_once get_stylesheet_directory() . '/includes/SignatureInvalidException.php';
    require_once get_stylesheet_directory() . '/includes/BeforeValidException.php';
}

use \Firebase\JWT\JWT;

function divi__child_theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'divi__child_theme_enqueue_styles');

// Generate JWT Token
function generate_jwt($user) {
    $secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v'; // Ensure this key is kept secure and consistent between WordPress and your React app
    $issued_at = time();
    $expire = $issued_at + 3600; // Token expiration (1 hour)
    $refresh_expire = $issued_at + 86400 * 7; // Refresh token expiration (7 days)

    $account_id = get_user_meta($user->ID, 'account_id', true);
    $user_type = get_user_meta($user->ID, 'user_type', true) ?: 'WEB'; // Use 'WEB' as default if not set
	$salesforce_id = get_user_meta($user->ID, 'salesforce_id', true); // Fetch Salesforce ID from user metadata


    $payload = array(
        'iss' => get_bloginfo('url'),
        'iat' => $issued_at,
        'exp' => $expire,
        'data' => array(
            'user_id' => $user->ID,
            'user_email' => $user->user_email,
            'account_id' => $account_id,
			'username' => $user->user_login,
            'user_type' => $user_type, // Include user type in JWT
			'salesforce_id' => $salesforce_id,
        ),
    );

    $refresh_payload = array(
        'iss' => get_bloginfo('url'),
        'iat' => $issued_at,
        'exp' => $refresh_expire,
        'data' => array(
            'user_id' => $user->ID,
			'user_email' => $user->user_email,
            'account_id' => $account_id,
			'username' => $user->user_login,
            'user_type' => $user_type, // Include user type in JWT
			'salesforce_id' => $salesforce_id,
        ),
    );

    // Ensure you provide the required headers (can be empty) to the encode function
    $access_token = JWT::encode($payload, $secret_key, 'HS256', null, []);
    $refresh_token = JWT::encode($refresh_payload, $secret_key, 'HS256', null, []);
	
// 	return JWT::encode($payload, $secret_key, 'HS256', null, []);
    return array('access_token' => $access_token, 'refresh_token' => $refresh_token);
}

function custom_refresh_jwt_token(WP_REST_Request $request) {
    $secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v'; // Ensure this matches the key used in generate_jwt

    // Get the refresh token from the request
    $refresh_token = sanitize_text_field($request->get_param('refresh_token'));

    if (!$refresh_token) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'No refresh token provided',
        ), 400);
    }

    try {
        // Decode the refresh token
        $decoded_token = \Firebase\JWT\JWT::decode($refresh_token, $secret_key, array('HS256'));

        // Check if the refresh token has expired
        if ($decoded_token->exp < time()) {
            return new WP_REST_Response(array(
                'success' => false,
                'error' => 'Refresh token has expired',
            ), 401);
        }

        // Get the user ID from the refresh token and find the user
        $user_id = $decoded_token->data->user_id;
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return new WP_REST_Response(array(
                'success' => false,
                'error' => 'Invalid user ID',
            ), 401);
        }

        // Generate a new access token using the existing generate_jwt function
        $new_tokens = generate_jwt($user);

        return new WP_REST_Response(array(
            'success' => true,
            'access_token' => $new_tokens['access_token'], // Return the new access token
            'refresh_token' => $new_tokens['refresh_token'], // Optionally return a new refresh token
        ), 200);
    } catch (Exception $e) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Invalid refresh token',
        ), 401);
    }
}


// Add Launch Button
/*
function add_launch_button() {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $tokens = generate_jwt($user);
        echo '<a href="https://licensing-app-0bd1e4ab8f0e.herokuapp.com/?access_token=' . $tokens['access_token'] . '&refresh_token=' . $tokens['refresh_token'] . '">Launch App</a>'; // Change this when website is hosted
    }
}

add_action('wp_footer', 'add_launch_button');
*/

// Add account ID field to user profile
function add_account_id_field($user) {
    ?>
    <h3>Account ID</h3>
    <table class="form-table">
        <tr>
            <th><label for="account_id">Account ID</label></th>
            <td>
                <input type="text" name="account_id" id="account_id" value="<?php echo esc_attr(get_the_author_meta('account_id', $user->ID)); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'add_account_id_field');
add_action('edit_user_profile', 'add_account_id_field');


 
// Save account ID field
function save_account_id_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    update_user_meta($user_id, 'account_id', $_POST['account_id']);
}
add_action('personal_options_update', 'save_account_id_field');
add_action('edit_user_profile_update', 'save_account_id_field');

// Add the user type field to the user profile page
function add_user_type_field($user) {
    // Get the current user type
    $user_type = get_user_meta($user->ID, 'user_type', true);
    ?>
    <h3>User Type</h3>
    <table class="form-table">
        <tr>
            <th><label for="user_type">User Type</label></th>
            <td>
                <select name="user_type" id="user_type" class="regular-text">
                    <option value="Admin" <?php selected($user_type, 'Admin'); ?>>Admin</option>
                    <option value="M2D" <?php selected($user_type, 'M2D'); ?>>M2D</option>
                    <option value="LER" <?php selected($user_type, 'LER'); ?>>LER</option>
                    <option value="LUK" <?php selected($user_type, 'LUK'); ?>>LUK</option>
                    <option value="LPT" <?php selected($user_type, 'LPT'); ?>>LPT</option>
                    <option value="LBR" <?php selected($user_type, 'LBR'); ?>>LBR</option>
                    <option value="WEB" <?php selected($user_type, 'WEB'); ?>>WEB</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'add_user_type_field');
add_action('edit_user_profile', 'add_user_type_field');

// Add Country field to user profile
function add_country_field($user) {
    ?>
    <h3>Country</h3>
    <table class="form-table">
        <tr>
            <th><label for="country">Country</label></th>
            <td>
                <input type="text" name="country" id="country" value="<?php echo esc_attr(get_the_author_meta('country', $user->ID)); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'add_country_field');
add_action('edit_user_profile', 'add_country_field');

// Save the Country field
function save_country_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    update_user_meta($user_id, 'country', sanitize_text_field($_POST['country']));
}
add_action('personal_options_update', 'save_country_field');
add_action('edit_user_profile_update', 'save_country_field');

// Add Country column to Users list table
function add_country_column($columns) {
    $columns['country'] = 'Country';
    return $columns;
}
add_filter('manage_users_columns', 'add_country_column');

// Populate the Country column with data
function show_country_column_content($value, $column_name, $user_id) {
    if ($column_name == 'country') {
        $country = get_user_meta($user_id, 'country', true);
        return $country ? $country : 'Not Set';
    }
    return $value;
}
add_action('manage_users_custom_column', 'show_country_column_content', 10, 3);

// Make the Country column sortable
function make_country_column_sortable($columns) {
    $columns['country'] = 'country';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'make_country_column_sortable');


// Save the user type field
function save_user_type_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    update_user_meta($user_id, 'user_type', $_POST['user_type']);
}
add_action('personal_options_update', 'save_user_type_field');
add_action('edit_user_profile_update', 'save_user_type_field');

// Assign default user type to existing users if not set
function assign_default_user_type($user_id) {
    $user_type = get_user_meta($user_id, 'user_type', true);
    if (!$user_type) {
        update_user_meta($user_id, 'user_type', 'WEB'); // Assign 'WEB' as the default user type
    }
}

// Assign default user type to all existing users on theme setup
function assign_default_user_type_to_all_users() {
    $users = get_users();
    foreach ($users as $user) {
        assign_default_user_type($user->ID);
    }
}
add_action('init', 'assign_default_user_type_to_all_users');

// Add custom column to Users list table
function add_user_type_column($columns) {
    $columns['user_type'] = 'User Type';
    return $columns;
}
add_filter('manage_users_columns', 'add_user_type_column');

// Populate the custom column with user type data
function show_user_type_column_content($value, $column_name, $user_id) {
    if ($column_name == 'user_type') {
        $user_type = get_user_meta($user_id, 'user_type', true);
        return $user_type ? $user_type : 'Not set';
    }
    return $value;
}
add_action('manage_users_custom_column', 'show_user_type_column_content', 10, 3);

// Make the custom column sortable
function make_user_type_column_sortable($columns) {
    $columns['user_type'] = 'user_type';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'make_user_type_column_sortable');

// Add Salesforce ID column to Users list table
function add_salesforce_id_column($columns) {
    $columns['salesforce_id'] = 'Salesforce ID';
    return $columns;
}
add_filter('manage_users_columns', 'add_salesforce_id_column');

// Populate the Salesforce ID column with data
function show_salesforce_id_column_content($value, $column_name, $user_id) {
    if ($column_name == 'salesforce_id') {
        $salesforce_id = get_user_meta($user_id, 'salesforce_id', true);
        return $salesforce_id ? $salesforce_id : 'Not Set';
    }
    return $value;
}
add_action('manage_users_custom_column', 'show_salesforce_id_column_content', 10, 3);

// Make the Salesforce ID column sortable (Optional)
function make_salesforce_id_column_sortable($columns) {
    $columns['salesforce_id'] = 'salesforce_id';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'make_salesforce_id_column_sortable');


// Generate unique account ID with prefix
function generate_account_id($user_id) {
    $user_type = get_user_meta($user_id, 'user_type', true);
    if ($user_type) {
        $account_id = $user_type . '_' . $user_id;
        update_user_meta($user_id, 'account_id', $account_id);
    }
}
add_action('user_register', 'generate_account_id');

// Add the user_id column to the Users list table
function add_user_id_column($columns) {
    $columns['user_id'] = 'User ID';
    return $columns;
}
add_filter('manage_users_columns', 'add_user_id_column');

function get_user_id_by_username($username) {
    // Use the get_user_by function to fetch user data by 'login' (username)
    $user = get_user_by('login', $username);
    
    // Check if the user exists
    if ($user) {
        // Return the user ID
        return $user->ID;
    } else {
        // Return null or handle the case where the user doesn't exist
        return null;
    }
}
add_action('user_username', 'get_user_id_by_username');

// Function to set the salesforce_id for a specific user
function set_salesforce_id_for_user($user_id, $salesforce_id) {
    // Add or update the salesforce_id in user meta
    return update_user_meta($user_id, 'salesforce_id', sanitize_text_field($salesforce_id));
}

// Populate the custom column with user ID data
function show_user_id_column_content($value, $column_name, $user_id) {
    if ($column_name == 'user_id') {
        return $user_id;
    }
    return $value;
}
add_action('manage_users_custom_column', 'show_user_id_column_content', 10, 3);

// Make the custom column sortable
function make_user_id_column_sortable($columns) {
    $columns['user_id'] = 'ID';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'make_user_id_column_sortable');

function authenticate_user_with_basic_auth(WP_REST_Request $request) {
    // Get the Authorization header
    $auth_header = $request->get_header('authorization');

    if (!$auth_header || stripos($auth_header, 'Basic ') !== 0) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Authorization header missing or incorrect',
        ), 403);
    }

    // Decode the Basic Auth header
    list($username, $password) = explode(':', base64_decode(substr($auth_header, 6)));

    // Authenticate the user
    $user = wp_authenticate($username, $password);

    if (is_wp_error($user)) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Invalid credentials',
        ), 403);
    }

    return $user; // or return a success message if appropriate
}



function custom_create_user_endpoint($request) {
    $secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v';
    $auth_header = $request->get_header('authorization');
    $secret_key_header = $request->get_header('x-secret-key');

    if (!$auth_header || !$secret_key_header || $secret_key_header !== $secret_key) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Unauthorized'
        ), 403);
    }
	
	// Decode the Basic Auth header to ensure user sending request is real
    list($username, $password) = explode(':', base64_decode(substr($auth_header, 6)));
    
    // Authenticate the user
    $user = wp_authenticate($username, $password);
    if (is_wp_error($user)) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Invalid credentials'
        ), 403);
    }

    $params = $request->get_json_params();
    $user_type = sanitize_text_field($params['userType']);
    $email = sanitize_email($params['email']);
    $password = sanitize_text_field($params['password']);
	$username = sanitize_text_field($params['username']);

    if (!$user_type || !$email || !$password || !$username) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Missing required fields'
        ), 400);
    }

    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'User creation failed'
        ), 500);
    }

    update_user_meta($user_id, 'user_type', $user_type);

    return new WP_REST_Response(array(
        'success' => true,
        'user' => array(
            'ID' => $user_id,
            'user_email' => $email,
            'user_type' => $user_type
        )
    ), 200);
}

function custom_delete_user_endpoint($request) {
    $secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v';
    $auth_header = $request->get_header('authorization');
    $secret_key_header = $request->get_header('x-secret-key');

    if (!$auth_header || !$secret_key_header || $secret_key_header !== $secret_key) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Unauthorized'
        ), 403);
    }
	
	// Decode the Basic Auth header to ensure user sending request is real
    list($username, $password) = explode(':', base64_decode(substr($auth_header, 6)));
    
    // Authenticate the user
    $user = wp_authenticate($username, $password);
    if (is_wp_error($user)) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Invalid credentials'
        ), 403);
    }

    $params = $request->get_json_params();
	$user_id = sanitize_text_field($params['wordpressId']);

	if (!$user_id) {
		return new WP_REST_Response(array(
            'success' => false,
            'error' => 'User ID for username not found'
        ), 400);
	}

    $response = wp_delete_user($user_id);
	
	if ($response) {
		return new WP_REST_Response(array(
        	'success' => true,
    	), 200);
	}

    return new WP_REST_Response(array(
        'success' => false,
		'error' => 'Unknown error ocurred at deletion stage'
    ), 400);
}

function custom_add_salesforce_id($request) {
	$secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v';
    $auth_header = $request->get_header('authorization');
    $secret_key_header = $request->get_header('x-secret-key');

    if (!$auth_header || !$secret_key_header || $secret_key_header !== $secret_key) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Unauthorized'
        ), 403);
    }
	
	// 	Decode the Basic Auth header to ensure user sending request is real
    list($username, $password) = explode(':', base64_decode(substr($auth_header, 6)));
    
    // Authenticate the user
    $user = wp_authenticate($username, $password);
    if (is_wp_error($user)) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Invalid credentials'
        ), 403);
    }
	
	$params = $request->get_json_params();
	$user_id = sanitize_text_field($params['wordpressId']);
	$salesforce_id = sanitize_text_field($params['salesforceId']);

    if (!$user_id || !$salesforce_id) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Missing required fields'
        ), 400);
    }
	
	// Call function to update user's salesforce_id and check if it was successful
	$update_successful = set_salesforce_id_for_user($user_id, $salesforce_id);
	
	if ($update_successful) {
		return new WP_REST_Response(array(
			'success' => true,
			'message' => 'Salesforce ID updated successfully',
		), 200);
	} else {
		return new WP_REST_Response(array(
			'success' => false,
			'error' => 'Failed to update Salesforce ID'
		), 500);
	}
}


function get_all_users($request) {
    $secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v';
    $auth_header = $request->get_header('authorization');
    $secret_key_header = $request->get_header('x-secret-key');

    if (!$auth_header || !$secret_key_header || $secret_key_header !== $secret_key) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Unauthorized'
        ), 403);
    }

    // Decode the Basic Auth header to ensure user sending request is real
    list($username, $password) = explode(':', base64_decode(substr($auth_header, 6)));

    // Authenticate the user
//     $user = wp_authenticate($username, $password);
//     if (is_wp_error($user)) {
//         return new WP_REST_Response(array(
//             'success' => false,
//             'error' => 'Invalid credentials'
//         ), 403);
//     }

    // Fetch all users
    $users_query = new WP_User_Query(array(
        'number' => -1, // -1 retrieves all users without pagination
    ));

    $users = $users_query->get_results();

    // Prepare user data
    $user_data = array();
    foreach ($users as $user) {
        $user_data[] = array(
            'ID'           => $user->ID,
            'username'     => $user->user_login,
            'email'        => $user->user_email,
            'userType'     => get_user_meta($user->ID, 'user_type', true),
            'salesforceId' => get_user_meta($user->ID, 'salesforce_id', true),
            'country'      => get_user_meta($user->ID, 'country', true), // Add Country to the response
        );
    }

    return new WP_REST_Response(array(
        'success' => true,
        'users'   => $user_data
    ), 200);
}


function get_customers_by_username(WP_REST_Request $request) {
	
	$auth_result = authenticate_user_with_basic_auth($request);

    if (is_wp_error($auth_result)) {
        return $auth_result; // Return the authentication error response
    }
	
    $username = sanitize_text_field($request->get_param('username'));

    if (!$username) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Username parameter is missing',
        ), 400);
    }

    // Use the existing function to get the user ID from the username
    $user_id = get_user_id_by_username($username);

    if (!$user_id) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'User not found',
        ), 404);
    }

    // Query for users who have this user as a customer
    $users_query = new WP_User_Query(array(
        'meta_key'   => 'customer_of',
        'meta_value' => $user_id,
    ));

    $customers = $users_query->get_results();

    if (empty($customers)) {
        return new WP_REST_Response(array(
            'success' => true,
            'users' => [], // Return an empty array if no customers are found
        ), 200);
    }

    // Prepare the response data
    $user_data = array();
    foreach ($customers as $customer) {
        $user_data[] = array(
            'ID'       => $customer->ID,
            'username' => $customer->user_login,
            'email'    => $customer->user_email,
            'user_type'=> get_user_meta($customer->ID, 'user_type', true),
			'country'=> get_user_meta($customer->ID, 'country', true),
        );
    }

    return new WP_REST_Response(array(
        'success' => true,
        'users'   => $user_data,
    ), 200);
}

function get_users_countries(WP_REST_Request $request) {
    $secret_key = 'zbaq2BRsIYN9OqCNFqU46f8EBUT6Oz0v';
    $auth_header = $request->get_header('authorization');
    $secret_key_header = $request->get_header('x-secret-key');

    // Check for Authorization and Secret Key headers
    if (!$auth_header || !$secret_key_header || $secret_key_header !== $secret_key) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Unauthorized'
        ), 403);
    }

    // Decode the Basic Auth header to ensure the user sending request is real
    list($admin_username, $admin_password) = explode(':', base64_decode(substr($auth_header, 6)));

    // Authenticate the user
    $admin_user = wp_authenticate($admin_username, $admin_password);
    if (is_wp_error($admin_user)) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'Invalid credentials'
        ), 403);
    }

    // Get the list of usernames from the request body
    $params = $request->get_json_params();
    $usernames = $params['usernames'];

    if (empty($usernames)) {
        return new WP_REST_Response(array(
            'success' => false,
            'error' => 'No usernames provided'
        ), 400);
    }

    $country_map = array();

    // Loop through the usernames and retrieve their countries
    foreach ($usernames as $username) {
        $user_id = get_user_id_by_username($username);
        if ($user_id) {
            $country = get_user_meta($user_id, 'country', true);
            $country_map[$username] = $country ? $country : 'Country not set';
        } else {
            $country_map[$username] = 'User not found';
        }
    }

    // Return the mapping of usernames to countries
    return new WP_REST_Response(array(
        'success' => true,
        'countries' => $country_map
    ), 200);
}

 




add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/createUser', array(
        'methods' => 'POST',
        'callback' => 'custom_create_user_endpoint',
        'permission_callback' => '__return_true', // We will handle authentication manually
    ));
	
	register_rest_route('custom/v1', '/deleteUser', array(
        'methods' => 'POST',
        'callback' => 'custom_delete_user_endpoint',
        'permission_callback' => '__return_true', // We will handle authentication manually
    ));
	
	register_rest_route('custom/v1', '/all-users', array(
		'methods' => 'POST',
		'callback' => 'get_all_users',
		'permission_callback' => '__return_true',
	));
	
	register_rest_route('custom/v1', '/addSalesforceId', array(
		'methods' => 'POST',
		'callback' => 'custom_add_salesforce_id',
		'permission_callback' => '__return_true', // We will handle authentication manually
	));
	
	register_rest_route('custom/v1', '/getCustomersByUsername', array(
        'methods'  => 'GET',
        'callback' => 'get_customers_by_username',
        'permission_callback' => '__return_true',
    ));
	
	register_rest_route('custom/v1', '/getUsersCountries', array(
        'methods' => 'POST',
        'callback' => 'get_users_countries',
        'permission_callback' => '__return_true', // We will handle authentication manually
    ));
	
	register_rest_route('custom/v1', '/refresh', array(
        'methods' => 'POST',
        'callback' => 'custom_refresh_jwt_token',
        'permission_callback' => '__return_true', // Handle permissions in the callback
    ));
});


 
function wpf_open_licensing_app_button() {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();   
        $tokens = generate_jwt($user);
        
        // $link = WORDPRESS_REACT_APP_HOST . '/?access_token=' . $tokens['access_token'] . '&refresh_token=' . $tokens['refresh_token'] ;
        $url = 'https://licensing-app-0bd1e4ab8f0e.herokuapp.com/?access_token=' . $tokens['access_token'] . '&refresh_token=' . $tokens['refresh_token'] ; // BEFORE
		
		   $url = 'https://licensing-manager-app-5d8bb04c0b66.herokuapp.com/?access_token=' . $tokens['access_token'] . '&refresh_token=' . $tokens['refresh_token'] ; // NEWEST
		
        // $items .= '<li><a href="'. $link .'" target="_black">Licensing</a></li>';
        echo '<style>#wpf_open_licensing_app_button { display: block; }</style>';
        echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("wpf_open_licensing_app_button").onclick = function() {
                        window.location.href = "' . $url . '";
                    };
                });
              </script>';
    } else {
        echo '<style>#wpf_open_licensing_app_button { display: none; }</style>';
    }
}
// Hook the function to wp_footer so it runs in the footer of the site
add_action('wp_footer', 'wpf_open_licensing_app_button');

# ****************************************************************

# *****************************************************************
