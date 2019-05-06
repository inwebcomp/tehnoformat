<div class="actions-list">
    <div class="action panel panel-default clear_cache"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span>{%lang Сбросить популярные%}</span></div>
</div>
<script type="text/javascript">
	$('#{%oc%} .actions-list .action.clear_cache').bind('click', function(){
		if (confirm('{%lang Подтвердить действие?%}')){
			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'reset_popular', loader: "global" });
		}
    });
</script>