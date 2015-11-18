<?php
/*
Plugin Name: WWIT Call
Plugin URI: http://example.com
Description: Simple http call
Version: 1.0
Author: ITSM WW IT
Author URI: http://w3guy.com
*/

function call_form() {
  echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
  echo '<p>';
  echo 'Your Name (required) <br/>';
  echo '<input type="text" name="call-name" pattern="[a-zA-Z0-9@. ]+" value="' . (isset($_POST["call-name"]) ? esc_attr($_POST["call-name"]) : '') . '" size="40" />';
  echo '</p>';
  echo '<p>';
  echo 'Your Password (required) <br/>';
  echo '<input type="password" name="http-password" value="' . (isset($_POST["http-password"]) ? esc_attr($_POST["http-password"]) : '') . '" size="40" />';
  echo '</p>';
  echo '<p>';
  echo 'Your Email (required) <br/>';
  echo '<input type="email" name="call-email" value="' . (isset($_POST["call-email"]) ? esc_attr($_POST["call-email"]) : '') . '" size="40" />';
  echo '</p>';
  echo '<p>';
  echo 'Application (required) <br/>';
  echo '<input type="text" name="call-application" pattern="[a-zA-Z ]+" value="' . (isset($_POST["call-application"]) ? esc_attr($_POST["call-application"]) : '') . '" size="40" />';
  echo '</p>';
  echo '<p><input type="submit" name="call-submitted" value="Send"></p>';
  echo '</form>';
} // end function call_form()
 
function call_http(){
  // if submit button clicked, make the call
  if (isset($_POST['call-submitted'])){
    // sanitize form values
    $name = sanitize_text_field($_POST["call-name"]);
    $email = sanitize_email($_POST["call-email"]);
    $application = sanitize_text_field($_POST["call-application"]);
    $password = sanitize_text_field($_POST["http-password"]);

    // UCD test site
    // $url = 'https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/cli/application/';

    // vLaunch test site
    $url = 'https://vlaunch.rtp.raleigh.ibm.com/groups';

    // vLaunch create VM
    // $url = 'https://vlaunch.rtp.raleigh.ibm.com/newrequests/RTP/1/createVM';

    $args = array(
      'headers' => array('Authorization' => 'Basic ' . base64_encode($name . ':' . $password)),
      'sslverify' => false
      ); // end arg definition

    $response = wp_remote_get($url, $args);
    $msg = wp_remote_retrieve_response_message ($response);
    // If not empty, display message
    if (! empty($msg)){
      echo 'Message: <pre>';
      print_r ($msg);
      echo '</pre>';
      }
    $http_code = wp_remote_retrieve_response_code ($response);
    if (! empty($http_code)){
      echo 'Code: <pre>';
      print_r ($http_code);
      echo '</pre>';
      }
    // Display result
    if ( $http_code == '200' ) {
      echo '<p>Success!</p>';
      } else { echo '<p>An unexpected error occurred</p>'; }

    echo 'Response: <pre>';
    print_r ($response);
    // var_dump ($response);
    echo '</pre>';

    $body = wp_remote_retrieve_body($response);
    echo '<p>Body:' . $body . '</p>';
  } // end isset($_POST['call-submitted'])
} // end function call_http

function call_shortcode() {
  ob_start();
  call_http();
  call_form();
  return ob_get_clean();
}
add_shortcode( 'wwit-call', 'call_shortcode' );
?>
