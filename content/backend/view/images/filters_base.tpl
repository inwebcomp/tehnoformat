<div class="actions-list">
    <div class="action panel panel-default create-thumbnails"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span>{%lang Переделать все миниатюры%}</span></div>
</div>
<script type="text/javascript">
	$('#{%oc%} .actions-list .action.create-thumbnails').bind('click', function(){
		if (confirm('{%lang Это может занять много времени, вы хотите продолжить?%}')){
			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'create_thumbnails', loader: "global" });
		}
    });
</script>