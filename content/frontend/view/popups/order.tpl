<popup ref="order">
    <div class="contacts-form order-form">
        <div class="contacts-form__info">
            <div class="title">{%title%}</div>

            <div class="contacts-form__info__line">
                <div class="subtitle subtitle--second">{%description%}</div>
            </div>
        </div>

        <div class="contacts-form__form">
            {%controller forms/contact contact-form cache%}
        </div>
    </div>
</popup>