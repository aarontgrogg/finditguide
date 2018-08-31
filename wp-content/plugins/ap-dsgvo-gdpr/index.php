<?php
/**
 * Plugin Name: AdvantiPro - DSGVO / GDPR
 * Plugin URI: https://www.advantipro.de
 * Description: Adds features for DSGVO / GDPR.
 * Version: 1.0
 * Author: Aaron T. Grogg, AdvantiPro GmbH
 * License: GPLv2
 * Template: ap_dsgvo
 *
 * Resources:
 * https://developer.wordpress.org/reference/functions/post_exists/
 * https://developer.wordpress.org/reference/functions/wp_insert_post/
 * https://codex.wordpress.org/Function_Reference/wp_update_post
 * https://blog.strategiq.co/gdpr-compliance-with-contact-form-7-and-flamingo-what-ive-found-so-far-f0aefd0ca91a
 * http://ottopress.com/2009/wordpress-settings-api-tutorial/
 **/


/**
 * REGISTRATION SCRIPTS
 */

/*
 * Create new forms for DSGVO/GDPR information requests
 */
/*if ( ! function_exists( 'ap_dsgvo_add_information_request_forms' ) ) :
    function ap_dsgvo_add_information_request_forms() {
        // create new forms
        // pass IDs to create pages
        ap_dsgvo_add_information_request_pages( $form_ids );
    }
endif;*/

/*
 * Create new pages for DSGVO/GDPR information request forms
 */
/*if ( ! function_exists( 'ap_dsgvo_add_information_request_pages' ) ) :
    function ap_dsgvo_add_information_request_pages( $form_ids ) {
    }
endif;*/

/*
 * Update Datenschutz/Privacy Policy pages with new content,
 * including links to forms and link to Cookie Information
 */
/*if ( ! function_exists( 'ap_dsgvo_update_datenschutz_page' ) ) :
    function ap_dsgvo_update_datenschutz_page() {
    }
endif;*/

/*
 * Run these functions when the plugin is registered
 */
/*if ( ! function_exists( 'ap_dsgvo_activate' ) ) :
    function ap_dsgvo_activate($input) {
        ap_dsgvo_add_information_request_forms();
    }
endif;*/
//register_activation_hook( __FILE__, 'ap_dsgvo_activate' );



/**
 * FRONTEND SCRIPTS
 */

if ( !is_admin() ) :

    /**
     * Output empty string instead of enqueued jQuery
     */
    if ( ! function_exists( 'ap_output_empty_jquery' ) ) :
        function ap_output_empty_jquery( $tag, $handle ) {
            if ( $handle === 'jquery' || $handle === 'jquery-core' || $handle === 'jquery-migrate' ) :
                return '';
            else :
                return $tag;
            endif;
        }
    endif;
    add_action( 'script_loader_tag', 'ap_output_empty_jquery', 1, 2 );

    /**
     * Move jQuery higher in page, to appear above GA code
     */
    if ( ! function_exists( 'ap_move_jquery_higher' ) ) :
        function ap_move_jquery_higher() {
            echo '<script src="/wp-includes/js/jquery/jquery.js"></script>'.PHP_EOL
                .'<script src="/wp-includes/js/jquery/jquery-migrate.min.js"></script>'.PHP_EOL;
        }
    endif;
    add_action( 'wp_head', 'ap_move_jquery_higher', 1 );

    /**
     * Add OneTrust JS
     * Dependency: jQuery; Version: false; Load in footer: false
     * NOTE: have to use a local version of file, as remote version breaks, so after any config edits, must:
        1) download new version of script for each language
        2) Find/Replace: '$' > 'jQuery'
        3) overwrite existing onetrustConsent-NN.js file
     */
    if ( ! function_exists( 'ap_add_onetrust_script' ) ) :
        function ap_add_onetrust_script() {
            $ap_str = '';
            // pick language for cookie consent script
            $ap_lang = 'en';
            if ( defined(ICL_LANGUAGE_CODE) ) :
                $ap_lang = ICL_LANGUAGE_CODE;
            endif;
			// check if GADWP is installed
			$ap_ua_code = false;
			if ( function_exists( GADWP ) ) :
				// get this site's UA
				$gadwp = GADWP();
				$ap_ua_code = $gadwp->config->options[ga_profiles_list][0][2];
			endif;
            //wp_enqueue_script( 'ap-onetrust-script', 'https://cdn.cookielaw.org/langswitch/4dff93a9-cde1-447d-b46e-aac602d8ccc3.js', array( 'jquery' ), false, false );
            //wp_enqueue_script( 'ap-onetrust-script', plugin_dir_url( __FILE__ ) . 'onetrustConsent-'.$lang.'.js', array( 'jquery' ), false, false );
            //wp_add_inline_script( 'ap-onetrust-script', 'function OptanonWrapper(){};', 'after' );
            $ap_str .= '<script>window.$=jQuery.noConflict();</script>'.PHP_EOL;
            $ap_str .= '<script src="/wp-content/plugins/ap-dsgvo-gdpr/onetrustConsent-'.$ap_lang.'.js"></script>'.PHP_EOL;
            $ap_str .= '<script>function OptanonWrapper(){};';
            if ( $ap_ua_code ) :
				$ap_str .= 'Optanon.BlockGoogleAnalytics("'.$ap_ua_code.'",2);';
			endif;
            $ap_str .= '</script>'.PHP_EOL;
			echo $ap_str;
        }
    endif;
    //add_filter( 'wp_enqueue_scripts', 'ap_add_onetrust_script', 0 );
    add_action( 'wp_head', 'ap_add_onetrust_script', 1 );

    /**
     * Add Cookiebot JS
     * <script src="//consent.cookiebot.com/uc.js" data-cbid="ea94d4e4-714c-40c9-b80e-c93bf92ad3fd" async></script>
     */
    /*if ( ! function_exists( 'ap_add_cookiebot_script' ) ) :
        function ap_add_cookiebot_script() {
            // make sure the cookiebot ID exists
            if ( get_option('ap_dsgvo_cookiebot_id') && get_option('ap_dsgvo_cookiebot_id') !== '' ) :
                // queue the script
                wp_enqueue_script( 'ap_cookiebot_script', '//consent.cookiebot.com/uc.js' );
                // and add the listener to modify the script tag
                add_filter( 'script_loader_tag', 'ap_add_async_data_attribute', 10, 2 );
            endif;
        }
    endif;
    add_action( 'wp_enqueue_scripts', 'ap_add_cookiebot_script' );*/

    /**
     * Add async and data-attribute to Cookiebot script
     */
    /*if ( ! function_exists( 'ap_add_async_data_attribute' ) ) :
        function ap_add_async_data_attribute( $tag, $handle ) {
            if ( $handle !== 'ap_cookiebot_script' ) :
                return $tag;
            endif;
            $cbid = esc_attr( get_option('ap_dsgvo_cookiebot_id') );
            return str_replace( '></script>', ' data-cbid="'.$cbid.'" async="async"></script>', $tag );
        }
    endif;*/

    /**
     * Add category data attribute required by Cookiebot
     */
    // array of known cookies, by category, per Cookiebot
    /*$ap_dsgvo_cookies = array(
        'Necessary'    => array(
            'CookieConsent' => 'Atg',
            'SESS#',
            'wc_cart_hash_#',
            'wc_fragments_#'
        ),
        'Preferences'  => array(
            '_icl_current_language'
        ),
        'Statistics'   => array(
            '__utma',
            '__utmb',
            '__utmc',
            '__utmt',
            '__utmv',
            '__utmz',
            '_ga',
            '_gat',
            '_gid',
            '__utm.gif',
            '__qca',
            'vuid'
        ),
        'Marketing'    => array(
            '__unam',
            'GoogleAdServingTest',
            'OAID',
            'impression.php/#',
            'NID',
            'collect',
            'iutk',
            'mc',
            '__stid',
            'r/collect',
            'CONSENT'
        ),
        'Unclassified' => array(
            'OAGEO'
        ),
    );

    $_SESSION['$ap_dsgvo_cookies'] = $ap_dsgvo_cookies;*/

endif; // !is_admin



/**
 * ADMIN SCRIPTS
 */

if ( is_admin() ) :

    /**
     * Add menu item
     **/
    /*if ( ! function_exists( 'ap_dsgvo_add_menu_item' ) ) :
        function ap_dsgvo_add_menu_item() {
            //                <title>,                   Menu text,       access rights,    slug,       function to call
            add_options_page('AdvantiPro DSGVO / GDPR', 'AP DSGVO/GDPR', 'manage_options', 'ap_dsgvo', 'ap_dsgvo_create_page');
        }
    endif;*/
    //add_action( 'admin_menu', 'ap_dsgvo_add_menu_item' );

    /**
     * Output the options page
     **/
    /*if ( ! function_exists( 'ap_dsgvo_create_page' ) ) :
        function ap_dsgvo_create_page() {
            $cookieid  = get_option('ap_dsgvo_cookiebot_id');
            ?>
                <div>
                    <h1>AdvantiPro DSGVO / GDPR</h1>
                    <br>
                    <form action="/wp-admin/options.php" method="post">
                        <?php settings_fields('ap_dsgvo'); ?>
                        <?php do_settings_sections('ap_dsgvo'); ?>
                        <label for="ap_dsgvo_cookiebot_id">Enter your Cookiebot Domain Group ID:</label><br>
            <pre><?php print_r($options); ?></pre>
                        <input class="button media-button select-mode-toggle-button" id="ap_dsgvo_cookiebot_id" name="ap_dsgvo_cookiebot_id" size="45" type="text" value="<?php echo esc_attr( $cookieid ); ?>" placeholder="abcd1234-ef56-gh78-ij90-klmnop123456" />
                        <input class="button media-button select-mode-toggle-button" name="Submit" type="submit" value="<?php esc_attr_e('Save'); ?>" /><br>
                        <small>
                            <a href="https://manage.cookiebot.com/en/manage" target="_blank">Find my Cookiebot Domain Group ID</a> | 
                            <a href="https://manage.cookiebot.com/en/signup" target="_blank">Get a Cookiebot Domain Group ID</a>
                        </small><br>
                    </form>
                </div>
            <?php
        }
    endif;*/

    /**
     * Define the options page settings
     **/
    /*if ( ! function_exists( 'ap_dsgvo_create_page_settings' ) ) :
        function ap_dsgvo_create_page_settings() {
            //          settings_fields,   db variable name,        validation function name
            register_setting( 'ap_dsgvo', 'ap_dsgvo_cookiebot_id' ); // , 'ap_dsgvo_validate_settings'
        }
    endif;*/
    //add_action('admin_init', 'ap_dsgvo_create_page_settings');

    /**
     * register_setting validation function
     **/
    /*if ( ! function_exists( 'ap_dsgvo_validate_settings' ) ) :
        function ap_dsgvo_validate_settings($input) {
            if ( !$input['ap_dsgvo_cookiebot_id'] || $input['ap_dsgvo_cookiebot_id'] == '' ) :
                return false;
            endif;
            $newinput['ap_dsgvo_cookiebot_id'] = trim( sanitize_text_field( $input['ap_dsgvo_cookiebot_id'] ) );
            return $newinput;
        }
    endif;*/

endif; // is_admin

?>