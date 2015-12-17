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

function check_error($response){
  $msg = wp_remote_retrieve_response_message ($response);
  $code = wp_remote_retrieve_response_code ($response);
  echo '<br>Code = '.$code.', '.$msg;
  switch ($code) {
    case '200':
      echo ' - Success!';
      break;
    case '201':
      $body = wp_remote_retrieve_body($response);
      echo '<br>';
      print_r ($body);
      break;
    case '401':
      echo ' - Insufficient privileges, bad credentials, or malformed request.';
      break;
    case '404':
      echo ' - Endpoint does not exist.';
      break;
    case '413':
//      echo ' - Request Entity too Large.';
      break;
    case '422':
      echo ' - No valid search predicate.';
      break;
    default: echo 'Unexpected error';}
  if ($code !== 200){
    // Print error from response body
      $body = wp_remote_retrieve_body($response);
      echo '<br>';
      print_r ($body);
  } // end $code !== 200
  echo '<br>';
  return $code;
} // end function check_error

function deploy_vm(){
  $host = 'https://vlaunch.rtp.raleigh.ibm.com/';

  // if submit button clicked, set up call
  if (isset($_POST['call-submitted'])){
    // sanitize form values
    $name = sanitize_text_field($_POST["call-name"]);
    $password = sanitize_text_field($_POST["http-password"]);
    $args = array(
      'headers' => array('Authorization' => 'Basic ' . base64_encode($name . ':' . $password)),
      'sslverify' => false
      ); // end arg definition

    echo '<b>Get token</b> ';
    // vLaunch authentication - get authorization
    $url = $host . 'api/token';
    $response = wp_remote_get($url, $args);
    $http_code = check_error ($response);
    if ($http_code == '200'){
    //  Parse API request authorization token from response body
        $body = wp_remote_retrieve_body($response);
        $auth = (explode('"', $body));
        $token = $auth[7];
        echo 'Token: '.$token.'<br>';
    } // end $http_code == '200'

    // vLaunch - create VM

    // $args[headers] [Authorization] = 'Token token=' . $token;

    // This url gets a list of the mgmt servers to which I have access
    // I will use ID=136 (visvc6A) 
    // $url = $host . 'api/v1/mgmtservers?id';
    // echo '<b>Get management servers</b> ';

    // This url gets a list of all workflows on the servers I can access
    // To Automatically Provision a New VM, use ID 387 (CreateVM) for visvc6a; ID 1 is for VISVC1
    // $url = $host . 'api/v1/workflows';
    // echo '<b>Get workflows</b> ';

    // Show request details; e.g., errors
    // $url = $host . 'api/v1/requests/178514';
    // echo '<b>Show Request</b> ';

    // This url gets a list of all workflow templates on the servers I can access
    // Using ID 151 (UB14-4-64SVR) for Ubuntu 14.04 Server 64-bit on visvc6a
    // $url = $host . 'api/v1/templates';
    // echo '<b>Get templates</b> ';

    // Get list of VM owners, filtered my email to determine my owner ID
    // Using ID 27274
    // $url = $host . 'api/v1/owners?email';
    // echo '<b>Get owners</b> ';
    // $args[body] = 'q[email_cont]=jbeegle';

    // $response = wp_remote_get($url, $args);

    // $url = $host . 'api/v1/requests'; // Create Request

    $url = $host . 'api/v1/actions/deployvm/136'; //Deploy a New VM

    echo '<b>Deploy VM</b> ';
                                    $t=time();
    $deploy_args = array(
      'headers' => array('Authorization' => 'Token token=' . $token),
      'body' => array(
        'server_id' => '136', 
        'hostname' => 'judy-test-' . time(), // make hostname unique; multiple deployments with the same name fail
        'domain' => 'rtp.raleigh.ibm.com', 
        'template' => 'UB14-4-64SVR',
        'owner_id' => '27274', 
        'cpu' => '2', 'ram' => '1', 
        'itsas-auto-patch' => 'Weekends NO reboot'),
      'sslverify' => false
      ); // end arg definition

    echo '<br>DeployArgs: ';
    var_dump ($deploy_args);
    echo '<br>';
    $response = wp_remote_post($url, $deploy_args);
    check_error ($response);
    echo 'Response: <pre>';
    print_r ($response);
    // var_dump ($response);
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
