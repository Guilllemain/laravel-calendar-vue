<template>
    <form @submit.prevent="createReservation" method="POST">
        <div class="relative">
            <select
                name="parking-name"
                v-model="parking_number"
                autocomplete="off"
                class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                id="parking_number"
                required
            >
                <option disabled selected value>--- Veuillez choisir une place ---</option>
                <option
                    v-for="parking in parkings"
                    :value="parking.number"
                >Place {{ parking.number }}</option>
            </select>
            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
            >
                <svg
                    class="fill-current h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                    />
                </svg>
            </div>
        </div>
        <button type="submit" class="btn mt-8">Réserver</button>
    </form>
</template>

<script>
import moment from "moment";

moment.locale("fr");

export default {
    props: {
        user: {
            type: Object,
            required: true
        },
        date: {
            type: Date,
            required: true
        },
        parkings: {
            type: Array,
            required: true
        }
    },
    data: function() {
        return {
            parking_number: ""
        };
    },
    methods: {
        async createReservation(event) {
            if (!this.parking_number)
                return flash("Vous devez sélectionner un parking", "danger");
            try {
                const { data } = await axios.post("/api/reservation", {
                    parking_number: this.parking_number,
                    date: moment(this.date).format("YYYY-MM-DD"),
                    api_token: this.user.api_token
                });
                this.$emit("createReservation", {
                    id: data,
                    username: this.user.fullname,
                    date: this.date,
                    parking_number: this.parking_number
                });
            } catch (error) {
                console.error(error);
            }
            this.closeModal();
        },
        closeModal() {
            this.$emit("closeModal");
        }
    }
};
</script>