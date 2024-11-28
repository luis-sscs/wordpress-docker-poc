<?php
// Assign default user type to existing users if not set
function assign_default_user_type($user_id) {
    $user_type = get_user_meta($user_id, 'user_type', true);
    if (!$user_type) {
        update_user_meta($user_id, 'user_type', 'UNDEFINED'); // Assign 'Admin' as the default user type
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