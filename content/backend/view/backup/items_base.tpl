<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>
                <th>{%lang Файл%}</th>
                <th>{%lang Создан%}</th>
                <th>{%lang Размер%}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {%list items%}
                <tr rel="{%.name%}" class="vertical-align">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    <th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.name%}">{%.name%}</a></th>
                    <th>{%.created%}</th>
                    <th>{%.sizeMB%} MB</th>
                </tr>
            {%end%}
        </tbody>
    </table>
</div>