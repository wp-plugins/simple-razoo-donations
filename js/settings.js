jQuery(document).ready(function(){
  var add_donation_amount = jQuery('#add-donation-amount'),
  donation_option_fields = jQuery('#donation-option-fields'),
  trash_cans = donation_option_fields.find('.row img');
      
  //Get the total number of showing fields    
  function get_showing_total(){
    var total_showing_fields = donation_option_fields.find('.row:not(.hide)').length;
    
    return total_showing_fields;
  }
  
  //If no fields then hide labels
  if(get_showing_total() === 0){
    jQuery('#option-labels').addClass('hide');
  }
  
  //Click to add donation option
  add_donation_amount.click(function(){
    donation_option_fields.find('.row.hide').first().removeClass('hide');
    
    if(get_showing_total() === 5){
      jQuery(this).addClass('default');
    }
    else {
      jQuery(this).removeClass('default');
    }
    
    jQuery('#option-labels').removeClass('hide');
          
    return false;
  });
  
  //Click to remove donation option
  trash_cans.click(function(){
    var $this = jQuery(this);
    
    $this.closest('.row').addClass('hide');
    $this.siblings('input').val('');
    
    var showing_total = get_showing_total();
    
    if(showing_total < 5){ //Show link to add
      add_donation_amount.removeClass('default');
    }
    
    if(showing_total === 0){ //Remove labels for amount and description
      $this.closest('#donation-option-fields').siblings('#option-labels').addClass('hide');
    }
  });
  
  //Check for proper amounts then put amounts into correct string
  jQuery('#razoo-settings').submit(function(){
    
    var amount_fields = donation_option_fields.find('.row .small-text'),
    donate_options = '',
    bad_field = false;
    
    //Check if each amount is greater than 10, and if not, throw an error
    amount_fields.each(function(){
      
      var $this = jQuery(this);  
          
      if($this.val() !== ''){
       
       var dollar_amount = parseFloat($this.val()).toFixed(2);
        
        if(dollar_amount < 10.00){
          
          $this.addClass('error');
          bad_field = true;
        
        }  
      }  
    });
    
    if(bad_field === true){
      jQuery('#donation-options-error').removeClass('hide');
      
      return false;
    }
        
    //Pull the donation amount and description into a string that can be used in the donation form
    amount_fields.each(function(){
      
      var $this = jQuery(this);
      
      if($this.val() !== ''){
        var amount, description;
        
        //If they included a decimal then we want to set it to two numbers after decimal
        if($this.val().indexOf('.') === -1){
          amount = parseFloat($this.val()); 
        }
        else {
          amount = parseFloat($this.val()).toFixed(2);
        }
        
        description = $this.siblings('input').val();

        donate_options += amount + '=' + description + '|';
      }
      
    });
    
    donate_options = donate_options.slice(0,-1);
    
    jQuery('#donation-options').val(donate_options);
  }); 
  
}); //End Document Ready


//Run the color picker.
jQuery(document).ready(function(){  
  //This if statement checks if the color picker widget exists within jQuery UI
  if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
    jQuery('#color').wpColorPicker();
  }
  else {
    jQuery('#colorpicker').farbtastic('#color');
  }
}); //End Document Ready

