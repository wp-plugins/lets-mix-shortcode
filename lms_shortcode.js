function lms_shortcode() {
    return '[letsmix][/letsmix]';
}
 
(function() {
    tinymce.create('tinymce.plugins.lms_shortcode', {
 
        init : function(ed, url){
            ed.addButton('lms_shortcode', {
            title : "Insert Let's Mix Shortcode",
                onclick : function() {
                    ed.execCommand(
                    'mceInsertContent',
                    false,
                    lms_shortcode()
                    );
                },
                image: url + "/images/letsmix-quicktag-button.png"
            });
        }
    });
 
    tinymce.PluginManager.add('lms_shortcode', tinymce.plugins.lms_shortcode);
 
})();