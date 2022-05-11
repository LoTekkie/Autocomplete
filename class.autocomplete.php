<?php

class Autocomplete
{
    private static $initiated = false;
    private static $notices   = array();

    public static function init()
    {
        if (!self::$initiated) {
            self::init_hooks();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks()
    {
        AutoComplete_Metabox::make();
        self::$initiated = true;
    }


    public static function predefined_api_key() {
        if ( defined( 'AUTOCOMPLETE_API_KEY' ) ) {
            return true;
        }

        return apply_filters( 'autocomplete_predefined_api_key', false );
    }

    public static function get_api_key()
    {
        return apply_filters('autocomplete_get_api_key', defined('AUTOCOMPLETE_API_KEY') ? constant('AUTOCOMPLETE_API_KEY') : get_option('autocomplete_api_key'));
    }

    public static function check_key_status($key, $ip = null)
    {
        //TODO: we can either ping server or add some sensible checks here
        return true;
    }

    public static function verify_key($key, $ip = null)
    {
      //TODO: wrapped for futher processing if needed as this may be a request returned
        return self::check_key_status($key, $ip) ? 'valid' : 'invalid';
    }

    public static function is_test_mode()
    {
        return defined('AUTOCOMPLETE_TEST_MODE') && AUTOCOMPLETE_TEST_MODE;
    }

    public static function get_ip_address()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    private static function get_user_agent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    private static function get_referer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    // return a comma-separated list of role names for the given user
    public static function get_user_roles($user_id)
    {
        $roles = false;

        if (!class_exists('WP_User'))
            return false;

        if ($user_id > 0) {
            $comment_user = new WP_User($user_id);
            if (isset($comment_user->roles))
                $roles = join(',', $comment_user->roles);
        }

        if (is_multisite() && is_super_admin($user_id)) {
            if (empty($roles)) {
                $roles = 'super_admin';
            } else {
                $comment_user->roles[] = 'super_admin';
                $roles = join(',', $comment_user->roles);
            }
        }

        return $roles;
    }

    public static function _cmp_time($a, $b)
    {
        return $a['time'] > $b['time'] ? -1 : 1;
    }

    public static function _get_microtime()
    {
        $mtime = explode(' ', microtime());
        return $mtime[1] + $mtime[0];
    }

    /**
     * Make a POST request to the Autocomplete API.
     * https://autocomplete.sh/documentation#api-access
     *
     * @param string $request The body of the request.
     * @param string $path The path for the request.
     * @return array A two-member array consisting of the headers and the response body, both empty in the case of a failure.
     */
    public static function http_post($request, $path)
    {
        $content_length = strlen($request);

        $api_key = self::get_api_key();

        $http_args = array(
            'body' => $request,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
                'Host' => constant('AUTOCOMPLETE_URL'),
                'Authorization' => 'Bearer ' . $api_key
            ),
            'httpversion' => '1.0',
            'timeout' => 15
        );

        $autocomplete_url = autocomplete_url("v1/engines/{$path}");

        $response = wp_remote_post($autocomplete_url, $http_args);

        AutoComplete::log(compact('autocomplete_url', 'http_args', 'response'));

        if (is_wp_error($response)) {
            do_action('autocomplete_https_request_failure', $response);
            return array('', '');
        }

        return array($response['headers'], $response['body']);
    }

    private static function bail_on_activation($message, $deactivate = true)
    {
        ?>
      <!doctype html>
      <html>
      <head>
        <meta charset="<?php bloginfo('charset'); ?>"/>
        <style>
          * {
            text-align: center;
            margin: 0;
            padding: 0;
            font-family: "Lucida Grande", Verdana, Arial, "Bitstream Vera Sans", sans-serif;
          }

          p {
            margin-top: 1em;
            font-size: 18px;
          }
        </style>
      </head>
      <body>
      <p><?php echo esc_html($message); ?></p>
      </body>
      </html>
        <?php
        if ($deactivate) {
            $plugins = get_option('active_plugins');
            $autocomplete = plugin_basename(AUTOCOMPLETE_PLUGIN_DIR . 'autocomplete.php');
            $update = false;
            foreach ($plugins as $i => $plugin) {
                if ($plugin === $autocomplete) {
                    $plugins[$i] = false;
                    $update = true;
                }
            }

            if ($update) {
                update_option('active_plugins', array_filter($plugins));
            }
        }
        exit;
    }

    public static function view($name, array $args = array())
    {
        $args = apply_filters('autocomplete_view_arguments', $args, $name);

        foreach ($args AS $key => $val) {
            $$key = $val;
        }

        load_plugin_textdomain('autocomplete');

        $file = AUTOCOMPLETE_PLUGIN_DIR . 'views/' . $name . '.php';

        include($file);
    }

    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation()
    {
        if (version_compare($GLOBALS['wp_version'], AUTOCOMPLETE_MINIMUM_WP_VERSION, '<')) {
            load_plugin_textdomain('autocomplete');

            $message = '<strong>' . sprintf(esc_html__('autocomplete %s requires WordPress %s or higher.', 'akismet'), AUTOCOMPLETE_VERSION, AUTOCOMPLETE_MINIMUM_WP_VERSION) . '</strong> ' . sprintf(__('Please <a href="%1$s">upgrade WordPress</a> to a current version, or <a href="%2$s">downgrade to version 2.4 of the Akismet plugin</a>.', 'akismet'), 'https://codex.wordpress.org/Upgrading_WordPress', 'https://wordpress.org/extend/plugins/akismet/download/');

            AutoComplete::bail_on_activation($message);
        } elseif (!empty($_SERVER['SCRIPT_NAME']) && false !== strpos($_SERVER['SCRIPT_NAME'], '/wp-admin/plugins.php')) {
            add_option('activated_autocomplete', true);
        }
    }

    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation()
    {
        delete_option('activated_autocomplete');
    }

    /**
     * Essentially a copy of WP's build_query but one that doesn't expect pre-urlencoded values.
     *
     * @param array $args An array of key => value pairs
     * @return string A string ready for use as a URL query string.
     */
    public static function build_query($args)
    {
        return _http_build_query($args, '', '&');
    }

    /**
     * Log debugging info to the error log.
     *
     * Enabled when WP_DEBUG_LOG is enabled (and WP_DEBUG, since according to
     * core, "WP_DEBUG_DISPLAY and WP_DEBUG_LOG perform no function unless
     * WP_DEBUG is true), but can be disabled via the akismet_debug_log filter.
     *
     * @param mixed $autocomplete_debug The data to log.
     */
    public static function log($autocomplete_debug)
    {
        if (apply_filters('autocomplete_debug_log', defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG && defined('AUTOCOMPLETE_DEBUG') && AUTOCOMPLETE_DEBUG)) {
            error_log(print_r(compact('autocomplete_debug'), true));
        }
    }
}
