<?php
/*
  Plugin Name: Under Construction
  Plugin URI: https://wordpress.org/plugins/under-construction-page/
  Description: Hide your site behind a great looking under construction page while you do maintenance work.
  Author: Web factory Ltd
  Version: 1.25
  Author URI: http://www.webfactoryltd.com/
  Text Domain: under-construction-page
  Domain Path: lang

  Copyright 2015 - 2016  Web factory Ltd  (email : ucp@webfactoryltd.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}


define('UCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('UCP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('UCP_OPTIONS_KEY', 'ucp_options');
define('UCP_META_KEY', 'ucp_meta');
define('UCP_POINTERS_KEY', 'ucp_pointers');
define('UCP_NOTICES_KEY', 'ucp_notices');

// main plugin class
class UCP {
  static $version = 0;

  
  // get plugin version from header
  static function get_plugin_version() {
    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');
    self::$version = $plugin_data['version'];
     
    return $plugin_data['version'];
  } // get_plugin_version
  
  
  // hook things up  
  static function init() {
    // check if minimal required WP version is present
    if (false === self::check_wp_version(4.0)) {
      return false;
    }
      
    if (is_admin()) {
      // if the plugin was updated from ver < 1.20 upgrade settings array
      self::maybe_upgrade();
      
      // add UCP menu to admin tools menu group
      add_action('admin_menu', array(__CLASS__, 'admin_menu'));

      // settings registration
      add_action('admin_init', array(__CLASS__, 'register_settings'));

      // aditional links in plugin description
      add_filter('plugin_action_links_' . plugin_basename(__FILE__),
                            array(__CLASS__, 'plugin_action_links'));
      add_filter('plugin_row_meta', array(__CLASS__, 'plugin_meta_links'), 10, 2);

      // manages admin header notifications
      add_action('admin_notices', array(__CLASS__, 'admin_notices'));
      add_action('admin_action_ucp_dismiss_notice', array(__CLASS__, 'dismiss_notice'));
      
      // enqueue admin scripts
      add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'));
      
      // AJAX endpoints
      add_action('wp_ajax_ucp_dismiss_pointer', array(__CLASS__, 'dismiss_pointer_ajax'));
    } else {
      // main logic
      add_action('wp', array(__CLASS__, 'display_construction_page'), 0, 1);

      // disable feeds
      add_action('do_feed_rdf', array(__CLASS__, 'disable_feed'), 1, 1);
      add_action('do_feed_rss', array(__CLASS__, 'disable_feed'), 1, 1);
      add_action('do_feed_rss2', array(__CLASS__, 'disable_feed'), 1, 1);
      add_action('do_feed_atom', array(__CLASS__, 'disable_feed'), 1, 1);
    } // if not admin

    // admin bar notice for frontend & backend
    add_action('wp_before_admin_bar_render', array(__CLASS__, 'admin_bar_notice'));
  } // init

  
  // check if user has the minimal WP version required by UCP
  static function check_wp_version($min_version) {
    if (!version_compare(get_bloginfo('version'), $min_version,  '>=')) {
        add_action('admin_notices', array(__CLASS__, 'notice_min_wp_version'));
      return false;
    } else {
      return true;  
    }
  } // check_wp_version
  
  
  // display error message if WP version is too low
  static function notice_min_wp_version() {
    echo '<div class="error"><p>' . sprintf('Under Construction plugin <b>requires WordPress version 4.0</b> or higher to function properly. You are using WordPress version %s. Please <a href="%s">update it</a>.', get_bloginfo('version'), admin_url('update-core.php')) . '</p></div>';
  } // notice_min_wp_version_error
  
  
  // some things have to be loaded earlier
  static function plugins_loaded() {
    self::get_plugin_version();
    
    load_plugin_textdomain('under-construction-page');
  } // plugins_loaded
  
  
  // activate doesn't get fired on upgrades so we have to compensate
  public static function maybe_upgrade() {
    $meta = self::get_meta();
    
    // check if we need to convert options from the old format to new, or maybe it is already done
    if (isset($meta['options_ver']) && $meta['options_ver'] == self::$version) {
      return;
    }    
    
    if (get_option('set_size') || get_option('set_tweet') || get_option('set_fb') || get_option('set_font') || get_option('set_msg') || get_option('set_opt') || get_option('set_admin')) {
      // convert old options to new
      $options = self::get_options();
      $options['status'] = (get_option('set_opt') === 'Yes')? '1': '0';
      $options['content'] = trim(get_option('set_msg'));
      $options['roles'] = (get_option('set_admin') === 'No')? array('administrator'): array();
      $options['social_facebook'] = trim(get_option('set_fb'));
      $options['social_twitter'] = trim(get_option('set_tweet'));
      update_option(UCP_OPTIONS_KEY, $options);
      
      delete_option('set_size');
      delete_option('set_tweet');
      delete_option('set_fb');
      delete_option('set_font');
      delete_option('set_msg');
      delete_option('set_opt');
      delete_option('set_admin');
      
      self::reset_pointers();
    }

    // we update only once    
    $meta['options_ver'] = self::$version;
    update_option(UCP_META_KEY, $meta);
  } // maybe_upgrade
  
  
  // get plugin's options
  static function get_options() {
    $options = get_option(UCP_OPTIONS_KEY, array());

    if (!is_array($options)) {
      $options = array();
    }
    $options = array_merge(self::default_options(), $options);

    return $options;
  } // get_options
  
  
  // get plugin's meta data
  static function get_meta() {
    $meta = get_option(UCP_META_KEY, array());

    if (!is_array($meta) || empty($meta)) {
      $meta['first_version'] = self::get_plugin_version();
      $meta['first_install'] = current_time('timestamp');
      update_option(UCP_META_KEY, $meta);
    }

    return $meta;
  } // get_meta
  

  // fetch and display construction the page if it's enabled
  static function display_construction_page() {
    $options = self::get_options();

    if (true == self::is_construction_mode_enabled(false)) {
      header(wp_get_server_protocol() . ' 503 Service Unavailable');
      echo self::get_template($options['theme']);
      exit;
    }
  } // display_construction_page

  
  // disables feed if necessary
  static function disable_feed() {
    if (true == self::is_construction_mode_enabled(false)) {
      echo '<?xml version="1.0" encoding="UTF-8" ?><status>Service unavailable.</status>';
      exit;
    }
  } // disable_feed

  
  // enqueue CSS and JS scripts in admin
  static function admin_enqueue_scripts($hook) {
    wp_enqueue_style('ucp-toolbar', UCP_PLUGIN_URL . 'css/ucp-toolbar.css', array(), self::$version);
    
    $js_localize = array('undocumented_error' => __('An undocumented error has occured. Please refresh the page and try again.', 'under-construction-page'),
                         'plugin_name' => __('Under Construction', 'under-construction-page'),
                         'settings_url' => admin_url('options-general.php?page=ucp'),
                         'deactivate_confirmation' => __('Are you sure you want to deactivate Under Construction plugin?' . "\n" . 'If you are removing it because of a problem please contact our support. They will be more than happy to help.', 'under-construction-page'));
                         
    if ('settings_page_ucp' == $hook) {
      wp_enqueue_style('ucp-admin', UCP_PLUGIN_URL . 'css/ucp-admin.css', array(), self::$version);
      wp_enqueue_script('ucp-admin', UCP_PLUGIN_URL . 'js/ucp-admin.js', array('jquery'), self::$version, true);
    }
    
    if ('plugins.php' == $hook) {
      wp_enqueue_script('ucp-admin-plugins', UCP_PLUGIN_URL . 'js/ucp-admin-plugins.js', array('jquery'), self::$version, true);
      wp_localize_script('ucp-admin-plugins', 'ucp', $js_localize);
    }
    
    $pointers = get_option(UCP_POINTERS_KEY);
    if ($pointers && 'settings_page_ucp' != $hook) {
      $pointers['_nonce_dismiss_pointer'] = wp_create_nonce('ucp_dismiss_pointer');
      wp_enqueue_script('wp-pointer');
      wp_enqueue_script('ucp-pointers', plugins_url('js/ucp-admin-pointers.js', __FILE__), array('jquery'), self::$version, true);
      wp_enqueue_style('wp-pointer');
      wp_localize_script('wp-pointer', 'ucp_pointers', $pointers);
    }
  } // admin_enqueue_scripts
  
  
  // permanently dismiss a pointer
  static function dismiss_pointer_ajax() {
    check_ajax_referer('ucp_dismiss_pointer');
    
    $pointers = get_option(UCP_POINTERS_KEY);
    $pointer = trim($_POST['pointer']);

    if (empty($pointers) || empty($pointers[$pointer])) {
      wp_send_json_error();
    }

    unset($pointers[$pointer]);
    update_option(UCP_POINTERS_KEY, $pointers);
    
    wp_send_json_success();
  } // dismiss_pointer_ajax
  
  
  // parse shortcode alike variables
  static function parse_vars($string) {
    $vars = array('site-title' => get_bloginfo('name'), 
                  'site-tagline' => get_bloginfo('description'), 
                  'site-description' => get_bloginfo('description'), 
                  'site-url' => trailingslashit(get_home_url()), 
                  'wp-url' => trailingslashit(get_site_url()),
                  'site-login-url' => get_site_url() . '/wp-login.php');
    
    foreach ($vars as $var_name => $var_value) {
      $var_name = '[' . $var_name . ']';
      $string = str_ireplace($var_name, $var_value, $string);
    }
    
    return $string;
  } // parse_vars
  
  
  // generate HTML from social icons
  static function generate_social_icons($options, $template_id) {
    $out = '';
    
    if (!empty($options['social_facebook'])) {
      $out .= '<a href="' . $options['social_facebook'] . '" target="_blank"><i class="fa fa-facebook-square fa-3x"></i></a>';
    }
    if (!empty($options['social_twitter'])) {
      $out .= '<a href="' . $options['social_twitter'] . '" target="_blank"><i class="fa fa-twitter-square fa-3x"></i></a>';
    }
    if (!empty($options['social_google'])) {
      $out .= '<a href="' . $options['social_google'] . '" target="_blank"><i class="fa fa-google-plus-square fa-3x"></i></a>';
    }
    if (!empty($options['social_linkedin'])) {
      $out .= '<a href="' . $options['social_linkedin'] . '" target="_blank"><i class="fa fa-linkedin-square fa-3x"></i></a>';
    }
    if (!empty($options['social_youtube'])) {
      $out .= '<a href="' . $options['social_youtube'] . '" target="_blank"><i class="fa fa-youtube-square fa-3x"></i></a>';
    }
    if (!empty($options['social_pinterest'])) {
      $out .= '<a href="' . $options['social_pinterest'] . '" target="_blank"><i class="fa fa-pinterest-square fa-3x"></i></a>';
    }
    
    return $out;
  } // generate_social_icons
  
   
  // returnes parsed template
  static function get_template($template_id) {
    $vars = array();
    $options = self::get_options();

    $vars['version'] = self::$version;
    $vars['site-url'] = trailingslashit(get_home_url());
    $vars['wp-url'] = trailingslashit(get_site_url());
    $vars['theme-url'] = trailingslashit(UCP_PLUGIN_URL . 'themes/' . $template_id);
    $vars['theme-url-common'] = trailingslashit(UCP_PLUGIN_URL . 'themes');
    $vars['title'] = self::parse_vars($options['title']);
    $vars['heading1'] = self::parse_vars($options['heading1']);
    $vars['content'] = nl2br(self::parse_vars($options['content']));
    $vars['description'] = get_bloginfo('description');
    $vars['social-icons'] = self::generate_social_icons($options, $template_id);
    
    ob_start();
    require UCP_PLUGIN_DIR . 'themes/' . $template_id . '/index.php';
    $template = ob_get_clean();
    
    foreach ($vars as $var_name => $var_value) {
      $var_name = '[' . $var_name . ']';
      $template = str_ireplace($var_name, $var_value, $template);
    }
    
    return $template;
  } // get_template

  
  // checks if construction mode is enabled for the current visitor
  static function is_construction_mode_enabled($global = false) {
    $options = self::get_options();
    
    if ($global) {
      if ($options['status']) {
        return true;
      } else {
        return false;
      }
    } else {
      // check if enabled for current user
      if (!$options['status']) {
        return false;
      } elseif (self::user_has_role($options['roles'])) {
        return false;
      } else {
        return true;
      }
    }
  } // is_construction_mode_enabled

  
  // check if user has the specified role
  static function user_has_role($roles) {
    $current_user = wp_get_current_user();

    if ($current_user->roles) {
      $user_role = $current_user->roles[0];
    } else {
      $user_role = 'guest';
    }

    return in_array($user_role, $roles);
  } // user_has_role

  
  // displays various notices in admin header
  static function admin_notices() {
    // temporary disabled, todo
    if (0 && self::is_construction_mode_enabled(true)) {
      echo '<div id="message" class="error"><p>Caution: Under Construction mode is <strong>enabled</strong>! Edit <a href="' . admin_url('options-general.php?page=ucp') . '" title="Under Construction Settings">settings</a> to disable it.</p></div>';
    }
    
    $notices = get_option(UCP_NOTICES_KEY);
    $meta = self::get_meta();
    
    if (empty($notices['dismiss_rate']) &&
        (current_time('timestamp') - $meta['first_install']) > (DAY_IN_SECONDS * 3)) {
      $rate_url = 'https://wordpress.org/support/plugin/under-construction-page/reviews/#new-post';
      $dismiss_url = add_query_arg(array('action' => 'ucp_dismiss_notice', 'notice' => 'rate', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

      echo '<div id="ucp_rate_notice" class="notice-info notice"><p>Hi! We saw you\'ve been using <b>Under Construction</b> plugin for a few days and wanted to ask for your help to <b>make the plugin better</b>.<br>We just need a minute of your time to rate the plugin. Thank you!';

      echo '<br><a target="_blank" href="' . esc_url($rate_url) . '" style="vertical-align: baseline; margin-top: 15px;" class="button-primary">Help us out &amp; rate the plugin</a>';
      echo '&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '">I\'ve already rated the plugin</a>';
      echo '</p></div>';  
    }
  } // notices
  
  
  // handle dismiss button for notices
  static function dismiss_notice() {
    if (empty($_GET['notice'])) {
      wp_redirect(admin_url());
      exit;
    }
    
    $notices = get_option(UCP_NOTICES_KEY, array());
    
    if ($_GET['notice'] == 'rate') {
      $notices['dismiss_rate'] = true;
    }
    
    update_option(UCP_NOTICES_KEY, $notices);

    if (!empty($_GET['redirect'])) {
      wp_redirect($_GET['redirect']);
    } else {
      wp_redirect(admin_url());
    }

    exit;
  } // dismiss_notice

  
  // add admin bar notice when construction is enabled
  static function admin_bar_notice() {
    global $wp_admin_bar;

    if (self::is_construction_mode_enabled(true)) {
      $title = '<span class="dashicons dashicons-admin-generic"></span> <span class="ab-label">Under Construction mode is <strong>enabled</strong></span>';
      $class = 'ucp-enabled';
    } else {
      $title = '<span class="dashicons dashicons-admin-generic"></span> <span class="ab-label">Under Construction mode is disabled</span>';
      $class = 'ucp-disabled';
    }
    
    $wp_admin_bar->add_menu(array(
        'parent' => '',
        'id' => 'construction-mode',
        'title' => $title,
        'href' => admin_url('options-general.php?page=ucp'),
        'meta'  => array('class' => $class)
    ));
  } // admin_bar_notice

  
  // add settings link to plugins page
  static function plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=ucp') . '" title="Under Construction Settings">Settings</a>';
    array_unshift($links, $settings_link);

    return $links;
  } // plugin_action_links
  
  
  // add links to plugin's description in plugins table
  static function plugin_meta_links($links, $file) {
    $support_link = '<a target="_blank" href="https://wordpress.org/support/plugin/under-construction-page" title="Get help">Support</a>';

    if ($file == plugin_basename(__FILE__)) {
      $links[] = $support_link;
    }

    return $links;
  } // plugin_meta_links
  

  // create the admin menu item
  static function admin_menu() {
    add_options_page('Under Construction', 'Under Construction', 'manage_options', 'ucp', array(__CLASS__, 'options_page'));
  } // admin_menu

  
  // all settings are saved in one option
  static function register_settings() {
    register_setting(UCP_OPTIONS_KEY, UCP_OPTIONS_KEY, array(__CLASS__, 'sanitize_settings'));
  } // register_settings

  
  // set default settings
  static function default_options() {
    $defaults = array('status' => '0',
                      'theme' => 'mad_designer',
                      'title' => '[site-title] is under construction',
                      'heading1' => 'Sorry, we\'re doing some work on the site',
                      'content' => 'Thank you for being patient. We are doing some work on the site and will be back shortly.',
                      'social_facebook' => '',
                      'social_twitter' => '',
                      'social_google' => '',
                      'social_linkedin' => '',
                      'social_youtube' => '',
                      'social_pinterest' => '',
                      'roles' => array('administrator')
                      );

    return $defaults;
  } // default_options
  

  // sanitize settings on save
  static function sanitize_settings($options) {
    $old_options = self::get_options();

    foreach ($options as $key => $value) {
      switch ($key) {
        case 'title':
        case 'heading1':
        case 'content':
        case 'social_facebook':
        case 'social_twitter':
        case 'social_google':
        case 'social_linkedin':
        case 'social_youtube':
        case 'social_pinterest':
          $options[$key] = trim($value);
        break;
      } // switch
    } // foreach
    
    $options['roles'] = (array) $options['roles'];
    $options = self::check_var_isset($options, array('status' => 0));

    if ($options['status'] != $old_options['status']) {
      if (function_exists('w3tc_pgcache_flush')) {
        w3tc_pgcache_flush(); 
      } 
      if (function_exists('wp_cache_clean_cache')) {
        global $file_prefix;
        wp_cache_clean_cache($file_prefix); 
      }
    }
    
    return array_merge($old_options, $options);
  } // sanitize_settings
  
  
  // checkbox helper function
  static function checked($value, $current, $echo = false) {
    $out = '';

    if (!is_array($current)) {
      $current = (array) $current;
    }

    if (in_array($value, $current)) {
      $out = ' checked="checked" ';
    }

    if ($echo) {
      echo $out;
    } else {
      return $out;
    }
  } // checked
  
  
  // helper function for saving options, mostly checkboxes
  static function check_var_isset($values, $variables) {
    foreach ($variables as $key => $value) {
      if (!isset($values[$key])) {
        $values[$key] = $value;
      }
    }
    
    return $values;
  } // check_var_isset
  
  
  // helper function for creating dropdowns
  static function create_select_options($options, $selected = null, $output = true) {
    $out = "\n";

    if(!is_array($selected)) {
      $selected = array($selected);
    }

    foreach ($options as $tmp) {
      $data = '';
      if (isset($tmp['disabled'])) {
        $data .= ' disabled="disabled" ';
      }
      if (in_array($tmp['val'], $selected)) {
        $out .= "<option selected=\"selected\" value=\"{$tmp['val']}\"{$data}>{$tmp['label']}&nbsp;</option>\n";
      } else {
        $out .= "<option value=\"{$tmp['val']}\"{$data}>{$tmp['label']}&nbsp;</option>\n";
      }
    } // foreach

    if ($output) {
      echo $out;
    } else {
      return $out;
    }
  } // create_select_options

  
  // output the whole options page
  static function options_page() {
    if (!current_user_can('manage_options'))  {
      wp_die('You do not have sufficient permissions to access this page.');
    }

    $options = self::get_options();
    $default_options = self::default_options();

    echo '<div class="wrap">
          <h1>Under Construction</h1>';

    echo '<form action="options.php" method="post">';
    settings_fields(UCP_OPTIONS_KEY);

    echo '<table class="form-table"><tbody>';

    $status[] = array('val' => '0', 'label' => 'Disabled - site is working normally');
    $status[] = array('val' => '1', 'label' => 'Enabled - site is in under construction mode');

    $tmp_roles = get_editable_roles();
    foreach ($tmp_roles as $tmp_role => $details) {
      $name = translate_user_role($details['name']);
      $roles[] = array('val' => $tmp_role,  'label' => $name);
    }
    $roles[] = array('val' => 'guest', 'label' => 'Guest (not logged in user)');

    echo '<tr valign="top">
    <th scope="row"><label for="status">Status</label></th>
    <td><div class="onoffswitch">
    <input ' . self::checked(1, $options['status']) . ' type="checkbox" value="1" name="' . UCP_OPTIONS_KEY . '[status]" class="onoffswitch-checkbox" id="status">
    <label class="onoffswitch-label" for="status">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</div>
    ';
    
    echo '<p class="description">By enabling construction mode all users (<a href="#whitelisted-roles">except selected ones</a>) will not be able to access the site\'s content. They will only see the under construction page.</p>';
    echo '</td></tr>';

    $img = plugins_url('/images/', __FILE__);
    echo '<tr valign="top">
    <th scope="row">Theme</th>
    <td>
    <div class="ucp-thumb"><label for="layout-1"><img src="' . $img . 'mad_designer.png" alt="Mad Designer" title="Mad Designer" /></label><br /><input ' . self::checked('mad_designer', $options['theme']) . ' type="radio" id="layout-1" name="' . UCP_OPTIONS_KEY . '[theme]" value="mad_designer" /> Mad Designer</div>
    
    <div class="ucp-thumb"><label for="layout-2"><img src="' . $img . 'plain_text.png" alt="Plain Text" title="Plain Text" /></label><br /><input ' . self::checked('plain_text', $options['theme']) . ' type="radio" id="layout-2" name="' . UCP_OPTIONS_KEY . '[theme]" value="plain_text" /> Plain Text</div>
    
    <div class="ucp-thumb"><label for="layout-3"><img src="' . $img . 'under_construction.png" alt="Under Construction" title="Under Construction" /></label><br /><input ' . self::checked('under_construction', $options['theme']) . ' type="radio" id="layout-3" name="' . UCP_OPTIONS_KEY . '[theme]" value="under_construction" /> Under Construction</div>
    
    <div class="ucp-thumb"><label for="layout-4"><img src="' . $img . 'dark.png" alt="Things Went Dark" title="Things Went Dark" /></label><br /><input ' . self::checked('dark', $options['theme']) . ' type="radio" id="layout-4" name="' . UCP_OPTIONS_KEY . '[theme]" value="dark" /> Things Went Dark</div>
    
    <div class="ucp-thumb"><a href="https://twitter.com/intent/tweet?text=' . urlencode('@webfactoryltd I need more themes for Under Construction #wordpress plugin. When are they coming out?') . '&url=https://wordpress.org/plugins/under-construction-page/" target="_blank"><img src="' . $img . 'more_coming_soon.png" alt="Need more themes?" title="Need more themes?" /></a><br />Click for More Themes</div>
    </td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="title">Title</label></th>
    <td><input type="text" id="title" class="regular-text" name="' . UCP_OPTIONS_KEY . '[title]" value="' . $options['title'] . '" />';
    echo '<p class="description">Page title. Default: ' . $default_options['title'] . '</p>';
    echo '<p><b>Available shortcodes:</b> (only active in UC themes, not on the rest of the site)</p>
    <ul class="ucp-list">
    <li><code>[site-title]</code> - blog title, as set in <a href="options-general.php">Options - General</a></li>
    <li><code>[site-tagline]</code> - blog tagline, as set in <a href="options-general.php">Options - General</a></li>
    <li><code>[site-url]</code> - site address (URL), as set in <a href="options-general.php">Options - General</a></li>
    <li><code>[wp-url]</code> - WordPress address (URL), as set in <a href="options-general.php">Options - General</a></li>
    <li><code>[site-login-url]</code> - URL to site login page</li>
    </ul>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="heading1">Headline</label></th>
    <td><input id="heading1" type="text" class="large-text" name="' . UCP_OPTIONS_KEY . '[heading1]" value="' . $options['heading1'] . '" />';
    echo '<p class="description">Page heading/title (see above for available <a href="#title">shortcodes</a>). Default: ' . $default_options['heading1'] . '</p>';
    echo '</td></tr>';

    echo '<tr valign="top">
    <th scope="row"><label for="content">Content</label></th>
    <td>';
    wp_editor($options['content'], 'content', array('tabfocus_elements' => 'insert-media-button,save-post', 'editor_height' => 250, 'resize' => 1, 'textarea_name' => UCP_OPTIONS_KEY . '[content]', 'drag_drop_upload' => 1));
    echo '<p class="description">All HTML elements are allowed. Shortcodes are not parsed except <a href="#title">UC template ones</a>. Default: ' . $default_options['content'] . '</p>';
    echo '</td></tr>';
    
    echo '<tr valign="top">
    <th scope="row"><label for="social_facebook">Facebook Page</label></th>
    <td><input id="social_facebook" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_facebook]" value="' . $options['social_facebook'] . '" placeholder="Facebook business or personal page URL">';
    echo '<p class="description">Complete URL, with http prefix, to Facebook page.</p>';
    echo '</td></tr>';
    
    echo '<tr valign="top">
    <th scope="row"><label for="social_twitter">Twitter Profile</label></th>
    <td><input id="social_twitter" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_twitter]" value="' . $options['social_twitter'] . '" placeholder="Twitter profile URL">';
    echo '<p class="description">Complete URL, with http prefix, to Twitter profile page.</p>';
    echo '</td></tr>';
    
    echo '<tr valign="top">
    <th scope="row"><label for="social_google">Google Page</label></th>
    <td><input id="social_google" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_google]" value="' . $options['social_google'] . '" placeholder="Google+ page URL">';
    echo '<p class="description">Complete URL, with http prefix, to Google+ page.</p>';
    echo '</td></tr>';
    
    echo '<tr valign="top">
    <th scope="row"><label for="social_linkedin">LinkedIn Profile</label></th>
    <td><input id="social_linkedin" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_linkedin]" value="' . $options['social_linkedin'] . '" placeholder="LinkedIn profile page URL">';
    echo '<p class="description">Complete URL, with http prefix, to LinkedIn profile page.</p>';
    echo '</td></tr>';
    
    echo '<tr valign="top">
    <th scope="row"><label for="social_youtube">YouTube Profile Page or Video</label></th>
    <td><input id="social_youtube" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_youtube]" value="' . $options['social_youtube'] . '" placeholder="YouTube page or video URL">';
    echo '<p class="description">Complete URL, with http prefix, to YouTube page or video.</p>';
    echo '</td></tr>';
    
    echo '<tr valign="top">
    <th scope="row"><label for="social_pinterest">Pinterest Profile</label></th>
    <td><input id="social_pinterest" type="url" class="regular-text code" name="' . UCP_OPTIONS_KEY . '[social_pinterest]" value="' . $options['social_pinterest'] . '" placeholder="Pinterest profile URL">';
    echo '<p class="description">Complete URL, with http prefix, to Pinterest profile.</p>';
    echo '</td></tr>';

    echo '<tr valign="top" id="whitelisted-roles">
    <th scope="row">Whitelisted User Roles</th>
    <td>';
    
    foreach ($roles as $tmp_role) {
      echo  '<input name="' . UCP_OPTIONS_KEY . '[roles][]" id="roles-' . $tmp_role['val'] . '" ' . self::checked($tmp_role['val'], $options['roles'], false) . ' value="' . $tmp_role['val'] . '" type="checkbox" /> <label for="roles-' . $tmp_role['val'] . '">' . $tmp_role['label'] . '</label><br />';
    }
    echo '<p class="description">Selected user roles will <b>not</b> be affected by the under construction mode and will always see the "normal" site. Default: administrator.</p>';
    echo '</td></tr>';

    echo '</tbody></table>';

    echo get_submit_button('Save Changes');
    echo '</form></div>';
  } // options_page
  
  
  // reset all pointers to default state - visible
  static function reset_pointers() {
    $pointers = array();
    $pointers['welcome'] = array('target' => '#menu-settings', 'edge' => 'left', 'align' => 'right', 'content' => 'Thank you for installing the <b>Under Construction</b> plugin! Please open <a href="' . admin_url('options-general.php?page=ucp'). '">Settings - Under Construction</a> to create a beautiful under construction page.');
    
    update_option(UCP_POINTERS_KEY, $pointers);
  } // reset_pointers
  
  
  // reset pointers on activation
  static function activate() {
    self::reset_pointers();
  } // activate
  
  // clean up on deactivation
  static function deactivate() {
    delete_option(UCP_POINTERS_KEY);
    delete_option(UCP_NOTICES_KEY);
  } // deactivate
  
  
  // clean up on uninstall
  static function uninstall() {
    delete_option(UCP_OPTIONS_KEY);
    delete_option(UCP_META_KEY);
    delete_option(UCP_POINTERS_KEY);
    delete_option(UCP_NOTICES_KEY);
  } // uninstall
} // class UCP


// hook everything up
register_activation_hook(__FILE__, array('UCP', 'activate'));
register_deactivation_hook(__FILE__, array('UCP', 'deactivate'));
register_uninstall_hook(__FILE__, array('UCP', 'uninstall'));
add_action('init', array('UCP', 'init'));
add_action('plugins_loaded', array('UCP', 'plugins_loaded'));
