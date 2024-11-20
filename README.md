# BW Update Maintain

**BW Update Maintain** is a WordPress plugin designed to customize update notifications and error handling for WordPress installations. It ensures that critical updates and errors are managed effectively with targeted email notifications and backup configurations.

## Features

1. **Customized Update Notifications**:
   - Sends update notifications (success, warning, error) to specific email addresses.
   - Modifies email subjects to include `[status]` and `[type]` for better clarity.

2. **Critical Error Handling**:
   - Replaces the default "critical error" screen with a user-friendly message for non-authenticated users.
   - Sends detailed error reports to the admin email for debugging.

3. **UpdraftPlus Integration**:
   - Automatically configures the backup email address for UpdraftPlus backups.

## Installation

1. Clone or download this repository.
2. Place the `bw-update-maintain` folder in your WordPress installation's `wp-content/plugins/` directory.
3. Activate the plugin via the WordPress admin panel.

## Configuration

- Ensure the following email addresses are configured to handle notifications:
  - **Error notifications**: `wpadmin-update-error@blickwert.at`
  - **Warning notifications**: `wpadmin-update-warning@blickwert.at`
  - **Success notifications**: `wpadmin-update-succsess@blickwert.at`
  - **Backup email (UpdraftPlus)**: `wpadmin-backup@blickwert.at`

You can modify these email addresses directly in the plugin's `bw-update-maintain.php` file.

## Usage

- The plugin automatically hooks into WordPress core update processes and error handling.
- No additional configuration is required once installed.

## Development

Contributions are welcome! If you'd like to contribute:
1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Submit a pull request.

## License

This plugin is licensed under the **GPL-2.0-or-later** license. See the `LICENSE` file for details.

## Support

For support or feature requests, please create an issue in the GitHub repository.
