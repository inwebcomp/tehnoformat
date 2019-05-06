<script type="text/javascript">
	/* Message */
	$.fn.disappear = function(){ 
		$(this).delay(5000).animate({ top: "-50px", opacity: 0 }, 600, "easeInBack", function(){ $(this).replaceWith(""); });
	}
	$.fn.disappearAtOnce = function(){
		$(this).stop().animate({ top: "-50px", opacity: 0 }, 600, "easeInBack", function(){ $(this).replaceWith(""); });
	}
	$.fn.appear = function(){
		$(this).animate({ top: "10px", opacity: 1 }, 600, "easeOutBack", function(){ });
	}
	
	/* Touch Effect */
	$(function(){
		var parent, ink, d, x, y;
		$(".effect-touch").mousedown(function(e){
			parent = $(this);
			
			if(parent.find(".ink").length == 0)
				parent.append("<span class='ink'></span>");
			
			ink = parent.find(".ink");
			
			ink.removeClass("animateIt");
		
			if(!ink.height() && !ink.width())
			{
				d = Math.max(parent.outerWidth(), parent.outerHeight());
				ink.css({height: d, width: d});
			}
			
			x = e.pageX - parent.offset().left - ink.width()/2;
			y = e.pageY - parent.offset().top - ink.height()/2;
			
			ink.css({top: y+'px', left: x+'px'}).addClass("animateIt");
		});
	});
</script>