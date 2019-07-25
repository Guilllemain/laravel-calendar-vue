<template>
    <div class="demo-app">
        <modal-component v-show="showModal" @hideModal="closeModal" name="add-event">
            <div class="modal__content">
                <template v-if="editEvent">
                    <h3>{{ user.fullname }}</h3>
                    <div>Parking {{ selectedEvent.parking_number }} réservé le {{ formatDate(selectedEvent.date) }}.</div>
                    <button class="modal__cta" @click="deleteEvent">Supprimer cette réservation</button>
                </template>
                <template v-else>
                    <label for="parking-name">Sélectionner votre place de parking</label>
                    <select name="parking-name" v-model="parking_number">
                        <option value="1">Parking 1</option>
                        <option value="2">Parking 2</option>
                    </select>
                    <button class="modal__cta" @click="addEvent">Valider</button>
                </template>
            </div>
        </modal-component>
        <FullCalendar
            class="demo-app-calendar"
            ref="fullCalendar"
            defaultView="dayGridMonth"
            timeZone="Europe/Paris"
            locale="fr"
            :firstDay=1
            :plugins="calendarPlugins"
            :weekends="calendarWeekends"
            :events="calendarEvents"
            @dateClick="handleDateClick"
            @eventClick="handleEventClick"
        />
    </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import moment from 'moment'

moment.locale('fr')

export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    components: {
        FullCalendar
    },
    data: function() {
        return {
            showModal: false,
            editEvent: false,
            selectedEvent: '',
            date: "",
            parking_number: "1",
            isAuthorized: true,
            calendarPlugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
            calendarWeekends: true,
            calendarEvents: []
        };
    },
    async mounted() {
        try {
            const { data } = await axios.get(`/api/reservations?api_token=${this.user.api_token}`)
            data.forEach(reservation => {
                this.pushEvent(
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
        this.isUserAuthorized()
    },
    methods: {
        handleDateClick(arg) {
            if (arg.date < moment().subtract(1, 'days')) return
            if (arg.date > moment().add(7, 'days')) return alert("You can't make a reservation more than 7 days in advance")
            if (!this.isAuthorized) return alert('You already have a reservation')
            if(this.isDayFull(arg.date)) return alert('day is full')
            console.log(arg)
            this.date = arg.date;
            this.showModal = true;
        },
        handleEventClick(arg) {
            console.log(arg.event)
            if (arg.event.start < moment()) return
            this.getReservation(arg.event.id)
        },
        async addEvent() {
            const parking_number = Number(this.parking_number)
            
            try {
                const { data } = await axios.post('/api/reservation', {
                    user_id: this.user.id,
                    parking_number: parking_number,
                    date: this.date,
                    api_token: this.user.api_token
                })
                this.pushEvent(data, this.user.fullname, this.date, parking_number, this.user.id, this.user.email)
            } catch (error) {
                console.error(error)
            }
            
            this.isUserAuthorized()
            this.showModal = false
        },
        async deleteEvent() {
            const { data } = await axios.delete(`/api/reservation/${this.selectedEvent.id}?api_token=${this.user.api_token}`)
            this.calendarEvents = this.calendarEvents.filter(event => event.id !== this.selectedEvent.id)
            
            this.isUserAuthorized()
            this.closeModal()
        },
        pushEvent(id, title, start, parking_number, user_id, email) {
            this.calendarEvents.push({
                id,
                title,
                start,
                allDay: true,
                color: parking_number === 1 ? "blue" : "red",
                parking_number,
                user_id,
                email
            });
        },
        async getReservation(id) {
            try {
                const { data } = await axios.get(`/api/reservation/${id}?api_token=${this.user.api_token}`)
                if (data.user_id !== this.user.id) return
                this.selectedEvent = data
                this.editEvent = true
                this.showModal = true
            } catch (error) {
                console.error(error)
            }
        },
        async isUserAuthorized() {
            try {
                const { data } = await axios.get(`/api/reservation/is-authorized/${this.user.id}?api_token=${this.user.api_token}`)
                if (!data) {
                    return this.isAuthorized = false
                }
                return this.isAuthorized = true
            } catch (error) {
                console.error(error)
            }
        },
        formatDate(date) {
            return moment(date).format('dddd D MMMM')
        },
        isDayFull(date) {
            if (this.calendarEvents
                            .filter(event => moment(event.start).format('YYYY-MM-D') === moment(date).format('YYYY-MM-D')).length >= 2) {
                                return true
                            }
            return false
        },
        closeModal() {
            this.editEvent = false
            this.selectedEvent = ''
            this.showModal = false
        }
    }
};
</script>

<style lang='scss'>
// paths prefixed with ~ signify node_modules
@import "~@fullcalendar/core/main.css";
@import "~@fullcalendar/daygrid/main.css";
@import "~@fullcalendar/timegrid/main.css";
.demo-app {
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
}
.demo-app-calendar {
    margin: 0 auto;
    max-width: 900px;
}

.modal__content {
    background-color: white;
    padding: 3rem;
    display: flex;
    flex-direction: column;
}
.modal__cta {
    margin-top: 2rem;
    padding: .5rem;
    background-color: rgb(88, 170, 233);
    color: white;
    text-transform: uppercase;
    border-radius: .2rem;
}

// overwrite default style
.fc-content {
    text-align: center;
}
.fc-day-grid-event {
    padding: .5rem;
}
</style>