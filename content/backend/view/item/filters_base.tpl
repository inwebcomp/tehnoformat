<form class="actions_values filters_base panel panel-default">
    <div class="cms_filters">
        {%block category_ID_block%}
			{%ifcount .path > 1%}
                <div onclick="$(this).next().find('.cms_category:last').prev().click()" class="back_parent animated"><img src="/img/backend/white_left_arrow.png" height="16" width="16"><span>{%lang Назад%}</span></div>
            {%endif%}
			
            <div class="cms_path">
                {%list .path%}
                    <a href="javascript:void(0)" level="{%.level%}" class="cms_category path{%._index%}" rel="{%.ID%}">{%ifnotset ._first%}<img src="/img/backend/white_right_arrow.png" height="16" width="16" />{%.title%}{%else%}{%lang Все%}{%endif%}</a>
                {%end%}
            </div>
    
            {%if .pathCount < .maxLevel%}
                {%block .nodes%}
                    {%block .select%}
                        {%if .num > 0%}
                        <div class="col">
                            {%block category_ID_block.nodes%}
       
                                    <label>&nbsp;</label>
                                    <select size="1" id="where_category_ID_{%oc%}" name="params[where][category_ID]" class="cmf_select2 cms_parent_select">
                                        <option value="{%selected_category%}">{%lang Выберите значение%}</option>
                                        {%list .items%}
                                            <option value="{%.ID%}">{%.title%}</option>
                                        {%end%}
                                    </select>
             
                                {%ifset items.level%}
                                    <input type="hidden" name="params[where][level]" id="level" value="{%items.level%}" />
                                {%endif%}
    
                             {%end%}
                        </div>
                        {%else%}
                            <input name="params[where][category_ID]" type="hidden" value="{%selected_category%}" />
                        {%endif%}
                    {%end%}
                {%end%}
            {%else%}
                {%block category_ID_block.nodes%}
                    {%ifset items.parent_ID%}
                        <input name="params[where][category_ID]" type="hidden" value="{%block select.where%}{%ifset .category_ID%}{%.category_ID%}{%else%}1{%endif%}{%end%}" />
                    {%endif%}
                {%end%}
                {%ifset items.level%}
                    <input type="hidden" name="params[where][level]" id="level" value="{%items.level%}" />
                {%endif%}
            {%endif%}
        {%end%}
    </div>
    
    <div class="cms_filters">
        <div class="col">
            {%block select.likeA%}{%end%}
            <label for="cms_name_{%oc%}" class="visible">{%lang Название%}:</label>
            <input id="cms_name_{%oc%}" name="params[likeA][title]" class="input-field search auto_width" value="{%select.likeA.title%}">
        </div>
    </div>

    <div class="cms_filters">
        <a id="sort_items" href="javascript:void(0)" class="nodecoration animated_all">
            <div class="col" style="font-size:14px;">
                <span>{%lang Сортировать%}</span>
            </div>
        </a>
    </div>

    <script>
        $("#sort_items").bind("click", function(){
            return $("#{%oc%}").Request({ controller: 'item', action: 'sort_items', data: $('.actions_values').serialize(), loader: "global" });
        });
    </script>
</form>