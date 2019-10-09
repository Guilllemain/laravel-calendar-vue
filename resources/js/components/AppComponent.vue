<template>
    <div class="flex flex-col items-center" @click="addBackground">
        <modal name="add" @updateBackground="addBackground">
            <add-reservation :user="user" :date="date" :parkings="parkingsAvailable" @createReservation="createReservation"></add-reservation>
        </modal>
        <modal name="view" @updateBackground="addBackground">
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
                {{ parking.number !== 3 ? `Place ${parking.number}` : `Place F. Kmit` }}
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
                {number: 1, color: '#c67bff'},
                {number: 2, color: '#319795'},
                {number: 3, color: '#ca2e4b'}
            ],
            parkingsAvailable: [],
            selectedReservation: '',
            date: "",
            calendarPlugins: [dayGridPlugin, interactionPlugin],
            calendarWeekends: false,
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
    mounted() {
        this.addBackground(700)
    },
    methods: {
        handleDateClick(arg) {
            this.getParkingsAvailable(arg.dateStr)
            if (!this.isRequestValid(arg.date)) return
            this.date = arg.date
            this.$modal.show('add')
        },
        handleEventClick(arg) {
            this.selectedReservation = ''
            if (!this.canUserViewReservation(arg.event)) return
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
            if (moment(date).startOf('day') > moment().add(1, 'days').startOf('day') && !this.user.isAdmin) {
                this.parkingsAvailable = this.parkings.filter(el => el.number !== 3)
            } else if (this.user.isAdmin) {
                this.parkingsAvailable = this.parkings.filter(el => el.number === 3)
            } else {
                this.parkingsAvailable = this.parkings
            }

            // get all reservations for the day clicked
            const dayClickedReservations = this.reservations.filter(event => event.start === date)

            if(dayClickedReservations.length > 0) {
                const parkingsAlreadyBooked = [...new Set(dayClickedReservations.map(res => res.parking_number))]
                this.parkingsAvailable = this.parkingsAvailable.filter(park => !parkingsAlreadyBooked.includes(park.number))
            }

            const firstDayofWeek = moment(date).startOf('week');
            const lastDayofWeek = moment(date).endOf('week');
            const userReservationsForTheWeek = this.reservations.filter(res => res.user_id === this.user.id && (moment(res.start) >= firstDayofWeek && moment(res.start) <= lastDayofWeek))
            if (userReservationsForTheWeek.some(res => res.parking_number === 3 && !this.user.isAdmin)) {
                this.parkingsAvailable = this.parkingsAvailable.filter(park => park.number !== 3)
            }
            if (userReservationsForTheWeek.some(res => res.parking_number !== 3) || moment(date).startOf('day') > moment().add(7, 'days')) {
                this.parkingsAvailable = this.parkingsAvailable.filter(park => park.number === 3)
            }
        },
        isRequestValid(date) {
            if (date < moment().startOf('day')) return false
            const firstDayofWeek = moment(date).startOf('week');
            const lastDayofWeek = moment(date).endOf('week');
            if(this.isDayFull(date)) return flash("Il n'y a plus de places disponible ce jour", 'danger')
            if (this.parkingsAvailable.length === 0 && this.user.isAdmin) return flash("Il n'y a plus de places disponible ce jour", 'danger')
            if (this.parkingsAvailable.length === 0) return flash('Vous avez déjà réservé cette semaine', 'danger')
            if (moment(date).startOf('day') > moment().add(7, 'days') && !this.user.isAdmin) return flash("Vous ne pouvez pas faire une réservation plus de 7 jours en avance", 'danger')
            return true
        },
        canUserViewReservation(request) {
            const clickedEvent = this.reservations.find(event => event.id === Number(request.id))
            if (clickedEvent.user_id === this.user.id && request.start < moment().startOf('day')) return flash('Vous ne pouvez pas modifier une réservation passée', 'danger')
            if (clickedEvent.user_id !== this.user.id) return flash('Vous pouvez modifier uniquement vos réservations', 'danger')
            return true
        },
        isDayFull(date) {
            const reservationsForTheSelectedDay = this.reservations.filter(event => moment(event.start).format('YYYY-MM-DD') === moment(date).format('YYYY-MM-DD'))
            if (moment(date).startOf('day') > moment().add(1, 'days').startOf('day')
                && !this.user.isAdmin
                && !reservationsForTheSelectedDay.some(res => res.parking_number === 3)) {
                return reservationsForTheSelectedDay.length === 2       
            } else if (this.user.isAdmin && moment(date).startOf('day') > moment().add(7, 'days')) {
                return reservationsForTheSelectedDay.length === 1
            } else {
                return reservationsForTheSelectedDay.length === 3
            }
        },
        createReservation(event) {
            this.pushReservation(event.id, event.username, event.date, event.parking_number, this.user.id, this.user.email)
        },
        updateReservations(event) {
            this.reservations = this.reservations.filter(el => el.id !== event.reservation_id)
        },
        addBackground(delay = 100) {
            setTimeout(() => {
                const unavailableTiles = [...document.querySelectorAll('td .fc-day')].filter(node => node.dataset.date > moment().add(7, 'days').format('YYYY-MM-DD') || node.dataset.date < moment().format('YYYY-MM-DD'))
                unavailableTiles.forEach(tile => tile.style.background = '#ededed')
            }, delay);
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