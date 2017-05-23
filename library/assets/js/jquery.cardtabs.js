/*!
 * CardTabs jQuery plugin
 * https://github.com/blekerfeld/cardtabs
 *
 * Released under the MIT license
 */

jQuery.fn.cardTabs = function(options){

	var mainClass =  $(this).attr('class');
	var activeCount = 0;

	var settings = $.extend({
        theme: '',
        extra: ''
     }, options );

	// Fixing the theme
	if(settings.theme != ''){
		$('.' + mainClass + ' .card-tabs-bar').addClass(settings.theme);
		$('.' + mainClass + ' .card-tabs-stack').addClass(settings.theme);
	}

	function toggleTab(obj){
		$('.' + mainClass + " .card-tabs-stack div[data-tab][data-tab='" + obj.data('tab') + "']").show();
		$('.' + mainClass + " .card-tabs-stack div[data-tab][data-tab!='" + obj.data('tab') + "']").hide();
	}

	// Checking whether we have to set a tab as active
    $('.' + mainClass + ' .card-tabs-stack').children('div[data-tab]').each(function () {
    	if($(this).hasClass('active')){
    		$('.' + mainClass + " .card-tabs-bar a[data-tab='" + $(this).data('tab') + "']").addClass('active');
    		toggleTab($(this));
    		$(this).removeClass('active');
    		activeCount++;
    	}
	});

	// Otherwise, it's the first one, and the first tab in the bar needs to be active
	if(activeCount == 0){
		$('.' + mainClass + ' .card-tabs-bar a:first-child').addClass('active');
	}

	$('.' + mainClass + ' .card-tabs-bar a').click(function(){
		$('.' + mainClass + ' .card-tabs-bar a').removeClass('active');
		$(this).addClass('active');
		toggleTab($(this));
	});

	return this;
};
