<?php
/*
Plugin Name: UCD Sample List Call
Plugin URI : http://example.com
Description: Plugin to use http Get call with UCD list
Version: 1.0
*/


function get_database() {
    //Hardcoded these for the demo,
    $username = "reports";
    $password = "passw0rd";

    $url = 'https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/cli/application/';
    $args = array(
    'headers' => array(
    'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password)
), 'sslverify' => false
);
    echo "<style>";
    echo "td {
        border: 3px solid #ccc;
        text-align: left;
        }
        th {
          text-align: center;
          background: lightblue;
          border-color: black;
        }
      tr:nth-child(odd){
        background: #b8d1f3;
        }
      tr:nth-child(even){
        background: #FFFAF0;
    }";
    echo "</style>";
    $json = wp_remote_get($url, $args);
    echo '<p>';
    echo '<img class="  wp-image-321 alignnone" src="https://dal05.objectstorage.softlayer.net/v1/AUTH_7bf6dc3c-5e82-4f41-b079-44ca51d0e452/WordPress/wp-content/uploads/2015/01/4778/cloud.jpg" alt="cloud" width="179" height="112" /> <img class="  wp-image-361 alignnone" src="https://dal05.objectstorage.softlayer.net/v1/AUTH_7bf6dc3c-5e82-4f41-b079-44ca51d0e452/WordPress/wp-content/uploads/2015/01/e180/pipeline-300x74.jpg" alt="pipeline" width="454" height="112" />';
    echo '<h4>Current Applications</h4>';
    echo "<a href= 'http://9.98.38.243/home/actions-list-2/'>Back to Actions List<img src='http://9.98.38.243/wp-content/uploads/2015/07/back-button.png' width='50' height='50' align='left'/></a>";
    echo "<table border= 2>";
    echo '<tr>';
    echo '<th width="10%"></th>';
    echo "<th width='30%'> NAME </th>";
    echo "<th width= '60%'> DESCRIPTION</th>";
    echo "</tr>";

    $count = 1;
    $data = json_decode($json['body'], true);

    foreach ($data as $key =>$value) {
        echo '<tr>';
        echo "<td><strong>".$count."</strong></td>";
        echo "<td><a href= 'https://rtpucd01-srv.tivlab.raleigh.ibm.com:8443/#application/".$value['id']."' target='_blank'>".$value['name']."</a></td>";
        echo "<td>".$value['description']."</td>";
        echo'</tr>';
        $count = $count + 1;
    };
    echo "</table>";
    echo '</p>';

}


function ucd_shortcode(){
    ob_start();
    get_database();

    return ob_get_clean();
}

add_shortcode( 'ucd_call_list', 'ucd_shortcode' );


?>
