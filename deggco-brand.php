<?php
/*
Plugin Name: DEGG CO Brand
Plugin URI: http://degg.co
Description: Degg Company, LLC branding.
Version: 1.0
Author: Travis Scheidegger
Author URI: http://travisscheidegger.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


function degg_thank_you($string) {
  $string = '<span id="footer-thankyou">' . __( 'Thank you for creating with <a href="http://degg.co/">Degg Company, LLC</a>.' ) . '</span>';
  return $string;
}

add_filter('admin_footer_text', 'degg_thank_you');

function degg_admin_editor_styles() {
  wp_enqueue_style('degg_admin_editor', plugin_dir_url( __FILE__ ) . 'assets/css/editor-role.css', false, null);
}

function degg_admin_styles() {
  wp_enqueue_style('degg_admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', false, null);
}

function degg_enqueue_style() {
  if (is_admin_bar_showing()) add_action('wp_enqueue_scripts', 'degg_admin_styles');
  add_action('admin_print_styles', 'degg_admin_styles');
  add_action('login_enqueue_scripts', 'degg_admin_styles');
}

add_action('init', 'degg_enqueue_style');

function degg_remove_notifications() {
    if (!current_user_can('edit_dashboard')) {
        delete_site_transient( 'update_core' );
        remove_action( 'admin_init', '_maybe_update_core' );
        remove_action( 'wp_version_check', 'wp_version_check' );
        wp_clear_scheduled_hook( 'wp_version_check' );
        
        remove_action( 'init', 'wp_schedule_update_checks' );
        
        delete_site_transient( 'update_plugins' );
        remove_action( 'admin_init', '_maybe_update_plugins' );
        remove_action( 'load-plugins.php', 'wp_update_plugins' );
        remove_action( 'load-update-core.php', 'wp_update_plugins' );
        remove_action( 'load-update.php', 'wp_update_plugins' );
        remove_action( 'wp_update_plugins', 'wp_update_plugins' );
        wp_clear_scheduled_hook( 'wp_update_plugins' );
        
        delete_site_transient( 'update_themes' );
        remove_action( 'admin_init', '_maybe_update_themes' );
        remove_action( 'load-themes.php', 'wp_update_themes' );
        remove_action( 'load-update-core.php', 'wp_update_themes' );
        remove_action( 'load-update.php', 'wp_update_themes' );
        remove_action( 'wp_update_themes', 'wp_update_themes' );
        wp_clear_scheduled_hook( 'wp_update_themes' );

        add_action('admin_print_styles', 'degg_admin_editor_styles');
    }
}

add_action('plugins_loaded', 'degg_remove_notifications');

function degg_admin_bar_menu($wp_admin_bar) {
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->add_menu( array(
        'id'    => 'deggco',
        'title' => '<span class="deggco-icon"></span>',
        'href'  => 'http://degg.co',
        'meta'  => array(
            'title' => __('DEGG CO'),
        ),
    ) );

    if ( is_user_logged_in() ) {
        $wp_admin_bar->add_menu( array(
            'parent' => 'deggco',
            'id'     => 'deggco-support',
            'title'  => __('Contact Support'),
            'href'   => 'mailto:t@degg.co',
        ) );
    }
    return $wp_admin_bar;
}

add_action('admin_bar_menu', 'degg_admin_bar_menu', 11, 1);

function degg_remove_help($old_help, $screen_id, $screen){
    $screen->remove_help_tabs();
    return $old_help;
}

add_filter('contextual_help', 'degg_remove_help', 999, 3);

function degg_login_headerurl($url) {
    $url = 'http://degg.co/';
    return $url;
}

add_filter('login_headerurl', 'degg_login_headerurl');

function degg_login_headertitle($title) {
    $title = 'Powered by Degg Company, LLC';
    return $title;
}

add_filter('login_headertitle', 'degg_login_headertitle');