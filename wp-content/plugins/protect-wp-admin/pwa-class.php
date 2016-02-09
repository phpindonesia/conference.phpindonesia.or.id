<?php
/*
 * Protect WP-Admin (C)
 * @register_install_hook()
 * @register_uninstall_hook()
 * */
?>
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/** Get all options value */
if(!function_exists('get_pwa_setting_options')):
function get_pwa_setting_options() {
		global $wpdb;
		$pwaOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'pwa_%'");
								
		foreach ($pwaOptions as $option) {
			$pwaOptions[$option->option_name] =  $option->option_value;
		}
		return $pwaOptions;	
	}
endif;	
$getPwaOptions=get_pwa_setting_options();
if(isset($getPwaOptions['pwa_active']) && '1'==$getPwaOptions['pwa_active'])
{
add_action('login_enqueue_scripts','csbwfs_load_jquery');
add_action('init', 'init_pwa_admin_rewrite_rules' );
add_action('init', 'pwa_admin_url_redirect_conditions' );
add_action('login_head', 'pwa_update_login_page_logo');
add_action('login_footer','csbwfs_custom_script',5);

	if(isset($getPwaOptions['pwa_logout']))
	{
	add_action('admin_init', 'pwa_logout_user_after_settings_save');
	add_action('admin_init', 'pwa_logout_user_after_settings_save');
	}
}
if(!function_exists('pwa_logout_user_after_settings_save')):
function pwa_logout_user_after_settings_save()
{
	$getPwaOptions=get_pwa_setting_options();
    if(isset($_GET['settings-updated']) && $_GET['settings-updated'] && isset($_GET['page']) && $_GET['page']=='pwa-settings')
    {
    flush_rewrite_rules();
	}
	
  if(isset($_GET['settings-updated']) && $_GET['settings-updated'] && isset($_GET['page']) && $_GET['page']=='pwa-settings' && isset($getPwaOptions['pwa_logout']) && $getPwaOptions['pwa_logout']==1)
   {
     $URL=str_replace('&amp;','&',wp_logout_url());
      if(isset($getPwaOptions['pwa_rewrite_text']) && isset($getPwaOptions['pwa_logout']) && $getPwaOptions['pwa_logout']==1 && $getPwaOptions['pwa_rewrite_text']!=''){
      wp_redirect(home_url('/'.$getPwaOptions['pwa_rewrite_text']));
     }else
     {
		 //silent
		 }
     //wp_redirect($URL);
   }
}
endif;
/** Create a new rewrite rule for change to wp-admin url */
if(!function_exists('init_pwa_admin_rewrite_rules')):
function init_pwa_admin_rewrite_rules() {
	$getPwaOptions=get_pwa_setting_options();
    if(isset($getPwaOptions['pwa_active']) && ''!=$getPwaOptions['pwa_rewrite_text']){
	$newurl=strip_tags($getPwaOptions['pwa_rewrite_text']);
    add_rewrite_rule( $newurl.'/?$', 'wp-login.php', 'top' );
    add_rewrite_rule( $newurl.'/register/?$', 'wp-login.php?action=register', 'top' );
    add_rewrite_rule( $newurl.'/lostpassword/?$', 'wp-login.php?action=lostpassword', 'top' );
    
    }
}
endif;
/** 
 * Update Login, Register & Forgot password link as per new admin url
 * */
if(!function_exists('csbwfs_load_jquery')):
function csbwfs_load_jquery()
{
wp_enqueue_script("jquery"); 
}
endif;

if(!function_exists('csbwfs_custom_script')):
function csbwfs_custom_script()
{	
$getPwaOptions=get_pwa_setting_options();
if(isset($getPwaOptions['pwa_active']) && ''!=$getPwaOptions['pwa_rewrite_text']){

echo '<script>jQuery(window).load(function(){
	jQuery("#login #login_error a").attr("href","'.home_url($getPwaOptions["pwa_rewrite_text"].'/lostpassword').'");
	jQuery("body.login-action-resetpass p.reset-pass a").attr("href","'.home_url($getPwaOptions["pwa_rewrite_text"].'/').'");
	var formId= jQuery("#login form").attr("id");
if(formId=="loginform"){
	jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["pwa_rewrite_text"]).'");
	}else if("lostpasswordform"==formId){
			jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["pwa_rewrite_text"].'/lostpassword').'");
			jQuery("#"+formId+" input:hidden[name=redirect_to]").val("'.home_url($getPwaOptions["pwa_rewrite_text"].'/?checkemail=confirm').'");
		}else if("registerform"==formId){
			jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["pwa_rewrite_text"].'/register').'");
			}
		else
			{
				//silent
				}
jQuery("#nav a").each(function(){
            var linkText=jQuery(this).text();
            if(linkText=="Log in"){jQuery(this).attr("href","'.home_url($getPwaOptions["pwa_rewrite_text"]).'");}
			else if(linkText=="Register"){jQuery(this).attr("href","'.home_url($getPwaOptions["pwa_rewrite_text"].'/register').'");}else if(linkText=="Lost your password?"){jQuery(this).attr("href","'.home_url($getPwaOptions["pwa_rewrite_text"].'/lostpassword').'");}else { 
				//silent
				}	
        });});</script>';
}

}
endif;

if(!function_exists('pwa_admin_url_redirect_conditions')):
function pwa_admin_url_redirect_conditions()
{
	$getPwaOptions=get_pwa_setting_options();
	$pwaActualURLAry =array
	                       (
                           home_url('/wp-login.php'),
                           home_url('/wp-login.php/'),
                           home_url('/wp-login'),
                           home_url('/wp-login/'),
                           home_url('/wp-admin'),
                           home_url('/wp-admin/'),
                           );
    $request_url = pwa_get_current_page_url($_SERVER);
    $newUrl = explode('?',$request_url);
	//print_r($pwaActualURLAry); echo $newUrl[0];exit;
if(! is_user_logged_in() && in_array($newUrl[0],$pwaActualURLAry) ) 
	{

/** is forgot password link */
if( isset($_GET['login']) && isset($_GET['action']) && $_GET['action']=='rp' && $_GET['login']!='')
{
$username = $_GET['login'];
if(username_exists($username))
{
//silent
}else{ wp_redirect(home_url('/'),301); //exit;
}
}elseif(isset($_GET['action']) && $_GET['action']=='rp')
{
	//silent
	}
elseif(isset($_GET['action']) && isset($_GET['error']) && $_GET['action']=='lostpassword' && $_GET['error']=='invalidkey')
{
	$redirectUrl=home_url($getPwaOptions["pwa_rewrite_text"].'/?action=lostpassword&error=invalidkey');
	wp_redirect($redirectUrl,301);//exit;
	}
elseif(isset($_GET['action']) && $_GET['action']=='resetpass')
{
// silent 
	}
	else{

	wp_redirect(home_url('/'),301);//exit;
	   }


		//exit;
		}
		else if(isset($getPwaOptions['pwa_restrict']) && $getPwaOptions['pwa_restrict']==1 && is_user_logged_in())
		{
			global $current_user;
	        $user_roles = $current_user->roles;
	        $user_ID = $current_user->ID;
	        $user_role = array_shift($user_roles);
	        
	        if(isset($getPwaOptions['pwa_allow_custom_users']) && $getPwaOptions['pwa_allow_custom_users']!='')
	        {
				$userids=explode(',' ,$getPwaOptions['pwa_allow_custom_users']);
				
				if(is_array($userids))
				{
					$userids=explode(',' ,$getPwaOptions['pwa_allow_custom_users']);
					}else
					{
						$userids[]=$getPwaOptions['pwa_allow_custom_users'];
						}
				}else
				{
					$userids=array();
					}
	        
			if($user_role=='administrator' || in_array($user_ID,$userids))
			{
				//silent is gold
				}else
				{
					wp_redirect(home_url('/'));//exit;
					}
			}else
			{
				//silent is gold
				}
	
}
endif;
/** Get the current url*/
if(!function_exists('pwa_current_path_protocol')):
function pwa_current_path_protocol($s, $use_forwarded_host=false)
{
    $pwahttp = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $pwasprotocal = strtolower($s['SERVER_PROTOCOL']);
    $pwa_protocol = substr($pwasprotocal, 0, strpos($pwasprotocal, '/')) . (($pwahttp) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$pwahttp && $port=='80') || ($pwahttp && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $pwa_protocol . '://' . $host;
}
endif;
if(!function_exists('pwa_get_current_page_url')):
function pwa_get_current_page_url($s, $use_forwarded_host=false)
{
    return pwa_current_path_protocol($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
endif;
/* Change Wordpress Default Logo */
if(!function_exists('pwa_update_login_page_logo')):
function pwa_update_login_page_logo() {
$getPwaOptions=get_pwa_setting_options();
	
    echo '<style type="text/css"> /* Protect WP-Admin Style*/';
    
    if(isset($getPwaOptions['pwa_logo_path']) && $getPwaOptions['pwa_logo_path']!='')
      echo ' h1 a { background-image:url('.$getPwaOptions['pwa_logo_path'].') !important; }';
      
    if(isset($getPwaOptions['pwa_login_page_bg_color']) && $getPwaOptions['pwa_login_page_bg_color']!='')
    echo ' body.login-action-login,html{ background:'.$getPwaOptions['pwa_login_page_bg_color'].' !important; height: 100% !important;}';
    
    echo '</style>';
   
}
endif;
?>
