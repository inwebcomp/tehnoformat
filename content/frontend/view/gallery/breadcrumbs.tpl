<div class="breadcrumbs">
    <a class="breadcrumbs__item" href="/{%if language_name !== config_default_language%}{%language_name%}{%endif%}">{%lang Главная%}</a>
    <span class="breadcrumbs__separator">/</span>
    <a class="breadcrumbs__item" href="/{%pagepath gallery%}">{%page_title%}</a>
    {%ifset images%}
        <span class="breadcrumbs__separator">/</span>
        <span class="breadcrumbs__item">{%title%}</span>
    {%endif%}
</div>