<form class="actions_values filters_base panel panel-default">
    <div class="cms_filters">
        {%block parent_ID_block%}
        
            {%ifcount .path > 1%}
                <div onclick="$(this).next().find('.cms_parent:last').prev().click()" class="back_parent animated"><img src="/img/backend/white_left_arrow.png" height="16" width="16"><span>{%lang Назад%}</span></div>
            {%endif%}
            
            <div class="cms_path">
                {%list .path%}
                    <a href="javascript:void(0)" level="{%.level%}" class="cms_parent path{%._index%}" rel="{%.ID%}">{%ifnotset ._first%}<img src="/img/backend/white_right_arrow.png" height="16" width="16" />{%endif%}{%.title%}</a>
                {%end%}
            </div>
    
            {%if .pathCount < .maxLevel%}
                {%block .nodes%}
                    {%block .select%}
                        {%if .num > 0%}
                        <div class="col">
                            {%block parent_ID_block.nodes%}
       
                                    <label>&nbsp;</label>
                                    <select size="1" id="where_parent_ID_{%oc%}" name="params[where][parent_ID]" class="cmf_select2 cms_parent_select">
                                        <option value="{%block select.where%}{%ifset .parent_ID%}{%.parent_ID%}{%else%}1{%endif%}{%end%}">{%lang Выберите значение%}</option>
                                        {%list .items%}
                                            {%if .last_level !== "1"%}<option value="{%.ID%}">{%.title%}</option>{%endif%}
                                        {%end%}
                                    </select>
             
                                {%ifset items.level%}
                                    <input type="hidden" name="params[where][level]" id="level" value="{%items.level%}" />
                                {%endif%}
    
                             {%end%}
                        </div>
                        {%else%}
                            {%block parent_ID_block.nodes%}
                                {%ifset items.parent_ID%}
                                <input name="params[where][parent_ID]" type="hidden" value="{%block select.where%}{%ifset .parent_ID%}{%.parent_ID%}{%else%}1{%endif%}{%end%}" />
                                {%endif%}
                            {%end%}
                            {%ifset items.level%}
                                <input type="hidden" name="params[where][level]" id="level" value="{%items.level%}" />
                            {%endif%}
                        {%endif%}
                    {%end%}
                {%end%}
            {%else%}
                {%block parent_ID_block.nodes%}
                    {%ifset items.parent_ID%}
                        <input name="params[where][parent_ID]" type="hidden" value="{%block select.where%}{%ifset .parent_ID%}{%.parent_ID%}{%else%}1{%endif%}{%end%}" />
                    {%endif%}
                {%end%}
                {%ifset items.level%}
                    <input type="hidden" name="params[where][level]" id="level" value="{%items.level%}" />
                {%endif%}
            {%endif%}
        {%end%}
    </div>
</form>
<!-- 
<div class="actions-list">
    <div class="action panel panel-default category_update_cache"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span>{%lang Обновить кэш категорий%}</span></div>
</div>
<script type="text/javascript">
	$('#{%oc%} .actions-list .action.category_update_cache').bind('click', function(){
		if (confirm('{%lang Вы действительно желаете обновить кэш категорий?%}')){
			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'category_update_cache', loader: "global" });
		}
    });
</script> -->