<?php
/*
Plugin Name: BW Update Maintain
Description: Customizes update notifications and error handling for WordPress updates.
Version: 1.0
Author: Blickwert Graz
License: GPL-2.0+
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class BW_Update_Maintain {

    private static $error_mail = 'wpadmin-update-error@blickwert.at';
    private static $warning_mail = 'wpadmin-update-warning@blickwert.at';
    private static $success_mail = 'wpadmin-update-success@blickwert.at';
    private static $backup_mail = 'wpadmin-backup@blickwert.at';

    public function __construct() {
        // Hook for update notifications
        add_filter('auto_core_update_email', [$this, 'customize_update_email'], 10, 4);

        // Replace error page with email notification
        add_filter('wp_die_handler', [$this, 'handle_critical_error']);

        // Configure UpdraftPlus backup email
        add_action('updraftplus_config', [$this, 'set_updraftplus_backup_email']);
    }

    /**
     * Customizes the update notification email to include status and type, and sends to specific recipients.
     */
    public function customize_update_email($email, $type, $core_update, $result) {
        $status = $result['success'] ? 'success' : 'error';
        $subject_status = $status === 'success' ? '[success]' : '[error]';
        $email_type = isset($email['type']) ? $email['type'] : 'general';

        $email['subject'] = "[site] {$email['subject']} {$subject_status} [{$email_type}]";

        // Send email to appropriate recipient based on status
        if ($status === 'error') {
            $email['to'] = self::$error_mail;
        } elseif ($status === 'warning') {
            $email['to'] = self::$warning_mail;
        } else {
            $email['to'] = self::$success_mail;
        }

        return $email;
    }

    /**
     * Replaces the default WordPress error page with an email notification to the admin.
     */
    public function handle_critical_error() {
        return function($message, $title, $args) {
            if (!is_user_logged_in() || !current_user_can('manage_options')) {
                // Simple error message for non-authenticated users
                wp_die('An error occurred. Please try again later.', 'Error');
                return;
            }

            // Send error details via email
            wp_mail(
                self::$error_mail,
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
            $options = get_option('updraftplus');
            $options['email'] = self::$backup_mail;
            update_option('updraftplus', $options);
        }
    }
}

// Initialize the plugin
new BW_Update_Maintain();
