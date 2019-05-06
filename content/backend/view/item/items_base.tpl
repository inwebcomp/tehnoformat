<div class="list wow {%config_animation_effect_list%}">
        <table class="table table-hover">
            <thead>
                {%list items%}
                    {%ifset ._first%}
                        <tr>
                            {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                            <th class="small"></th>
                            <th>{%lang Название%}</th>
                            <th>{%lang Категория%}</th>
                            <th>{%lang Автор%}</th>
                            <th>{%lang Цена%}</th>
                            <th class="center"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></th>
                            <th class="center"><span class="glyphicon glyphicon-ok" aria-hidden="true"></th>
                            <th style="text-align:right">{%lang Статус%}</th>
                            {%ifset .pos%}<th style="display:none">{%lang Позиция%}</th>{%endif%}
                        </tr>
                    {%endif%}
                {%end%}
            </thead>
            <tbody>
                {%list items%}
                    <tr rel="{%.ID%}" class="vertical-align{%ifcount items < 200%} with_position{%endif%}">
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                        <th class="image"><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}"><img src="/image/Item/{%.ID%}/60x60/{%.base_image%}" width="60" height="60" /></a></th>
                        <th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.title%}</a></th>
                        <th fast-edit="category_ID">{%.category%}</th>
                        <th>{%.person%}</th>
                        <th fast-edit="price">{%.price%} {%.currency%}</th>
    
                        <th fast-edit="block" class="checkbox_field free center"><div class="checkbox show_only_label"><input class="styled focus-input" type="checkbox" value="1"{%if .block !== "1"%} checked{%endif%} id="{%.ID%}_checkbox_block" /><label for="{%.ID%}_checkbox_block"><span class="glyphicon glyphicon-eye-open{%if .block !== "1"%} active{%endif%}" aria-hidden="true"></span></label><input type="hidden" value="{%.block%}" /></div></th>
                        
                        <th fast-edit="expired" class="checkbox_field free center"><div class="checkbox show_only_label"><input class="styled focus-input" type="checkbox" value="1"{%if .expired !== "1"%} checked{%endif%} id="{%.ID%}_checkbox_expired" /><label for="{%.ID%}_checkbox_expired"><span class="glyphicon glyphicon-ok{%if .expired !== "1"%} active{%endif%}" aria-hidden="true"></span></label><input type="hidden" value="{%.expired%}" /></div></th>

                        <th style="text-align:right">
                            {%if .status == "verifying"%}<div class="color-block red inline r"></div>{%lang Ожидает проверки%}{%endif%}
                            {%if .status == "accepted"%}<div class="color-block green inline r"></div>{%lang Проверен%}{%endif%}
                            {%if .status == "rejected"%}<div class="color-block gray inline r"></div>{%lang Отклонён%}{%endif%}
                        </th>
    
                        {%ifcount items < 200%}
                            {%ifset .pos%}<th class="pos" style="display:none">{%ifset __fast_save%}<div class="input-group input-group-sm"><input name="params[{%.ID%}][pos]" type="text" value="{%.pos%}" class="filter-int form-control small" />{%else%}{%.pos%}{%endif%}</div></th>{%endif%}
                        {%endif%}
                    </tr>
                {%end%}
            </tbody>
        </table>
    </div>