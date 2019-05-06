<div class="block block--gallery">
    <div class="container">
        <a href="{%pagepath gallery%}" class="title">{%title%}</a>

        <div class="gallery owl-carousel owl-theme">
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
</div>