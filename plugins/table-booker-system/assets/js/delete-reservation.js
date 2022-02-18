const DeleteReservation = {
    init: function() {
        document.querySelectorAll('.js-res-delete').forEach(btn => {
        btn.addEventListener('click', DeleteReservation.submit);
        });
    },

    submit: function(e) {
        const xhr = new XMLHttpRequest();

        const button = e.target;
        
        // Listens for a response.
        xhr.addEventListener('load', (e) => {
            // Success!
            if (e.target.status === 200) {
                button.closest('li').remove();
                console.log('success');
            } else {
                let temp = button.innerText;
                setTimeout(() => button.innerText = 'Error', 5000);
                button.innerText = temp;
            }
        });

        const resID = e.target.getAttribute('data-res-id');

        // Where we are going we don't need query parameters.
        xhr.open('DELETE', `${location.protocol}//${location.host}/wp-json/tb/v1/reservations/${resID}`);

        // Ensure the restaurant nonce is set.
        if (!tbReservationDeleteNonce) {
            throw new Error('Missing restaurant nonce.');
        }
        xhr.setRequestHeader('X-WP-Nonce', tbReservationDeleteNonce);

        // We have lift off!
        xhr.send();
    }
}

DeleteReservation.init();