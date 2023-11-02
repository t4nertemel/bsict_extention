<?php
// Add a settings page to the admin menu
add_action('admin_menu', 'bsict_plugin_add_admin_menu');
function bsict_plugin_add_admin_menu() {
  add_options_page('BSICT Plugin Settings', 'BSICT Plugin', 'manage_options', 'bsict-plugin-settings', 'bsict_plugin_settings_page');
}

// Display the settings page
function bsict_plugin_settings_page() {
  ?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form action="options.php" method="post">
      <?php settings_fields('bsict_plugin_settings'); ?>

      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"><label for="bsict_plugin_function_name">Function name:</label></th>
            <td><input type="text" id="bsict_plugin_function_name" name="bsict_plugin_function_name" value="<?php echo esc_attr(get_option('bsict_plugin_function_name')); ?>" /></td>
          </tr>
          <tr>
            <th scope="row"><label for="bsict_plugin_function_code">Function code:</label></th>
            <td><textarea id="bsict_plugin_function_code" name="bsict_plugin_function_code" rows="10" cols="50"><?php echo esc_attr(get_option('bsict_plugin_function_code')); ?></textarea></td>
          </tr>
        </tbody>
      </table>

      <p class="submit">
        <input type="submit" name="submit" class="button-primary" value="Save Changes" />
      </p>
    </form>
  </div>
  <?php
}

// Register the settings
register_setting('bsict_plugin_settings', 'bsict_plugin_function_name');
register_setting('bsict_plugin_settings', 'bsict_plugin_function_code');

// Add the function to the WordPress environment
add_action('plugins_loaded', 'bsict_plugin_add_function');
function bsict_plugin_add_function() {
  $function_name = get_option('bsict_plugin_function_name');
  $function_code = get_option('bsict_plugin_function_code');

  if (!empty($function_name) && !empty($function_code)) {
    eval($function_code);
  }
}
?>