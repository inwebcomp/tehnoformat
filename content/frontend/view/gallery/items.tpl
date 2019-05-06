<div class="container">
    {%includeview breadcrumbs%}

    <div class="title">{%title%}</div>

    <div class="gallery">
        {%list gallery%}
            <a href="{%.href%}" class="gallery__image">
                <div class="gallery__content">
                    <div class="gallery__title">{%.title%}</div>
                </div>
                <img class="gallery__image" src="/img/images/Gallery/{%.ID%}/index/{%.base_image%}" width="370" height="230" alt="{%.title%}">
            </a>
        {%end%}
    </div>
</div>