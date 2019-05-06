<form class="actions_values filters_base panel panel-default">
    <div class="cms_filters">
        <div class="col">
            {%block select.likeA%}{%end%}
            <label for="cms_title_{%oc%}" class="visible">{%lang Поиск%}:</label>
            <input id="cms_title_{%oc%}" name="params[likeA][title]" class="input-field search auto_width" value="{%select.likeA.title%}">
        </div>
    </div>
</form>