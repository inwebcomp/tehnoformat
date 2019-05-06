<div class="actions-list">
    <div class="action panel panel-default clear_history"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span><span>{%lang Очистить историю посетителей%}</span></div>
</div>
<script type="text/javascript">
	$('#{%oc%} .actions-list .action.clear_history').bind('click', function(){
		if (confirm('{%lang Вы действительно желаете очистить историю?%}')){
			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'delete_all', loader: "global" });
		}
    });
</script>