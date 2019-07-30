const form = document.login

form.email.addEventListener('keyup', function () {
    checkError(!this.value.includes('@fft.fr'), this, '.email-error')
})

form.password.addEventListener('keyup', function () {
    checkError(this.value.length < 8, this, '.password-error')
    checkError(form.password_confirmation.value !== this.value, form.password_confirmation, '.password_confirmation-error')
})

form.password_confirmation.addEventListener('keyup', function () {
    checkError(this.value !== form.password.value, this, '.password_confirmation-error')
})

const checkError = (condition, input, className) => {
    const el = document.querySelector(className)
    if (condition) {
        input.classList.add('is-invalid')
        el.removeAttribute('hidden')
        isFormValid()
        return el.firstElementChild.innerHTML = input.dataset.error
    }
    input.classList.remove('is-invalid')
    el.setAttribute('hidden', 'hidden')
    isFormValid()
}

const isFormValid = () => {
    if ([...form.querySelectorAll('input')].some(el => el.classList.contains('is-invalid'))) {
        form.btn.setAttribute('disabled', true)
    } else {
        form.btn.removeAttribute('disabled')
    }
}
