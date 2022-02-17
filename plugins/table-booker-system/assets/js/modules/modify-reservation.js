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

       
        //Listen for a response 
        xhr.addEventListener('load', (e) => {
            //Success!
            if(e.target.status === 201) {
                PopUp.message = 'Data successfully loaded!';
                PopUp.show();
            } else {
                PopUp.message = e.target.statusText;
                PopUp.show();
            }

        });

        // Where we are going we don't need query parameters.
        xhr.open('PUT', `${location.protocol}//${location.host}/wp-json/tb/v1/reservations/{reservationID}`);
        
         
        xhr.onload = function () {
            // return response type as text 
            var reservation = JSON.parse(xhr.responseText);
            if(xhr.status == "201") {
                console.log(reservation);
            } else {
                console.error("Cannot load reservations");
            }
        }

        // xhr.send(reservation);
        // Ensure the restaurant nonce is set.
        if (!tbReservationNonce) {
            throw new Error('Missing reservation nonce.');
        }
        xhr.setRequestHeader('X-WP-Nonce', tbReservationNonce);


        // We have lift off!
        xhr.send(ModifyReservation.forms);
        
    }

    

}

console.log('Hello world.');

export default ModifyReservation;