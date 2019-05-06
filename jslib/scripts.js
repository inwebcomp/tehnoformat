	$.fn.Checkboxes = function(){

		this.find('div').bind('click',function(){
			$(this).parent().find('div').removeClass('selected'); 
			$(this).addClass('selected');
			$(this).parent().find('input').val($(this).attr('name'));
		});
		
	}
	
	$.fn.TabsOld = function(){
		this.find('.tab:first').removeClass('selected').addClass('selected');
		
		this.find('.tabs_content').hide();
		
		var tab = this.find('.tab.selected');
		var tab_content = this.find('#' + tab.attr('rel'));
		
		tab_content.show();
		
		var tab_block = this;
		
		this.find('.tab').bind('click', function(){

			var ID = $(this).attr("rel");
			
			tab_block.find('.tabs_content:not(#'+ID+')').stop().fadeOut(animation_speed);
			tab_block.find('.tab.selected').removeClass('selected');
			$(this).addClass('selected');
			
			var tab_content = tab_block.find('#' + ID);
			if(timeout){ clearTimeout(timeout); }
			var timeout = setTimeout(function(){ tab_content.stop().fadeIn(animation_speed); }, animation_speed);
			
		});
		
		this.find('.tab').bind('change', function(){

			var ID = $(this).attr("rel");
			
			tab_block.find('.tabs_content:not(#'+ID+')').stop().fadeOut(animation_speed);
			tab_block.find('.tab.selected').removeClass('selected');
			$(this).addClass('selected');
			
			var tab_content = tab_block.find('#' + ID);
			if(timeout){ clearTimeout(timeout); }
			var timeout = setTimeout(function(){ tab_content.stop().fadeIn(animation_speed); }, animation_speed);
			
		});
		
	}

	$.fn.Tabs = function(){
		this.find('.tabs__caption').on('click', ':not(.active)', function(){
			$(this)
			.addClass('active').siblings().removeClass('active')
			.parent().parent().find('.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
		});
	}
	
	var ScrollTop = function(scrollTime){

		var curPos = document.documentElement.clientHeight;
		var scrollTime = (!scrollTime) ? 500 : scrollTime;
		$("body,html").animate({ "scrollTop": 0 }, scrollTime);
		
	}
	
	$.fn.ColorPallete = function(element){
		
		var pallete = $(this);
		
		var input = $(element);
		var colors = {
			1 : "660000",
			2 : "CC0000",
			3 : "EA4C88",
			4 : "993399",
			5 : "0066CC",
			6 : "66CCCC",
			7 : "669900",
			8 : "666600",
			9 : "FFFF00",
			10 : "FFCC33",
			11 : "FF9900",
			12 : "FF6600",
			13 : "CC6633",
			14 : "663300",
			15 : "000000",
			16 : "CCCCCC",
			17 : "FFFFFF",
			18 : "E7D8B1"
		}
		
		var selected_color = input.val();
		
		pallete.append('<div class="color empty_color" rel=""></div>');
		
		var html = "";
		
		for(i in colors){
			if(selected_color == colors[i]){
				var selected = " selected";	
			}else{
				var selected = "";	
			}
			html = html+'<div class="color'+selected+'" rel="'+colors[i]+'" style="background-color:#'+colors[i]+'"></div>';
		}

		pallete.append(html);
		
		if(pallete.find('.selected').length == 0){
			pallete.find('.empty_color').addClass('selected');
		}
		
		var Hide = function(){
			pallete.addClass('hidden').find('.color:not(.selected)').hide();
		}
		var Show = function(){
			pallete.removeClass('hidden').find('.color').show();
		}
		
		Hide();
		
		pallete.find('.color').bind('click', function(){
			if(pallete.hasClass('hidden')){
				Show();
			}else{
				pallete.find('.color').removeClass("selected");
				$(this).addClass('selected');
				Hide();
				input.val($(this).attr('rel'));
			}
		});
	}

	var inProgress = false;
	var enough = false;

	/*$(document).bind('scroll', function(){
		var scrolled = $(document).scrollTop();
		if(scrolled > 1000){
			$('.up_button').fadeIn(200);	
		}else{
			$('.up_button').fadeOut(200);
		}
	});*/
	
	$.fn.ScrollTo = function(val, type, complete, offset, speed){
		
		var val = (!val) ? 0 : val;
		var type = (!type) ? 'auto' : type;
		var complete = (!complete) ? function(){} : complete;
		var offset = (!offset) ? 0 : offset;
		var speed = (!speed) ? 1.73 : speed;
		
		if(type == 'top'){
			$(this).click(function(){
				var curPos = document.documentElement.clientHeight;
				var scrollTime = curPos / speed;
				$("body,html").animate({ "scrollTop": val }, scrollTime, function(){ (complete)(); });
			});
		}
		if(type == 'auto'){
			var curPos = document.documentElement.clientHeight;
			var newPos = $('#'+this.attr('id')).offset().top + offset;
			var scrollTime = 400;
			$("body, html").animate({ "scrollTop": newPos }, scrollTime, function(){ (complete)(); });
		}
		
	}
	
	$.fn.preloadImages = function()
	{
		$("head").append("<style>img { opacity:0; }</style>");

		var pageBody = this;
		
		pageBody.find('img').each(function(i){
			var img = pageBody.find('img').eq(i);
			img.load(function(){
				$(this).fadeTo(200, 1);
			});	
		});
	}
	
	Share = {
		vkontakte: function(purl, ptitle, pimg, text) {
			url  = 'http://vkontakte.ru/share.php?';
			if(purl) url += 'url='          + encodeURIComponent(purl);
			if(ptitle) url += '&title='       + encodeURIComponent(ptitle);
			if(text) url += '&description=' + encodeURIComponent(text);
			if(pimg) url += '&image='       + encodeURIComponent(pimg);
			url += '&noparse=true';
			Share.popup(url);
		},
		odnoklassniki: function(purl, text) {
			url  = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
			if(text) url += '&st.comments=' + encodeURIComponent(text);
			if(purl) url += '&st._surl='    + encodeURIComponent(purl);
			Share.popup(url);
		},
		facebook: function(purl, ptitle, pimg, text) {
			url  = 'http://www.facebook.com/sharer.php?s=100';
			if(ptitle) url += '&p[title]='     + encodeURIComponent(ptitle);
			if(text) url += '&p[summary]='   + encodeURIComponent(text);
			if(purl) url += '&p[url]='       + encodeURIComponent(purl);
			if(pimg) url += '&p[images][0]=' + encodeURIComponent(pimg);
			Share.popup(url);
		},
		twitter: function(purl, ptitle) {
			url  = 'http://twitter.com/share?';
			if(ptitle) url += 'text='      + encodeURIComponent(ptitle);
			if(purl) url += '&url='      + encodeURIComponent(purl);
			if(purl) url += '&counturl=' + encodeURIComponent(purl);
			Share.popup(url);
		},
		mailru: function(purl, ptitle, pimg, text) {
			url  = 'http://connect.mail.ru/share?';
			if(purl) url += 'url='          + encodeURIComponent(purl);
			if(ptitle) url += '&title='       + encodeURIComponent(ptitle);
			if(text) url += '&description=' + encodeURIComponent(text);
			if(pimg) url += '&imageurl='    + encodeURIComponent(pimg);
			Share.popup(url)
		},
	
		popup: function(url) {
			window.open(url,'','toolbar=0,status=0,width=626,height=436');
		}
	};
	
	$.fn.setRating = function(type, size, callback){
		var size = (!size) ? 12 : size;
		var n = 1;
		var num = this.length;
		while(n <= num){
			if(type == 'int'){
				var count = (isNaN(parseInt(this.eq(n - 1).text())) == false) ? parseInt(this.eq(n - 1).text()) : 0; 
				var rating_val = count * size; 
				var rating = '<div class="rating_val" val="' + count + '" style="width:' + rating_val + 'px;"></div>';
				this.eq(n - 1).html(rating);
			}
			if(type == 'float'){
				var count = (isNaN(parseFloat(this.eq(n - 1).text())) == false) ? parseFloat(this.eq(n - 1).text()) : 0;
				var rating_val = count * size; 
				var rating = '<div class="rating_val" val="' + count + '" style="width:' + rating_val + 'px;"></div>';
				this.eq(n - 1).html(rating);
			}
			n++;
		}
		
		if(type !== ''){
			if(type == 'int' || type == 'float'){
				
				var rating = this;
				
				if(rating.attr('rated') !== 'true'){
					this.css({ cursor: 'pointer' });
				}
				
				this.hover(function(){
					$(this).mousemove(function(e){
						var offset = $(this).offset();
						var value = Math.ceil((e.pageX - offset.left) / size) * size;
						if(rating.attr('rated') !== 'true' && value !== 0){
							$(this).find('.rating_val').width(value);
						}
					});
					if(rating.attr('rated') !== 'true' && rating.attr('sendrequest') !== 'false'){ 
						$(this).bind('click', function(e){
							if(rating.attr('rated') !== 'true'){
								var offset = $(this).offset();
								var value = Math.ceil((e.pageX - offset.left) / size);
								if(value !== 0){
									if(callback){
										callback(value, e);
									}					
								}
							}
						});
					}
					
				}, function(){ 
					if(rating.attr('rated') !== 'true' && $(this).find('.rating_val').attr('val') !== 0){ 
						$(this).find('.rating_val').width($(this).find('.rating_val').attr('val') * size);
					}
				});
					
			}
		}
		
		
		/*this.bind('click',function(e){
			var offset = $(this).offset();
			var value = Math.ceil((e.pageX - offset.left) / size);
			if(value !== 0 && callback){
				callback(value, e);	
			}
		});*/
	}
	
	var keys = {37: 1, 38: 1, 39: 1, 40: 1};

	function preventDefault(e) {
	  e = e || window.event;
	  if (e.preventDefault)
		  e.preventDefault();
	  e.returnValue = false;  
	}
	
	function preventDefaultForScrollKeys(e) {
		if (keys[e.keyCode]) {
			preventDefault(e);
			return false;
		}
	}
	
	function disableScroll() {
	  if (window.addEventListener) // older FF
		  window.addEventListener('DOMMouseScroll', preventDefault, false);
	  window.onwheel = preventDefault; // modern standard
	  window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
	  window.ontouchmove  = preventDefault; // mobile
	  document.onkeydown  = preventDefaultForScrollKeys;
	}
	
	function enableScroll() {
		if (window.removeEventListener)
			window.removeEventListener('DOMMouseScroll', preventDefault, false);
		window.onmousewheel = document.onmousewheel = null; 
		window.onwheel = null; 
		window.ontouchmove = null;  
		document.onkeydown = null;  
	}





// Required for Meteor package, the use of window prevents export by Meteor
(function(window){
  if(window.Package){
    Materialize = {};
  } else {
    window.Materialize = {};
  }
})(window);

// Unique ID
Materialize.guid = (function() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return function() {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
           s4() + '-' + s4() + s4() + s4();
  };
})();

Materialize.elementOrParentIsFixed = function(element) {
    var $element = $(element);
    var $checkElements = $element.add($element.parents());
    var isFixed = false;
    $checkElements.each(function(){
        if ($(this).css("position") === "fixed") {
            isFixed = true;
            return false;
        }
    });
    return isFixed;
};

// Velocity has conflicts when loaded with jQuery, this will check for it
var Vel;
if ($) {
  Vel = $.Velocity;
} else if (jQuery) {
  Vel = jQuery.Velocity;
} else {
  Vel = Velocity;
}

(function ($) {
  $(document).ready(function() {

    $.fn.pushpin = function (options) {

      var defaults = {
        top: 0,
        bottom: Infinity,
        offset: 0
      }
      options = $.extend(defaults, options);

      $index = 0;
      return this.each(function() {
        var $uniqueId = Materialize.guid(),
            $this = $(this),
            $original_offset = $(this).offset().top;

        function removePinClasses(object) {
          object.removeClass('pin-top');
          object.removeClass('pinned');
          object.removeClass('pin-bottom');
        }

        function updateElements(objects, scrolled) {
          objects.each(function () {
            // Add position fixed (because its between top and bottom)
            if (options.top <= scrolled && options.bottom >= scrolled && !$(this).hasClass('pinned')) {
              removePinClasses($(this));
              $(this).css('top', options.offset);
              $(this).addClass('pinned');
            }

            // Add pin-top (when scrolled position is above top)
            if (scrolled < options.top && !$(this).hasClass('pin-top')) {
              removePinClasses($(this));
              $(this).css('top', 0);
              $(this).addClass('pin-top');
            }

            // Add pin-bottom (when scrolled position is below bottom)
            if (scrolled > options.bottom && !$(this).hasClass('pin-bottom')) {
              removePinClasses($(this));
              $(this).addClass('pin-bottom');
              $(this).css('top', options.bottom - $original_offset);
            }
          });
        }

        updateElements($this, $(window).scrollTop());
        $(window).on('scroll.' + $uniqueId, function () {
          var $scrolled = $(window).scrollTop() + options.offset;
          updateElements($this, $scrolled);
        });

      });

    };


  });
}( jQuery ));