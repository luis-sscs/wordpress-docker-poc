<?php
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

// Generate and assign account ID during user registration
function generate_account_id($user_id) {
    $account_id = 'ACC-' . strtoupper(uniqid());
    update_user_meta($user_id, 'account_id', $account_id);
}
add_action('user_register', 'generate_account_id');

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

// Save the user type field
function save_user_type_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    update_user_meta($user_id, 'user_type', $_POST['user_type']);
}
add_action('personal_options_update', 'save_user_type_field');
add_action('edit_user_profile_update', 'save_user_type_field');

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