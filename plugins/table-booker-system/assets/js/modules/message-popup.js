const PopUp = {
    popup: undefined,
    message: undefined,

    init: function() {
        this.popup = document.querySelector('.popup-message');
        this.popup.hidden = true;
    },

    show: function() {
        this.popup.querySelector('p').innerText = this.message;
        this.popup.hidden = false;

        // Shows message for 5 seconds.
        setTimeout(() => {
            // Runs the hide animation.
            window.requestAnimationFrame(() => {
                this.popup.style.animation = undefined;
                window.requestAnimationFrame(() => {
                    this.popup.style.animation = '0.3s ease 0s 1 reverse none running slide-in';
                });
            });

            // Hides from DOM and reverts styles back.
            setTimeout(() => {
                this.popup.hidden = true;
                this.popup.style.animation = '0.3s ease 0s 1 forward none running slide-in';
            }, 300);
        }, 5000);
    }
};

export default PopUp;