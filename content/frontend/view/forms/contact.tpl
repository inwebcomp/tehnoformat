<contact-form inline-template>
    <form @submit.prevent="submit" class="form form--contact">
        <div class="row grid--cols-2">
            <div class="col">
                <div class="form-control" :class="{ 'form-control--error': errors.name }">
                    <input type="text" class="form-control__input" placeholder="{%lang Имя%}" @keyup="verify(true)" v-model="form.name">
                </div>
            </div>
            <div class="col">
                <div class="form-control" :class="{ 'form-control--error': errors.phone }">
                    <input type="text" class="form-control__input" placeholder="{%lang Номер телефона%}" @keyup="verify(true)" v-model="form.phone">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-control" :class="{ 'form-control--error': errors.email }">
                <input type="email" class="form-control__input" placeholder="{%lang Электронная почта%}" @keyup="verify(true)" v-model="form.email">
            </div>
        </div>
        <div class="row">
            <div class="form-control" :class="{ 'form-control--error': errors.message }">
                <textarea class="form-control__input form-control__input--textarea" placeholder="{%lang Сообщение%}" @keyup="verify(true)" v-model="form.message"></textarea>
            </div>
        </div>
        <div class="row grid--cols-2 contacts-from__row--buttons">
            <div class="col">
                <label class="form-control form-control--file" :class="{ 'form-control--error': errors.file }">
                    <div class="form-control__input form-control__input--file">
                        <i class="icon icon--clip"></i>
                        <span v-text="fileName ? fileName : '{%lang Прикрепить файл (jpg, png, zip)%}'"></span>
                    </div>
                    <input ref="file" @change="setImage" type="file" accept=".jpg,.jpeg,.png,.zip" />
                </label>
            </div>
            <div class="col">
                <button class="button button--accent">{%lang Отправить%}</button>
            </div>
        </div>
        <div class="row">
            <template>
                <div class="form__message" :class="{ 'form__message--error': error, 'form__message--success': ! error }" v-if="message != ''" v-text="message"></div>
            </template>
        </div>
    </form>
</contact-form>