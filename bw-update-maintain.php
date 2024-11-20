<?php
/*
Plugin Name: BW Update Maintain
Description: Customizes update notifications and error handling for WordPress updates.
Version: 1.1
Author: Blickwert Graz
License: GPL-2.0+
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class BW_Update_Maintain {

    private static $default_options = [
        'error_mail' => '',
        'warning_mail' => '',
        'success_mail' => '',
        'backup_mail' => '',
        'send_success_mail' => false,
        'prevent_critical_error_page_from_user' => false
    ];

    public function __construct() {
        // Initialize plugin options
        add_action('admin_menu', [$this, 'register_options_page']);
        add_action('admin_init', [$this, 'register_settings']);

        // Hook for update notifications
        add_filter('auto_core_update_email', [$this, 'customize_update_email'], 10, 4);

        // Replace error page with email notification
        add_filter('wp_die_handler', [$this, 'handle_critical_error']);

        // Configure UpdraftPlus backup email
        add_action('updraftplus_config', [$this, 'set_updraftplus_backup_email']);
    }

    /**
     * Register the plugin options page in the admin menu.
     */
    public function register_options_page() {
        add_options_page(
            'BW Update Maintain',
            'BW Update Maintain',
            'manage_options',
            'bw-update-maintain',
            [$this, 'render_options_page']
        );
    }

    /**
     * Register plugin settings.
     */
    public function register_settings() {
        register_setting('bw_update_maintain_options', 'bw_update_maintain', [
            'default' => self::$default_options
        ]);
    }

    /**
     * Render the plugin options page.
     */
    public function render_options_page() {
        ?>
        <div class="wrap">
            <h1>BW Update Maintain Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('bw_update_maintain_options');
                $options = get_option('bw_update_maintain', self::$default_options);
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="error_mail">Error Email</label></th>
                        <td><input type="email" name="bw_update_maintain[error_mail]" id="error_mail" value="<?php echo esc_attr($options['error_mail']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="warning_mail">Warning Email</label></th>
                        <td><input type="email" name="bw_update_maintain[warning_mail]" id="warning_mail" value="<?php echo esc_attr($options['warning_mail']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="success_mail">Success Email</label></th>
                        <td><input type="email" name="bw_update_maintain[success_mail]" id="success_mail" value="<?php echo esc_attr($options['success_mail']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="backup_mail">Backup Email</label></th>
                        <td><input type="email" name="bw_update_maintain[backup_mail]" id="backup_mail" value="<?php echo esc_attr($options['backup_mail']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="send_success_mail">Send Success Email</label></th>
                        <td>
                            <select name="bw_update_maintain[send_success_mail]" id="send_success_mail">
                                <option value="false" <?php selected($options['send_success_mail'], false); ?>>False</option>
                                <option value="true" <?php selected($options['send_success_mail'], true); ?>>True</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="prevent_critical_error_page_from_user">Prevent Critical Error Page for Users</label></th>
                        <td>
                            <select name="bw_update_maintain[prevent_critical_error_page_from_user]" id="prevent_critical_error_page_from_user">
                                <option value="false" <?php selected($options['prevent_critical_error_page_from_user'], false); ?>>False</option>
                                <option value="true" <?php selected($options['prevent_critical_error_page_from_user'], true); ?>>True</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Customizes the update notification email to include status and type, and sends to specific recipients.
     */
    public function customize_update_email($email, $type, $core_update, $result) {
        $options = get_option('bw_update_maintain', self::$default_options);

        $status = $result['success'] ? 'success' : 'error';
        $subject_status = $status === 'success' ? '[success]' : '[error]';
        $email_type = isset($email['type']) ? $email['type'] : 'general';

        $email['subject'] = "[site] {$email['subject']} {$subject_status} [{$email_type}]";

        // Send email to appropriate recipient based on status
        if ($status === 'error') {
            $email['to'] = $options['error_mail'];
        } elseif ($status === 'warning') {
            $email['to'] = $options['warning_mail'];
        } elseif ($options['send_success_mail'] === true) {
            $email['to'] = $options['success_mail'];
        }

        return $email;
    }

    /**
     * Replaces the default WordPress error page with an email notification to the admin.
     */
    public function handle_critical_error() {
        return function($message, $title, $args) {
            $options = get_option('bw_update_maintain', self::$default_options);

            if ($options['prevent_critical_error_page_from_user'] === true) {
                // Try serving a cached version of the page if available
                if (function_exists('wp_cache_get')) {
                    $cached_page = wp_cache_get('error_page_cache', 'bw_update_maintain');
                    if ($cached_page) {
                        echo $cached_page;
                        exit;
                    }
                }
                // Simple message if no cache
                wp_die('An error occurred. Please try again later.', 'Error');
                return;
            }

            // Send error details via email
            wp_mail(
                $options['error_mail'],
                "[Critical Error] {$title}",
                "Message: {$message}\n\nDetails:\n" . print_r($args, true)
            );

            // Display the default WordPress error page for administrators
            wp_die($message, $title, $args);
        };
    }

    /**
     * Configures the backup email address for UpdraftPlus.
     */
    public function set_updraftplus_backup_email() {
        if (class_exists('UpdraftPlus')) {
            $options = get_option('bw_update_maintain', self::$default_options);
            $updraft_options = get_option('updraftplus');
            $updraft_options['email'] = $options['backup_mail'];
            update_option('updraftplus', $updraft_options);
        }
    }
}

// Initialize the plugin
new BW_Update_Maintain();
