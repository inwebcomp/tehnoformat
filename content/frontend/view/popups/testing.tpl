<popup ref="testing">
    <div class="title title--small">{%lang Мы свяжемся с вами!%}</div>

    <contact-form inline-template>
        <form @submit.prevent="submit" class="contact-form">
            <div class="form-control" :class="{ 'form-control--error': errors.name }">
                <div class="form-control__icon icon icon--user"></div>
                <input type="text" class="form-control__input" placeholder="{%lang Ваше имя%}" @keyup="verify(true)" v-model="form.name">
            </div>

            <div class="form-control" :class="{ 'form-control--error': errors.phone }">
                <div class="form-control__icon icon icon--phone"></div>
                <input type="text" class="form-control__input" placeholder="{%lang Номер телефона%}" @keyup="verify(true)" v-model="form.phone">
            </div>

            <button class="button">{%lang Заказать тестирование%}</button>

            <template>
                <div class="form__message" :class="{ 'form__message--error': error, 'form__message--success': ! error }" v-if="message != ''" v-text="message"></div>
            </template>
        </form>
    </contact-form>
</popup>