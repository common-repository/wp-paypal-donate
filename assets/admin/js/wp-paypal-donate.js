var wpdAdmin;
(function(a){
	wpdAdmin = 
	{
		init: function()
		{ 
			this.methods.pagesdropdown();		
		},
		methods:
		{
			pagesdropdown : function(){
				jQuery('.pagesdropwodn').live('click', function(e){
					var _self = jQuery(this);
					var _href = _self.attr('href');
					tb_show('Select an existed page', _href);
					jQuery('#TB_window').addClass('wpdthickbox selectpage');
					jQuery('#wpd-pick-page').off('click').on('click', function(e){
						e.preventDefault();
						tb_remove();
						
						_self.next('.col-sm-10').find('input').val( jQuery(this).siblings('#pages-dropdown').val() )
					})
				})

			}
			
		}
	}

})(jQuery); 
	
jQuery(document).ready(function(){
	wpdAdmin.init();
});