=== Plugin Name ===
Contributors: (https://profiles.wordpress.org/clu55ter/)
Donate link: https://www.linkedin.com/in/taner-temel-ba7b9844
Tags: bsict, functions
Requires at least: 6.0.1
Tested up to: 6.2
Stable tag: 6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Here is a short description of the plugin.  This should be no more than 150 characters.  No markup here.

== Description ==

Extend Bolton SICT site functionality, this plugin add a custom login page and a custom settings page to add functions through the admin panel.

For backwards compatibility, if this section is missing, the full length of the short description will be used, and
Markdown parsed.

A few notes about the sections above:

*   "Contributors" is a comma separated list of wp.org/wp-plugins.org usernames
*   "Tags" is a comma separated list of tags that apply to the plugin
*   "Requires at least" is the lowest version that the plugin will work on
*   "Tested up to" is the highest version that you've *successfully used to test the plugin*. Note that it might work on
higher versions... this is just the highest one you've verified.
*   Stable tag should indicate the Subversion "tag" of the latest stable version, or "trunk," if you use `/trunk/` for
stable.

    Note that the `readme.txt` of the stable tag is the one that is considered the defining one for the plugin, so
if the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used
for displaying information about the plugin.  In this situation, the only thing considered from the trunk `readme.txt`
is the stable tag pointer.  Thus, if you develop in trunk, you can update the trunk `readme.txt` to reflect changes in
your in-development version, without having that information incorrectly disclosed about the current stable version
that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.

    If no stable tag is provided, it is assumed that trunk is stable, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `bsict_extention.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= Do You have an example function to add from settings/BSICT Plugin page? =

Below are some examples.
/*-- Global dashboard color scheme --*/
add_filter( 'get_user_option_admin_color', 'update_user_option_admin_color', 5 );
function update_user_option_admin_color( $color_scheme ) {
    $color_scheme = 'midnight';
    return $color_scheme;
}
/*------------------------------------------------*/
/*-- Sender email address --*/
function wpb_sender_email( $original_email_address ) {
    return 'school@bolton.education';
}
// Function to change sender name
function wpb_sender_name( $original_email_from ) {
    return 'Primary School';
}
/* Hooking up our functions to WordPress filters  */
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );
/*------------------------------------------------*/
// Enqueue and add Facebook widget script right after the opening <body> tag*/
function fb_script_enqueue_header_script() {
    echo '<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v18.0&appId=159431251379434" nonce="PMdI63il"></script>';
}

// Hook the function to the wp_body_open action hook
add_action( 'wp_body_open', 'fb_script_enqueue_header_script' );

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 0.6 =
* Rebuilt plugin adding new BSICT Plugin page to setting page
* When active this plugin will customise the login page for BSICT
* You can now add new functions from the settings page in the admin panel. See function examples of functions in FAQs above.

= 0.5 =
* Image hover styles.
* Added CSS bsict custom hover styles to images.

= 0.4 =
* Enqued custom style sheets.
* Added css folder and file to change button colours for cookie confirm.

= 0.3 =
* Added cookie confirm to plugin (https://www.freeprivacypolicy.com/free-cookie-consent/).

== Upgrade Notice ==

= 0.2 =
Added Sender email address.

= 0.1 =
Added SICT logo to login screen with background image.  Upgrade immediately.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Logo in logo and link
2. Global dashboard colour
3. Login background image
4. WordPress sender email address.
5. Cookie Confirm.

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`