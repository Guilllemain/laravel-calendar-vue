<template>
    <div class="flex flex-col items-center" @click="addBackground">
        <modal name="add">
            <add-reservation :user="user" :date="date" :parkings="parkingsAvailable" @createReservation="createReservation"></add-reservation>
        </modal>
        <modal name="view">
            <view-reservation :reservation="selectedReservation" @updateReservations="updateReservations"></view-reservation>
        </modal>
        <FullCalendar
            class="demo-app-calendar"
            ref="fullCalendar"
            defaultView="dayGridMonth"
            timeZone="Europe/Paris"
            locale="fr"
            :header="{
              right: 'prev,next'
            }"
            :firstDay=1
            :plugins="calendarPlugins"
            :weekends="calendarWeekends"
            :events="reservations"
            @dateClick="handleDateClick"
            @eventClick="handleEventClick"
        />
        <div class="flex justify-center m-4">
            <div class="flex items-center mr-8" v-for="parking in parkings" :key="parking.number">
                <span class="h-4 w-8 block mr-2" :style="{ backgroundColor: parking.color}"></span>
                Place {{parking.number}}
            </div>
        </div>
    </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import moment from 'moment'

import ViewReservation from './ViewReservation'
import AddReservation from './AddReservation'

moment.locale('fr')

export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    components: {
        FullCalendar,
        ViewReservation,
        AddReservation
    },
    data() {
        return {
            parkings: [
                {number: 1, color: '#dd6b20'},
                {number: 2, color: '#319795'}
            ],
            parkingsAvailable: [],
            selectedReservation: '',
            date: "",
            isAuthorized: true,
            calendarPlugins: [dayGridPlugin, interactionPlugin],
            calendarWeekends: true,
            reservations: []
        };
    },
    async created() {
        try {
            const { data } = await axios.get(`/api/reservations?api_token=${this.user.api_token}`)
            data.forEach(reservation => {
                this.pushReservation(
                    reservation.id,
                    reservation.user.fullname,
                    reservation.date,
                    reservation.parking_number,
                    reservation.user_id,
                    reservation.user.email
                )
            })
        } catch (error) {
            console.error(error)
        }
    },
    watch: {
        async reservations(val) {
            this.addBackground()
            try {
                const { data } = await axios.get(`/api/reservation/is-authorized/${this.user.id}?api_token=${this.user.api_token}`)
                if (!data) {
                    return this.isAuthorized = false
                }
                return this.isAuthorized = true
            } catch (error) {
                console.error(error)
            }            
        }
    },
    methods: {
        handleDateClick(arg) {
            if (!this.isRequestValid(arg)) return
            this.getParkingsAvailable(arg.dateStr)
            this.date = arg.date
            this.$modal.show('add')
        },
        handleEventClick(arg) {
            this.selectedReservation = ''
            if (!this.canUserViewReservation(arg)) return
            this.getReservation(arg.event.id)
        },
        pushReservation(id, title, start, parking_number, user_id, email) {
            this.reservations.push({
                id,
                title,
                start,
                allDay: true,
                color: this.parkings.find(el => el.number === parking_number).color,
                parking_number,
                user_id,
                email
            });
        },
        async getReservation(id) {
            try {
                const { data } = await axios.get(`/api/reservation/${id}?api_token=${this.user.api_token}`)
                this.selectedReservation = data
                this.$modal.show('view')
            } catch (error) {
                console.error(error)
            }
        },
        getParkingsAvailable(date) {
            const dayClickedReservations = this.reservations.filter(event => event.start === date)
            if(dayClickedReservations.length > 0) {
                return this.parkingsAvailable = this.parkings.filter(park => {
                    const numberAvailable = dayClickedReservations.find(day => day.parking_number !== park.number)
                    if(numberAvailable) return park.number !== numberAvailable.parking_number
                })
            }
            return this.parkingsAvailable = this.parkings
        },
        isRequestValid(request) {
            if (request.date < moment().startOf('day')) return flash('Vous ne pouvez pas réserver une date passée', 'danger')
            if (moment(request.date).startOf('day') > moment().add(7, 'days')) return flash("Vous ne pouvez pas faire une réservation plus de 7 jours en avance", 'danger')
            if (!this.isAuthorized) return flash('Vous avez déjà une réservation en cours', 'danger')
            if(this.isDayFull(request.date)) return flash("Il n'y a plus de places disponible ce jour", 'danger')
            return true
        },
        canUserViewReservation(request) {
            const clickedEvent = this.reservations.find(event => event.id === Number(request.event.id))
            if (clickedEvent.user_id === this.user.id && request.event.start < moment().startOf('day')) return flash('Vous ne pouvez pas modifier une réservation passée', 'danger')
            if (clickedEvent.user_id !== this.user.id) return flash('Vous pouvez modifier uniquement vos réservations', 'danger')
            return true
        },
        isDayFull(date) {
            if (this.reservations
                            .filter(event => moment(event.start).format('YYYY-MM-DD') === moment(date).format('YYYY-MM-DD')).length >= 2) {
                                return true
                            }
            return false
        },
        createReservation(event) {
            this.pushReservation(event.id, event.username, event.date, event.parking_number, this.user.id, this.user.email)
        },
        updateReservations(event) {
            this.reservations = this.reservations.filter(el => el.id !== event.reservation_id)
        },
        addBackground() {
            setTimeout(() => {
                const unavailableTiles = [...document.querySelectorAll('td .fc-day')].filter(node => node.dataset.date > moment().add(7, 'days').format('YYYY-MM-DD') || node.dataset.date < moment().format('YYYY-MM-DD'))
                unavailableTiles.forEach(tile => tile.style.background = '#f7fafc')
            }, 100);
        }
    }
}
</script>

<style lang='scss'>
.demo-app-calendar {
    margin: 0 3%;
    max-width: 900px;
}
</style>