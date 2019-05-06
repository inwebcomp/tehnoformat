<div class="actions-list">
    <div class="action panel panel-default clear_cache"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span>{%lang Очистить кэш%}</span></div>
</div>
<script type="text/javascript">
	$('#{%oc%} .actions-list .action.clear_cache').bind('click', function(){
		if (confirm('{%lang Выполнить очистку кэша?%}')){
			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'clear_cache', loader: "global" });
		}
    });
</script>