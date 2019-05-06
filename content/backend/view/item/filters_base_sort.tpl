<form class="actions_values filters_base panel panel-default">
    <div class="cms_filters">
        {%block category_ID_block%}
            {%ifcount .items > 0%}
                <div class="col">
                    <label>&nbsp;</label>
                    <select size="1" name="params[where][category_ID]" id="where_category_ID_{%oc%}" class="cmf_select2 cms_parent_select">
                        <option{%if selected_category == ""%} selected{%endif%} value="_NULL">{%lang Все%}</option>
                        {%list .items%}
                            <option{%if selected_category == .ID%} selected{%endif%} value="{%.ID%}">{%.title%}</option>
                        {%end%}
                    </select>
                </div>
            {%else%}
                {%block category_ID_block.nodes%}
                    {%block .select%}
                        <input name="params[where][category_ID]" type="hidden" value="{%block .where%}{%ifset .parent_ID%}{%.parent_ID%}{%else%}1{%endif%}{%end%}" />
                    {%end%}
                {%end%}
                {%ifset items.level%}
                    <input type="hidden" name="params[where][level]" id="level" value="{%items.level%}" />
                {%endif%}
            {%endif%}
 
        {%end%}
    </div>
    {%block select%}
    	{%if .num > 0%}
            <div class="cms_filters">
                <div class="col">
                    <label class="visible" for="cmf_onPage_{%oc%}">{%lang На странице%}:</label>
                    <select id="cmf_onPage_{%oc%}" size="1" name="params[onPage]" class="cmf_select2 cms_onPage">
                        <option value="10" {%if .onPage == 10%}selected="selected"{%endif%}>10</option>
                        <option value="30" {%if .onPage == 30%}selected="selected"{%endif%}>30</option>
                        <option value="40" {%if .onPage == 40%}selected="selected"{%endif%}>40</option>
                        <option value="50" {%if .onPage == 50%}selected="selected"{%endif%}>50</option>
                        <option value="100" {%if .onPage == 100%}selected="selected"{%endif%}>100</option>
                        <option value="500" {%if .onPage == 500%}selected="selected"{%endif%}>500</option>
                        <option value="1000" {%if .onPage == 1000%}selected="selected"{%endif%}>1000</option>
                    </select>
                </div>
            </div>
        {%endif%}
    {%end%}
    <div class="cms_filters">
        <a id="sort_items" href="javascript::void(0)" class="nodecoration animated_all">
            <div class="col" style="font-size:14px;">
                <span>{%lang К списку%}</span>
            </div>
        </a>
    </div>
</form>

<script>
    $("#sort_items").unbind().bind("click", function(){
        return $("#{%oc%}").Request({ controller: 'item', action: 'items', data: $('#{%oc%}_request_params').serialize(), loader: "global" });
    });
	
	$("#autosort").unbind().bind("click", function(){
		var blocked = [];
        $("#{%oc%} .sortable .item").each(function(index, element){
            if($(element).hasClass('blocked')){
				blocked[index] = $(element);
				$(element).replaceWith('');
			}
        });
		$.each(blocked, function(index, element){
            $("#{%oc%} .sortable .item").last().after(element);
        });
		
		CalculatePositions();
		
		$.Request({ type: 'json', controller: '{%controllerName%}', action: 'fast_save', data: $('#cms_actions_form_{%oc%}').serialize()+"&"+$('#{%oc%}_request_params').serialize(), loader: 'global', complete: function(){ $('.cms_loader').replaceWith(" "); } });
    });
	
	/* Select Actions */
	$('#{%oc%} .actions_values select').bind('change', function(){
		if($('#{%oc%} .actions_values').attr("rel") !== undefined){
			var av_action = $('#{%oc%} .actions_values').attr("rel");
		}else{
			var av_action = "sort_items";
		}
	
    	return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: av_action, data: 'params[page_is]=1&' + $('#{%oc%} .actions_values').serialize()+"&"+$('#{%oc%}_request_params').serialize() }); 
    });
	
	
	/* Category filter select */
	$('#{%oc%} .cms_path .cms_category:not(.last_level)').bind('click', function(){
		var self = $(this);
		if(self.attr('rel') == "1"){
			self.attr('rel', '_NULL');
		}
		$('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'sort_items', data: 'params[page_is]={%select.page_is%}&' + $(".actions_values").serialize() + '&params[where][category_ID]=' + self.attr('rel')+"&"+$('#{%oc%}_request_params').serialize() });
				
		return false;
    });
</script>