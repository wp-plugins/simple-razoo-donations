<?php
/*
Plugin Name: Simple Razoo Donations
Plugin URI: http://wiredimpact.com/simple-razoo-donations/
Description: Simple Razoo Donations allows you to easily embed the <a href='http://www.razoo.com/p/donationWidget'>Razoo Donation Widget</a> and accept donations on your website without typing a line of code.
Version: 0.1
Author: Wired Impact
Author URI: http://wiredimpact.com/
License: GPLv2
*/

/*
GPLv2 - read it - http://www.gnu.org/licenses/license-list.html#GPLCompatibleLicenses
*/

/**
 * Plugin wrapper - razoo donation form
 *
 * Embed a razoo donation widget on your site
 *
 * @author Wired Impact - @wiredimpact
 * 
 */
class razoo_donation_form {

	/**
	 * Internal, global storage for defaults
	 */
	public static $default_atts;

	/**
	 * Initiate the plugin, options, etc
	 */
	public function __construct(){
		add_shortcode('razoo_donation_form', array(&$this, 'shortcode_form_customize'));
		
		// set some defaults
    $options = get_option('razoo_options');
        
		self::$default_atts = array(
			'id' => (isset($options['charity_id']) && $options['charity_id'] != '') ? str_replace(' ', '-', sanitize_text_field($options['charity_id'])) : 'United-Way-of-America',
			'title' => (isset($options['title'])) ? sanitize_text_field($options['title']) : '',
			'short_description' => (isset($options['summary'])) ? sanitize_text_field($options['summary']) : '',
      'long_description' => (isset($options['more_info'])) ? wp_strip_all_tags($options['more_info']) : '',
			'color' => (isset($options['color'])) ? sanitize_text_field($options['color']) : '',
      'image' => (isset($options['show_image'])) ? 'true' : null,
			'donation_options' => (isset($options['donation_options'])) ? sanitize_text_field($options['donation_options']) : ''
		);

	}//--	fn	__construct

	/**
	 * Add razoo widget - shortcode handler
   * 
	 * @param $atts shortcode attributes {id, title, short_description, color, donation_options, image, long_description}
	 */
	function shortcode_form_customize($atts){
		$shortcode_params = shortcode_atts(self::$default_atts, $atts);
		   
    if($shortcode_params['donation_options'] != ''){
      $shortcode_params['donation_options'] = self::parse_query_string($shortcode_params['donation_options']);
    }
    
		return $this->embed($shortcode_params);
	}//--	fn	shortcode_form_customize
  
  /**
   * Parse a string in the correct format for JSON encoding
   * 
   * @param string $string The strig that you want to parse.
   */
  function parse_query_string($string) { 
    $op = array(); 
    $pairs = explode("|", $string); 
    foreach ($pairs as $pair) { 
        list($k, $v) = array_map("urldecode", explode("=", $pair)); 
        $op[$k] = $v; 
    } 
    
    return $op; 
  } 

	/**
	 * Add razoo form - embed with options
   * 
	 * @param $shortcode_params shortcode attributes {id, title, short_description, long_description, color, donation_options, image}
	 */
	function embed($shortcode_params) {
		extract($shortcode_params);
		
		// cheat!
		/* catch the echo output, so we can control where it appears in the text  */
		ob_start();
		
		?>
		<!-- from http://www.razoo.com/story/<?php echo $id ?>/share -->

	<div id='razoo_donation_widget'>
		<span><a href="http://www.razoo.com/">Donate online</a> to <a href="http://www.razoo.com/story/<?php echo $id ?>"><?php echo $title ?></a> at Razoo</span>
	</div>
	<script type='text/javascript'>
	var r_params = {
		"title":"<?php echo addslashes($title) ?>",
		"short_description":"<?php /* note that you need the charset to correctly interpret quotes? */ echo addslashes( html_entity_decode($short_description, ENT_QUOTES, 'UTF-8') ) ?>",
		"long_description":"<?php /* note that you need the charset to correctly interpret quotes? */ echo addslashes( html_entity_decode($long_description, ENT_QUOTES, 'UTF-8') ) ?>",
		"color":"<?php echo $color ?>",
		"donation_options": <?php echo self::fallback_json_encode( $donation_options ); // turn listing into json list ?>,
		"image":"<?php echo ($image == 'true') ? $image : 'false'; ?>"
		};
		var r_protocol=(("https:"==document.location.protocol)?"https://":"http://");var r_path='www.razoo.com/javascripts/widget_loader.js';
		var r_identifier='<?php echo $id?>';
		document.write(unescape("%3Cscript id='razoo_widget_loader_script' src='"+r_protocol+r_path+"' type='text/javascript'%3E%3C/script%3E"));
	</script>
		<?php
		
		return ob_get_clean();	//return the output (and stop the buffer)

	}//--	fn	embed
	
	/**
	 * Substitute encoder when lacking native function
   * 
	 * @param $data the stuff to encode
	 * @param $flags flags used by json_encode
	 * @see http://www.php.net/manual/en/function.json-encode.php#100835
	 */
	static function fallback_json_encode($data, $flags = null){
		//fallback to provided method
		if(function_exists('json_encode')){
      if($flags) return json_encode($data, $flags);
      
			return json_encode($data);
			#return json_encode($data, ($flags ? $flags : JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP));
		}
		
		if( is_array($data) || is_object($data) ) {
			 $islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );
		   
			if( $islist ) {
				$json = '[' . implode(',', array_map(array(__CLASS__, 'fallback_json_encode'), $data) ) . ']';
			} else {
			
				$items = Array();
				foreach( $data as $key => $value ) {
					$items[] = self::fallback_json_encode("$key") . ':' . self::fallback_json_encode($value);
				}
				$json = '{' . implode(',', $items) . '}';
			}
		} elseif( is_string($data) ) {
			# Escape non-printable or Non-ASCII characters.
			# I also put the \\ character first, as suggested in comments on the 'addclashes' page.
			$string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
			$json    = '';
			$len    = strlen($string);
			# Convert UTF-8 to Hexadecimal Codepoints.
			for( $i = 0; $i < $len; $i++ ) {
			   
				$char = $string[$i];
				$c1 = ord($char);
			   
				# Single byte;
				if( $c1 <128 ) {
					$json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
					continue;
				}
			   
				# Double byte
				$c2 = ord($string[++$i]);
				if ( ($c1 & 32) === 0 ) {
					$json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
					continue;
				}
			   
				# Triple
				$c3 = ord($string[++$i]);
				if( ($c1 & 16) === 0 ) {
					$json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
					continue;
				}
				   
				# Quadruple
				$c4 = ord($string[++$i]);
				if( ($c1 & 8 ) === 0 ) {
					$u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1;
			   
					$w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3);
					$w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128);
					$json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
				}
			}
		} else {
			# int, floats, bools, null
			$json = strtolower(var_export( $data, true ));
		}
		return $json;
		
	}//--	fn	fallback_json_encode


  
}//---	class	razoo_donation_form


//Setup some constants for us to more easily work with files
define("RAZOO_DONATION_BASENAME", plugin_basename(__FILE__) );
define("RAZOO_DONATION_PLUGINPATH", "/" . plugin_basename(dirname(__FILE__)) . "/");
define("RAZOO_DONATION_PLUGINFULLPATH", WP_PLUGIN_DIR . RAZOO_DONATION_PLUGINPATH);
define("RAZOO_DONATION_PLUGINFULLURL", WP_PLUGIN_URL . RAZOO_DONATION_PLUGINPATH);

//Include the settings and button if we're in the admin section.
if(is_admin()){
  include_once(RAZOO_DONATION_PLUGINFULLPATH . 'simple-razoo-donations-settings.php');
  include_once(RAZOO_DONATION_PLUGINFULLPATH . 'simple-razoo-donations-button.php');
}

// engage!
new razoo_donation_form();