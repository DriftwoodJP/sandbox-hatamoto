<?php
/**
 * Plugin Name: Sandbox Hatamoto
 * Plugin URI:  https://example.com
 * Description: This is a awesome cool plugin.
 * Version:     0.1.0
 * Author:      Foo Bar
 * Author URI:  https://example.com
 * License:     GPLv2
 * Text Domain: sandbox_hatamoto
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2017 Foo Bar ( https://example.com )
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


require( dirname( __FILE__ ).'/vendor/autoload.php' );


define( 'SANDBOX_HATAMOTO_URL',  plugins_url( '', __FILE__ ) );
define( 'SANDBOX_HATAMOTO_PATH', dirname( __FILE__ ) );

$sandbox_hatamoto = new Sandbox_Hatamoto();
$sandbox_hatamoto->register();

class Sandbox_Hatamoto {

private $version = '';
private $langs   = '';

function __construct()
{
    $data = get_file_data(
        __FILE__,
        array( 'ver' => 'Version', 'langs' => 'Domain Path' )
    );
    $this->version = $data['ver'];
    $this->langs   = $data['langs'];
}

public function register()
{
    add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
}

public function plugins_loaded()
{
    load_plugin_textdomain(
        'sandbox_hatamoto',
        false,
        dirname( plugin_basename( __FILE__ ) ).$this->langs
    );

    add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    add_action( 'admin_init', array( $this, 'admin_init' ) );

}

public function admin_menu()
{
    // See http://codex.wordpress.org/Administration_Menus
    add_options_page(
        __( 'Sandbox Hatamoto', 'sandbox_hatamoto' ),
        __( 'Sandbox Hatamoto', 'sandbox_hatamoto' ),
        'manage_options', // http://codex.wordpress.org/Roles_and_Capabilities
        'sandbox_hatamoto',
        array( $this, 'options_page' )
    );
}

public function admin_init()
{
    if ( isset( $_POST['_wpnonce_sandbox_hatamoto'] ) && $_POST['_wpnonce_sandbox_hatamoto'] ){
        if ( check_admin_referer( 'ot0kr5rzg5pim9bnnf4j2', '_wpnonce_sandbox_hatamoto' ) ){

            // save something

            wp_safe_redirect( menu_page_url( 'sandbox_hatamoto', false ) );
        }
    }
}

public function options_page()
{
?>
<div id="sandbox_hatamoto" class="wrap">
<h2><?php _e( 'Sandbox Hatamoto', 'sandbox_hatamoto' ); ?></h2>

<form method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>">
<?php wp_nonce_field( 'ot0kr5rzg5pim9bnnf4j2', '_wpnonce_sandbox_hatamoto' ); ?>

Admin Panel Here!

<p style="margin-top: 3em;">
    <input type="submit" name="submit" id="submit" class="button button-primary"
            value="<?php _e( "Save Changes", "sandbox_hatamoto" ); ?>"></p>
</form>
</div><!-- #sandbox_hatamoto -->
<?php
}

public function admin_enqueue_scripts($hook)
{
    if ( 'settings_page_sandbox_hatamoto' === $hook ) {
        wp_enqueue_style(
            'admin-sandbox_hatamoto-style',
            plugins_url( 'css/admin-sandbox-hatamoto.min.css', __FILE__ ),
            array(),
            $this->version,
            'all'
        );

        wp_enqueue_script(
            'admin-sandbox_hatamoto-script',
            plugins_url( 'js/admin-sandbox-hatamoto.min.js', __FILE__ ),
            array( 'jquery' ),
            $this->version,
            true
        );
    }
}

public function wp_enqueue_scripts()
{
    wp_enqueue_style(
        'sandbox-hatamoto-style',
        plugins_url( 'css/sandbox-hatamoto.min.css', __FILE__ ),
        array(),
        $this->version,
        'all'
    );

    wp_enqueue_script(
        'sandbox-hatamoto-script',
        plugins_url( 'js/sandbox-hatamoto.min.js', __FILE__ ),
        array( 'jquery' ),
        $this->version,
        true
    );
}

} // end class Sandbox_Hatamoto

add_shortcode( 'twitter', 'twitter_shortcode' );
function twitter_shortcode( $p, $content ) {
	$content = str_replace( "@", '', $content );

	if ( !preg_match( "/^[0-9a-z_]{1,15}$/i", $content ) ) {
		return;
	}

	return sprintf(
		'<a href="https://twitter.com/%s">@%s</a>',
		esc_attr( $content ),
		esc_html( $content )
	);
}

// EOF
