<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        {%ifset .title%}<th>{%lang Заголовок%}</th>{%endif%}
                        {%ifset .name%}<th>{%lang Имя%}</th>{%endif%}
                        {%ifset .value%}<th class="padding">{%lang Значение%}</th>{%endif%}
                        {%ifset .group%}<th>{%lang Группа%}</th>{%endif%}
                        {%ifset .pos%}<th>{%lang Позиция%}</th>{%endif%}
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr class="vertical-align{%if .block == 1%} warning{%endif%} with_position">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    {%ifset .title%}<th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.title%}</a></th>{%endif%}
                    {%ifset .name%}<th>{%.name%}</th>{%endif%}
                    {%ifset .value%}
                        {%if .type == "string"%}
                            <th><div class="input-group input-group-sm"><input name="params[{%.ID%}][value]" type="text" value="{%.value%}" class="form-control" /></div></th>
                        {%endif%}
                        {%if .type == "int"%}
                            <th><div class="input-group input-group-sm"><input name="params[{%.ID%}][value]" type="number" value="{%.value%}" class="form-control" /></div></th>
                        {%endif%}
                        {%if .type == "checkbox"%}
                            <th class="checkbox_field free"><center><div class="checkbox"><input class="styled" type="checkbox" value="1"{%if .value == 1%} checked{%endif%} /><label></label><input name="params[{%.ID%}][value]" type="hidden" value="{%.value%}" /></div></center></th>
                        {%endif%}
                        {%if .type == "select"%}<th>{%.value%}</th>{%endif%}
                        {%if .type == "mediafile"%}
                            <th>
                                {%block .file%}
                                    {%if .type == "image"%}
                                        <img src="{%root%}/files/{%.group%}/{%modelName%}/{%items.ID%}/{%.name%}" height="80" />
                                    {%else%}
                                        <a href="{%root%}/files/{%.group%}/{%modelName%}/{%items.ID%}/{%.name%}" target="_blank">{%.name%}</a>
                                    {%endif%}
                                {%end%}
                            </th>
                        {%endif%}       
                    {%endif%}
                    {%ifset .group%}<th>{%.group%}</th>{%endif%}
                    {%ifset .pos%}<th class="pos">{%ifset __fast_save%}<div class="input-group input-group-sm"><input name="params[{%.ID%}][pos]" type="text" value="{%.pos%}" class="filter-int form-control small" />{%else%}{%.pos%}{%endif%}</div></th>{%endif%}
                </tr>
            {%end%}
        </tbody>
    </table>
</div>