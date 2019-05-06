{%includeview textblock%}

{%if form_title !== ""%}
    <div class="help-form">
        <div class="help-form__title title">{%form_title%}</div>
        <div class="help-form__text subtitle">{%form_text%}</div>
        <button class="help-form__button button button--accent"{%if form_button_type !== "1"%} @click="showPopup('contact')"{%else%} @click="showPopup('order')"{%endif%}>{%form_button_text%}</button>
    </div>
{%endif%}

{%ifset admin%}
    {%includeview textblock2%}
{%else%}
    {%ifset text2 !== ""%}
        {%includeview textblock2%}
    {%endif%}
{%endif%}


{%block gallery%}
    <div class="category__gallery">
        <div class="title">{%lang Наши работы%}</div>

        <div class="gallery">
            {%list .images%}
            <a href="/img/images/Gallery/{%.object_ID%}/full/{%.name%}" class="gallery-images__image" data-fancybox="gallery">
                <img class="gallery-images__image__img" src="/img/images/Gallery/{%.object_ID%}/index/{%.name%}" width="370" height="230" alt="{%gallery.title%}">
            </a>
            {%end%}
        </div>

        <a href="{%.href%}" class="category__gallery__all">{%lang Посмотреть все%}</a>
    </div>
{%end%}

{%ifset admin%}
    <div class="save-button" @click="saveCategory({%ID%})">
        <i class="fa fa-save"></i> {{ saveButtonText }}
    </div>
{%endif%}