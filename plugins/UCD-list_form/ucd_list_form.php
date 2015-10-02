<?php
/*
Plugin Name: UCD List Items
Plugin URI : http://example.com
Description: Plugin to output UCD list in list form.  Shortcode: [ucd_list_form]
Version: 1.0
Author: Chris Pennington
*/

function html() {
    if (!isset( $_POST['ucd-submitted'])) {
        echo '<form id="UCD_list" method="post">';
        echo '<h5>Please enter Urban Code Depoly Credentials</h5>';
        echo '<p>';
        echo 'Username (required) <br/>';
        echo '<input type="text" name="http-name" pattern="[a-zA-Z0-9@. ]+" value="' . ( isset( $_POST["http-name"] ) ? esc_attr( $_POST["http-name"] ) : '' ) . '" size="40" />';
        echo '</p>';
        echo '<p>';
        echo 'Password (required) <br/>';
        echo '<input type="password" name="http-password" value="' . ( isset( $_POST["http-password"] ) ? esc_attr( $_POST["http-password"] ) : '' ) . '" size="40" />';
        echo '</p>';
        echo '<p><input type="submit" name="ucd-submitted" value="Send"></p>';
        echo '</form>';
    }
}

function get_data() {
    //if the button is pressed, it attempts to log into UCD server to retrieve list
    if (isset($_POST['ucd-submitted'])) {
        $username = sanitize_text_field( $_POST["http-name"] );
        $password = sanitize_text_field( $_POST["http-password"] );
        $url = 'https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/cli/application/';
        $args = array(
        'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password)
    ), 'sslverify' => false
);
        $count = 0;
        $json = wp_remote_get($url, $args);
        echo "<ol>";
        $data = json_decode($json['body'], true);
        foreach($data as $key =>$value) {
            echo "<br><li><strong>Name:</strong>".$value['name']."<br> <strong>Description:</strong> ".$value['description']."</li>";
            $count = $count + 1;
        };
        echo "</ol>";
        echo "<p><strong>Total Number of Applications:</strong>".$count."</p>";
}
}
function ucd_list_shortcode(){
    ob_start();
    get_data();
    html();

    return ob_get_clean();
}

add_shortcode( 'ucd_list_form', 'ucd_list_shortcode' );


?>
