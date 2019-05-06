<div class="block block--contacts-form" id="contacts-form">
    <div class="container">
        <div class="contacts-form">
            <div class="contacts-form__info">
                <div class="title">{%title%}</div>

                <div class="contacts-form__info__line">
                    <i class="icon icon--contacts-form1 contacts-form__icon"></i>
                    <div class="subtitle subtitle--first">{%description1%}</div>
                </div>
                <div class="contacts-form__info__line">
                    <i class="icon icon--contacts-form2 contacts-form__icon"></i>
                    <div class="subtitle subtitle--second">{%description2%}</div>
                </div>
            </div>
            <div class="contacts-form__form">
                {%controller forms/contact contact-form cache%}
            </div>
        </div>
    </div>
</div>