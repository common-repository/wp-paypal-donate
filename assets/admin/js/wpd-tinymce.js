(function() {

    tinymce.create('tinymce.plugins.wpd', {

        init : function(ed, url){

            ed.addButton('wpd', {
                title : 'WP Paypal Donate',
                'class' : 'wpd button button-small button-primary',
                onclick : function() {
                    tb_show('Select a donation form', 'admin-ajax.php?action=wpdthickbox&width=300&height=400');
                    jQuery('#TB_window').addClass('wpdthickbox');
                    jQuery('#wpd-to-editor').live('click', function(e){
                        var _self = jQuery('#wpd-form-selector').val();
                        e.preventDefault()
                        tb_remove();
                        ed.execCommand('mceInsertContent', false, '[wpd form="'+_self+'"]');
                    })
                }
            });

        },

        getInfo : function() {
            return {
                longname : 'WP Paypal Donate',
                author : 'SAID ASSEMLAL',
                authorurl : 'http://www.diascodes.com',
                infourl : '',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('wpd', tinymce.plugins.wpd);
    
})();