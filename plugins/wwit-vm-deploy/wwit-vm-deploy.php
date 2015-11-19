<?php
/*
Plugin Name: WWIT Deploy VM
Plugin URI: http://example.com
Description: Use http API to create a VM; shortcode = [deploy_vm_shortcode]
Version: 1.0
Author: ITSM WW IT
Author URI: http://w3guy.com
*/

function deploy_vm_form() {
  echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
  echo '<p>';
  echo 'VM Owner (required) <br/>';
  echo '<input type="text" name="call-name" pattern="[a-zA-Z0-9@. ]+" value="' . (isset($_POST["call-name"]) ? esc_attr($_POST["call-name"]) : '') . '" size="40" />';
  echo '</p>';
  echo '<p>';
  echo 'Your Password (required) <br/>';
  echo '<input type="password" name="http-password" value="' . (isset($_POST["http-password"]) ? esc_attr($_POST["http-password"]) : '') . '" size="40" />';
  echo '</p>';
  echo '<p><input type="submit" name="call-submitted" value="Send"></p>';
  echo '</form>';
} // end function deploy_vm_form()

function deploy_vm(){
  // if submit button clicked, create a vm
  if (isset($_POST['call-submitted'])){
    // sanitize form values
    $name = sanitize_text_field($_POST["call-name"]);
    $password = sanitize_text_field($_POST["http-password"]);

    // vLaunch test site - list groups
    
    $url = 'https://vlaunch.rtp.raleigh.ibm.com/groups';
    $args = array(
      'headers' => array('Authorization' => 'Basic ' . base64_encode($name . ':' . $password)),
      'sslverify' => false
      ); // end arg definition
    $response = wp_remote_get($url, $args);
   

    // vLaunch - create VM
    /*
    $url = 'https://vlaunch.rtp.raleigh.ibm.com/newrequests/RTP/1/createVM';
    $args = array(
      'headers' => array('Authorization' => 'Basic ' . base64_encode($name . ':' . $password)),
      'sslverify' => false
    $response = wp_remote_get($url, $args);
    */
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
    echo 'Body: <pre>';
    print_r ($body);
    echo '</pre>';
  } //end (isset( $_POST['call-submitted'] ) )
} // end function deploy_vm

function deploy_vm_shortcode() {
  ob_start();
  deploy_vm();
  deploy_vm_form();
  return ob_get_clean();
}
add_shortcode( 'wwit-deploy-vm', 'deploy_vm_shortcode' );
?>
