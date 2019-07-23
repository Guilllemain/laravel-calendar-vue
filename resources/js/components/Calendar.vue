<template>
  <div class='demo-app'>
    <modal-component v-show="showModal" @hideModal="showModal = false" name="add-event">
      <label for="parking-name">Sélectionner votre place de parking</label>
      <select ref="parking" name="parking-name">
        <option value="1">Parking 1</option>
        <option value="2">Parking 2</option>
      </select>
      <button @click="addEvent">Valider</button>
    </modal-component>
    <FullCalendar
      class='demo-app-calendar'
      ref="fullCalendar"
      defaultView="dayGridMonth"
      :header="{
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      }"
      :plugins="calendarPlugins"
      :weekends="calendarWeekends"
      :events="calendarEvents"
      @dateClick="handleDateClick"
      />
  </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
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
      date: '',
      calendarPlugins: [
        dayGridPlugin,
        timeGridPlugin,
        interactionPlugin
      ],
      calendarWeekends: true,
      calendarEvents: [
        { title: `${this.user.firstname} ${this.user.lastname}` , start: new Date(), allDay: true }
      ]
    }
  },
  methods: {
    handleDateClick(arg) {
      this.showModal = true;
      this.date = arg.date
      console.log(arg)
    },
    addEvent() {
      const parkingValue = this.$refs.parking.value
      this.calendarEvents.push({ 
          title: `${this.user.firstname} ${this.user.lastname}`,
          start: this.date,
          allDay: true,
          color: parkingValue === '1' ? 'blue' : 'red'
        })
    }
  }
}
</script>

<style lang='scss'>
// paths prefixed with ~ signify node_modules
@import '~@fullcalendar/core/main.css';
@import '~@fullcalendar/daygrid/main.css';
@import '~@fullcalendar/timegrid/main.css';
.demo-app {
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
  font-size: 14px;
}
.demo-app-calendar {
  margin: 0 auto;
  max-width: 900px;
}
</style>