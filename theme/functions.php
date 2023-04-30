add_action( 'rest_api_init', function() {
register_rest_route( 'myapi/v1', '/users/signup', array(
'methods' => 'POST',
'callback' => 'myapi_user_signup',
) );

register_rest_route( 'myapi/v1', '/users/login', array(
'methods' => 'POST',
'callback' => 'myapi_user_login',
) );

register_rest_route( 'myapi/v1', '/users/social-login', array(
'methods' => 'POST',
'callback' => 'myapi_user_social_login',
) );

register_rest_route( 'myapi/v1', '/users/forgot-password', array(
'methods' => 'POST',
'callback' => 'myapi_user_forgot_password',
) );

register_rest_route( 'myapi/v1', '/users/profile', array(
'methods' => 'GET',
'callback' => 'myapi_get_user_profile',
'permission_callback' => function () {
return is_user_logged_in();
},
) );

register_rest_route( 'myapi/v1', '/users/profile', array(
'methods' => 'PUT',
'callback' => 'myapi_update_user_profile',
'permission_callback' => function () {
return is_user_logged_in();
},
) );

register_rest_route( 'myapi/v1', '/users/profile', array(
'methods' => 'DELETE',
'callback' => 'myapi_delete_user_profile',
'permission_callback' => function () {
return is_user_logged_in();
},
) );
} );

function myapi_user_signup( $request ) {
$username = sanitize_text_field( $request->get_param( 'username' ) );
$email = sanitize_email( $request->get_param( 'email' ) );
$password = $request->get_param( 'password' );

$user_id = wp_create_user( $username, $password, $email );

if ( is_wp_error( $user_id ) ) {
return new WP_Error( 'user_signup_error', $user_id->get_error_message() );
}

return array( 'user_id' => $user_id,'email' => $email,'username' => $username,'massage'=> 'create successfully');
}
function myapi_user_login( $request ) {
$username = sanitize_text_field( $request->get_param( 'username' ) );
$password = $request->get_param( 'password' );

$user = wp_authenticate( $username, $password );

if ( is_wp_error( $user ) ) {
return new WP_Error( 'user_login_error', $user->get_error_message() );
}

wp_set_current_user( $user->ID );
wp_set_auth_cookie( $user->ID );

return array( 'user_id' => $user->ID );
}
function myapi_user_social_login( $request ) {
$provider = $request->get_param( 'provider' );
$access_token = $request->get_param( 'access_token' );

// Handle social media login using appropriate provider API
// Return user ID on successful login
}
function myapi_user_forgot_password( $request ) {
$user_login = $request->get_param( 'user_login' );
$user_data = get_user_by( 'login', $user_login );

if ( ! $user_data ) {
return new WP_Error( 'user_not_found', __( 'Invalid username or email address.', 'my-text-domain' ) );
}

$user_login = $user_data->user_login;
$user_email = $user_data->user_email;
$key = get_password_reset_key( $user_data );

// Send password reset email to the user
}
function myapi_get_user_profile( $request ) {
$user_id = get_current_user_id();
$user = get_user_by( 'id', $user_id );

$user_data = array(
'user_id' => $user_id,
'username' => $user->user_login,
'email' => $user->user_email,
'display_name' => $user->display_name,
// Add other user data fields as required
);

return $user_data;
}

function myapi_update_user_profile( $request ) {
$user_id = get_current_user_id();
$user = get_user_by( 'id', $user_id );

$username = sanitize_text_field( $request->get_param( 'username' ) );
$email = sanitize_email( $request->get_param( 'email' ) );
$display_name = sanitize_text_field( $request->get_param( 'display_name' ) );

// Validate fields
if ( empty( $username ) || empty( $email ) || empty( $display_name ) ) {
return new WP_Error( 'update_error', __( 'All fields are required.', 'my-text-domain' ) );
}

if ( ! is_email( $email ) ) {
return new WP_Error( 'update_error', __( 'Invalid email address.', 'my-text-domain' ) );
}

// Check if the username or email address is already in use by another user
$existing_user = get_user_by( 'login', $username );
if ( $existing_user && $existing_user->ID != $user_id ) {
return new WP_Error( 'update_error', __( 'Username already exists.', 'my-text-domain' ) );
}

$existing_user = get_user_by( 'email', $email );
if ( $existing_user && $existing_user->ID != $user_id ) {
return new WP_Error( 'update_error', __( 'Email address already in use.', 'my-text-domain' ) );
}

$update_args = array(
'ID' => $user_id,
'user_login' => $username,
'user_email' => $email,
'display_name' => $display_name,
// Add other user data fields as required
);

$updated = wp_update_user( $update_args );

if ( is_wp_error( $updated ) ) {
return new WP_Error( 'update_error', $updated->get_error_message() );
}

return array( 'message' => 'User profile updated successfully.' );
}