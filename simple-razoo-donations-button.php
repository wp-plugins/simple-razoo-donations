<?php
//Adapted from tutorials at http://brettterpstra.com/adding-a-tinymce-button/, http://www.garyc40.com/2010/03/how-to-make-shortcodes-user-friendly/ and http://www.ilovecolors.com.ar/tinymce-plugin-wordpress/.

class razoo_button {

  public function __construct() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
      return;
    if (get_user_option('rich_editing') == 'true') {
      add_filter('mce_buttons', array($this, 'register_razoo_button'));
      add_filter('mce_external_plugins', array($this, 'add_razoo_tinymce_plugin'));
      add_filter('tiny_mce_version', array($this, 'refresh_mce'));
    }
  }

  function register_razoo_button($buttons) {
    array_push($buttons, "|", "razoo");
    return $buttons;
  }

  function add_razoo_tinymce_plugin($plugin_array) {
    $plugin_array['razoo'] = RAZOO_DONATION_PLUGINFULLURL . 'js/button.js';
    return $plugin_array;
  }

  //Make TinyMCE check for added plugins
  function refresh_mce($version) {
    return ++$version;
  }
}

//Add the button during admin init.
add_action('init', 'create_razoo_button');
function create_razoo_button(){
  new razoo_button();
}
