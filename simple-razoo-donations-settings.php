<?php

/**
 * Razoo Donation form Settings
 *
 * Creates a settings page for the plugin and handles saving the settings to the database.
 *
 * @version 0.1.5
 * @author Wired Impact (wiredimpact) - http://wiredimpact.com
 */
class razoo_settings_page {
  
  /**
	 * Initiate the settings page by adding the appropriate actions
	 */
  public function __construct() {
    add_action('admin_menu', array($this, 'add_settings_page'));
    add_action('admin_init', array($this, 'settings_init'));
    add_action('admin_head', array($this, 'custom_admin_css'));
    add_filter( 'plugin_action_links_' . RAZOO_DONATION_BASENAME, array($this, 'add_plugin_settings_link'), 10, 2);
  }
  
  /**
   * Add a settings link to our plugin on the Plugins list page.
   * 
   * @param array $links An array with all the links for htat plugin.
   * @return array $links All the links including our new settingg link.
   */
  public function add_plugin_settings_link( $links ) {

    return array_merge(
      array(
        'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=simple-razoo-donations-settings">' . __('Settings') . '</a>'
      ),
      $links
    );

  } 
  
  /**
   * Add the settings page using the WordPress Settings API
   */
  public function add_settings_page(){
    $settings = add_options_page(
      __('Simple Razoo Donations'),
      __('Simple Razoo Donations'),
      'manage_options',
      'simple-razoo-donations-settings',
      array($this, 'settings_page_content')
    );
    
    //Add our styles and scripts only on the settings page.
    add_action('load-' . $settings, array($this, 'add_styles_scripts'));
  }
  
  /**
   * Build the basic structure for the settings page including form, fields, and submit button.
   */
  public function settings_page_content(){
    ?>
    <div class="wrap">
      <?php screen_icon(); ?>
      <h2><?php _e('Simple Razoo Donations'); ?></h2>
      <form id="razoo-settings" action="options.php" method="post">
        
        <?php settings_fields('razoo_options'); ?>
        <div class="alignright razoo-form"><?php echo do_shortcode('[razoo_donation_form]'); ?></div>
        <?php do_settings_sections('razoo-donation-form-settings'); ?>
        
        <p class="submit">
          <input id="razoo-submit" name="Submit" type="submit" class="button-primary" value="<?php _e('Save Changes & Update Donation Form'); ?>" />
        </p>
        
      </form>
    </div>
    <?php
  }
  
  /**
   * Add the setting sections and fields.
   */
  function settings_init(){
    register_setting(
      'razoo_options',
      'razoo_options',
      array($this, 'validate_options')
    );
    
    /**Documentation Info**/
    add_settings_section(
      'razoo-options-docs',
      __('How to Add a Razoo Donation Form to Your Website'),
      array($this, 'options_docs_text'),
      'razoo-donation-form-settings'
    );
    
    /**Option Settings**/
    add_settings_section(
      'razoo-options-main',
      __('Settings'),
      array($this, 'options_settings_text'),
      'razoo-donation-form-settings'
    );
    
    add_settings_field(
      'charity_id',
      __('Razoo ID'),
      array($this, 'id_input'),
      'razoo-donation-form-settings',
      'razoo-options-main'
    );
    
    add_settings_field(
      'title',
      __('Title'),
      array($this, 'title_input'),
      'razoo-donation-form-settings',
      'razoo-options-main'
    );
    
    add_settings_field(
      'summary',
      __('Summary'),
      array($this, 'summary_input'),
      'razoo-donation-form-settings',
      'razoo-options-main'
    );
    
    add_settings_field(
      'more-info',
      __('More Info'),
      array($this, 'more_info_textarea'),
      'razoo-donation-form-settings',
      'razoo-options-main'
    );
    
    add_settings_field(
      'color',
      __('Color'),
      array($this, 'color_input'),
      'razoo-donation-form-settings',
      'razoo-options-main'
    );
    
    add_settings_field(
      'image',
      __('Show Image'),
      array($this, 'show_image_input'),
      'razoo-donation-form-settings',
      'razoo-options-main'
    );
    
    add_settings_field(
      'donation-options',
      __('Donation Options'),
      array($this, 'donation_option_input'),
      'razoo-donation-form-settings',
      'razoo-options-main'
    );
    
  }
  
  /**
   * Output the text for documentation.
   */
  function options_docs_text(){ ?>
    <p><?php _e('To add the Razoo donation form to your website follow these steps:'); ?></p>
    <ol>
      <li><?php _e('Complete the settings below with all your organization\'s information, then save your changes.'); ?></li>
      <li><?php _e('Go to the edit screen for the page or post where you want to add the donation form.'); ?></li>
      <li><?php _e('Place your cursor within the editor\'s text where you want the donation form to be added.'); ?></li>
      <li><?php echo __('Click the Razoo icon in the WordPress editor toolbar ') . '<img class="editor-icon" src="' . RAZOO_DONATION_PLUGINFULLURL . 'img/razoo-icon.png" />' .  __('. The Razoo shortcode reading "[razoo_donation_form]" will be added in the editor.'); ?></li>
      <li><?php _e('Click the blue "Publish" or "Update" button to save your changes and add the form to your live website.'); ?></li>
    </ol>
    
  <?php }
  
  /**
   * Settings Section and Fields
   */
  function options_settings_text(){
    echo '<p>' . __('Use the settings below to adjust the Razoo donation form.  Every time you save changes the donation form on the right will update to show you exactly what it will look like on your website.  Any changes saved here will adjust all the donation forms on your website that are not overriden with shortcode attributes.  For more information on how to use shortcode attributes to customize specific forms on your website check out ') . '<a href="http://wiredimpact.com/simple-razoo-donations/" target="_blank">' . __('http://wiredimpact.com/simple-razoo-donations/') . '</a>.</p>';
  }
  
  /**
   * Charity ID Field
   */
  function id_input(){
    $options = get_option('razoo_options');
    $id = str_replace(' ', '-', sanitize_text_field($options['charity_id']));
    
    echo '<input id="id" name="razoo_options[charity_id]" type="text" value="' . $id .'" class="regular-text" />';
    echo '<p class="description">' . __('This is the ID for your organization according to Razoo.  When on your organization\'s landing page it\'s the text that comes right after "/story/".  For example, the United Way of America\'s ID is "United-Way-Of-America".  You can view their ID at ') . '<a href="http://www.razoo.com/story/United-Way-Of-America" target="_blank">' . __('http://www.razoo.com/story/United-Way-Of-America') . '</a>.  If you\'ve never used Razoo before you can find your nonprofit by searching at <a href="http://www.razoo.com/search" target="_blank">http://www.razoo.com/search</a>.</p>';
  }
  
  /**
   * Donation Form Title Field
   */
  function title_input(){
    $options = get_option('razoo_options');
    $title = sanitize_text_field($options['title']);
    
    echo '<input id="title" name="razoo_options[title]" type="text" value="' . $title .'" class="regular-text" />';
    echo '<p class="description">' . __('The title will show up in big letters at the top of the donation form.') . '</p>';
  }
  
  /**
   * Summary Field
   */
  function summary_input(){
    $options = get_option('razoo_options');
    $summary = sanitize_text_field($options['summary']);
    
    echo '<input id="summary" name="razoo_options[summary]" type="text" value="' . $summary .'" class="regular-text" />';
    echo '<p class="description">' . __('The summary is a short description of your organization or an ask for people to donate.  This text shows up just below the title.') . '</p>';
  }
  
  /**
   * More Info Field
   */
  function more_info_textarea(){
    $options = get_option('razoo_options');
    $more_info = wp_strip_all_tags($options['more_info']);
    
    echo '<textarea id="more-info" rows="5" name="razoo_options[more_info]" class="large-text">' . $more_info .'</textarea>';
    echo '<p class="description">' . __('The more info section can be much longer, describing more about your organization and where the donors money will go.  This text shows up when users click the "More info" link on the donation form.') . '</p>';
  }
  
  /**
   * Color Field
   */
  function color_input(){
    $options = get_option('razoo_options');
    $color = ($options['color'] != "") ? sanitize_text_field($options['color']) : '#3D9B0C';
    
    echo '<input id="color" name="razoo_options[color]" type="text" value="' . $color .'" />';
    echo '<p class="description">' . __('Provide the color you want to use for the donation form using the color picker.  If possible, you should match this to one of your website\'s colors by using a <a href="http://www.w3schools.com/html/html_colors.asp" target="_blank">hexadecimal value</a> (#000000).') . '</p>';
    echo '<div id="colorpicker"></div>';
  }
  
  /**
   * Show Image Field
   */
  function show_image_input(){
    $options = get_option('razoo_options');
    $show_image = (isset($options['show_image'])) ? 'true' : null;
    
    echo '<label for="show-image"><input id="show-image" name="razoo_options[show_image]" type="checkbox" value="true" ' . checked($show_image, 'true', false) . '/>';
    _e(' Show the main image for your organization on the donation form');
    echo '</label>';
  }
  
  /**
   * Donation Options Field
   */
  function donation_option_input(){
    $options = get_option('razoo_options');
    $donation_options = (isset($options['donation_options'])) ? sanitize_text_field($options['donation_options']) : null;
    
    if($donation_options != ''){
      $donation_options = explode('|', $donation_options);
      for($i = 0; $i < count($donation_options); $i++){
        $donation_options[$i] = explode('=', $donation_options[$i]);
      }
    }
    
    echo '<p class="hide" id="donation-options-error">' . __('Oops, at least one of your donation amounts is less than $10.00.  Please increase the amount and save your changes.') . '</p>';
    echo '<div id="option-labels"><span class="amount">' . __('Amount') . '</span><span class="amount-description">' . __('Description') . '</span></div>';
    //Add three donation amounts by default or if they already have them add as many as they have    
    echo '<div id="donation-option-fields">';
    if(isset($donation_options)){
      for($i = 0; $i < 5; $i++){
        $donation_amount = ($donation_options != '' && isset($donation_options[$i][0])) ? $donation_options[$i][0] : null;
        $donation_description = ($donation_options != '' && isset($donation_options[$i][1])) ? $donation_options[$i][1] : null;
        $hide = ($donation_options != '' && isset($donation_options[$i][0]) && $donation_options[$i][0] != '') ? false : true;
        
        echo self::make_donation_row($i, $donation_amount, $donation_description, $hide);
      }
      
    }
    else { //If they are loading the settings page for the first time
      for($i = 0; $i < 5; $i++){
        if($i < 3){ //Show the first three by default before they've ever saved settings
          echo self::make_donation_row($i, null, null, false); 
        }
        else { //Hide the last two by default before they've ever saved settings
          echo self::make_donation_row($i, null, null, true);
        }
      }
    }
    echo '</div>';
    
    echo '<p class="description"><a href="#" id="add-donation-amount">' . __('Add Donation Option (Up to 5 Total)') . '</a></p>';
    echo '<p class="description">' . __('Add the donation options you want to offer potential donors with the amount in the small box <strong>($10 Min.)</strong> and the description in the large box.  Please only use numbers and periods in the amount field.  The numbers will also be sorted automatically with the smallest donation amounts coming first.  The field for donors to input an amount of their choosing will always be added.') . '</p>';
    
    //Add hidden input that is updated on save with the data from all the fields using jQuery
    echo '<input id="donation-options" name="razoo_options[donation_options]" type="hidden" value="" />';
    
  }
  
  
  /**
   * Sanitize and validate the submitted field.
   * This is called using register_setting in the WordPress API.
   * 
   * @param array The content of all the submitted fields within these settings.
   * @return array Sanitized valid content to be written to the database.
   */
  function validate_options( $input ){
    $valid = array();
    
    $valid['charity_id'] = str_replace(' ', '-', sanitize_text_field($input['charity_id']));
    $valid['title'] = sanitize_text_field($input['title']);
    $valid['summary'] = sanitize_text_field($input['summary']);
    $valid['more_info'] = wp_strip_all_tags($input['more_info']);
    $valid['color'] = sanitize_text_field($input['color']);
    $valid['show_image'] = (isset($input['show_image'])) ? 'true' : null;
    $valid['donation_options'] = sanitize_text_field($input['donation_options']);
    
    return $valid;
  }
  
  /**
   * Make the rows to handle the donation amounts and descriptions
   * 
   * @param int $num The number of the current donation amount row (0-5)
   * @param float $donation_amount The donation amount in dollars.
   * @param string $donation_description The description for that donation amount.
   * @param boolean $hide Whether the row should be hidden upon page load.
   * @return string HTML for the given donation option row.
   */
  function make_donation_row( $num, $donation_amount = null, $donation_description = null, $hide = false ){
    $row = '';
    
    $row .= '<div class="row';
    if($hide == true) $row .= ' hide';
    $row .= '">';
    $row .= '<label for="donation_amount[' . $num . ']">$</label> <input id="donation_amount[' . $num . ']" name="donation_amount[' . $num . ']" type="text" class="small-text" value="';
    if(isset($donation_amount)) $row .= $donation_amount;
    $row .= '" />';
    $row .= '<input id="donation_title[' . $num . ']" name="donation_title[' . $num . ']" type="text" class="regular-text" value="';
    if(isset($donation_description)) $row .=  $donation_description;
    $row .= '" />';
    $row .= '<img id="donation-trash[' . $num . ']" src="' . RAZOO_DONATION_PLUGINFULLURL . 'img/trash-can.png" />';
    $row .= '</div>';
    
    return $row;
  }
  
  /**
   * Add CSS to the settings page.
   */
  function custom_admin_css(){
    ?>
    <style>
      #razoo-settings .editor-icon { vertical-align: middle; }
      .razoo-form { margin: 200px 100px; }
      #razoo-settings .form-table { width: auto; clear: none; }
      #razoo-settings span.amount { margin-left: 12px; width: 51px; display: inline-block; font-size: .9em; }
      #razoo-settings span.amount-description { font-size: .9em; }
      #donation-option-fields .row img { vertical-align: middle; cursor: pointer; }
      #donation-option-fields .hide, #donation-options-error.hide, #option-labels.hide { display: none; }
      #donation-options-error { color: #C00; }
      #donation-option-fields .error { border: 1px solid #C00; }
      #razoo-settings .default { color: gray; text-decoration: none; }
      #razoo-settings .default:hover { cursor: default; }
    </style>
    <?php
  }
  
  /**
   * Enqueue the styles and scripts needed for the settings.
   */
  function add_styles_scripts(){
    global $wp_version;
    
    if ( 3.5 <= $wp_version ){
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_script( 'wp-color-picker' );
    }
    else {
      wp_enqueue_style( 'farbtastic' );
      wp_enqueue_script( 'farbtastic' );
    }
    
    wp_enqueue_script('razoo-settings', RAZOO_DONATION_PLUGINFULLURL . 'js/settings.js');
  }
  
}

//Create Our Settings Page!
new razoo_settings_page();