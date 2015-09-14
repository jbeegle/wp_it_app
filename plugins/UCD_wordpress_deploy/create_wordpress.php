<?php
/*
Plugin Name: UCD Wordpress Deploy
Plugin URI : http://example.com
Description: Plugin to use http api to initiate an instance of wordpress [wp_create]
Version: 1.0
*/

function wp_html() {
    if ( !isset( $_POST['ucd-submitted'] ) ){
        echo '<form id= "Myform" method="post">';
        echo "<p><a href= 'http://9.98.38.243/home/actions-list-2/'>Back to Actions List<img src='http://9.98.38.243/wp-content/uploads/2015/07/back-button.png' width='50' height='50' align='left'/></a></p>";
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
function wp_deploy() {
    //if the submit button is clicked, it intiates this block of code
    if (isset($_POST['ucd-submitted'])) {

        $username = sanitize_text_field( $_POST["http-name"] );
        $password = sanitize_text_field( $_POST["http-password"] );

        $deploy_url = " https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/cli/applicationProcessRequest/request";
        $deploy_args = array(
        "method" => "PUT",
        'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password),
        'Content-Type' => "application/json"
    ),
        'body'=> '{"application": "WordPress", "applicationProcess": "deploy WordPress", "environment": "dev for Linda", "versions": [{
            "version": "latest","component": "wp_prereqs"}, {
            "version": "latest", "component": "wp_mysql"}, {
                "version": "latest","component": "wp_http"},{
                    "version": "latest","component": "wp_cms"
  }
  ]
}',
        'sslverify' => false
);
        //runs the call to ucd to create a wordpress instance
        $deploy_call = wp_remote_request($deploy_url, $deploy_args);
        //gets the response code from the call
        $response_code =  wp_remote_retrieve_response_code( $deploy_call);
        $data = json_decode($deploy_call['body'], true);
        echo "<div>";
        echo '<img class="  wp-image-321 alignnone" src="https://dal05.objectstorage.softlayer.net/v1/AUTH_7bf6dc3c-5e82-4f41-b079-44ca51d0e452/WordPress/wp-content/uploads/2015/01/4778/cloud.jpg" alt="cloud" width="179" height="112" /> <img class="  wp-image-361 alignnone" src="https://dal05.objectstorage.softlayer.net/v1/AUTH_7bf6dc3c-5e82-4f41-b079-44ca51d0e452/WordPress/wp-content/uploads/2015/01/e180/pipeline-300x74.jpg" alt="pipeline" width="454" height="112" />';
        echo "<p><a href= 'http://9.98.38.243/home/actions-list-2/'>Back to Actions List<img src='http://9.98.38.243/wp-content/uploads/2015/07/back-button.png' width='50' height='50' align='left'/></a></p>";
        if ($reponse_code = 200) {
            echo "<p><strong>Wordpress Deploy Successful!</strong></p>";
            $req_id = $data['requestId'];
            echo '<p><strong>Your Request ID: </strong>'.$req_id."</p>";
            //$status_url= 'https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/cli/applicationProcessRequest/requestStatus?request='.$req_id;
            $ucd_url ='https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/#application/fd42c4b9-7b4b-48dc-bf99-6b3816cfb14d/environments';
            echo '<CENTER><p><a href="'.$ucd_url.'" target="_blank"</a><img src="http://9.98.38.243/wp-content/uploads/2015/07/icon-colocation1.png" width ="100" height= "100"/>Check on Status of Wordpress Instance</p></CENTER>';
        }
        else {
            echo "<p>Error: Wordpress Deploy Unsuccessful</p>";
        }
        echo "</div>";

    }
}



function wp_shortcode(){
    ob_start();
    wp_deploy();
    wp_html();

    return ob_get_clean();
}

add_shortcode( 'wp_create', 'wp_shortcode' );



?>
