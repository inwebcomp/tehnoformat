<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        {%ifset .title%}<th>{%lang Заголовок%}</th>{%endif%}
                        {%ifset .name%}<th>{%lang Код ISO%}</th>{%endif%}
                        {%ifset .symbol%}<th>{%lang Символ%}</th>{%endif%}
                        {%ifset .display_type%}<th>{%lang Формат%}</th>{%endif%}
                        {%ifset .space%}<th>{%lang Отступ%}</th>{%endif%}
                        {%ifset .def%}<th class="padding_s center">{%lang Основная%}</th>{%endif%}
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
                    {%ifset .symbol%}<th>{%.symbol%}</th>{%endif%}
                    {%ifset .display_type%}<th>
                    	{%if .display_type == "1"%}0000.00X{%endif%}
                        {%if .display_type == "2"%}0000,00X{%endif%}
                        {%if .display_type == "3"%}0,000.00X{%endif%}
                        {%if .display_type == "4"%}0,000,00X{%endif%}
                        {%if .display_type == "5"%}0.000.00X{%endif%}
                        {%if .display_type == "6"%}0.000,00X{%endif%}
                        {%if .display_type == "7"%}0 000.00X{%endif%}
                        {%if .display_type == "8"%}0 000,00X{%endif%}
                    </th>{%endif%}
                    {%ifset .space%}<th class="checkbox_field free"><center><div class="checkbox"><input class="styled" type="checkbox" value="1"{%if .space == 1%} checked{%endif%} /><label></label><input name="params[{%.ID%}][space]" type="hidden" value="{%.space%}" /></div></center></th>{%endif%}   
                    {%ifset .def%}<th class="checkbox_field free"><center><div class="radio"><input class="styled" type="radio" value="1"{%if .def == 1%} checked{%endif%} group="param_def" /><label></label><input name="params[{%.ID%}][def]" type="hidden" value="{%.def%}" /></div></center></th>{%endif%}   
                    {%ifset .pos%}<th class="pos">{%ifset __fast_save%}<div class="input-group input-group-sm"><input name="params[{%.ID%}][pos]" type="text" value="{%.pos%}" class="filter-int form-control small" />{%else%}{%.pos%}{%endif%}</div></th>{%endif%}
                </tr>
            {%end%}
        </tbody>
    </table>
</div>