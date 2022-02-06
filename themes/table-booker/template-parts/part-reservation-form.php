<form  class="reservation-form" action="">
    <h2 class="title">Create Reservation</h2>

    <label class="label-general" for="reservation-name">
        <label for="reservation-name">Reservation Name</label>
        <input type="text" name="reservation-name" id="reservation-name" />
    </label>

    <label class="label-general" for="reservation-date">
        Reservation Date
        <input type="date" name="reservation-date" id="reservation-date" />
    </label>

    <fieldset>
        <legend>Time</legend>
        <input id="time-1" type="radio" name="reservation-time" checked />
        <label for="time-1">
            6:30 PM
        </label>
        <input id="time-2" type="radio" name="reservation-time" />
        <label for="time-2">
            7:00 PM
        </label>
        <input id="time-3" type="radio" name="reservation-time" />
        <label for="time-3">
            7:30 PM
        </label>
        <input id="time-4" type="radio" name="reservation-time" />
        <label for="time-4">
            8:00 PM
        </label>
        <input id="time-5" type="radio" name="reservation-time" />
        <label for="time-5">
            8:30 PM
        </label>
        <input id="time-6" type="radio" name="reservation-time" />
        <label for="time-6">
            9:00 PM
        </label>
        <input id="time-7" type="radio" name="reservation-time" />
        <label for="time-7">
            9:30 PM
        </label>
    </fieldset>

    <label class="label-general" for="party-size">
        Party Size
        <input type="number" name="party-size" id="party-size" />
    </label>

    <label for="">
        <input type="checkbox" name="" id="">
        Agree to Allow Restaurant to Contact You.
    </label>
    <button type="submit">Create Reservation</button>

    <!-- Popup Confirmation Message -->
    <div class="confirmation-message" hidden>
        <p>Reservation Confirmed!</p>
    </div>
</form>