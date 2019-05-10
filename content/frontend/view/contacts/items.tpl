<div class="container contacts">
    {%list contacts%}
        <div class="contact">
            <div class="contact__icon"><img class="contact__icon__img" src="/img/images/Contacts/{%.ID%}/{%.base_image%}" alt="{%.title%}"></div>
            <div class="contact__title">{%.title%}</div>

            <div class="contact__info">
                {%if .address !== ""%}
                    <div class="contact__line">
                        <i class="fas fa-map-marker-alt contact__line__icon"></i>
                        <div class="contact__line__info">
                            <div class="contact__line__label">{%lang Адрес%}</div>
                            <div class="contact__line__value">{%.address%}</div>
                        </div>
                    </div>
                {%endif%}
                {%if .phone !== ""%}
                    <div class="contact__line">
                        <i class="fas fa-phone contact__line__icon"></i>
                        <div class="contact__line__info contact__line__info--phone">
                            <div class="contact__line__label">{%lang Телефон%}</div>
                            <a href="tel:{%.phone_clean%}" class="contact__line__value">{%.phone%}</a>
                            <a href="tel:{%.phone2_clean%}" class="contact__line__value">{%.phone2%}</a>
                        </div>
                    </div>
                {%endif%}
                {%if .email !== ""%}
                    <div class="contact__line">
                        <i class="fas fa-envelope contact__line__icon"></i>
                        <div class="contact__line__info">
                            <div class="contact__line__label">{%lang Эл. почта%}</div>
                            <a href="mailto:{%.email%}" class="contact__line__value">{%.email%}</a>
                            <a href="mailto:{%.email2%}" class="contact__line__value">{%.email2%}</a>
                        </div>
                    </div>
                {%endif%}

            </div>
        </div>
    {%end%}
</div>