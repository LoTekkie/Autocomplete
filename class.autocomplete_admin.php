<?php

class AutoComplete_Admin {
    const NONCE = 'autocomplete-update-key';

    private static $initiated = false;
    private static $notices   = array();

    public static function init() {
        if ( ! self::$initiated ) {
            self::init_hooks();
        }

        if ( isset( $_POST['action'] ) && $_POST['action'] == 'enter-key' ) {
            self::enter_api_key();
        }
    }

    public static function init_hooks() {
        self::$initiated = true;

        add_action( 'admin_init', array( 'AutoComplete_Admin', 'admin_init' ) );
        add_action( 'admin_menu', array( 'AutoComplete_Admin', 'admin_menu' ), 5 );
        add_action( 'admin_notices', array( 'AutoComplete_Admin', 'display_notice' ) );
        add_action( 'admin_enqueue_scripts', array( 'AutoComplete_Admin', 'load_resources' ) );

        add_filter( 'plugin_action_links', array( 'AutoComplete_Admin', 'plugin_action_links' ), 10, 2 );

        add_filter( 'plugin_action_links_'.plugin_basename( plugin_dir_path( __FILE__ ) . 'autocomplete.php'), array( 'AutoComplete_Admin', 'admin_plugin_settings_link' ) );

        add_filter( 'all_plugins', array( 'AutoComplete_Admin', 'modify_plugin_description' ) );
    }

    public static function admin_init() {
        if ( get_option( 'activated_autocomplete' ) ) {
            delete_option( 'activated_autocomplete' );
            if ( ! headers_sent() ) {
                wp_redirect( add_query_arg( array( 'page' => 'autocomplete-key-config', 'view' => 'start' ) ) );
            }
        }

        load_plugin_textdomain( 'autocomplete' );
    }

    public static function admin_menu() {
        self::load_menu();
    }

    public static function admin_head() {
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
    }

    public static function admin_plugin_settings_link( $links ) {
        $settings_link = '<a href="'.esc_url( self::get_page_url() ).'">'.__('Settings', 'autocomplete').'</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    public static function load_menu() {
        $hook = add_options_page( __('autocomplete', 'autocomplete'), __('autocomplete', 'autocomplete'), 'manage_options', 'autocomplete-key-config', array( 'AutoComplete_Admin', 'display_page' ) );
        if ( $hook ) {
            add_action( "load-$hook", array( 'AutoComplete_Admin', 'admin_help' ) );
        }
    }

    public static function load_resources() {
        global $hook_suffix;


        if ( in_array( $hook_suffix, apply_filters( 'autocomplete_admin_page_hook_suffixes', array(
            'index.php', # dashboard
            'post.php',
            'settings_page_autocomplete-key-config',
            'plugins.php',
        ) ) ) ) {
            wp_register_style( 'autocomplete.css', plugin_dir_url( __FILE__ ) . '_inc/autocomplete.css', array(), AUTOCOMPLETE_VERSION );
            wp_enqueue_style( 'autocomplete.css');

            wp_register_script( 'autocomplete.js', plugin_dir_url( __FILE__ ) . '_inc/autocomplete.js', array('jquery'), AUTOCOMPLETE_VERSION );
            wp_enqueue_script( 'autocomplete.js' );
        }
    }

    /**
     * Add help to the autocomplete page
     *
     * @return false if not the autocomplete page
     */
    public static function admin_help() {
        $current_screen = get_current_screen();

        // Screen Content
        if ( current_user_can( 'manage_options' ) ) {
            if ( !AutoComplete::get_api_key() || ( isset( $_GET['view'] ) && $_GET['view'] == 'start' ) ) {
                //setup page
                $current_screen->add_help_tab(
                    array(
                        'id'		=> 'overview',
                        'title'		=> __( 'Overview' , 'autocomplete'),
                        'content'	=>
                            '<p><strong>' . esc_html__( 'Autocomplete Setup' , 'autocomplete') . '</strong></p>' .
                            '<p>' . esc_html__( constant("AUTOCOMPLETE_DESCRIPTION"), 'autocomplete') . '</p>' .
                            '<p>' . esc_html__( 'On this page, you are able to set up the AutoComplete plugin.' , 'autocomplete') . '</p>',
                    )
                );

                $current_screen->add_help_tab(
                    array(
                        'id'		=> 'setup-api-key',
                        'title'		=> __( 'New to AutoComplete' , 'autocomplete'),
                        'content'	=>
                            '<p><strong>' . esc_html__( 'AutoComplete Setup' , 'autocomplete') . '</strong></p>' .
                            '<p>' . esc_html__( 'You need to enter an Autocomplete API key to make use of this plugin on your site.' , 'autocomplete') . '</p>' .
                            '<p>' . sprintf( __( 'Sign up for an account on %s to get an API Key.' , 'autocomplete'), '<a href="' . autocomplete_url() . '" target="_blank">' .autocomplete_url().'</a>' ) . '</p>',
                    )
                );

                $current_screen->add_help_tab(
                    array(
                        'id'		=> 'setup-manual',
                        'title'		=> __( 'Enter an API Key' , 'autocomplete'),
                        'content'	=>
                            '<p><strong>' . esc_html__( 'AutoComplete Setup' , 'autocomplete') . '</strong></p>' .
                            '<p>' . esc_html__( 'If you already have an API key' , 'autocomplete') . '</p>' .
                            '<ol>' .
                            '<li>' . esc_html__( 'Copy and paste the API key into the text field.' , 'autocomplete') . '</li>' .
                            '<li>' . esc_html__( 'Click the Use this Key button.' , 'autocomplete') . '</li>' .
                            '</ol>',
                    )
                );
            }

            else {
                //configuration page
                $current_screen->add_help_tab(
                    array(
                        'id'		=> 'overview',
                        'title'		=> __( 'Overview' , 'autocomplete'),
                        'content'	=>
                            '<p><strong>' . esc_html__( 'AutoComplete Configuration' , 'autocomplete') . '</strong></p>' .
                            '<p>' . esc_html__( constant('AUTOCOMPLETE_DESCRIPTION') , 'autocomplete') . '</p>' .
                            '<p>' . esc_html__( 'On this page, you are able to update your Autocomplete API Key.' , 'autocomplete') . '</p>',
                    )
                );

                $current_screen->add_help_tab(
                    array(
                        'id'		=> 'settings',
                        'title'		=> __( 'Settings' , 'autocomplete'),
                        'content'	=>
                            '<p><strong>' . esc_html__( 'AutoComplete Configuration' , 'autocomplete') . '</strong></p>' .
                            ( AutoComplete::predefined_api_key() ? '' : '<p><strong>' . esc_html__( 'AutoComplete API Key' , 'autocomplete') . '</strong> - ' . esc_html__( 'Enter/Delete an API Key.' , 'autocomplete') . '</p>' )
                    )
                );
            }
        }

        $current_screen->set_help_sidebar(
            '<p><strong>' . esc_html__( 'For more information:' , 'autocomplete') . '</strong></p>' .
            '<p><a href="' . autocomplete_url('documentation#faq') . '" target="_blank">'     . esc_html__( 'AutoComplete FAQ' , 'autocomplete') . '</a></p>' .
            '<p><a href="mailto:' . constant('AUTOCOMPLETE_EMAIL_SUPPORT') .'" target="_blank">' . esc_html__( 'AutoComplete Support' , 'autocomplete') . '</a></p>'
        );
    }

    public static function enter_api_key() {
        if ( ! current_user_can( 'manage_options' ) ) {
            die( __( "Ah ah ah, you didn't say the magic word...", 'autocomplete' ) );
        }

        if ( !wp_verify_nonce( $_POST['_wpnonce'], self::NONCE ) )
            return false;

        if ( AutoComplete::predefined_api_key() ) {
            return false; //shouldn't have option to save key if already defined
        }

        $new_key = preg_replace( '/[^a-f0-9]/i', '', $_POST['key'] );
        $old_key = AutoComplete::get_api_key();

        if ( empty( $new_key ) ) {
            if ( !empty( $old_key ) ) {
                delete_option( 'autocomplete_api_key' );
                self::$notices[] = 'new-key-empty';
            }
        }
        elseif ( $new_key != $old_key ) {
            self::save_key( $new_key );
        }

        return true;
    }

    public static function save_key( $api_key ) {
        $key_status = AutoComplete::verify_key( $api_key );

        if ( $key_status == 'valid' ) {
            update_option( 'autocomplete_api_key', $api_key );
            self::$notices['status'] = 'new-key-valid';
        }
        elseif ( in_array( $key_status, array( 'invalid', 'failed' ) ) ) {
            self::$notices['status'] = 'new-key-'.$key_status;
        }
    }

    public static function recheck_queue_portion( $start = 0, $limit = 100 ) {
        global $wpdb;

        $paginate = '';

        if ( $limit <= 0 ) {
            $limit = 100;
        }

        if ( $start < 0 ) {
            $start = 0;
        }

        $moderation = $wpdb->get_col( $wpdb->prepare( "SELECT * FROM {$wpdb->comments} WHERE comment_approved = '0' LIMIT %d OFFSET %d", $limit, $start ) );

        $result_counts = array(
            'processed' => count( $moderation ),
            'spam' => 0,
            'ham' => 0,
            'error' => 0,
        );

        foreach ( $moderation as $comment_id ) {
            $api_response = AutoComplete::recheck_comment( $comment_id, 'recheck_queue' );

            if ( 'true' === $api_response ) {
                ++$result_counts['spam'];
            }
            elseif ( 'false' === $api_response ) {
                ++$result_counts['ham'];
            }
            else {
                ++$result_counts['error'];
            }
        }

        return $result_counts;
    }

    public static function plugin_action_links( $links, $file ) {
        if ( $file == plugin_basename( plugin_dir_url( __FILE__ ) . '/autocomplete.php' ) ) {
            $links[] = '<a href="' . esc_url( self::get_page_url() ) . '">'.esc_html__( 'Settings' , 'autocomplete').'</a>';
        }

        return $links;
    }

    // Check connectivity between the WordPress blog and autocomplete's servers.
    // Returns an associative array of server IP addresses, where the key is the IP address, and value is true (available) or false (unable to connect).
    public static function check_server_ip_connectivity() {

        $servers = $ips = array();

        // Some web hosts may disable this function
        if ( function_exists('gethostbynamel') ) {

            $ips = gethostbynamel( 'rest.autocomplete.com' );
            if ( $ips && is_array($ips) && count($ips) ) {
                $api_key = AutoComplete::get_api_key();

                foreach ( $ips as $ip ) {
                    $response = AutoComplete::verify_key( $api_key, $ip );
                    // even if the key is invalid, at least we know we have connectivity
                    if ( $response == 'valid' || $response == 'invalid' )
                        $servers[$ip] = 'connected';
                    else
                        $servers[$ip] = $response ? $response : 'unable to connect';
                }
            }
        }

        return $servers;
    }

    // Simpler connectivity check
    public static function check_server_connectivity($cache_timeout = 86400) {

        $debug = array();
        $debug[ 'PHP_VERSION' ]         = PHP_VERSION;
        $debug[ 'WORDPRESS_VERSION' ]   = $GLOBALS['wp_version'];
        $debug[ 'AKISMET_VERSION' ]     = AKISMET_VERSION;
        $debug[ 'AKISMET__PLUGIN_DIR' ] = AKISMET__PLUGIN_DIR;
        $debug[ 'SITE_URL' ]            = site_url();
        $debug[ 'HOME_URL' ]            = home_url();

        $servers = get_option('autocomplete_available_servers');
        if ( (time() - get_option('autocomplete_connectivity_time') < $cache_timeout) && $servers !== false ) {
            $servers = self::check_server_ip_connectivity();
            update_option('autocomplete_available_servers', $servers);
            update_option('autocomplete_connectivity_time', time());
        }

        if ( wp_http_supports( array( 'ssl' ) ) ) {
            $response = wp_remote_get( 'https://rest.autocomplete.com/1.1/test' );
        }
        else {
            $response = wp_remote_get( 'http://rest.autocomplete.com/1.1/test' );
        }

        $debug[ 'gethostbynamel' ]  = function_exists('gethostbynamel') ? 'exists' : 'not here';
        $debug[ 'Servers' ]         = $servers;
        $debug[ 'Test Connection' ] = $response;

        AutoComplete::log( $debug );

        if ( $response && 'connected' == wp_remote_retrieve_body( $response ) )
            return true;

        return false;
    }

    // Check the server connectivity and store the available servers in an option.
    public static function get_server_connectivity($cache_timeout = 86400) {
        return self::check_server_connectivity( $cache_timeout );
    }

    public static function get_page_url( $page = 'config' ) {

        $args = array( 'page' => 'autocomplete-key-config' );

        if ( $page == 'delete_key' ) {
            $args = array('page' => 'autocomplete-key-config', 'view' => 'start', 'action' => 'delete-key', '_wpnonce' => wp_create_nonce(self::NONCE));
        }

        return add_query_arg( $args, class_exists( 'Jetpack' ) ? admin_url( 'admin.php' ) : admin_url( 'options-general.php' ) );
    }

    public static function display_alert() {
        AutoComplete::view( 'notice', array(
            'type' => 'alert',
            'code' => (int) get_option( 'autocomplete_alert_code' ),
            'msg'  => get_option( 'autocomplete_alert_msg' )
        ) );
    }

    public static function display_api_key_warning() {
        AutoComplete::view( 'notice', array( 'type' => 'plugin' ) );
    }

    public static function display_page() {
        if ( !AutoComplete::get_api_key() || ( isset( $_GET['view'] ) && $_GET['view'] == 'start' ) ) {
            self::display_start_page();
        } else {
            self::display_configuration_page();
        }
    }

    public static function display_start_page() {
        if ( isset( $_GET['action'] ) ) {
            if ( $_GET['action'] == 'delete-key' ) {
                if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], self::NONCE ) )
                    delete_option( 'autocomplete_api_key' );
            }
        }

        if ( $api_key = AutoComplete::get_api_key() && ( empty( self::$notices['status'] ) || 'existing-key-invalid' != self::$notices['status'] ) ) {
            self::display_configuration_page();
            return;
        }

        AutoComplete::view( 'start', array('notices' => self::$notices));

        /*
        // To see all variants when testing.
        $autocomplete_user->status = 'no-sub';
        AutoComplete::view( 'start', compact( 'autocomplete_user' ) );
        $autocomplete_user->status = 'cancelled';
        AutoComplete::view( 'start', compact( 'autocomplete_user' ) );
        $autocomplete_user->status = 'suspended';
        AutoComplete::view( 'start', compact( 'autocomplete_user' ) );
        $autocomplete_user->status = 'other';
        AutoComplete::view( 'start', compact( 'autocomplete_user' ) );
        $autocomplete_user = false;
        */
    }

    public static function display_configuration_page() {
        $api_key = AutoComplete::get_api_key();

        AutoComplete::view( 'config', ['api_key' => $api_key, 'notices' => self::$notices]);
    }

    public static function display_notice() {
        global $hook_suffix;

        if ( in_array( $hook_suffix, array( 'jetpack_page_autocomplete-key-config', 'settings_page_autocomplete-key-config' ) ) ) {
            // This page manages the notices and puts them inline where they make sense.
            return;
        }

        if ( in_array( $hook_suffix, array( 'edit-comments.php' ) ) && (int) get_option( 'autocomplete_alert_code' ) > 0 ) {
            AutoComplete::verify_key( AutoComplete::get_api_key() ); //verify that the key is still in alert state

            $alert_code = get_option( 'autocomplete_alert_code' );
            if ( isset( AutoComplete::$limit_notices[ $alert_code ] ) ) {
                self::display_usage_limit_alert();
            } elseif ( $alert_code > 0 ) {
                self::display_alert();
            }
        }
        elseif ( ( 'plugins.php' === $hook_suffix || 'edit-comments.php' === $hook_suffix ) && ! AutoComplete::get_api_key() ) {
            // Show the "Set Up autocomplete" banner on the comments and plugin pages if no API key has been set.
            self::display_api_key_warning();
        }
        elseif ( $hook_suffix == 'edit-comments.php' && wp_next_scheduled( 'autocomplete_schedule_cron_recheck' ) ) {
            self::display_spam_check_warning();
        }

        if ( isset( $_GET['autocomplete_recheck_complete'] ) ) {
            $recheck_count = (int) $_GET['recheck_count'];
            $spam_count = (int) $_GET['spam_count'];

            if ( $recheck_count === 0 ) {
                $message = __( 'There were no comments to check. autocomplete will only check comments awaiting moderation.', 'autocomplete' );
            }
            else {
                $message = sprintf( _n( 'autocomplete checked %s comment.', 'autocomplete checked %s comments.', $recheck_count, 'autocomplete' ), number_format( $recheck_count ) );
                $message .= ' ';

                if ( $spam_count === 0 ) {
                    $message .= __( 'No comments were caught as spam.', 'autocomplete' );
                }
                else {
                    $message .= sprintf( _n( '%s comment was caught as spam.', '%s comments were caught as spam.', $spam_count, 'autocomplete' ), number_format( $spam_count ) );
                }
            }

            echo '<div class="notice notice-success"><p>' . esc_html( $message ) . '</p></div>';
        }
        else if ( isset( $_GET['autocomplete_recheck_error'] ) ) {
            echo '<div class="notice notice-error"><p>' . esc_html( __( 'autocomplete could not recheck your comments for spam.', 'autocomplete' ) ) . '</p></div>';
        }
    }

    /**
     * When autocomplete is active, remove the "Activate autocomplete" step from the plugin description.
     */
    public static function modify_plugin_description( $all_plugins ) {
        if ( isset( $all_plugins['autocomplete/autocomplete.php'] ) ) {
            if ( AutoComplete::get_api_key() ) {
                $all_plugins['autocomplete/autocomplete.php']['Description'] = __( 'Welcome to autocomplete!', 'autocomplete' );
            }
            else {
                $all_plugins['autocomplete/autocomplete.php']['Description'] = __( 'Welcome to autocomplete! To get started, just go to <a href="admin.php?page=autocomplete-key-config">your autocomplete Settings page</a> to set up your API key.', 'autocomplete' );
            }
        }

        return $all_plugins;
    }
}
