<form class="actions_values filters_base panel panel-default">
    <div class="cms_filters">
        <div class="col">
            {%block select.where%}{%end%}
            <label for="cms_status_{%oc%}" class="visible">{%lang Статус%}:</label>
            <select id="cms_status_{%oc%}" size="1" name="params[where][status]" class="cmf_select auto_width">
            	<option value="_NULL" {%if select.where.status == ""%}selected{%endif%}>{%lang Все%}</option>
                <option value="0" {%if select.where.status == "0"%}selected{%endif%}>{%lang Ожидает проверки%}</option>
                <option value="1" {%if select.where.status == "1"%}selected{%endif%}>{%lang Ожидание оплаты%}</option>
                <option value="2" {%if select.where.status == "2"%}selected{%endif%}>{%lang В работе%}</option>
                <option value="3" {%if select.where.status == "3"%}selected{%endif%}>{%lang Завершён%}</option>
                <option value="4" {%if select.where.status == "4"%}selected{%endif%}>{%lang Отклонён%}</option>
            </select>
        </div>
    </div>
    <div class="cms_filters">
        <div class="col">
            {%block select.likeA%}{%end%}
            <label for="cms_name_{%oc%}" class="visible">{%lang Имя%}:</label>
            <input id="cms_name_{%oc%}" name="params[likeA][name]" class="input-field search auto_width" value="{%select.likeA.name%}">
        </div>
    </div>
</form>