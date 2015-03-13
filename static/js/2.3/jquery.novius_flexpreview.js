/**
 * Pour ajouter des preview sur le control de flexSlider
 * @source : http://tympanus.net/codrops/2011/01/27/thumbnails-preview-slider/
 * @author : Novius, Julien : mise en plugin jQuery + integration a flexSlider 
 */

;(function ($) {

    $.novius_flexpreview = function(el, options) {

        // Configuration de base
        var settings = {
            horizontal             : true,
            callback_touchstart    : function() { },
            callback_touchmove     : function() { },
            callback_touchend      : function() { }
        };
        if ( options ) 
        {
            $.extend(settings, options);
        }

        var $flexslider         = el,
	    $flexnav            = $flexslider.find('.flex-control-nav'),
            $tooltip            = $('<li class="ps_preview"><div class="ps_preview_wrapper"></div><span></span></li>').css({opacity: 0}).show(),
            $ps_preview_wrapper = $tooltip.find('.ps_preview_wrapper'),
            currentHovered	= -1,
	    preview_width	= 0;
	
	var showTooltip = function() 
	{
        var $link = $(this),
            $parent = $link.parent(),
            listWidth = $parent.parent().width(),
            idx = $parent.index(),
            linkOuterWidth = $parent.outerWidth(),
            left = $link.position().left - $ps_preview_wrapper.width() / 2,
            $thumb = $flexslider.find('.slides li:nth-child(' + (idx + 1) + ')').data('thumb');

	    //if we are not hovering the current one
	    if ( currentHovered != idx ) 
	    {

		//the next thumb image to be shown in the tooltip
		var $newImage = $('<img/>').css('left','0px').attr('src', $thumb);

		// if theres more than 1 image (if we would move the mouse too fast it would probably happen)
		if ( $ps_preview_wrapper.children().length > 1 )
		{
		    $ps_preview_wrapper.children(':last').remove();
		}

		//prepend the new image
		$ps_preview_wrapper.prepend($newImage);

		var $tooltip_imgs	= $ps_preview_wrapper.children(),
		    tooltip_imgs_count	= $tooltip_imgs.length;
		    
		// if theres 2 images on the tooltip animate the current one out, and the new one in
		if(tooltip_imgs_count > 1)
		{
		    $tooltip_imgs.eq(tooltip_imgs_count-1).css({'position': 'absolute', left: 0}).stop().animate({
                opacity: 0
		    }, 150, function() {
			$(this).remove();
		    });
		    $tooltip_imgs.eq(0).css({opacity: 0}).stop().animate({
                opacity: 1
		    }, 150);
		}
	    }
	    
	    //if we are not using a "browser", we just show the tooltip, otherwise we fade it
	    if( $.browser.msie )
	    {
		$tooltip.css('margin-left', left + 'px').show();
	    }
	    else 
	    {
		$tooltip.stop().animate({
            marginLeft	: left + 'px',
		    opacity	: 1
		}, 150);
	    }
	}
    
	var hideTooltip = function()
	{
	    if ( $.browser.msie )
	    {
		$tooltip.hide();
	    }
	    else 
	    {
		$tooltip.stop().animate({
			opacity	: 0
		}, 150);
	    }
	}
	    
        $tooltip.appendTo($flexnav);
        $flexslider.find('.flex-control-nav a').on('mouseenter', showTooltip).on('mouseout', hideTooltip);
    }

    // Plugin jquery
    $.fn.novius_flexpreview = function(options) {
        return this.each(function() {
            new $.novius_flexpreview($(this), options);
        });
    }

})(jQuery);
