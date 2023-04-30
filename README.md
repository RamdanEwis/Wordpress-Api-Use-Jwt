# Wordpress-Api-Use-Jwt


#install this plugin JWT Authentication for WP-API
Extends the WP REST API using JSON Web Tokens Authentication as an authentication method.
 | By Enrique Chavez | 
 
 
#PHP HTTP Authorization Header enable
Most of the shared hosting has disabled the HTTP Authorization Header by default.

To enable this option you’ll need to edit your .htaccess file adding the follow

RewriteEngine on
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule ^(.*) - [E=HTTP_AUTHORIZATION:%1]


WPENGINE

To enable this option you’ll need to edit your .htaccess file adding the follow

See https://github.com/Tmeister/wp-api-jwt-auth/issues/1

echo this ==>   SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1


#Configurate the Secret Key
The JWT needs a secret key to sign the token this secret key must be unique and never revealed.

To add the secret key edit your wp-config.php file and add a new constant called JWT_AUTH_SECRET_KEY

define('JWT_AUTH_SECRET_KEY', 'your-top-secret-key');
You can use a string from here https://api.wordpress.org/secret-key/1.1/salt/


#Configurate CORs Support
The wp-api-jwt-auth plugin has the option to activate CORs support.

To enable the CORs Support edit your wp-config.php file and add a new constant called JWT_AUTH_CORS_ENABLE

define('JWT_AUTH_CORS_ENABLE', true);
Endpoint | HTTP Verb
/wp-json/jwt-auth/v1/token | POST
/wp-json/jwt-auth/v1/token/validate | POST

#user this for posts website/wp-json/wp/v2/posts


#use this url for woocommerce products
https://woocommerce.github.io/woocommerce-rest-api-docs/?php#
