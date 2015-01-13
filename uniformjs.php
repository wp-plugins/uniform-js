<?php 
/*
Plugin Name: WP UniformJS
Plugin URI: http://matthewwoodard.com
Version: 1.1
Author: Matthew Woodard
Author URI: http://matthewwoodard.com
Description: Adds Uniform JS (sexy forms with jQuery) to your wordpress forms.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// globals

$uniformjs_options = get_option('uniformjssettings');
$uniformjs_style = $uniformjs_options['style'];
$uniformjs_elements = $uniformjs_options['elements'];

//add uniform script to wp_scripts after jquery

function uniformjs_scripts()  {  

    wp_register_script( 'uniformjs-script', plugins_url( '/js/jquery.uniform.min.js', __FILE__ ), array( 'jquery' ), true );  

    wp_enqueue_script( 'uniformjs-script' );

}  
add_action( 'wp_enqueue_scripts', 'uniformjs_scripts' ); 

// uniformjs admin page

function uniformjs_admin_page() {

	$uniformjs_options = get_option('uniformjssettings');
	$uniformjs_elements = $uniformjs_options['elements'];
	
	// check settings for selelcted style
	if ($uniformjs_options['style'] == 'default') {
		$uniformjs_style = 'default';
	}
	if ($uniformjs_options['style'] == 'aristo') {
		$uniformjs_style = 'aristo';
	}
	if ($uniformjs_options['style'] == 'agent') {
		$uniformjs_style = 'agent';
	}
	
	// exit php and enter html mode
	ob_start(); ?>
		
		<div class="wrap">
			<form action="options.php" method="post">
			
			<?php settings_fields('uniformjsgroup'); ?>
			
				<h1>Uniform JS Settings</h1>
				<p>Uniform JS had three styles included, you can see a preview of these styles here, <a target="_blank" href="http://uniformjs.com/#themes">http://uniformjs.com/#themes</a></p>
				<h3>Choose you Uniform theme.</h3>
				<input type="radio" value="default" <?php if ($uniformjs_style == 'default') { echo "checked"; } ?> name="uniformjssettings[style]" <?php echo $uniformjs_options['style']; ?> />
				<label>Default <em>(grey colored theme)</em></label><br/>
				<input type="radio" value="agent" <?php if ($uniformjs_style == 'agent') { echo "checked"; } ?> name="uniformjssettings[style]" <?php echo $uniformjs_options['style']; ?> />
				<label>Agent <em>(dark colored theme)</em></label><br/>
				<input type="radio" value="aristo" <?php if ($uniformjs_style == 'aristo') { echo "checked"; } ?> name="uniformjssettings[style]" <?php echo $uniformjs_options['style']; ?> />
				<label>Aristo <em>(blue colored theme)</em></label>
				
				<h3>Select your form elements.</h3>
				<p>Enter your form element selectors that you would like to apply UniformJS to.<br/>
					<em>Note: enter selectors as plain text like "input" or "input:text". You can also enter more specific selectors such as "input.uniform" or "#myform input".</em>
				</p>
				
				<p>
					<textarea cols="100" rows="5" name="uniformjssettings[elements]"><?php echo $uniformjs_options['elements']; ?></textarea>
				</p>
				<p><input type="submit" class="button-primary" value="Save Settings" /></p>
			</form>
			
			<?php print 'Your selectors will appear as: $("' . $uniformjs_elements . '").uniform();'; ?>
		</div>
	<?php
	// restart php and cleanup output buffering
	echo ob_get_clean();
}

// add the uniform styles based on user setting

function uniformjs_styles()  {

	$uniformjs_options = get_option('uniformjssettings');	
	$uniformjs_style = $uniformjs_options['style'];
	
	wp_register_style( 'uniformjs-user-style', plugins_url( '/css/uniform.' . $uniformjs_style . '.css', __FILE__ ), array(), 'all' );  
    wp_enqueue_style( 'uniformjs-user-style' );  
}  
add_action( 'wp_enqueue_scripts', 'uniformjs_styles' );

// uniformjs user set selectors in footer scripts
add_action('wp_footer', 'uniformjs_selectors');
	
	function uniformjs_selectors() {
		$uniformjs_options = get_option('uniformjssettings');
		$uniformjs_elements = $uniformjs_options['elements'];
	?>
	
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(<?php echo json_encode($uniformjs_elements); ?>).uniform();
		});
	</script>
	
<?php
}

// add admin page settting link

function uniformjs_admin_tab() {
	
	add_options_page( 'uniformjs' , 'UniformJS' , 'manage_options' , 'uniformjs' , 'uniformjs_admin_page' );
	
}
add_action( 'admin_menu' , 'uniformjs_admin_tab');

// admin register settings function

function uniformjs_setting() {
	register_setting( 'uniformjsgroup' , 'uniformjssettings');
}

add_action( 'admin_init' , 'uniformjs_setting');

?>