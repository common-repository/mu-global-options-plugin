<?php
/*
Plugin Name: MU Global Options Plugin
Plugin URI: http://www.BlogsEye.com/
Description: Plugin to let you use options from main blog in other blogs.
Version: 0.8
Author: Keith P. Graham
Author URI: http://www.BlogsEye.com/

This software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

// now we need to get a ask the user for a list of options that he wants to get

function kpg_global_init() {
   add_options_page('MU Global Options', 'MU Global Options', 'manage_options','mu_global_options','kpg_global_control');
}
  
function kpg_global_uninstall() {
	if(!current_user_can('manage_options')) {
		die('Access Denied');
	}
	delete_option('kpg_global_options'); 
	return;
}  

add_action('admin_menu', 'kpg_global_init');
if ( function_exists('register_uninstall_hook') ) {
	register_uninstall_hook(__FILE__, 'kpg_global_uninstall');
}


// add the options. Since the add action is already implied at the load_plugins level we don't need to add_action 
// get a list of option names and values from the site config 
function kpg_global_setup() {
	global $blog_id;
	if ($blog_id==1) {
		return;
	}
	$ops=kpg_get_global_option('kpg_global_options');
	$kpg_readwrite=false;
	if (array_key_exists('kpg_readwrite',$ops)) $kpg_readwrite=$ops['kpg_readwrite'];
	if (is_array($ops)) {
		foreach ($ops as $key=>$value) {
		    if ($key!='kpg_readwrite') {
				if ($kpg_readwrite) {
					add_filter('pre_update_option_'.$key,'kpg_global_set',10,2);
					add_filter('add_option_'.$key,'kpg_global_add',1,2);
					add_filter('delete_option_'.$key,'kpg_global_delete');
				}
				add_filter('pre_option_'.$key,'kpg_global_get',1);	
			}
		}
	}
}
kpg_global_setup(); // when plugin is loaded this get's done



// get a list of options that you want globalized
function kpg_global_control() { // I like the name of this function

	if (!function_exists('switch_to_blog')) {
	?>
		<h3>Global Options</h3>
		<p>This plugin is only works if your blog has been configured as a network (WPMU) blog.</p>
	<?php
	    return false;
	}

	global $blog_id;
	$ops=kpg_get_global_option('kpg_global_options');
	if (empty($ops)) $ops=array();
	if (!is_array($ops)) $ops=array();
	$kpg_readwrite=false;
	if (array_key_exists('kpg_readwrite',$ops)) 	$kpg_readwrite=$ops['kpg_readwrite'];
	if (empty($kpg_readwrite)) {
		$kpg_readwrite=false;
	} else {
		$kpg_readwrite=true;
	}


	
	
	if ($blog_id!=1) { 
?>
		<h3>Global Options</h3>
		<p>This plugin is working correctly. The options are only available from the Main Blog.</p>
		<p>The following global options are set:</p><p>
		<?php
		foreach($ops as $key=>$value) {
			if ($key!='readonly') {
				echo "$key ";
			}	
		}
		echo "</p>";
		return;
	}

/*	echo "Current ops <br/><pre>";
	print_r($ops);
	echo "</pre><br/>";
*/
	// now update the options if they pressed the submit button
	if (array_key_exists('Submit',$_POST)) {
		echo "<br/>Updating<br/>";
		// pressed submit
		$fops=$_POST['kpg_option'];
		$ops=array();
		if (!empty($fops)) {
			// now load up the options
			foreach ($fops as $j=>$value) {
				$ops[$value]=$alloptions[$fops[$j]];
			}
		} 
		$kpg_readwrite=$_POST['kpg_readwrite'];
		if (empty($kpg_readwrite)) {
			$kpg_readwrite=false;
		} else {
			$kpg_readwrite=true;
		}
		$ops['kpg_readwrite']=$kpg_readwrite;
		update_option('kpg_global_options',$ops);
	/*	echo "fixed ops<br/><pre>";
		print_r($ops);
		echo "</pre><br/>";
		echo "form ops<br/><pre>";
		print_r($fops);
		echo "</pre><br/>";
	*/
	}
	
	// just to make sure
	
	$ops=kpg_get_global_option('kpg_global_options');
	if (empty($ops)) $ops=array();
	if (!is_array($ops)) $ops=array();
	$alloptions=wp_load_alloptions();

?>
  <form method="post" action="">
    <input type="hidden" name="action" value="update" />
    <?php wp_nonce_field('update-options'); ?>
    <fieldset style="border:thin black solid;padding:2px;">
    <legend style="font-weight:bold">Globalize Options</legend>
	<p><span style="font-weight:bold;">
	  <input type="checkbox" name="kpg_readwrite" value="true" <?php if ($kpg_readwrite==true) echo "checked=\"true\""; ?> />
	</span>Check if Blogs can change global settings (beware!) </p>
	<p>Check off the options that you would like globalized.</p>
	<table width="100%">
	<?php
		$crow=-1;
		$numlines=0;
		// do the user a favor and not show some of the more sensitive entries. I will add to this list.
		// for now just a list of options that look like they could be trouble
		$noshow=array(
			"kpg_global_options",
			"siteurl",
			"blogname",
			"blogdescription",
			"admin_email",
			"comments_notify",
			"home",
			"mailserver_url","mailserver_login","mailserver_pass","mailserver_port",
			"cron",
			"_transient_doing_cron",
			"_site_transient_update_core",
			"_transient_plugins_delete_result_1",
			"_site_transient_update_themes",
			"_transient_random_seed",
			"_site_transient_update_plugins");
		$j=0;
		foreach ($alloptions as $key=>$value) { 
			if (!in_array($key,$noshow)) {
				$crow++;
				if ($crow>2) {
					$crow=0;
					if ($numlines>0) {
						echo "</tr><tr>";
					} else {
						echo "<tr>";
					}
					$numlines++;
				}
				$v=$value;
				if (is_array(maybe_unserialize($value))) $v='--array--';
					else
				if (strlen($v)>24) $v=substr($value,0,24).'...';
		
    ?> 
<td width="33%" style="font-weight:bold;"><input type="checkbox" name="kpg_option[<?php echo $j; ?>]" value="<?php echo $key; ?>" <?php if (array_key_exists($key,$ops)) echo "checked=\"true\""; ?> /> <?php echo $key; ?> [<span style="font-weight:normal;font-style:italic;"><?php echo $v; ?></span>]</td>
<?php 		}
			$j++;
		} ?>
	 </tr></table>
    <p class="submit">
    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes') ?>" /><br>
    </p>
    </fieldset>
  </form>

<?php
}
$kpg_semaphore=0;
function kpg_get_global_option($option) {
	// this gets the plugin control - never globalized (I hope)
	if (!function_exists('switch_to_blog')) return false;
	switch_to_blog(1); 
	$ansa=get_option($option);
	restore_current_blog();
	return $ansa;  // continue and update the local newvalue
}

function kpg_global_set($newvalue, $oldvalue) {
	if (!function_exists('switch_to_blog')) return $newvalue;
	global $kpg_semaphore;
	if ($kpg_semaphore) return $newvalue;
	$kpg_semaphore++;
	$filt=current_filter();
	$f=substr($filt,strlen('pre_update_option_'));
	// now add to list of options we are hooking
	switch_to_blog(1);
	$ansa=update_option($f,$newvalue);
	restore_current_blog();
	$kpg_semaphore--;
	return $oldvalue;  // returning the old value keeps the add from updating the current

}
function kpg_global_add($option, $value) {
	if (!function_exists('switch_to_blog')) return false;
	global $kpg_semaphore;
	if ($kpg_semaphore) return false;
	$kpg_semaphore++;
	$filt=current_filter();
	$f=substr($filt,strlen('add_option_'));
	// now add to list of options we are hooking
	switch_to_blog(1);
	//echo "<br/>Updating $f, $value <br/>";
	$ansa=update_option($f,$value);
	restore_current_blog();
	$kpg_semaphore--;
	return true; // functions.php ignores result anyway.
}
function kpg_global_get($option) {
	if (!function_exists('switch_to_blog')) return false;
	global $kpg_semaphore;
	if ($kpg_semaphore) return false;
	$kpg_semaphore++;
	$filt=current_filter();
	$f=substr($filt,strlen('pre_option_'));
	// switch to main blog
	// undo the filter to prevent deadly recursion
	switch_to_blog(1);
	$ansa=get_option($f);
	restore_current_blog();
	// restore the filter
	$kpg_semaphore--;
	return $ansa;
}
function kpg_global_Delete($ops) {
	if (!function_exists('switch_to_blog')) return false;
	global $kpg_semaphore;
	if ($kpg_semaphore) return false;
	$kpg_semaphore++;
	$filt=current_filter();
	$f=substr($filt,strlen('delete_option_'));
	switch_to_blog(1);
	$ansa=delete_option($ops);
	restore_current_blog();
	$kpg_semaphore--;
	return $ansa;
}


?>