<template>
    <transition :name="transition" :duration="duration">
        <div class="popup-wrapper" v-show="show" @click="$emit('close')" v-prevent-parent-scroll>
            <div class="popup-container" @click.stop>
                <div class="popup-content">
                    <slot></slot>
                </div>
                <div class="popup-close" @click="$emit('close')"></div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        name: 'popup',

        props: {
            transition: {
                default: 'popup'
            },
            duration: {
                default: null
            }
        },

        data() {
            return {
                show: false,
                params: {}
            }
        },

        created() {
            let self = this

            self.$on('show', function (params) {
                self.show = true
                self.$root.popupIsActive = true
                self.params = params
            })
            self.$on('close', function () {
                self.show = false
                self.$root.popupIsActive = false
            })
            self.$root.$on('closePopup', function () {
                self.show = false
                self.$root.popupIsActive = false
            })
        }
    }
</script>
