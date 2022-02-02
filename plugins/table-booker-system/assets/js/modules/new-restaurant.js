import PopUp from './message-popup.js';

const NewRestaurant = {
    form: undefined,

    init: function() {
        NewRestaurant.form = document.getElementById('tb-form-new-restaurant');
        NewRestaurant.form.addEventListener('submit', NewRestaurant.submit);
        PopUp.init();
    },

    submit: function(e) {
        // Prevents page reload.
        e.preventDefault();
                    
        const xhr = new XMLHttpRequest();
        
        // Listens for a response.
        xhr.addEventListener('load', (e) => {
            // Success!
            if (e.target.status === 201) {
                PopUp.message = 'Restaurant successfully created!';
                PopUp.show();
                NewRestaurant.form.reset();
            } else {
                PopUp.message = e.target.statusText;
                PopUp.show();
            }
        });

        // Where we are going we don't need query parameters.
        xhr.open('POST', `${location.protocol}//${location.host}/wp-json/tb/v1/restaurant`);

        // Ensure the restaurant nonce is set.
        if (!tbRestaurantNonce) {
            throw new Error('Missing restaurant nonce.');
        }
        xhr.setRequestHeader('X-WP-Nonce', tbRestaurantNonce);

        // We have lift off!
        xhr.send(new FormData(NewRestaurant.form));
    }
}

export default NewRestaurant;