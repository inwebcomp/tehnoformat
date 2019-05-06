<script>
    import Form from '../components/Form'

    export default {
        mixins: [
            Form
        ],

        data() {
            return {
                errors: {
                    name: false,
                    phone: false,
                    email: false,
                    message: false,
                    file: false
                },
                form: {
                    name: '',
                    phone: '',
                    email: '',
                    message: '',
                    file: null
                },
                fileName: ''
            }
        },

        methods: {
            submit() {
                let self = this

                if (!self.checkLoading())
                    return false

                self.message = ''

                if (!self.verify())
                    return false

                self.loading = true

                this.axios.post(
                    '/json/frontend/' + App.language +'/forms/contact',
                    Object.assign({}, self.form, {
                        fileName: this.fileName
                    })
                ).then(function ({data}) {
                    self.loading = false
                    self.error = !! data.error
                    self.message = data.message

                    if (! self.error) {
                        self.form = {
                            name: '',
                            phone: '',
                            email: '',
                            message: '',
                            file: null
                        }
                        self.$refs.file.value = '';
                        self.fileName = false;
                    }
                }).catch(function () {
                    self.error = true
                    self.loading = false
                    self.message = 'Server error'

                    if (response && response.data.errors.avatar.length) {
                        self.message = response.data.errors.avatar[0];
                        self.errors.avatar = true;
                    }
                })
            },

            verify(onlyWhenError = false) {
                if (onlyWhenError && this.error == false)
                    return

                this.errors.name = false
                this.errors.phone = false
                this.errors.email = false
                this.errors.message = false
                this.errors.file = false

                if (this.form.name == '')
                    this.errors.name = true
                if (this.form.phone == '' && this.form.email == '')
                    this.errors.phone = true
                if (this.form.message == '')
                    this.errors.message = true

                this.error = this.errors.name || this.errors.phone || this.errors.email || this.errors.message || this.errors.file

                return !this.error
            },

            setImage(event) {
                let self = this;
                let fileName = event.target.files[0];

                if (! fileName) {
                    self.form.file = '';
                    self.fileName = false;
                    return;
                }

                let reader = new FileReader();
                reader.onload = function () {
                    let data = reader.result;
                    self.form.file = data;
                };
                reader.readAsDataURL(fileName);

                self.fileName = fileName.name;
            },
        }
    }
</script>
