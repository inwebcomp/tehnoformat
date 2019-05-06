<div class="list wow {%config_animation_effect_list%}">
    <div class="panel grid-sort sortable">
        {%list items%}
        	<a rel="{%.ID%}" class="item{%if .block == "1"%} blocked{%endif%}" href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">
                <img{%if .block == "1"%} style="opacity:0.5"{%endif%} src="{%root%}/image/Item/{%.ID%}/70x70/{%.base_image%}" width="70" height="70" title="{%.title%}, ID: {%.ID%}" />
				{%if .discount > 0%}<div class="discount">-{%.discount%}%</div>{%endif%}
                <input name="params[{%.ID%}][pos]" type="hidden" value="{%.pos%}" class="filter-int form-control small" />
            </a>
        {%end%}
    </div>
</div>
<script>
	$('#{%oc%} .cms_pagination li:not(.disabled,.active) a').unbind().bind('click', function(){
		return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'sort_items', data: 'params[page_is]=' + $(this).attr('rel')+"&"+$('#{%oc%} .actions_values').serialize()+"&"+$('#{%oc%}_request_params').serialize(), loader: "global" });
	});
	
	var CalculateIndexes = function(){
		$("#{%oc%} .sortable .item").each(function(index, element){
            $(element).attr('data-index', index).attr('data-position', $(element).find('input').val());
        });
	}
	
	var CalculatePositions = function(){
		$("#{%oc%} .sortable .item").each(function(index, element){
            $(element).find('input').val($("#{%oc%} .sortable .item[data-index="+$(element).index()+"]").data('position'));
        });
	}
	
	CalculateIndexes();

	var InitSelection = function(objects){
		objects.bind('click', function(e){
			if(e.ctrlKey == true){
				if($(this).hasClass('selected') == true){
					$(this).removeClass('selected');
				}else{
					$(this).addClass('selected');
				}
				return false;
			}else{
				if($(this).hasClass('selected') == false){
					$(this).parent().find('.item').removeClass('selected');
				}
			}
		});
	}
	
	InitSelection($("#{%oc%} .sortable .item"));
	
	$("body").bind('click', function(e){
		if(e.ctrlKey == false){
			$("#{%oc%} .sortable .item").removeClass('selected');
		}
	});
	
	var MoveSelected = function(ui){
		var selectedItems = $("#{%oc%} .sortable .item.selected:not([data-index="+ui.item.data('index')+"])");
		selectedItems.replaceWith('');
		ui.item.after(selectedItems);
		InitSelection(selectedItems);
	}
	
    $("#{%oc%} .sortable").sortable({
		cursor: "move",
		distance: 5,
		update: function(event, ui){
			MoveSelected(ui);
			
			var pos = ui.item.data('position');
			CalculatePositions();
			//CalculateIndexes();
			
			$.Request({ type: 'json', controller: '{%controllerName%}', action: 'fast_save', data: $('#cms_actions_form_{%oc%}').serialize()+"&"+$('#{%oc%}_request_params').serialize(), complete: function(){ $('.cms_loader').replaceWith(" "); } });
		}
	});
    $("#{%oc%} .sortable").disableSelection();

  </script>