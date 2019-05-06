<transition name="fade">
    <div class="popup-mask" v-show="popupIsActive" @click="$root.$emit('closePopup')"></div>
</transition>

{%controller popups/contact popup-contact cache%}
{%controller popups/order popup-order cache%}
