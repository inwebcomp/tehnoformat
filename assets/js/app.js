import Vue from "vue"
import axios from 'axios'
import VueAxios from 'vue-axios'
import ScrollIntoView from 'scroll-into-view'

Vue.use(VueAxios, axios)
Vue.use(require('vue-prevent-parent-scroll'))

import Popup from './components/Popup'
import ContactForm from './forms/ContactForm'

import qs from 'qs';
import StickySidebar from "sticky-sidebar"

let app = new Vue({
    el: "#app",

    components: {
        Popup,
        ContactForm,
    },

    data() {
        return {
            popupIsActive: false,
            saveButtonText: "Сохранить",
            textChanged: false,
            floatingHeader : false,
            floatingButton : false,
            floatingCategories : false,
        }
    },

    methods: {
        showPopup(name, params = {}) {
            if (this.$refs[name] == undefined)
                return console.error("[Vue/Popup]: Popup '" + name + "' not found")

            this.$refs[name].$emit('show', params)

            window.addEventListener("scroll", function () {
                return false
            })
        },

        scrollTop() {
            document.getElementsByClassName('header')[0].scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            })
        },

        scrollToBlock(e, id) {
            let el = document.getElementById(id)

            if (window.location.pathname == '/' || window.location.pathname == '/ru' || window.location.pathname == '/ro') {
                window.history.pushState({}, e.target.innerText, e.target.href)

                ScrollIntoView(el, {
                    time: 350
                })

                e.preventDefault()
            }
        },

        saveTextpage(ID) {
            let value = $("#editor").froalaEditor('html.get')

            if (! value && ! confirm("Сохранится пустое значение. Продолжить?"))
                return;

            let self = this

            self.axios.request({
                method: 'POST',
                url: '/json/frontend/' + App.language + '/pages/save',
                data: qs.stringify({ID, value})
            }).then(() => {
                self.saveButtonText = "Данные сохранены"
                self.textChanged = false

                setTimeout(() => {
                    self.saveButtonText = "Сохранить"
                }, 2000)
            }).catch((error) => {
                alert("Произошла ошибка")
                console.log(error)
            })
        },

        saveCategory(ID) {
            let value = $("#editor").froalaEditor('html.get')
            let value2 = $("#editor2").froalaEditor('html.get')

            if (! value && ! confirm("Сохранится пустое значение. Продолжить?"))
                return;

            let self = this

            self.axios.request({
                method: 'POST',
                url: '/json/frontend/' + App.language + '/category/save',
                data: qs.stringify({ID, value, value2})
            }).then(() => {
                self.saveButtonText = "Данные сохранены"
                self.textChanged = false

                setTimeout(() => {
                    self.saveButtonText = "Сохранить"
                }, 2000)
            }).catch((error) => {
                alert("Произошла ошибка")
                console.log(error)
            })
        },

        calculateFloatingHeader() {
            if (window.pageYOffset >= 40) {
                if (! this.floatingHeader) {
                    this.floatingHeader = true
                    document.getElementsByTagName('body')[0].classList.add('float-header')
                }
            } else {
                if (this.floatingHeader) {
                    this.floatingHeader = false
                    document.getElementsByTagName('body')[0].classList.remove('float-header')
                }
            }
        },

        calculateFloatingButton() {
            if (window.pageYOffset >= 600) {
                if (! this.floatingButton) {
                    this.floatingButton = true
                    document.getElementsByClassName('floating-button')[0].classList.add('floating-button--visible')
                }
            } else {
                if (this.floatingButton) {
                    this.floatingButton = false
                    document.getElementsByClassName('floating-button')[0].classList.remove('floating-button--visible')
                }
            }
        },
    },

    mounted() {
        let self = this

        self.calculateFloatingHeader()
        self.calculateFloatingButton()

        if (document.getElementById('sidebar')) {
            document.addEventListener("DOMContentLoaded", function() {
                // setTimeout(() => {
                    new StickySidebar('#sidebar', {
                        containerSelector: '.layout',
                        innerWrapperSelector: '.categories-list',
                        topSpacing: 96,
                        bottomSpacing: 16
                    });
                // }, 500)
            })
        }

        window.onscroll = function(e) {
            self.calculateFloatingHeader()
            self.calculateFloatingButton()
        }

        // window.onbeforeunload = function(e) {
        //     return true;
        // }
    }
})
