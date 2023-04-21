<?php
/*
Plugin Name: Headers Security Advanced & HSTS WP
Plugin URI: https://www.tentacleplugins.com/
Description:  Headers Security Advanced & HSTS WP - Simple, Light and Fast. The plugin uses advanced security rules that provide huge levels of protection and it is important that your site uses it. This step is important to submit your website and/or domain to an approved HSTS list. Google officially compiles this list and it is used by Chrome, Firefox, Opera, Safari, IE11 and Edge. You can forward your site to the official HSTS preload directory. Cross Site Request Forgery (CSRF) is a common attack with the installation of Headers Security Advanced & HSTS WP will help you mitigate CSRF on your Wordpress site.
Version: 5.0.21
Text Domain: headers-security-advanced-hsts-wp
Domain Path: /languages
Author: 🐙  Andrea Ferro, Augusto Bombana
Author URI: https://www.linkedin.com/in/andrea-ferro-55046186/                                                              
*          __
*      ___( o)>
*      \ <_. )
*       `---'   iron3     
*        
* -------------------------------------------------------------------------
* Headers Security Advanced & HSTS WP plugin for GLPI
* -------------------------------------------------------------------------
*
* LICENSE
*
* This file is part of Headers Security Advanced & HSTS WP.
*
* Headers Security Advanced & HSTS WP is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 3 of the License, or
* (at your option) any later version.
*
* Headers Security Advanced & HSTS WP is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Headers Security Advanced & HSTS WP. If not, see <http://www.gnu.org/licenses/>.
* -------------------------------------------------------------------------
* @copyright Copyright (C) 2014-2022 by Headers Security Advanced & HSTS WP plugin by Andrea Ferro.
* @license   AGPLv3 https://www.gnu.org/licenses/agpl-3.0.html
* -------------------------------------------------------------------------
*/

defined('ABSPATH') or die(__('Don\'t try to be smart with us, only real ninjas can enter here!', 'headers-security-advanced-hsts-wp'));

function Headers_Security_Advanced_HSTS_WP_Headers($headers){
    $HEadersSecurityAdvancedServerCheck = $_SERVER['SERVER_NAME'];
    $HEadersSecurityAdvancedCheck = str_replace('www.', '', $HEadersSecurityAdvancedServerCheck);
    
    $HEadersSecurityAdvancedServerCheck3 = $_SERVER['SERVER_NAME'];
    
    $headers['X-XSS-Protection'] = '1; mode=block';
    $headers['Access-Control-Allow-Origin'] = 'null';
    $headers['Access-Control-Allow-Methods'] = 'GET,PUT,POST,DELETE';
    $headers['Access-Control-Allow-Headers'] = 'Content-Type, Authorization';
    $headers['X-Content-Security-Policy'] = 'default-src \'self\'; img-src *; media-src * data:;';
    $headers['X-Content-Type-Options'] = 'nosniff';
    $headers['Content-Security-Policy'] = "upgrade-insecure-requests;";
    $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
    $headers['Cross-Origin-Embedder-Policy-Report-Only'] = 'unsafe-none; report-to="default"';
    $headers['Cross-Origin-Embedder-Policy'] = 'unsafe-none; report-to="default"';
    $headers['Cross-Origin-Opener-Policy'] = 'unsafe-none';
    $headers['Cross-Origin-Resource-Policy'] = 'cross-origin';
    $headers['X-Frame-Options'] = 'SAMEORIGIN';
    $headers['Permissions-Policy'] = "accelerometer=(), autoplay=(), camera=(), cross-origin-isolated=(), encrypted-media=(), fullscreen=*, geolocation=(self), gyroscope=(), keyboard-map=(), magnetometer=(), microphone=(), midi=(), payment=*, picture-in-picture=(), publickey-credentials-get=(), screen-wake-lock=(), sync-xhr=(), usb=(), xr-spatial-tracking=(), gamepad=(), serial=(), window-placement=()";
    $headers['Feature-Policy'] = "display-capture 'self'";
    $headers['X-Permitted-Cross-Domain-Policies'] = "none";
    
    return $headers;
}
add_filter('wp_headers', 'Headers_Security_Advanced_HSTS_WP_Headers');

function hsts_plugin_settings(){
add_options_page('Impostazioni HSTS', 'Headers Security Advanced & HSTS WP', 'manage_options', 'headers-security-advanced-hsts-wp-plugin', 'hsts_plugin_settings_page');
}
add_action('admin_menu', 'hsts_plugin_settings');

function HeaderSecurityAdvancedHSTSWPROSHUEheartStyle(){
wp_register_style('HeaderSecurityAdvancedHSTSWPROSHUEheartStyle', plugins_url('/assets/css/style-dist.css', __FILE__));
wp_enqueue_style('HeaderSecurityAdvancedHSTSWPROSHUEheartStyle');
}
add_action('admin_init', 'HeaderSecurityAdvancedHSTSWPROSHUEheartStyle');

function hsts_plugin_settings_page(){
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'headers-security-advanced-hsts-wp'));
} ?>
<div class="wrap HeaderSecurityAdvancedHSTSWPROSHUEbkgsuccess"><?php printf(esc_html__( '%1$sYour website is finally safe! 🚀🚀 %3$s%2$sImplement enhanced security headers, HSTS preload for optimum website protection.', 'headers-security-advanced-hsts-wp' ),'<strong>','</strong>','&nbsp'); ?></div>

<div class="wrap HeaderSecurityAdvancedHSTSWPROSHUEbkgpadw">
    <div class="HeaderSecurityAdvancedHSTSWPROSHUEbub1459bk">
        <div class="HeaderSecurityAdvancedHSTSWPROSHUEbub14591"></div>
        <div class="HeaderSecurityAdvancedHSTSWPROSHUEbub14592"></div>
    </div>

    <img class="HeaderSecurityAdvancedHSTSWPROSHUE_svgsize" src="data:image/png;base64,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" />
    <h2 class="HeaderSecurityAdvancedHSTSWPROSHUEhero2112"><?php printf(esc_html__('Headers Security Advanced & HSTS WP: The security of your website is our priority! %s🛸', 'headers-security-advanced-hsts-wp' ),'&nbsp'); ?></h2>

    <div class="HeaderSecurityAdvancedHSTSWPROSHUEgridgap1546">
        <div class="HeaderSecurityAdvancedHSTSWPROSHUEtxgrh1"><?php printf(esc_html__('The %1$sHeaders Security Advanced & HSTS WP%2$s security plugin for WordPress is an essential and completely free plugin to protect your WordPress site. ', 'headers-security-advanced-hsts-wp'),'<b>','</b>'); ?><?php printf(esc_html__('%1$sHeaders Security Advanced & HSTS WP%2$s adds security headers quickly to your site to protect it from threats such as phishing attacks, data theft and more. ', 'headers-security-advanced-hsts-wp'),'<b>','</b>'); ?><?php printf(esc_html__('%1$sHeaders Security Advanced & HSTS WP%2$s is easy to use and allows you to customize the HSTS Preload header to suit your needs. ', 'headers-security-advanced-hsts-wp'),'<b>','</b>'); ?><?php esc_html_e( 'The plugin is compatible with all major browsers and operating systems and can be installed on any WordPress site.', 'headers-security-advanced-hsts-wp' ); ?></div>
        <div class="HeaderSecurityAdvancedHSTSWPROSHUEtxgrh1"><?php printf(esc_html__('Please consider buying me a coffee to support my work on this %1$sHeaders Security Advanced & HSTS WP%2$s plugin.', 'headers-security-advanced-hsts-wp'),'<b>','</b>'); ?><br /><br />
            <table border="0px">
                <tr>
                    <td><a href="https://www.buymeacoffee.com/tentacleplugins" target="_blank"><img src="../wp-content/plugins/headers-security-advanced-hsts-wp/assets/images/orange-button-min.png" style="max-width: 90%;" alt="<?php esc_attr_e( 'buy me coffee', 'headers-security-advanced-hsts-wp' ); ?>" /></a></td>
                    <td> <a href="https://www.paypal.com/donate/?hosted_button_id=M72GQUM8CWTZS" target="_blank"><img src="../wp-content/plugins/headers-security-advanced-hsts-wp/assets/images/cyan-button-min.png" style="max-width: 90%;" alt="<?php esc_attr_e( 'buy me paypal', 'headers-security-advanced-hsts-wp' ); ?>" /></a></td>
                </tr>
            </table><br /><?php esc_html_e( 'I create projects available to everyone for free and I like to create simple but functional projects.', 'headers-security-advanced-hsts-wp' ); ?>
        </div>
        <a href="https://wordpress.org/plugins/headers-security-advanced-hsts-wp/" class="HeaderSecurityAdvancedHSTSWPROSHUEbkg">
            <div class="HeaderSecurityAdvancedHSTSWPROSHUErw1"><?php printf(esc_html__('%1$sDo you need help with the Headers Security Advanced & HSTS WP plugin?%2$s Don\'t worry, I\'ve got it covered!', 'headers-security-advanced-hsts-wp'),'<strong>','</strong>'); ?></div>
        </a>
        <a href="https://forms.gle/d8PYXYaKZJXRQzeh9" class="HeaderSecurityAdvancedHSTSWPROSHUEbkg">
            <div class="HeaderSecurityAdvancedHSTSWPROSHUErw1"><?php printf(esc_html__('%1$sHelp us decide what the next features of our plugin will be!%2$s Take part in our nerdy survey!', 'headers-security-advanced-hsts-wp'),'<strong>','</strong>'); ?></div>
        </a>
    </div>

    <div class="HeaderSecurityAdvancedHSTSWPROSHUEgrid_settings HeaderSecurityAdvancedHSTSWPROSHUEselspace1128t">
        <h4 class="HeaderSecurityAdvancedHSTSWPROSHUEtboxy1440"><?php esc_html_e( 'Quick selection:', 'headers-security-advanced-hsts-wp' ); ?><h4>
                <div class="HeaderSecurityAdvancedHSTSWPROSHUEselspeed1127 HeaderSecurityAdvancedHSTSWPROSHUEselspace1128">
                    <div> <span class="HeaderSecurityAdvancedHSTSWPROSHUEbadge1950"><a href="#HSTSPRELOADLISTSUB"><?php esc_html_e( 'HSTS Preload List Submission', 'headers-security-advanced-hsts-wp' ); ?></a></span></div>
                </div>
                <form method="post" action="options.php">
                    <?php settings_fields('hsts-plugin-settings-group'); ?>
                    <?php do_settings_sections('hsts-plugin-settings-group'); ?>
                    <table class="form-table">
                        <tr id="HSTSPRELOADLISTSUB">
                            <th class="HeaderSecurityAdvancedHSTSWPROSHUEcltab1636"><label for="HeaderSecurityAdvancedHSTSWPROSHUErunFieldId">
                                    <h4 class="HeaderSecurityAdvancedHSTSWPROSHUEtboxy1348"><?php esc_html_e( 'Max-Age: ', 'headers-security-advanced-hsts-wp' ); ?></h4><span class="HeaderSecurityAdvancedHSTSWPROSHUEbadge1353"><?php esc_html_e( 'Beta', 'headers-security-advanced-hsts-wp' ); ?></span>
                                </label>
                                <p><span class="HeaderSecurityAdvancedHSTSWPROSHUEctd3"><?php esc_html_e( 'Max-Age is a parameter used in the HTTP Strict-Transport-Security (HSTS) header to specify the period of time (in seconds) for which the browser should store the HSTS information. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'During this time period, the browser will always use HTTPS to communicate with the website, even if the visitor has entered "http" or an HTTP link in the address bar. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'This helps protect the website and its visitors from man-in-the-middle (MITM) attacks and other security threats.', 'headers-security-advanced-hsts-wp' ); ?></span></p>
                            </th>
                            <td>
                                <input type="text" name="hsts_max_age" value="<?php echo esc_attr(get_option('hsts_max_age')); ?>" />
                                <div class="HeaderSecurityAdvancedHSTSWPROSHUEbox333">
                                    <p><span class="HeaderSecurityAdvancedHSTSWPROSHUEtxexttextSize"><?php esc_html_e( 'Regarding setting the "max-age" value for the HSTS header, it is advisable to set it to a high value, such as a full year (31536000 seconds). ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'This ensures that browsers continue to store security information for a long period of time, which helps protect users from man-in-the-middle attacks. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'However, it is important to keep in mind that setting the value too high could cause problems if you need to change your site\'s SSL configuration in the future. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'Therefore, it is important to carefully consider your usage and security needs before setting the value.', 'headers-security-advanced-hsts-wp' ); ?></span></p>
                                </div>
                            </td>
                        </tr>
                        <tr id="HSTSPRELOADLISTSUB">
                            <th class="HeaderSecurityAdvancedHSTSWPROSHUEcltab1636"><label for="HeaderSecurityAdvancedHSTSWPROSHUErunFieldId">
                                    <h4 class="HeaderSecurityAdvancedHSTSWPROSHUEtboxy1348"><?php esc_html_e( 'Include Subdomains: ', 'headers-security-advanced-hsts-wp' ); ?></h4><span class="HeaderSecurityAdvancedHSTSWPROSHUEbadge1353"><?php esc_html_e( 'Beta', 'headers-security-advanced-hsts-wp' ); ?></span>
                                </label>
                                <p><span class="HeaderSecurityAdvancedHSTSWPROSHUEctd3"><?php esc_html_e( 'IncludeSubDomains is an HTTP Strict Transport Security (HSTS) directive that specifies whether the header should also be applied to subdomains of the domain. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'When this directive is present, all requests to the subdomain are automatically redirected to the HTTPS protocol, providing enhanced security for the website and the users who visit it.', 'headers-security-advanced-hsts-wp' ); ?></span></p>
                            </th>
                            <td>
                                <input type="checkbox" name="hsts_include_subdomains" value="1" <?php checked(1, get_option('hsts_include_subdomains'), true); ?> />
                                <div class="HeaderSecurityAdvancedHSTSWPROSHUEbox333">
                                    <p><span class="HeaderSecurityAdvancedHSTSWPROSHUEtxexttextSize"><?php esc_html_e( 'As a professional tip, we recommend enabling the "includeSubDomains" option in the HSTS header to ensure that all subdomains on the website use HTTPS. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'This increases connection security for users and reduces the possibility of man-in-the-middle attacks. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'However, you should be aware that this option may cause problems for some external subdomains that do not support HTTPS. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'In this case, you may consider disabling the option for such specific subdomains.', 'headers-security-advanced-hsts-wp' ); ?></span></p>
                                </div>
                            </td>
                        </tr>
                        <tr id="HSTSPRELOADLISTSUB">
                            <th class="HeaderSecurityAdvancedHSTSWPROSHUEcltab1636"><label for="HeaderSecurityAdvancedHSTSWPROSHUErunFieldId">
                                    <h4 class="HeaderSecurityAdvancedHSTSWPROSHUEtboxy1348"><?php esc_html_e( 'Preload: ', 'headers-security-advanced-hsts-wp' ); ?></h4><span class="HeaderSecurityAdvancedHSTSWPROSHUEbadge1353"><?php esc_html_e( 'Beta', 'headers-security-advanced-hsts-wp' ); ?></span>
                                </label>
                                <p><span class="HeaderSecurityAdvancedHSTSWPROSHUEctd3"><?php esc_html_e( 'The "preload" parameter is used to indicate to the browser that the website should only be loaded via HTTPS. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'This means that even if a user types "http://" in front of the website URL, he or she will automatically be redirected to "https://" to ensure the security of the connection. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'In addition, this parameter allows the website to be included in the pre-loading list of browsers, which means that browsers will only use the HTTPS connection for the site without the need for verification. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'This makes the site load faster for users and improves the security of the connection.', 'headers-security-advanced-hsts-wp' ); ?></span></p>
                            </th>
                            <td>
                                <input type="checkbox" name="hsts_preload" value="1" <?php checked(1, get_option('hsts_preload'), true); ?> />
                                <div class="HeaderSecurityAdvancedHSTSWPROSHUEbox333">
                                    <p><span class="HeaderSecurityAdvancedHSTSWPROSHUEtxexttextSize"><?php esc_html_e( 'Professional advice would be to turn on the "preload" parameter to ensure greater security and speed of site loading for users. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'Including the subdomain in the HSTS configuration is also a good practice to ensure that all subsections of your site are only loaded via HTTPS. ', 'headers-security-advanced-hsts-wp' ); ?><?php esc_html_e( 'However, before enabling these parameters, it is important to ensure that all subdomains and resources on your site are available only via HTTPS and that there are no compatibility issues with any external services used by your site.', 'headers-security-advanced-hsts-wp' ); ?></span></p>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button();
?>
    </div>
</div>
<?php
}
function hsts_plugin_settings_init(){
register_setting('hsts-plugin-settings-group', 'hsts_max_age');
register_setting('hsts-plugin-settings-group', 'hsts_include_subdomains');
register_setting('hsts-plugin-settings-group', 'hsts_preload');
}
add_action('admin_init', 'hsts_plugin_settings_init');

function update_hsts_headers(){
$max_age = get_option('hsts_max_age');
$include_subdomains = get_option('hsts_include_subdomains');
$preload = get_option('hsts_preload');

$headers = headers_list();
$header_exists = false;
foreach ($headers as $header) {
    if (strpos($header, 'Strict-Transport-Security') !== false) {
        $header_exists = true;
        break;
    }
}

if (!$header_exists) {
    $hsts_header = "Strict-Transport-Security: max-age=$max_age;";
    if ($include_subdomains) {
        $hsts_header .= " includeSubDomains;";
    }
    if ($preload) {
        $hsts_header .= " preload";
    }

    add_filter('wp_headers', function ($headers) use ($hsts_header) {
        $headers['Strict-Transport-Security'] = $hsts_header;
        return $headers;
    });
}
}

function add_headers_security_advanced_hsts_wp_htaccess() {
    $htaccess_file = ABSPATH . '.htaccess';
    $content = "# Headers Security Advanced & HSTS WP 5.0.21\n";
    $content .= "<IfModule mod_headers.c>\n";
    $content .= "Header always set X-XSS-Protection \"1; mode=block\"\n";
    $content .= "Header always set X-Content-Type-Options \"nosniff\"\n";
    $content .= "Header always set Referrer-Policy \"strict-origin-when-cross-origin\"\n";
    $content .= "Header set Access-Control-Allow-Origin \"null\"\n";
    $content .= "Header set Access-Control-Allow-Methods \"GET,PUT,POST,DELETE\"\n";
    $content .= "Header set Access-Control-Allow-Headers \"Content-Type, Authorization\"\n";
    $content .= "Header set X-Content-Security-Policy \"img-src *; media-src * data:;\"\n";
    $content .= "Header always set Content-Security-Policy \"upgrade-insecure-requests;\"\n";
    $content .= "Header always set X-Frame-Options \"SAMEORIGIN\"\n";
    $content .= "Header always set Permissions-Policy \"accelerometer=(), autoplay=(), camera=(), fullscreen=*, geolocation=(self), gyroscope=(), microphone=(), payment=*\"\n";
    $content .= "Header set X-Permitted-Cross-Domain-Policies \"none\"\n";
    $content .= "</IfModule>\n";
    $content .= "# END Headers Security Advanced & HSTS WP\n\n";
    file_put_contents($htaccess_file, $content, FILE_APPEND);
}

function hsts_update_settings(){
update_hsts_headers();
}

function hsts_plugin_activate(){
if (!get_option('hsts_max_age')) {
    add_option('hsts_max_age', '63072000');
}
if (!get_option('hsts_include_subdomains')) {
    add_option('hsts_include_subdomains', '1');
}
if (!get_option('hsts_preload')) {
    add_option('hsts_preload', '1');
}
update_hsts_headers();
register_activation_hook(__FILE__, 'add_Headers_Security_Advanced_HSTS_WP_htaccess');

$htaccess_file = ABSPATH . '.htaccess';

if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
    $htaccess_contents = file_get_contents($htaccess_file);

    if (strpos($htaccess_contents, '# BEGIN WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21') === false) {
        $hsts_header = PHP_EOL . "# BEGIN WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21" . PHP_EOL;
    } else {
        $hsts_header = "";
    }
    $hsts_header .= "\n<IfModule mod_headers.c>" . PHP_EOL . "Header set Strict-Transport-Security \"max-age=" . get_option('hsts_max_age') . ";";
    if (get_option('hsts_include_subdomains') === '1') {
        $hsts_header .= " includeSubDomains;";
    }
    if (get_option('hsts_preload') === '1') {
        $hsts_header .= " preload\"" . PHP_EOL;
    }
    $hsts_header .= "</IfModule>" . PHP_EOL;
    if (strpos($htaccess_contents, '# END WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21') === false) {
        $hsts_header .= "# END WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21" . PHP_EOL;
    }

    if (strpos($htaccess_contents, 'Strict-Transport-Security') === false) {
        file_put_contents($htaccess_file, $hsts_header, FILE_APPEND);
    }
}
}
register_activation_hook(__FILE__, 'hsts_plugin_activate');

function hsts_plugin_update_htaccess(){
$htaccess_file = ABSPATH . '.htaccess';

if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
    $htaccess_contents = file_get_contents($htaccess_file);

    if (strpos($htaccess_contents, '# BEGIN WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21') === false) {
        $hsts_header = PHP_EOL . "# BEGIN WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21" . PHP_EOL;
    } else {
        $hsts_header = "";
    }
    // if (strpos($htaccess_contents, '\n<IfModule mod_headers.c>') === false) {
    //     $hsts_header .= "\ntest 2 apre\n<IfModule mod_headers.c>" . PHP_EOL;
    // }
    $hsts_header .= "Header set Strict-Transport-Security \"max-age=" . get_option('hsts_max_age') . ";";
    if (get_option('hsts_include_subdomains') == '1') {
        $hsts_header .= " includeSubDomains;";
    }
    if (get_option('hsts_preload') == '1') {
        $hsts_header .= " preload\"" . PHP_EOL;
    } else {
        $hsts_header .= "\"" . PHP_EOL;
    }
    if (strpos($htaccess_contents, '</IfModule>') === false) {
        $hsts_header .= "</IfModule>" . PHP_EOL;
    }
    if (strpos($htaccess_contents, '# END WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21') === false) {
        $hsts_header .= "# END WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21" . PHP_EOL;
    }
    if (strpos($htaccess_contents, 'Strict-Transport-Security') !== false) {
        $htaccess_contents = preg_replace("/Header set Strict-Transport-Security.*/", $hsts_header, $htaccess_contents);
    } else {
        $htaccess_contents .= PHP_EOL . $hsts_header;
    }
    $creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());
    if (!WP_Filesystem($creds)) {
        return; 
    }

    global $wp_filesystem;

    $wp_filesystem->put_contents($htaccess_file, $htaccess_contents);
}
}

function hsts_clean_old_comments() {
    $htaccess_file = get_home_path() . '.htaccess';
    $htaccess_contents = file_get_contents($htaccess_file);
    $regex = '/# (BEGIN|END) WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION \d+.\d+.\d+.|Headers Security Advanced & HSTS WP - \d+.\d+.\d+|# BEGIN WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION \d+.\d+.\d+.# END WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION \d+.\d+.\d+/s';
    $htaccess_contents = preg_replace($regex, '', $htaccess_contents);
    file_put_contents($htaccess_file, $htaccess_contents);
}

define('HEADERS_SECURITY_ADVANCED_HSTS_WP_PLUGIN_VERSION', '5.0.21');

function hsts_handle_plugin_update() {
    if (get_option('HEADERS_SECURITY_ADVANCED_HSTS_WP_PLUGIN_VERSION') != HEADERS_SECURITY_ADVANCED_HSTS_WP_PLUGIN_VERSION) {
        if (file_exists(get_home_path() . '.htaccess') && is_writable(get_home_path() . '.htaccess')) {
            hsts_clean_old_comments();
        }
        hsts_plugin_update_htaccess();
        update_option('HEADERS_SECURITY_ADVANCED_HSTS_WP_PLUGIN_VERSION', HEADERS_SECURITY_ADVANCED_HSTS_WP_PLUGIN_VERSION);
    }
}

register_activation_hook(__FILE__, 'hsts_handle_plugin_update');

add_action('upgrader_process_complete', 'hsts_handle_plugin_update', 10, 2);

add_action('update_option_hsts_max_age', 'hsts_plugin_update_htaccess', 10, 2);
add_action('update_option_hsts_include_subdomains', 'hsts_plugin_update_htaccess', 10, 2);
add_action('update_option_hsts_preload', 'hsts_plugin_update_htaccess', 10, 2);

add_action('update_option_hsts_max_age', 'hsts_update_settings');
add_action('update_option_hsts_include_subdomains', 'hsts_update_settings');
add_action('update_option_hsts_preload', 'hsts_update_settings');

function Headers_Security_Advanced_HSTS_WP_widgets(){
wp_add_dashboard_widget(
    'wpexplorer_dashboard_widget',
    '<img style="max-width:30px;" src=" data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF0WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNy4xLWMwMDAgNzkuOWNjYzRkZTkzLCAyMDIyLzAzLzE0LTE0OjA3OjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjMuMyAoTWFjaW50b3NoKSIgeG1wOkNyZWF0ZURhdGU9IjIwMjItMDMtMjlUMTY6Mjk6NDgrMDI6MDAiIHhtcDpNb2RpZnlEYXRlPSIyMDIyLTA0LTI4VDE3OjA2OjUyKzAyOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIyLTA0LTI4VDE3OjA2OjUyKzAyOjAwIiBkYzpmb3JtYXQ9ImltYWdlL3BuZyIgcGhvdG9zaG9wOkNvbG9yTW9kZT0iMyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo0YjcxODEyNy05ZjQ0LTRmNjItOWVmYS0xODVhYjFiMDBhNTEiIHhtcE1NOkRvY3VtZW50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" />Headers Security Advanced & HSTS WP',
    'Headers_Security_Advanced_HSTS_WP_widget_function'
);
}
add_action('wp_dashboard_setup', 'Headers_Security_Advanced_HSTS_WP_widgets');

function Headers_Security_Advanced_HSTS_WP_widget_function(){
echo '<h2><span style="color:#0f135e;font-weight: 800;"><b>Congratulations</b> you are safe</span> 🦖</h2><br><b>The Headers Security Advanced & HSTS WP</b> project implements HTTP response headers that your site can use to increase the security of your website. The plug-in will automatically set up all Best Practices (you don’t have to think about anything). You will also be able to change the headers simply in the plugin settings.<br /><br /><span style="color:#0ca533;"></span> <br />';
echo '<table border="0px"><tr><td><a href="https://www.buymeacoffee.com/tentacleplugins" target="_blank"><img src="../wp-content/plugins/headers-security-advanced-hsts-wp/assets/images/orange-button-min.png" style="max-width: 90%;" alt="buy me coffee" /></a></td><td> <a href="https://www.paypal.com/donate/?hosted_button_id=M72GQUM8CWTZS" target="_blank"><img src="../wp-content/plugins/headers-security-advanced-hsts-wp/assets/images/cyan-button-min.png" style="max-width: 90%;" alt="buy me paypal" /></a></td></tr></table>';
echo '<p><span class="SentinelHeadersUnlimitedExtensionSHUEtxextSizeCenter">Security is a right, not a privilege. Plugin version 5.0.21</span></p><br />';
}

function Headers_Security_Advanced_HSTS_WP_enable_flush_rules(){
global $wp_rewrite;
$wp_rewrite->flush_rules();
}
register_activation_hook(__FILE__, 'Headers_Security_Advanced_HSTS_WP_enable_flush_rules');

function Headers_Security_Advanced_HSTS_WP_hs_links($links, $file){
static $this_plugin;

if (!$this_plugin) {
    $this_plugin = plugin_basename(__FILE__);
}

if ($file == $this_plugin) {
    $settings_link = '<a href="https://www.paypal.com/donate/?hosted_button_id=M72GQUM8CWTZS">Donate a coffee</a>';
    array_unshift($links, $settings_link);
}
return $links;
}
add_filter('plugin_action_links', 'Headers_Security_Advanced_HSTS_WP_hs_links', 10, 2);

register_activation_hook(__FILE__, 'add_Headers_Security_Advanced_HSTS_WP_htaccess');

function hsts_plugin_deactivate(){
    $htaccess_file = ABSPATH . '.htaccess';

    if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
        $htaccess_contents = file_get_contents($htaccess_file);
        $htaccess_contents = preg_replace("/# BEGIN WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21.*# END WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION 5.0.21/s", "", $htaccess_contents);
        file_put_contents($htaccess_file, $htaccess_contents);
    }
    
    if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
        $htaccess_contents = file_get_contents($htaccess_file);
        $htaccess_contents = preg_replace("/# BEGIN WordPress HEADERS SECURITY ADVANCED & HSTS WP VERSION.*# END Headers Security Advanced & HSTS WP/s", "", $htaccess_contents);
        file_put_contents($htaccess_file, $htaccess_contents);
    }

    if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
        $htaccess_contents = file_get_contents($htaccess_file);
        $htaccess_contents = preg_replace("/# Headers Security Advanced & HSTS WP 5.0.21.*# END Headers Security Advanced & HSTS WP/s", "", $htaccess_contents);
        file_put_contents($htaccess_file, $htaccess_contents);
    }

    if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
        $htaccess_contents = file_get_contents($htaccess_file);
        $htaccess_contents = preg_replace("/Header set Strict-Transport-Security.*/", "", $htaccess_contents);
        file_put_contents($htaccess_file, $htaccess_contents);
    }

    if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
        $htaccess_contents = file_get_contents($htaccess_file);
        $htaccess_contents = preg_replace("/<IfModule mod_headers.c>.*<\/IfModule>/s", "", $htaccess_contents);
        file_put_contents($htaccess_file, $htaccess_contents);
    }

    delete_option('hsts_max_age');
    delete_option('hsts_include_subdomains');
    delete_option('hsts_preload');

    remove_filter('mod_rewrite_rules', 'add_Headers_Security_Advanced_HSTS_WP_htaccess');

    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
register_deactivation_hook(__FILE__, 'hsts_plugin_deactivate');