<?php
/*
Plugin Name: Cloudant Trial1 Table
Plugin URI : http://example.com
Description: Plugin that reads the Cloudant Trial1 DB and formats it into a table. Shortcode = [trial1_table]
Version: 1.0
Author: Chris Pennington
*/

function trial1_html_form(){
    echo '<form action= ""  method="post">';
    echo '<h5> Please Enter Cloudant Credentials </h5>';
    echo '<p>';
    echo 'Username <br/>';
    echo '<input type="text" name="t1-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["t1-name"] ) ? esc_attr( $_POST["t1-name"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Password <br/>';
    echo '<input type="password" name="t1-password" value="' . ( isset( $_POST["t1-password"] ) ? esc_attr( $_POST["t1-password"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p><input type="submit" name="t1-submitted" value="Send"></p>';
    echo '</form>';
}

function trial1_get_db(){
    if (isset( $_POST['t1-submitted'])) {
        $username = sanitize_text_field( $_POST["t1-name"]);
        $password = sanitize_text_field( $_POST["t1-password"]);
        $url = "https://".$username.".cloudant.com/trial1-db/_design/design101/_view/new-view2";
        $args = array(
        'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password)
    ), 'sslverify' => false
);
        $json = wp_remote_get($url, $args);
        $data = json_decode($json['body'], true);
        $clients = $data['rows'];
        echo '<p>'.$clients.'</p>';
        //$clients = ['key'];
        //echo '<p>'.$clients.'</p>';

}
}

function trial1_shortcode(){
    ob_start();
    trial1_get_db();
    trial1_html_form();
    return ob_get_clean();
}


add_shortcode('trial1_table', 'trial1_shortcode' );
?>
