<div class="container">
    {%includeview breadcrumbs%}

    <div class="title">{%title%}</div>

    <div class="gallery-images">
        {%list images%}
            <a href="/img/images/Gallery/{%.object_ID%}/full/{%.name%}" class="gallery-images__image" data-fancybox="gallery">
                <img class="gallery-images__image__img" src="/img/images/Gallery/{%.object_ID%}/index/{%.name%}" width="370" height="230" alt="{%.title%}">
            </a>
        {%end%}
    </div>
</div>