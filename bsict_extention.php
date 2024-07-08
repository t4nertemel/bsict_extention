<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/taner-temel-ba7b9844
 * @since             0.0.4
 * @package           Bsict_Extention
 *
 * @wordpress-plugin
 * Plugin Name:       Bolton SICT Extention
 * Plugin URI:        https://www.bolton365.net
 * Description:       Extend Bolton SICT site functionality, do not disable it.
 * Version:           0.7.4
 * Author:            Taner Temel
 * Author URI:        https://www.linkedin.com/in/taner-temel-ba7b9844
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bsict-extention
 * Domain Path:       /languages
 */
/*------------------------------------------------*/
// Include the new admin settings page file
require_once( plugin_dir_path( __FILE__ ) . 'bsict_extention_admin.php' );
/*------------------------------------------------*/
/* SICT Login Logo */
function my_login_logo() { ?>
  <style type="text/css">
      #login h1 a, .login h1 a {
        background-image: url(<?php plugin_dir_path(__DIR__); ?>wp-content/plugins/bsict_extention/assets/images/sict-logo.png);
  height:90px;
  width:250px;
  background-size: 250px 90px;
  background-repeat: no-repeat;
        padding-bottom: 0px;
      }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

/* Logo Link */
function my_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo_url_title() {
  return 'Bolton Schools ICT';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

/*------------------------------------------------*/
/* Login BG Image */
function login_background_image() {
  echo '<style type="text/css">
  body.login{
  background-image: url( "https://ik.imagekit.io/eei82mxvvgg/Bolton%20SICT/Pngtreecartoon_books_sunflowers_RhRpwydBD.png?updatedAt=1710164790446" )!important;
  }
  </style>';
  }
  add_action('login_head', 'login_background_image');
/*------------------------------------------------*/
/*-- Enqueue custom CSS file --*/
function sictext_enqueue_styles() {
  wp_enqueue_style( 'sictext-custom-styles', plugins_url( '/assets/css/custom-styles.css', __FILE__ ), array(), '1.0' );
}
// Hook the function to the wp_enqueue_scripts action hook
add_action( 'wp_enqueue_scripts', 'sictext_enqueue_styles' );
/*------------------------------------------------*/
/* Cookie Consent Script */
function cookie_javascript() {
  ?>
<!-- Cookie Consent by FreePrivacyPolicy.com https://www.FreePrivacyPolicy.com -->
<script type="text/javascript" src="//www.freeprivacypolicy.com/public/cookie-consent/4.1.0/cookie-consent.js" charset="UTF-8"></script>
<script type="text/javascript" charset="UTF-8">
document.addEventListener('DOMContentLoaded', function () {
cookieconsent.run({"notice_banner_type":"simple","consent_type":"express","palette":"light","language":"en","page_load_consent_levels":["strictly-necessary"],"notice_banner_reject_button_hide":false,"preferences_center_close_button_hide":false,"page_refresh_confirmation_buttons":false});
});
</script>

<noscript>Cookie Consent by <a href="https://www.freeprivacypolicy.com/">Free Privacy Policy Generator</a></noscript>
<!-- End Cookie Consent by FreePrivacyPolicy.com https://www.FreePrivacyPolicy.com -->

<!-- Below is the link that users can use to open Preferences Center to change their preferences. Do not modify the ID parameter. Place it where appropriate, style it as needed. -->

<a href="#" id="open_preferences_center">Update cookies preferences</a>

  <?php
}
add_action('wp_footer', 'cookie_javascript');
/*------------------------------------------------*/
/* Register Custom Dashboard Widget */
function register_training_dashboard_widget() {
  global $wp_meta_boxes;

  wp_add_dashboard_widget(
       'training_video_dashboard_widget',
       'Website Training Videos',
       'training_video__dashboard_widget_display'
   );

    $dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

   $my_widget = array(  'training_video_dashboard_widget' => $dashboard[ 'training_video_dashboard_widget'] );
   unset( $dashboard[ 'mtraining_video_dashboard_widget' ] );

    $sorted_dashboard = array_merge( $my_widget, $dashboard );
    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

add_action('wp_dashboard_setup',  'register_training_dashboard_widget');

function training_video__dashboard_widget_display() {
   ?>
<table style="width: 100%; border-collapse: collapse; margin-left: auto; margin-right: auto;" border="0" cellpadding="5px">
<tbody>
<tr>
<td style="width: 100%; text-align: center;">
<p><strong>Introduction</strong>: <br />This video provides a quick overview of your website and guides you through accessing the admin dashboard and editing pages/articles.<br /><a title="Introduction" href="https://bolton365net-my.sharepoint.com/:v:/g/personal/websitecontent_bolton365_net/EfTrK9CaXyFIo0U4XJzKWHkBGvw-3NxFcaXjLGXegc0k8w?e=wxKO9g&amp;referrer=Outlook.Web&amp;referrerScenario=email-linkwithembed" target="_blank" rel="noopener"><img src="https://ik.imagekit.io/eei82mxvvgg/Bolton%20SICT/intro_DVkx_Odwq.png?updatedAt=1715588099383" alt="" width="300" height="170" /></a></p>
</td>
</tr>
<tr>
<td style="width: 100%; text-align: center;"><strong>Using the block editor:</strong><br />Using the WordPress Block Editor: In this tutorial learn how to add and customise content blocks on your website&rsquo;s pages and posts.<br /><a title="Block Editor" href="https://bolton365net-my.sharepoint.com/:v:/g/personal/websitecontent_bolton365_net/EZYRh193ESxLirykKZpEnjIBHsa69USdyVgyX1jeTFeDsQ?e=ZCOUBW&amp;referrer=Outlook.Web&amp;referrerScenario=email-linkwithembed" target="_blank"><img style="display: block; margin-left: auto; margin-right: auto;" src="https://ik.imagekit.io/eei82mxvvgg/Bolton%20SICT/block-editor_30BulLj75.png?updatedAt=1715588099332" alt="Gutenberg Editor" width="300" height="169" /></a></td>
</tr>
<tr> 
<td style="width: 100%; text-align: center;"><strong>WordPress Media Library:</strong><br />Using the WordPress Media Library.<br /><a title="WordPress Media Libarary" href="https://www.youtube.com/watch?v=PcsythkdHrI" target="_blank"><img style="display: block; margin-left: auto; margin-right: auto;" src="https://ik.imagekit.io/eei82mxvvgg/Bolton%20SICT/media-library_YO3mrstKy.jpg?updatedAt=1720444278952" alt="Media Library" width="300" height="169" /></a></td> 
</tr>
</tbody>
</table>

<?php
}
?>
