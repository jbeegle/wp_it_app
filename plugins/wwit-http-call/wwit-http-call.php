<?php
/*
Plugin Name: WWIT HTTP Call Plugin
Plugin URI: http://example.com
Description: Simple HTTP Call for WW IT Resources and Services
Version: 2.0
Author: ITSM WW IT
Author URI: http://w3guy.com
// */
function html_http_code() {
if ( !isset( $_POST['http-submitted'] ) ){
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Your Name (required) <br/>';
    echo '<input type="text" name="http-name" pattern="[a-zA-Z0-9@. ]+" value="' . ( isset( $_POST["http-name"] ) ? esc_attr( $_POST["http-name"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Password (required) <br/>';
    echo '<input type="password" name="http-password" value="' . ( isset( $_POST["http-password"] ) ? esc_attr( $_POST["http-password"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Email (required) <br/>';
    echo '<input type="email" name="http-email" value="' . ( isset( $_POST["http-email"] )? esc_attr( $_POST["http-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Application (required) <br/>';
    echo '<input type="text" name="http-application" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["http-application"] ) ? esc_attr( $_POST["http-aplication"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p><input type="submit" name="http-submitted" value="Send"></p>';
    echo '</form>';
   }
}
 
function http_call() {
 
    // if the submit button is clicked, send the email
    if ( isset( $_POST['http-submitted'] ) ) {
 
        // sanitize form values
        $name    = sanitize_text_field( $_POST["http-name"] );
        $email   = sanitize_email( $_POST["http-email"] );
        $application = sanitize_text_field( $_POST["http-application"] );
        $password = $_POST["http-password"];

//        $DispForm = False;

        // setup the http call

/* UCD test site
$url = 'https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/cli/application/';
*/

/* vLaunch test site
$url = 'https://vlaunch.rtp.raleigh.ibm.com/groups';
*/

$url = 'https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/cli/application/';
$args = array(
    'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $name . ':' . $password)
    ), 'sslverify' => false
);
  $response = wp_remote_get( $url, $args );
  print_r( $response  );

  $response_code =  wp_remote_retrieve_response_code( $response );
 
  print_r( $response_code  );
        
        // If http has been processed, display a success message
        if ( $response_code == '200' ) {
            echo '<div>';
            echo '<p>http call successful, result is:</p>';
            echo '</div>';
 
 $body = wp_remote_retrieve_body( $response );
      	 print_r( $response_code  );
 
        } else {
            echo 'An unexpected error occurred';
        }
    }
}
 
function http_shortcode() {
    ob_start();
    http_call();
    html_http_code();
    
    return ob_get_clean();
}
 
add_shortcode( 'wwit_http_call', 'http_shortcode' );
 
?>