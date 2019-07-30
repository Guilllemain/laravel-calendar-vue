<template>
    <div class="">
        <modal-component v-show="showModal" @hideModal="closeModal" name="add-event">
            <div class="modal__content">
                <template v-if="isEditing">
                    <h3 class="font-bold">{{ user.fullname }}</h3>
                    <div class="text-gray-600 mt-2">Place {{ selectedEvent.parking_number }} réservé le {{ formatDate(selectedEvent.date) }}.</div>
                    <button class="btn mt-8" @click="deleteEvent">Supprimer cette réservation</button>
                </template>
                <template v-if="isAdding">
                    <label class="block text-gray-600 mb-2 pr-4" for="parking-name">Sélectionner votre place de parking</label>
                    <form @submit.prevent="addEvent" method="POST">
                        <div class="relative">
                            <select name="parking-name" v-model="parking_number" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="parking_number">
                                <option disabled selected>Veuillez choisir une place</option>
                                <option v-for="parking in parkings" :value="parking.number">Place {{ parking.number }}</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        <button type="submit" class="btn mt-8">Valider</button>
                    </form>
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
        <div class="flex justify-center m-4">
            <div class="flex items-center mr-8" v-for="parking in parkings">
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
            parkings: [
                {number: 1, color: '#dd6b20'},
                {number: 2, color: '#319795'}
            ],
            showModal: false,
            isEditing: false,
            isAdding: false,
            selectedEvent: '',
            date: "",
            parking_number: 1,
            isAuthorized: true,
            calendarPlugins: [dayGridPlugin, interactionPlugin],
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
            if (arg.date < moment().startOf('day')) return flash('Vous ne pouvez pas réserver une date passée', 'danger')
            if (moment(arg.date).startOf('day') > moment().add(7, 'days')) return flash("Vous ne pouvez pas faire une réservation plus de 7 jours en avance", 'danger')
            if (!this.isAuthorized) return flash('Vous avez déjà une réservation en cours', 'danger')
            if(this.isDayFull(arg.date)) return flash("Il n'y a plus de places disponible ce jour", 'danger')
            this.parkingsAvailable(arg.dateStr)
            this.date = arg.date
            this.isEditing = false
            this.isAdding = true
            this.showModal = true
        },
        handleEventClick(arg) {
            const clickedEvent = this.calendarEvents.find(event => event.id === Number(arg.event.id))
            if (clickedEvent.user_id === this.user.id && arg.event.start < moment().startOf('day')) return flash('Vous ne pouvez pas modifier une réservation passée', 'danger')
            if (clickedEvent.user_id !== this.user.id) return flash('Vous pouvez modifier uniquement vos réservations', 'danger')
            this.isAdding = false
            if (moment(arg.event.start).endOf('day') < moment()) return
            this.getReservation(arg.event.id)
        },
        async addEvent(event) {
            console.log(event)
            const parking_number = this.parking_number
            
            try {
                const { data } = await axios.post('/api/reservation', {
                    parking_number: parking_number,
                    date: moment(this.date).format('YYYY-MM-DD'),
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
            try {
                await axios.delete(`/api/reservation/${this.selectedEvent.id}?api_token=${this.user.api_token}`)
            } catch (error) {
                console.error(error)
            }
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
                color: parking_number === 1 ? "#dd6b20" : "#319795",
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
                this.isEditing = true
                this.showModal = true
            } catch (error) {
                console.error(error)
            }
        },
        parkingsAvailable(date) {
            const dayClicked = this.calendarEvents.filter(event => event.start === date)
            if(dayClicked.length > 0) {
                return this.parkings = this.parkings.filter(park => {
                    const numberAvailable = dayClicked.find(day => day.parking_number !== park)
                    if(numberAvailable) return numberAvailable.parking_number
                })
            }
            return this.parkings = [1, 2]
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
            this.showModal = false
            this.editEvent = false
            this.selectedEvent = ''
        }
    }
}
</script>

<style lang='scss'>
// paths prefixed with ~ signify node_modules
@import "~@fullcalendar/core/main.css";
@import "~@fullcalendar/daygrid/main.css";
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

// overwrite default style
.fc-content {
    text-align: center;
}
.fc-day-grid-event {
    padding: .5rem;
    cursor: pointer;
    transition: all .2s ease-in-out;

    &:hover {
        transform: scale(0.97);
    }
}
.fc-button-primary {
    background-color: #44337a;
    border-color: #44337a;

    &:disabled {
        background-color: #44337a;
        border-color: #44337a;
    }

    &:hover {
        background-color: #6b46c1;
        border-color: #6b46c1;
    }

    &:not(:disabled):active {
        background-color: #553c9a;
        border-color: #553c9a;
    }

    &:focus {
        box-shadow: none;
    }

    &:not(:disabled):active:focus {
        outline: none;
        box-shadow: none;
    }
}
</style>