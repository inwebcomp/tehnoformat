<label>{%lang Язык админ-панели%}:</label>
{%list items%}
    <a href="/backend/{%.name%}/{%noLangRequest%}" rel="{%.name%}" class="item animated {%ifset selected_lang%} {%if selected_lang == .name%}selected{%endif%}{%else%}{%if language_name == .name%}selected{%endif%}{%endif%}"><img src="{%root%}/img/lang/{%.name%}.jpg" class="icon" width="20" height="13"><span>{%.title%}</span></a>
{%end%}


<script type="text/javascript">
{%ifset selected_lang%}
	$('#header .settings_block').ready(function(){
		$("#header .settings_block a").bind("click", function(e){
			e.preventDefault();
			$.Request({ type: "json", controller: "catalog", action: "language_text_set", data: "lang=" + $(this).attr("rel"), complete: function(data){ window.location.reload(); } });	
			return false;
		});
	});
{%endif%}
</script>