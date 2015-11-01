var sm_screen_media_query = "screen and (max-width:767px)";
var md_screen_media_query = "screen and (max-width:1000px)";
var frame_border_color = "rgba(255, 255, 255, .2)";
var transp_color = "rgba(0, 0, 0, 0)";

function popup(url)
{
var width = 800;
var height = 600;
var left = (screen.width - width)/2;
var top = (screen.height - height)/2;
var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left +
	', resizable=1, scrollbars=1, menubar=1, toolbar=1';
	newwin = window.open(url,'', params);
	if (window.focus)
	{
		newwin.focus();
	}
}

function resize_text_content()
{
    jQuery('.text').each(function(){
        var fontSize = .03 * jQuery(this).closest('.text-area').innerWidth();
        if (fontSize < 12)
            fontSize = 12;
        if (fontSize > 18)
            fontSize = 18;
        jQuery(this).css("font-size", fontSize + 'px');
    });
}

function resize_carousel_title(carousel, _class)
{
    var is_sm_screen = window.matchMedia( sm_screen_media_query ).matches;
    var controls = jQuery(carousel).find(_class);
    var height = jQuery(controls).height();
    var border_width = parseInt(jQuery('.sub-page-carousel-frame').css('borderTopWidth'));
    if (height > 0 && border_width > 0)
    {
        if (is_sm_screen) {
            jQuery(controls).css({
                'top': 0,
                'bottom': 'auto',
                '-webkit-transform-origin': 'left top',
                'transform-origin': 'left top'
            });
            jQuery('.text').css({
                'top': 'auto',
                'bottom': 0,
            });   
        }
        else{
            jQuery('.text').css({
                'bottom': 'auto',
                'top': 0,
            }); 
            jQuery(controls).css({
                'bottom': -(border_width * 0.5) + 'px',
                'top': 'auto',
                '-webkit-transform-origin': 'left bottom',
                'transform-origin': 'left bottom'
            });
        }
        
        var scale = 'scale(' + border_width / height + ')';
        jQuery(controls).css({
            'z-index': 2000,
            '-webkit-transform': scale + ' translateX(-50%)',
            '-ms-transform': scale + ' translateX(-50%)',
            '-moz-transform': scale + ' translateX(-50%)',
            '-o-transform': scale + ' translateX(-50%)',
            'transform': scale + ' translateX(-50%)'
        });
    }
}

function avoid_portrait()
{
    if (jQuery(window).innerWidth() < jQuery(window).innerHeight())
    {
        jQuery('[class^="rotate-device"]').show();
        jQuery('.rotate-device').css({'height': '100%', 'width' : '100%'});
    }
    else
    {
        jQuery('[class^="rotate-device"]').hide();
        jQuery('.rotate-device').css({'height': '0', 'width' : '0'});
    }
}

function navbar_display(navbar_on)
{
    if (navbar_on)
    {
        
        jQuery('.navbar-fixed-top').fadeIn();
        jQuery('.my-navbar-toggle').fadeOut();
    }
    else
    {
        jQuery('.sub-page-carousel-frame').css("border-color", frame_border_color);
        jQuery('.carousel-controls').show();
        jQuery('.navbar-fixed-top').fadeOut();
        jQuery('.my-navbar-toggle').fadeIn();
    }
}

function fix_wpsbcToolTips()
{
    jQuery('[data-tooltip]').each(function() {
        if (jQuery(this).attr('data-tooltip-date') === '')
            return(false);
        jQuery(this).attr('data-tooltip-date','');
        jQuery(this).attr('data-tooltip','<div style="direction:ltr;">' + jQuery(this).attr('data-tooltip') + '</div>');
    });
    window.setTimeout(function(){
            fix_wpsbcToolTips();
        }, 500);
}

function update_all_calendars(obj, action)
{
    var timestamp = jQuery(obj).closest('.wpsbc-heading').find('.wpsbc-dropdown').first().val();
    if (timestamp === undefined)
        return;
    var source_parent = jQuery(obj).closest('.wpsbc-heading');
    jQuery('.wpsbc-container').each(function(){
        if (jQuery(this).find('.wpsbc-heading').first() === source_parent)
            return(true);
        changeDay(action, timestamp, jQuery(this));
    });
}

function is_in_View(elem)
{
    var docViewTop = jQuery(window).scrollTop();
    var docViewBottom = docViewTop + jQuery(window).height();
    var elemTop = jQuery(elem).offset().top;
    var elemBottom = elemTop + jQuery(elem).height();
    return (((elemTop <= docViewBottom) && (elemTop >= docViewTop)) ||
            ((elemBottom <= docViewBottom) && (elemBottom >= docViewTop)) ||
            ((elemTop < docViewTop) && (elemBottom > docViewBottom)));
}

function play_pause_carousels()
{
    jQuery('.carousel').each(function() {
        if (jQuery(this).closest('.text-area').is(":visible"))
            return(true);
        jQuery(this).carousel('pause');
        if (is_in_View(this))
            jQuery(this).carousel('cycle');
    });
}

jQuery(document).ready(function(){

    avoid_portrait();
    // Configure scrollbar
    jQuery(".content").mCustomScrollbar({
        theme:"light-thick",
        autoDraggerLength:true,
        alwaysShowScrollbar: 1
    });
    /////////////////////////////

    // For all devices -- change carousel images through arrow clicks
    jQuery('.carousel-left-arrow').click(function(event){
        jQuery(event.target).closest('.carousel-controls').find('.anchor-prev-slide').trigger( "click" );
    });

    jQuery('.carousel-right-arrow').click(function(event){
        jQuery(event.target).closest('.carousel-controls').find('.anchor-next-slide').trigger( "click" );
    });
    /////////////////////////////

    // For touch devices -- change carousel images through swipe gestures
    jQuery('.sub-page-carousel-frame').swipe( {
        swipeLeft:function(event) {
            jQuery(event.target).parent().parent().parent().find('.anchor-prev-slide').trigger( "click" );
        },
        swipeRight: function(event) {
            jQuery(event.target).parent().parent().parent().find('.anchor-next-slide').trigger( "click" );
        },
        threshold:0
    });
    /////////////////////////////

    // Show navbar when the Show Navbar button is clicked
    jQuery('.my-navbar-toggle').click(function(){
        jQuery('.sub-page-show-carousel').trigger( "click" );
        if (window.matchMedia( sm_screen_media_query ).matches)
        {
            jQuery('.sub-page-carousel-frame').css("border-color", transp_color);
            jQuery('.carousel-controls').hide();
        }
        navbar_display(true);
    });
    /////////////////////////////

    // Manage navbar visibility
    jQuery(window).bind("scroll",function(){
        
        navbar_display(jQuery(this).scrollTop() === 0);
        play_pause_carousels();
    });
    /////////////////////////////

    jQuery(window).bind("load",function(){
        // load images "right bebore" exposure to screen
        avoid_portrait();
        jQuery(".lazy_backgrounds").lazyload({
            skip_invisible : false,
            threshold : 200,
            load : function() {
                var subPage = jQuery(this).closest('.sub-page-cover');
                jQuery(subPage).find('.carousel').css("visibility", "visible");
            }
        });
        jQuery(".lazy").lazyload({
            skip_invisible : false,
            threshold : 200
        });
    });

    jQuery(window).bind("load resize",function(){
        var is_sm_screen = window.matchMedia( sm_screen_media_query ).matches;
        var is_md_screen = window.matchMedia( md_screen_media_query ).matches;
        // Apply sm_screen/md_screen/desktop design changes
        if (is_sm_screen){
            jQuery('.sub-page-carousel-cover').height(window.innerHeight);//jQuery(window).height());
            jQuery('.sub-page-carousel-cover').width(jQuery(window).innerWidth());
            jQuery('.bg-border').hide();
        }
        else{
            jQuery('.sub-page-carousel-cover').height(jQuery(window).width() * .28);
            jQuery('.sub-page-carousel-cover').width(jQuery(window).width() * .42);
            jQuery('.bg-border').show();
            jQuery('.carousel-controls').show();
        }
        var is_odd_item = false;
        jQuery('[hide-page-on]').each(function() {
            var obj = jQuery(this);
            var hide_page_on = obj.attr('hide-page-on').toLowerCase();
            if (
                    (is_sm_screen && hide_page_on.indexOf('small') !== -1 ||
                    (is_md_screen && hide_page_on.indexOf('medium') !== -1)))
                obj.hide();
            else
            {
                jQuery(this).children('a').first().removeClass('odd-item even-item');
                jQuery(this).children('a').first().addClass(is_odd_item ? 'odd-item' : 'even-item');
                is_odd_item = !is_odd_item;
                obj.show();
            }
        });
        
        /////////////////////////////

        jQuery('.fullscreen-cover').height(window.innerHeight);
        
        jQuery('.sub-page-carousel-frame').each(function() {
            var carousel = jQuery(this).closest('.carousel');
            var border_width = jQuery(carousel).width();
            border_width *= (is_sm_screen ? .03 : .04);
            var half_border_width = border_width * .5;
            if (is_sm_screen) {
                jQuery(this).css({
                    'border-left-width': 0,
                    'border-right-width': 0,
                    'border-bottom-width': 0,
                    'border-top-width': 2 * border_width + 'px',
                    'top': 0,
                    'left': 0,
                    'width': jQuery(carousel).width() + 'px',
                    'height': jQuery(carousel).height() +  'px'
                });
            }
            else{
                jQuery(this).css({
                    'border-width': border_width + 'px',
                    'top': -half_border_width + 'px',
                    'left': -half_border_width + 'px',
                    'width': jQuery(carousel).width() + border_width + 'px',
                    'height': jQuery(carousel).height() + border_width + 'px'
                });
            }
            jQuery(this).css('border-color', frame_border_color);
            resize_text_content();
            resize_carousel_title(carousel, '.text-controls');
            resize_carousel_title(carousel, '.carousel-controls');
            jQuery('.bg-border').css({
                'top': -half_border_width + 'px',
                'height': border_width + 'px'
            });
        });
        avoid_portrait();
        jQuery('[data-tooltip]').each(function() {
            jQuery(this).attr('data-tooltip-date','');
        });
         play_pause_carousels();
    });

    // switch between text/image carousel views
    jQuery('.sub-page-show-text').click(function(event){
        navbar_display(false);
        resize_text_content();
        jQuery(event.target).closest('.row').find('.text-area').fadeIn(200);
        jQuery(event.target).closest('.row').find('.carousel-controls').fadeOut(200);
        var carousel = jQuery(event.target).closest('.row').find('.carousel');
        carousel.carousel('pause');
        resize_carousel_title(carousel, '.text-controls');
    });

    jQuery('.sub-page-show-carousel').click(function(event){
        jQuery(event.target).closest('.row').find('.carousel-controls').fadeIn(200);
        jQuery(event.target).closest('.row').find('.text-area').fadeOut(200);
        var carousel = jQuery(event.target).closest('.row').find('.carousel');
        carousel.carousel('cycle');
        resize_carousel_title(jQuery(event.target).closest('.row').find('.carousel'), '.carousel-controls');
    });
    
    jQuery('.page-up-down').click(function(event){
        var scrollElement;
        if (event.pageY - jQuery(this).offset().top < jQuery(this).height() / 2) //scroll down
            scrollElement = jQuery(this).closest('.sub-page-cover');
        else
            scrollElement = jQuery(this).closest('.sub-page-cover').prev();
        jQuery('html, body').animate({
            scrollTop: scrollElement.offset().top
        }, 300);
    });
    
    /* Calendar code.. */
    jQuery('.wpsbc-container').fadeTo("fast", 0.8);
    fix_wpsbcToolTips();
    jQuery(this).on('change','.wpsbc-dropdown',function(){
        update_all_calendars(this, 'jump');
    });
    jQuery(this).on('click','.wpsbc-next',function(){
        update_all_calendars(this, 'next');
    });
    jQuery(this).on('click','.wpsbc-prev',function(){
        update_all_calendars(this, 'prev');
    });
});