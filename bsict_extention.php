<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://www.linkedin.com/in/taner-temel-ba7b9844
 * @since             0.0.4
 * @package           Bsict_Extension
 *
 * @wordpress-plugin
 * Plugin Name:       Bolton SICT Extension
 * Plugin URI:        https://www.bolton365.net
 * Description:       Extend Bolton SICT site functionality safely. Do not disable.
 * Version:           0.8.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Taner Temel
 * Author URI:        https://www.linkedin.com/in/taner-temel-ba7b9844
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bsict-extension
 * Domain Path:       /languages
 */

// Prevent direct access
defined( 'ABSPATH' ) || exit;

// Include admin settings
require_once plugin_dir_path( __FILE__ ) . 'bsict_extention_admin.php';

/* 1. Custom Login Logo */
function bsict_login_logo() { ?>
  <style type="text/css">
    #login h1 a, .login h1 a {
      background-image: url('<?php echo esc_url( plugins_url( 'assets/images/sict-logo.png', __FILE__ ) ); ?>');
      height: 90px;
      width: 250px;
      background-size: 250px 90px;
      background-repeat: no-repeat;
      padding-bottom: 30px;
    }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'bsict_login_logo' );

// Enqueue color picker on settings page only
add_action( 'admin_enqueue_scripts', 'bsict_admin_enqueue_scripts' );
function bsict_admin_enqueue_scripts( $hook ) {
  if ( $hook !== 'settings_page_bsict-plugin-settings' ) {
    return;
  }
  wp_enqueue_script( 'wp-color-picker' );
  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ) . 'assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '3.0.0', true );
  wp_add_inline_script( 'wp-color-picker-alpha', '
    jQuery(document).ready(function($) {
      $(".bsict-color-picker").wpColorPicker();
    });
  ' );
}

/* 2. Login Logo Link */
function bsict_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'bsict_login_logo_url' );

function bsict_login_logo_url_title() {
  return 'Bolton Schools ICT';
}
add_filter( 'login_headertitle', 'bsict_login_logo_url_title' );

/* 3. Login Background Image */
function bsict_login_background() { ?>
  <style type="text/css">
    body.login {
      background-image: url('https://ik.imagekit.io/eei82mxvvgg/Bolton%20SICT/Pngtreecartoon_books_sunflowers_RhRpwydBD.png?updatedAt=1710164790446') !important;
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'bsict_login_background' );

/* 4. Enqueue Custom CSS */

function bsict_enqueue_custom_styles() {
  $css_file = plugin_dir_path( __FILE__ ) . 'assets/css/custom-styles.css';
  $version = file_exists( $css_file ) ? filemtime( $css_file ) : '1.0';

  wp_enqueue_style(
    'bsict-custom-styles',
    plugins_url( 'assets/css/custom-styles.css', __FILE__ ),
    array(),
    $version
  );
}
add_action( 'wp_enqueue_scripts', 'bsict_enqueue_custom_styles' );

/*------------------------------------------------*/
/* Cookie Consent Script */
function bsict_cookie_javascript() { // Renamed function for consistency with new naming scheme
  ?>
<!-- Cookie Consent by FreePrivacyPolicy.com https://www.FreePrivacyPolicy.com   -->
<script type="text/javascript" src="//www.freeprivacypolicy.com/public/cookie-consent/4.1.0/cookie-consent.js" charset="UTF-8"></script>
<script type="text/javascript" charset="UTF-8">
document.addEventListener('DOMContentLoaded', function () {
window.cookieconsent.run({"notice_banner_type":"simple","consent_type":"express","palette":"light","language":"en","page_load_consent_levels":["strictly-necessary"],"notice_banner_reject_button_hide":false,"preferences_center_close_button_hide":false,"page_refresh_confirmation_buttons":false});
});
</script>

<noscript>Cookie Consent by <a href="https://www.freeprivacypolicy.com/">Free Privacy Policy Generator</a></noscript>
<!-- End Cookie Consent by FreePrivacyPolicy.com https://www.FreePrivacyPolicy.com   -->

<!-- Below is the link that users can use to open Preferences Center to change their preferences. Do not modify the ID parameter. Place it where appropriate, style it as needed. -->

<a href="#" id="open_preferences_center">Update cookies preferences</a>

  <?php
}
add_action('wp_footer', 'bsict_cookie_javascript'); // Hook it to wp_footer
/*------------------------------------------------*/

/* 5. Dashboard Widgets */
function bsict_training_video_widget() { ?>
  <table style="width: 100%; border-collapse: collapse; margin: 0 auto;" border="0" cellpadding="5">
    <tbody>
      <tr>
        <td style="text-align: center;">
          <p><strong>Introduction</strong><br>Quick overview of your website and editing content.<br>
          <a href="https://bolton365net-my.sharepoint.com/:v:/g/personal/websitecontent_bolton365_net/EfTrK9CaXyFIo0U4XJzKWHkBGvw-3NxFcaXjLGXegc0k8w?e=wxKO9g" target="_blank" rel="noopener">
            <img src="https://ik.imagekit.io/eei82mxvvgg/Bolton%20SICT/intro_DVkx_Odwq.png?updatedAt=1715588099383" alt="Intro Video" width="300" height="170" />
          </a></p>
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">
          <p><strong>Block Editor</strong><br>Learn how to use the WordPress block editor.<br>
          <a href="https://bolton365net-my.sharepoint.com/:v:/g/personal/websitecontent_bolton365_net/EZYRh193ESxLirykKZpEnjIBHsa69USdyVgyX1jeTFeDsQ?e=ZCOUBW" target="_blank" rel="noopener">
            <img src="https://ik.imagekit.io/eei82mxvvgg/Bolton%20SICT/block-editor_30BulLj75.png?updatedAt=1715588099332" alt="Block Editor" width="300" height="169" />
          </a></p>
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">
          <p><strong>Media Library</strong><br>Using the WordPress Media Library.<br>
          <a href="https://www.youtube.com/watch?v=PcsythkdHrI" target="_blank" rel="noopener">
            <img src="https://ik.imagekit.io/eei82mxvvgg/Bolton%20SICT/media-library_YO3mrstKy.jpg?updatedAt=1720444278952" alt="Media Library" width="300" height="169" />
          </a></p>
        </td>
      </tr>
    </tbody>
  </table>
<?php }

function bsict_review_widget() { ?>
  <div style="text-align: center; padding: 10px;">
    <h3>Book a Website Review</h3>
    <p>Schedule a time to review your website and discuss improvements.</p>
    <p>
      <a href="https://outlook.office365.com/book/TanerTemel@Bolton365net.onmicrosoft.com/" target="_blank" rel="noopener" style="font-size: 16px; color: #0073aa; text-decoration: underline;">
        Click here to book your review
      </a>
    </p>
  </div>
<?php }

function bsict_register_dashboard_widgets() {
  wp_add_dashboard_widget(
    'bsict_training_widget',
    '🎓 Website Training Videos',
    'bsict_training_video_widget'
  );

  wp_add_dashboard_widget(
    'bsict_review_widget',
    '🥇 Book a Website Review',
    'bsict_review_widget'
  );
}
add_action( 'wp_dashboard_setup', 'bsict_register_dashboard_widgets' );