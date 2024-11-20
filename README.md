# BW Update Maintain

**BW Update Maintain** is a WordPress plugin designed to customize update notifications and error handling for WordPress installations. It ensures that critical updates and errors are managed effectively with targeted email notifications and backup configurations.

---

## Changelog

### Version 1.1
- **New Features**:
  - Added an **options page** to configure:
    - Email addresses for `error_mail`, `warning_mail`, `success_mail`, and `backup_mail`.
    - `send_success_mail` option to toggle success email notifications.
    - `prevent_critical_error_page_from_user` option to prevent critical error pages for non-logged-in users.
  - Implemented support for loading cached versions of pages when critical errors occur and caching is enabled.
- **Enhancements**:
  - Default email addresses are now configurable via the options page.
  - Success emails can be disabled or enabled via the settings.
  - Critical error handling enhanced to provide user-friendly fallback behavior.

### Version 1.0
- Initial release:
  - Customized update notification emails to include status and type.
  - Replaced default critical error pages with email notifications to the admin.
  - Configured UpdraftPlus backup email address.

---

## Features

1. **Customized Update Notifications**:
   - Sends update notifications (success, warning, error) to specific email addresses configured in the plugin settings.
   - Modifies email subjects to include `[status]` and `[type]` for better clarity.

2. **Critical Error Handling**:
   - Prevents critical error pages for non-authenticated users (optional).
   - Sends detailed error reports to the admin email for debugging.
   - Can serve cached versions of pages when critical errors occur.

3. **UpdraftPlus Integration**:
   - Automatically configures the backup email address for UpdraftPlus backups via the plugin settings.

4. **Admin Options Page**:
   - Easily configure email addresses and behavior for notifications and error handling.

---

## Installation

1. Clone or download this repository.
2. Place the `bw-update-maintain` folder in your WordPress installation's `wp-content/plugins/` directory.
3. Activate the plugin via the WordPress admin panel.

---

## Configuration

1. Navigate to **Settings > BW Update Maintain** in your WordPress admin panel.
2. Configure the following fields:
   - **Error Email**: Email address for error notifications.
   - **Warning Email**: Email address for warning notifications.
   - **Success Email**: Email address for success notifications (if enabled).
   - **Backup Email**: Email address for UpdraftPlus backups.
   - **Send Success Email**: Choose whether to send success notifications (true/false).
   - **Prevent Critical Error Page for Users**: Enable or disable user-facing critical error pages.

---

## Usage

- The plugin automatically hooks into WordPress core update processes and error handling.
- Settings configured via the options page will be applied dynamically.

---

## Development

Contributions are welcome! If you'd like to contribute:
1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Submit a pull request.

---

## License

This plugin is licensed under the **GPL-2.0-or-later** license. See the `LICENSE` file for details.

---

## Support

For support or feature requests, please create an issue in the GitHub repository.
