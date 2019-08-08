<template>
        <modal-component @hideModal="closeModal">
            <div class="modal__content">
                    <h3 class="font-bold">{{ reservation.user.fullname }}</h3>
                    <div class="text-gray-600 mt-2">Place {{ reservation.parking_number }} réservé le {{ formatDate(reservation.date) }}.</div>
                    <button class="btn mt-8" @click="deleteEvent">Supprimer cette réservation</button>
            </div>
        </modal-component>
</template>

<script>
import moment from 'moment'

moment.locale('fr')

export default {
    props: {
        reservation: {
            type: Object,
            required: true
        },
    },
    methods: {
        async deleteEvent() {
            try {
                await axios.delete(`/api/reservation/${this.reservation.id}?api_token=${this.reservation.user.api_token}`)
                this.$emit('updateReservations', { reservation_id: this.reservation.id})
            } catch (error) {
                console.error(error)
            }
            this.closeModal()
        },
        formatDate(date) {
            return moment(date).format('dddd D MMMM')
        },
        closeModal() {
            this.$emit('closeModal')
        }
    }
}
</script>