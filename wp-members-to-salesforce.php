<?php
/*
Plugin Name: WP-Members to Salesforce
Description: Save your leads to SalesForce
Version: 1.0
*/

// run our function when the wpmem_pre_register_data action hook from the WP-Members plugin is activated
add_action( 'wpmem_pre_register_data', 'mem_save_sf_lead', 1 );

// our function to send the reg information to Salesforce, takes $fields from reg form as an argument
function mem_save_sf_lead( $fields ) {
	// create an array of information to send
	$post = array();
        //translate the submitted reg info into SalesForce format
        $post['email']   = $fields['user_email']; 
        $post['first_name'] = $fields['first_name'];
        $post['last_name'] = $fields['last_name'];
        $post['phone'] = $fields['phone1'];
        $post['address'] = $fields['addr1'];
        $post['oid']             = '000000000000000'; //set your OID number here
        $post['lead_source']    = get_bloginfo('url'); //change the lead source if you don't want it to be the url of your site
        $post['debug']            = 0;

        // Put together our body and headers for posting to Salesforce. Set SSL verify to false because of server issues.
        $args = array(     
            'body'         => $post,
            'headers'     => array(
                'user-agent' => 'WP-Members to Salesforce plugin - WordPress; '. get_bloginfo('url')
            ),
            'sslverify'    => false,  
        );

		// Use wp_remote_post to send lead to Salesforce
        $result = wp_remote_post('https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8', $args);
}

?>