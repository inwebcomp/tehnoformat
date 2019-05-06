{%list items%}
    <a href="javascript:void(0)" rel="{%.name%}" class="item animated {%ifset selected_lang%}text {%if selected_lang == .name%}selected{%endif%}{%else%}{%if language_name == .name%}selected{%endif%}{%endif%}"><img src="{%root%}/img/lang/{%.name%}.jpg" class="icon" width="20" height="13"><span>{%.title%}</span></a>
{%end%}

<script type="text/javascript">
$('#header .language_block').ready(function(){
	$("#header .language_block a").bind("click", function(e){
		e.preventDefault();
		
		var lang = $(this).attr("rel");
		var shref = window.location.href.split("/");
		var href = "";
		$.each(shref, function(i, val){
			if(val == "{%language_name%}"){
				val = lang;
			}
			if(i > 2)
				href = href+"/"+val;
		});
		window.location.href = href;
	});
});
</script>