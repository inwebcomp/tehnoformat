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
                            <th>{%lang Дата%}</th>
                            <th>{%lang Статус%}</th>
                            {%ifset .pos%}<th style="display:none">{%lang Позиция%}</th>{%endif%}
                        </tr>
                    {%endif%}
                {%end%}
            </thead>
            <tbody>
                {%list items%}
                    <tr rel="{%.ID%}" class="vertical-align{%ifcount items < 200%} with_position{%endif%}">
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                        <th class="image"><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}"><img src="/image/ItemCopy/{%.ID%}/60x60/{%.base_image%}" width="60" height="60" /></a></th>
                        <th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.title%}</a></th>
                        <th>{%.category%}</th>
                        <th>{%.person%}</th>
                        <th>{%.price%} {%.currency%}</th>


                        <th>{%.updated%}</th>
                        <th>
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