/* ---------------------------------------------
Accordian Effect
http://adipalaz.com/experiments/jquery/accordion3.html
Requires: jQuery v.1.4.2+
Copyright (c) 2009 Adriana Palazova
Useful link: http://www.learningjquery.com/2007/03/accordion-madness
------------------------------------------------ */
$(function() {
    $('#outer h4.trigger').wrapInner('<a style="display:block" href="#" title="expand/collapse"></a>');
    $('#outer div.menus:eq(0)').find('h4.trigger:eq(0)').addClass('open').end()
    .find('div.toggle_container:gt(0)').hide().end()
    .find('h4.trigger').each(function() {
          $(this).click(function() {
          
              var $thisCllps = $(this).next('div.toggle_container');
              var $cllpsVisible = $(this).siblings('h4.trigger').next('div.toggle_container:visible');
              
              ($cllpsVisible.length) ? $(this).toggleClass('open').siblings('h4.trigger').removeClass('open')
                  .next('div.toggle_container:visible').slideUp('fast', function() {
                  $thisCllps.slideDown();
                  }) : $(this).toggleClass('open').next('div.toggle_container').slideToggle();
              return false;
          });
     });
});
/* ---------------------------------------------
Sidebar Toggler v.1.0.2
http://adipalaz.com/experiments/jquery/toggle_sidebar.html
Requires: jQuery v.1.4.2+
Copyright (c) 2009 Adriana Palazova
Dual licensed under the MIT (http://www.adipalaz.com/docs/mit-license.txt) and GPL (http://www.adipalaz.com/docs/gpl-license.txt) licenses.
------------------------------------------------ */
(function($) {
$.fn.toggleSidebar = function(options, arg) {
    var opts = $.extend({}, $.fn.toggleSidebar.defaults, options);   
    return this.each(function() {
        $$ = $(this);
        var o = $.meta ? $.extend({}, opts, $$.data()) : opts;
        
        // Generate the element that handles the toggle action:
        var trigger1, triger2;
        (o.initState == 'shown') ? (trigger1 = o.triggerShow, trigger2 = o.triggerHide) : (trigger1 = o.triggerHide, trigger2 = o.triggerShow);
        $('<a class="trigger" href="#" />').text(trigger2).insertBefore('#' + $$.attr("id") + ' ' + o.sidebar);
        // the variables we need to create the animation effect:
        var $trigger = $$.find('a.trigger'),
            $thisPanel = $$.find(o.sidebar),
            $main = $$.closest(o.wrapper).find(o.mainContent),
            pw = $thisPanel.width(), // the width of the sidebar
            tw = $trigger.outerWidth(true), // the width of the trigger
            hTimeout = null;
        // initial positions
        init = $.fn.toggleSidebar.init[o.init];
        init($$, $trigger, $thisPanel, $main, pw, tw, o);
        // event
        if (o.event == 'click') {
            var ev = 'click';
        } else {
            if (o.focus) {var addev = ' focus';} else {var addev = '';} // for backward compatibility
            if (o.addEvents) {var addev = ' ' + o.addEvents;} else {var addev = '';}
            var ev = 'mouseenter' + addev;
        }
         
        $$.delegate('a.trigger', ev, function(ev) {
            ev.preventDefault();
            var $trigger = $(this),
                setanim = (o.attr && $.isFunction($.fn.toggleSidebar.animations[$trigger.attr(o.attr)])) ? $trigger.attr(o.attr) : null, 
                animation = setanim || o.animation,
                anim = $.fn.toggleSidebar.animations[animation];
            if ($.isFunction(anim)) {
                if (o.event == 'click') {
                    anim($$, $trigger, $thisPanel, $main, pw, tw, o);
                } else {
                  hTimeout = window.setTimeout(function() {
                      anim($$, $trigger, $thisPanel, $main, pw, tw, o);
                  }, o.interval);        
                  //$trigger.click(function() {$trigger.blur();});
                }
            } else {
              //alert('The ' + animation +  ' function is not defined!');
              //return false;
            }
        });
        if (o.event != 'click') {$$.delegate('a.trigger', 'mouseleave', function() {window.clearTimeout(hTimeout); });}
    });
};
// define defaults and override with options, if available:
$.fn.toggleSidebar.defaults = {
    initState : 'shown', // 'shown' or 'hidden' - the initial state of the sliding panel
    animation : 'queuedEffects', // the animation effect
    init : 'initPositions', // the initial positions of the elements, needed when the switchable panel is initially hidden
    full : false, // if 'true', the expanded main content will use the whole of the available space (optional)
    position : 'right', // position of the switchable panel
    triggerShow : 'Show', // the text of the trigger for showing
    triggerHide : 'Hide', // the text of the trigger for hiding
    sidebar : 'div.slide', // the selector for the switchable panel
    mainContent : '#main', // the selector for the main column
    wrapper : '#content', // the selector for the element that wraps the columns
    p : 5, // the distance between the columns
    attr : 'id', //[*] null, or an attribute 'id', 'class', etc. of the trigger element.  
    speed : 400, // the duration of the animation
    event : 'click', //'click', 'hover'
    addEvents : 'click', // it is needed for  keyboard accessibility when we use {event:'hover'}
    interval : 300 // time-interval for delayed actions used to prevent the accidental activation of animations when we use {event:hover} (in milliseconds)
    //[*] If {attr} is not null, and its name coincides with the name of one of the defined animation functions, this function will be used for the animation.
};
// initial state
$.fn.toggleSidebar.init = {
    initPositions : function($$, $trigger, $thisPanel, $main, pw, tw, o) {
                if (o.initState == 'hidden') {
                    var mrg = (o.full == true) ? 0 : tw+o.p;
                    switch (o.position) {
                      case 'right': var pos='right'; var margin='margin-right'; break;
                      case 'left': var pos='left'; var margin='margin-left'; break;
                      default: var pos='right'; var margin='margin-right';
                    }
                    $(o.sidebar, $$).css(pos, -(pw+1));
                    $main.css(margin, (mrg));
                    $trigger.addClass('collapsed');
                }
    }
};
// Pre-defined animation functions (you can add your own custom animations here).
// To save file size, feel free to remove any animation function you don't need.
$.fn.toggleSidebar.animations = {
    queuedEffects : function($$, $trigger, $thisPanel, $main, pw, tw, o) {
                if (o.full == true) {var mrg=0} else {var mrg=tw+o.p}
                $trigger.animate({opacity: 0}, o.speed);
                if ($trigger.text() == o.triggerShow) {
                    $main.animate({marginRight: (pw+o.p)}, o.speed, function() {
                      $thisPanel.animate({opacity: 1}, 'fast').animate({right: 0}, o.speed, function() {
                        $trigger.removeClass('collapsed').text(o.triggerHide).animate({opacity: 1}, o.speed);
                    }); });
                } else {
                    $thisPanel.animate({opacity: 0, right: -(pw+1)}, o.speed, function() {
                      $main.animate({marginRight:  (mrg)}, o.speed, function() {
                        $trigger.addClass('collapsed').text(o.triggerShow).animate({opacity: 1}, o.speed);
                    }); });
                };
            },
                
    concurrentEffects : function($$, $trigger, $thisPanel, $main, pw, tw, o) {
                if (o.full == true) {var mrg=0} else {var mrg=tw+o.p}
                $trigger.animate({opacity: 0}, 'fast');
                if ($trigger.text() == o.triggerShow) {
                    $main.animate({marginRight: (pw+o.p)}, o.speed, 'linear');
                    $thisPanel.animate({ right: 0, opacity: 1}, o.speed, function() {
                        $trigger.removeClass('collapsed').text(o.triggerHide).animate({opacity: 1}, o.speed);
                    });
                } else {
                    $thisPanel.animate({opacity: 0, right: -(pw+1)}, o.speed, 'linear');
                    $main.animate({marginRight:  (mrg)},  o.speed, function() {
                        $trigger.addClass('collapsed').text(o.triggerShow).animate({opacity: 1}, o.speed);
                    });
                };
            },
    simpleToggle : function($$, $trigger, $thisPanel, $main, pw, tw, o) {
                if (o.full == true) {var mrg=0} else {var mrg=tw+o.p}
                if ($trigger.text() == o.triggerShow) {
                    $trigger.removeClass('collapsed').text(o.triggerHide);
                    $thisPanel.css({right: 0, opacity: 1});
                    $main.css({marginRight: (pw+o.p)});
                } else {
                    $trigger.addClass('collapsed').text(o.triggerShow);
                    $thisPanel.css({right: -(pw+1), opacity: 0});
                    $main.css({marginRight: (mrg)});
                };
            }
};
})(jQuery);
//////////////////////
// The plugin can be invoked like this:
/* ---------------------------------------------------------------------- 
$(function(){
  $('#rightcol').toggleSidebar(); // --- the sidebar is initially shown
  //$('#rightcol').toggleSidebar({state:"hidden", event:"hover"}); // --- the sidebar is initially hidden, the animatioin is triggered whenever a 'hover' event occurs.
});
 ---------------------------------------------------------------------- */