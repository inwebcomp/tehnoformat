<div id="user_statistics">
    {%ifcount items > 0%}        
        <div class="list">
            <table class="table table-hover">	
				<caption style="font-size: 20px; padding: 15px;">{%lang Количество обратных звонков%}<span style="float: right;">{%lang Всего: %}<b>{%all%}</b></span></caption>
                <thead>
                    <tr>
                        <th>{%lang Курс%}</th>
                        <th class="padding">{%lang Обратные звонки%}</th>
                    </tr>
                </thead>
                <tbody>
                    {%list items%}
                        <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                            <th><a href="{%root%}/backend/{%language_name%}/index/link/item/edit/{%.course_ID%}">{%.course_title%}</a></th>
                            <th class="padding">{%.count%}</th>
                        </tr>
                    {%end%}
                </tbody>
            </table>
        </div>
    {%endif%}
</div>