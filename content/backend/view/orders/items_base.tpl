<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        {%ifset .name%}<th>{%lang Имя%}</th>{%endif%}
                        {%ifset .phone%}<th>{%lang Телефон%}</th>{%endif%}
                        {%ifset .created%}<th>{%lang Создан%}</th>{%endif%}
                        {%ifset .status%}<th>{%lang Статус%}</th>{%endif%}
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    {%ifset .name%}<th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.name%}</a></th>{%endif%}
                    {%ifset .phone%}<th>{%.phone%}</th>{%endif%}
                    {%ifset .created%}<th>{%.created%}</th>{%endif%}
                    {%ifset .status%}
                        <th>
                            {%if .status == "0"%}<div class="color-block red inline r"></div>{%lang Ожидает проверки%}{%endif%}
                            {%if .status == "1"%}<div class="color-block yellow inline r"></div>{%lang Ожидание оплаты%}{%endif%}
                            {%if .status == "2"%}<div class="color-block blue inline r"></div>{%lang В работе%}{%endif%}
                            {%if .status == "3"%}<div class="color-block green inline r"></div>{%lang Завершён%}{%endif%}
                            {%if .status == "4"%}<div class="color-block gray inline r"></div>{%lang Отклонён%}{%endif%}
                        </th>
                    {%endif%}
                </tr>
            {%end%}
        </tbody>
    </table>
</div>