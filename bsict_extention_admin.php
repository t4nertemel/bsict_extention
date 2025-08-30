<?php
// Prevent direct access
defined( 'ABSPATH' ) || exit;

/* Admin Menu & Settings */
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

/* Register & Sanitize Settings */
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

  // Register font size
register_setting(
  'bsict_plugin_settings',
  'bsict_login_message_font_size',
  array(
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
    'default' => 'medium'
  )
);

// Register text color
register_setting(
  'bsict_plugin_settings',
  'bsict_login_message_color',
  array(
    'type' => 'string',
    'sanitize_callback' => 'sanitize_hex_color',
    'default' => '#444444'
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

  register_setting(
    'bsict_plugin_settings',
    'bsict_login_message',
    array(
      'type' => 'string',
      'sanitize_callback' => 'wp_kses_post', // Allows safe HTML
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

/* Apply Forced Admin Color */
add_filter( 'get_user_option_admin_color', 'bsict_apply_forced_admin_color', 1 );
function bsict_apply_forced_admin_color( $color ) {
  $forced = get_option( 'bsict_force_admin_color' );
  return $forced ? $forced : $color;
}

/* Custom Login Message */
add_action( 'login_footer', 'bsict_custom_login_message' );
function bsict_custom_login_message() {
  $message = get_option( 'bsict_login_message' );
  if ( ! $message ) {
    return;
  }
  ?>
  <style>
    .bsict-login-message {
      text-align: center;
      margin-top: 20px;
      padding: 10px;
      color: #000;
      font-size: 18px;
      max-width: 400px;
      margin-left: auto;
      margin-right: auto;
      border-top: 1px solid #000;
    }
    .bsict-login-message a {
      color: #0073aa;
      text-decoration: none;
    }
    .bsict-login-message a:hover {
      text-decoration: underline;
    }
  </style>
  <div class="bsict-login-message">
    <?php echo wp_kses_post( $message ); ?>
  </div>
  <?php
}

/* Settings Page UI */
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

  // Handle reset action
  if ( isset( $_POST['bsict_reset_settings'] ) && current_user_can( 'manage_options' ) ) {
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'bsict_plugin_settings-options' ) ) {
      wp_die( 'Security check failed.' );
    }

    delete_option( 'bsict_plugin_custom_css' );
    delete_option( 'bsict_plugin_custom_js' );
    delete_option( 'bsict_force_admin_color' );
    delete_option( 'bsict_login_message' );

    add_settings_error( 'bsict_reset', 'reset_success', 'All settings have been reset to default.', 'updated' );
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
      <p class="description">Choose a color scheme to apply site-wide.</p>
    </td>
  </tr>
  <tr>
    <th scope="row">Sync All Users</th>
    <td>
      <button type="submit" name="bsict_sync_colors" class="button">Apply to All Users</button>
      <p class="description">Update all users' admin color to match the global setting.</p>
    </td>
  </tr>
  <tr>
    <th scope="row">Custom Login Message</th>
    <td>
      <textarea name="bsict_login_message" id="bsict_login_message" rows="3" cols="80" class="large-text"><?php echo wp_kses_post( get_option('bsict_login_message') ); ?></textarea>
      <p class="description">HTML allowed. Example: <code>Welcome! <a href="#">Need help?</a></code></p>
    </td>
  </tr>

  <!-- Font Size and Color Side-by-Side -->
  <tr>
    <th scope="row">Message Styling</th>
    <td>
      <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center;">
        <!-- Font Size -->
        <div style="flex: 1; min-width: 200px;">
          <label for="bsict_login_message_font_size"><strong>Font Size</strong></label>
          <select name="bsict_login_message_font_size" id="bsict_login_message_font_size" style="margin-top: 5px;">
            <option value="small" <?php selected( get_option('bsict_login_message_font_size'), 'small' ); ?>>Small</option>
            <option value="medium" <?php selected( get_option('bsict_login_message_font_size'), 'medium' ); ?>>Medium</option>
            <option value="large" <?php selected( get_option('bsict_login_message_font_size'), 'large' ); ?>>Large</option>
            <option value="x-large" <?php selected( get_option('bsict_login_message_font_size'), 'x-large' ); ?>>Extra Large</option>
          </select>
        </div>

        <!-- Text Color -->
        <div style="flex: 1; min-width: 200px;">
          <label for="bsict_login_message_color"><strong>Text Color</strong></label>
          <input 
            type="text" 
            name="bsict_login_message_color" 
            id="bsict_login_message_color" 
            value="<?php echo esc_attr( get_option('bsict_login_message_color', '#444444') ); ?>" 
            class="bsict-color-picker" 
            data-default-color="#444444" 
            style="width: 80px; margin-top: 5px;" 
          />
        </div>
      </div>
      <p class="description" style="margin-top: 10px;">Customise the appearance of the footer login message.</p>
    </td>
  </tr>
</table>

<!-- Submit Button -->
<?php submit_button( 'Save Changes' ); ?>

<!-- Reset Settings at the Bottom -->
<h2>Advanced</h2>
<table class="form-table">
  <tr>
    <th scope="row">Reset All Settings</th>
    <td>
      <button type="submit" name="bsict_reset_settings" class="button button-secondary" onclick="return confirm('Are you sure you want to reset all settings? This cannot be undone.');">Reset All Settings</button>
      <p class="description">Clears all custom CSS, JS, color scheme, and login message styling.</p>
    </td>
  </tr>
</table>
      
    </form>
  </div>
  <?php
}

/* Output CSS & JS */
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