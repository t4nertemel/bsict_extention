<?php
// Prevent direct access
defined( 'ABSPATH' ) || exit;

/*----------------------------------------*/
/* Admin Menu & Settings */
/*----------------------------------------*/
add_action( 'admin_menu', 'bsict_plugin_add_admin_menu' );
function bsict_plugin_add_admin_menu() {
  add_options_page(
    'BSICT Plugin Settings',
    'BSICT Plugin',
    'manage_options',
    'bsict-plugin-settings',
    'bsict_plugin_settings_page'
  );

  // Hide from non-admins
  add_action( 'admin_menu', 'bsict_plugin_hide_menu', 999 );
}

function bsict_plugin_hide_menu() {
  if ( ! current_user_can( 'manage_options' ) ) {
    remove_menu_page( 'bsict-plugin-settings' );
  }
}

/*----------------------------------------*/
/* Register & Sanitize Settings */
/*----------------------------------------*/
add_action( 'admin_init', 'bsict_plugin_register_settings' );
function bsict_plugin_register_settings() {
  register_setting(
    'bsict_plugin_settings',
    'bsict_plugin_custom_css',
    array(
      'type' => 'string',
      'sanitize_callback' => 'bsict_sanitize_css',
      'default' => ''
    )
  );

  register_setting(
    'bsict_plugin_settings',
    'bsict_plugin_custom_js',
    array(
      'type' => 'string',
      'sanitize_callback' => 'bsict_sanitize_js',
      'default' => ''
    )
  );

  register_setting(
    'bsict_plugin_settings',
    'bsict_force_admin_color',
    array(
      'type' => 'string',
      'sanitize_callback' => 'sanitize_key',
      'default' => ''
    )
  );
}

function bsict_sanitize_css( $css ) {
  return wp_strip_all_tags( trim( $css ) );
}

function bsict_sanitize_js( $js ) {
  $js = trim( $js );
  if ( empty( $js ) ) return '';

  $disallowed = array( 'eval', 'system(', 'exec(', 'shell_exec', 'base64_decode', 'file_', 'curl_' );
  foreach ( $disallowed as $pattern ) {
    if ( stripos( $js, $pattern ) !== false ) {
      add_settings_error( 'bsict_plugin_custom_js', 'js_error', 'Dangerous JavaScript detected.' );
      return '';
    }
  }
  return $js;
}

/*----------------------------------------*/
/* Apply Forced Admin Color */
/*----------------------------------------*/
add_filter( 'get_user_option_admin_color', 'bsict_apply_forced_admin_color', 1 );
function bsict_apply_forced_admin_color( $color ) {
  $forced = get_option( 'bsict_force_admin_color' );
  return $forced ? $forced : $color;
}

/*----------------------------------------*/
/* Settings Page UI */
/*----------------------------------------*/
function bsict_plugin_settings_page() {
  // Handle sync action
  if ( isset( $_POST['bsict_sync_colors'] ) && current_user_can( 'manage_options' ) ) {
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'bsict_plugin_settings-options' ) ) {
      wp_die( 'Security check failed.' );
    }

    $forced_color = get_option( 'bsict_force_admin_color' );
    if ( $forced_color ) {
      $users = get_users( array( 'fields' => array( 'ID' ) ) );
      foreach ( $users as $user ) {
        update_user_meta( $user->ID, 'admin_color', $forced_color );
      }
      add_settings_error( 'bsict_sync_colors', 'sync_success', "All users updated to '$forced_color' color scheme.", 'updated' );
    } else {
      add_settings_error( 'bsict_sync_colors', 'sync_error', 'No color scheme selected.' );
    }
  }

  settings_errors();

  ?>
  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <p>Securely extend your site with custom styles, scripts, and global settings.</p>

    <form method="post" action="options.php">
      <?php settings_fields( 'bsict_plugin_settings' ); ?>

      <table class="form-table">
        <tr>
          <th scope="row"><label for="bsict_plugin_custom_css">Custom CSS</label></th>
          <td>
            <textarea name="bsict_plugin_custom_css" id="bsict_plugin_custom_css" rows="6" cols="80" class="large-text"><?php echo esc_textarea( get_option( 'bsict_plugin_custom_css' ) ); ?></textarea>
            <p class="description">e.g. <code>.my-class { color: red; }</code></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="bsict_plugin_custom_js">Custom JavaScript</label></th>
          <td>
            <textarea name="bsict_plugin_custom_js" id="bsict_plugin_custom_js" rows="6" cols="80" class="large-text"><?php echo esc_textarea( get_option( 'bsict_plugin_custom_js' ) ); ?></textarea>
            <p class="description">No PHP or dangerous functions.</p>
          </td>
        </tr>
        <tr>
          <th scope="row">Force Admin Color Scheme</th>
          <td>
            <select name="bsict_force_admin_color" id="bsict_force_admin_color">
              <option value="" <?php selected( get_option('bsict_force_admin_color', 'fresh'), '' ); ?>>Use User Preference</option>
              <option value="fresh" <?php selected( get_option('bsict_force_admin_color'), 'fresh' ); ?>>Default (Blue)</option>
              <option value="light" <?php selected( get_option('bsict_force_admin_color'), 'light' ); ?>>Light</option>
              <option value="blue" <?php selected( get_option('bsict_force_admin_color'), 'blue' ); ?>>Blue (Legacy)</option>
              <option value="midnight" <?php selected( get_option('bsict_force_admin_color'), 'midnight' ); ?>>Midnight</option>
              <option value="ectoplasm" <?php selected( get_option('bsict_force_admin_color'), 'ectoplasm' ); ?>>Ectoplasm</option>
              <option value="ocean" <?php selected( get_option('bsict_force_admin_color'), 'ocean' ); ?>>Ocean</option>
              <option value="coffee" <?php selected( get_option('bsict_force_admin_color'), 'coffee' ); ?>>Coffee</option>
              <option value="sunrise" <?php selected( get_option('bsict_force_admin_color'), 'sunrise' ); ?>>Sunrise</option>
            </select>
            <p class="description">Choose a color scheme to apply site-wide. Users won’t be able to change it if this is set.</p>
          </td>
        </tr>
        <tr>
          <th scope="row">Sync All Users</th>
          <td>
            <button type="submit" name="bsict_sync_colors" class="button">Apply to All Users</button>
            <p class="description">Update all users' admin color to match the global setting.</p>
          </td>
        </tr>
      </table>

      <?php submit_button( 'Save Changes' ); ?>
    </form>
  </div>
  <?php
}

/*----------------------------------------*/
/* Output CSS & JS */
/*----------------------------------------*/
add_action( 'wp_head', 'bsict_output_custom_css' );
function bsict_output_custom_css() {
  $css = get_option( 'bsict_plugin_custom_css' );
  if ( $css ) {
    echo "<style>" . wp_strip_all_tags( $css ) . "</style>";
  }
}

add_action( 'wp_footer', 'bsict_output_custom_js' );
function bsict_output_custom_js() {
  $js = get_option( 'bsict_plugin_custom_js' );
  if ( $js ) {
    echo "<script>" . wp_strip_all_tags( $js ) . "</script>";
  }
}