const Private = Symbol('private');

class SignInController {
    init(){
        this[Private]().registerEvents();
    }

    [Private]() {
        let Private = {};

        Private.registerEvents = () => {
            $('.form-signin').on('submit', e => {
                e.stopPropagation();
                e.preventDefault();

                $('span[id$="error"]').each((index, item) => {
                    item.textContent = '';
                });

                let form = $('.form-signin').serializeArray();

                $.post('/api/sign-in', form)
                    .then(response => {
                        location.href = '/trips';
                    })
                    .fail(error => {
                        console.log(error);
                        let errors = error.responseJSON.errors;

                        for (let prop in errors) {
                            $(`#${prop}-error`).text(errors[prop]);
                        }
                    })
            });
        }

        return Private;
    }
};

export default SignInController;