/**
 * Handles the submission of the create new
 * reservation form.
 * 
 * @author Nathaniel Waldschmidt <Nathaniel.Waldsch@gmail.com>
 * 
 */
const SubmitNewReservation  = {
    form: undefined,

    /**
     * Finds the form and then adds the event listener
     * to it.
     */
    init: () => {
        SubmitNewReservation.form = document.getElementById('tb-new-reservation');

        SubmitNewReservation.form.addEventListener('submit', SubmitNewReservation.submit);
    },

    /**
     * Handles the submission and sends the request
     * to the REST API for creation of a new reservation.
     * 
     * @param {SubmitEvent} e 
     */
    submit: (e) => {
        e.preventDefault();

        const xhr = new XMLHttpRequest();

        // Listens for a response.
        xhr.addEventListener('load', (e) => {
            if (e.target.status === 201) {
                SubmitNewReservation.form.reset();
                console.log('Success');
            } else {
                console.error(e.target.responseText);
            }
        });

        // Where we are going we don't need query parameters.
        xhr.open('POST', `${location.protocol}//${location.host}/wp-json/tb/v1/reservations`);

        // Ensure the restaurant nonce is set.
        if (!tbNewReservationNonce) {
            throw new Error('Missing reservation nonce.');
        }
        xhr.setRequestHeader('X-WP-Nonce', tbNewReservationNonce);

        // We have lift off!
        xhr.send(new FormData(SubmitNewReservation.form));
    }
}

export default SubmitNewReservation;