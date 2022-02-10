const ModifyReservation = {
    forms: undefined,

    init: function() {
        ModifyReservation.forms = document.querySelectorAll('.tb-modify-reservation');
        ModifyReservation.forms.forEach(form => {
            form.addEventListener('submit', ModifyReservation.submit);
        });
    },

    submit: function(e) {
        e.preventDefault();

        const xhr = new XMLHttpRequest();

        // Ensure the restaurant nonce is set.
        if (!tbReservationNonce) {
            throw new Error('Missing reservation nonce.');
        }
        xhr.setRequestHeader('X-WP-Nonce', tbReservationNonce);
    }
}

console.log('Hello world.');

export default ModifyReservation;