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

        /** The reservation ID to use in the query. */
        const resID = document.getElementById('tb-reservation-id').value;

        xhr.open('POST', `${location.protocol}//${location.host}/wp-json/tb/v1/reservations/${resID}`);
        
        xhr.onload = function () {
            // return response type as text 
            var reservation = JSON.parse(xhr.responseText);
            if(xhr.status == "204") {
                console.log(reservation);
            } else {
                console.error("Error");
            }
        }

        // xhr.send(reservation);
        // Ensure the restaurant nonce is set.
        if (!tbReservationNonce) {
            throw new Error('Missing reservation nonce.');
        }
        xhr.setRequestHeader('X-WP-Nonce', tbReservationNonce);


        // We have lift off!
        xhr.send(new FormData(e.target));
        
    }

    

}

console.log('Hello world.');

export default ModifyReservation;