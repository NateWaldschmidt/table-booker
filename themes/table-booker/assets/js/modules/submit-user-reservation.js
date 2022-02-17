/**
 * The object for managing the submission of the
 * form to modify a reservation record for a user.
 * 
 * @author Nathaniel Waldschmidt <Nathaniel.Waldsch@gmail.com>
 */
const SubmitUserReservation = {
    /** The form that will be used to submit the data. */
    form: undefined,

    /**
     * Initalizes the form property and adds the
     * event listener to the form for submission.
     */
    init: function() {
        // Sets the form.
        SubmitUserReservation.form = document.getElementById('tb-book-reservation');

        // Adds the event listener for when the form is submitted.
        SubmitUserReservation.form.addEventListener('submit', (e) => {
            SubmitUserReservation.submit(e);
        });
    },

    /**
     * Handles the submission of the form and sends 
     * a put request to the server to update the 
     * reservation record.
     * 
     * @param {Event} e 
     */
    submit: function(e) {
        e.preventDefault();

        const xhr = new XMLHttpRequest();

        // Listens for a response.
        xhr.addEventListener('load', (e) => {
            if (e.target.status === 204) {
                SubmitUserReservation.form.reset();
            } else {
                console.error(e.target.responseText);
            }
        });

        const resID = document.getElementById('reservation-id').value;

        // Where we are going we don't need query parameters.
        xhr.open('POST', `${location.protocol}//${location.host}/wp-json/tb/v1/reservations/${resID}`);

        // Ensure the restaurant nonce is set.
        if (!tbUserReservationNonce) {
            throw new Error('Missing reservation nonce.');
        }
        xhr.setRequestHeader('X-WP-Nonce', tbUserReservationNonce);

        // We have lift off!
        xhr.send(new FormData(SubmitUserReservation.form));
    }
};

export default SubmitUserReservation;