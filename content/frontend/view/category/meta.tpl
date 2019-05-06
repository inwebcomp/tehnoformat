{%block meta%}
    <title>{%.meta_title%}</title>
    {%if .meta_keywords !== ""%}
        <meta name="keywords" content="{%.meta_keywords%}">
    {%endif%}
    {%if .meta_description !== ""%}
        <meta name="description" content="{%.meta_description%}">
    {%endif%}
{%end%}
