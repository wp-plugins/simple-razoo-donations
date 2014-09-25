(function() {
  tinymce.create('tinymce.plugins.Razoo', {
    init : function(ed, url) {
      ed.addButton('razoo', {
        title : 'Insert Razoo Donation Form Shortcode',
        image : url.replace('js', 'img/razoo-icon.png'),
        onclick : function() {
          ed.execCommand('mceInsertContent', false, '[razoo_donation_form]');
        }
      });
    },
    createControl : function(n, cm) {
      return null;
    },
    getInfo : function() {
      return {
        longname : "Razoo Donation Form Shortcode",
        author : 'Wired Impact',
        authorurl : 'http://wiredimpact.com/',
        infourl : 'http://wiredimpact.com/',
        version : "1.0"
      };
    }
  });
  tinymce.PluginManager.add('razoo', tinymce.plugins.Razoo);    
})();