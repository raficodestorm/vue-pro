<template>
  <div class="seat-reservation container">
    <div class="row">

      <!-- LEFT SIDE -->
      <div class="col-md-6">
        <div class="legend">
          <span class="seat soldM">Booked</span>
          <span class="seat blocked">Blocked</span>
          <span class="seat available">Available</span>
          <span class="seat selected">Selected</span>
        </div>

        <div class="bus-container" v-if="scheduleLoaded">
          <div class="bus-front">
            <div class="door">Door</div>
            <div class="driver" @click="playHorn" style="cursor:pointer;">
              <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px">
              <path
                d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-40-84v-120q-60-12-102-54t-54-102H164q12 109 89.5 185T440-164Zm80 0q109-12 186.5-89.5T796-440H676q-12 60-54 102t-102 54v120ZM164-520h116l120-120h160l120 120h116q-15-121-105-200.5T480-800q-121 0-211 79.5T164-520Z" />
            </svg>
            </div>
          </div>

          <div class="seats">
            <div v-for="(row, rowIndex) in seatRows" :key="rowIndex" class="seat-row">
              <div
                v-for="seat in row.left"
                :key="seat.id"
                class="seat"
                :class="seatClass(seat)"
                @click="toggleSeat(seat)"
              >
                {{ seat.id }}
              </div>

              <div class="aisle"></div>

              <div
                v-for="seat in row.right"
                :key="seat.id"
                class="seat"
                :class="seatClass(seat)"
                @click="toggleSeat(seat)"
              >
                {{ seat.id }}
              </div>
            </div>
          </div>

          <div class="summary">
            <h4>Selected Seats</h4>
            <p>{{ selectedSeats.length ? selectedSeats.join(', ') : 'None' }}</p>
          </div>
        </div>

        <div v-else class="text-center mt-5">
          Loading seat info...
        </div>
      </div>

      <!-- RIGHT SIDE -->
      <div class="col-md-6" v-if="scheduleLoaded">
        <div class="reservation-form">
          <form @submit.prevent="submitReservation">

            <h4 class="text-center mb-3" style="color:#780116;">BUS INFORMATION</h4>

            <div class="ticket-card p-3 mb-3">
              <p class="inline"><strong>Bus type:</strong></p>
              <input class="hidein" type="text" v-model="form.bus_type" readonly><br>

              <p class="inline"><strong>Coach No:</strong></p>
              <input class="hidein" type="text" v-model="form.coach_no" readonly><br>

              <p class="inline"><strong>Route:</strong></p>
              <input class="hidein" type="text" v-model="form.route" readonly><br>

              <p class="inline"><strong>Fare per Seat: ৳</strong></p>
              <input class="hidein" type="text" v-model="form.seat_price" readonly><br>

              <p class="inline"><strong>Departure:</strong></p>
              <input class="hidein" type="text" :value="formattedDeparture" readonly><br>

              <p class="inline"><strong>Boarding point:</strong></p>
              <select v-model="form.boarding" required>
                <option value="">Select boarding point</option>
                <option>Counter A</option>
                <option>Counter B</option>
              </select>

              <p class="inline"><strong>Dropping point:</strong></p>
              <select v-model="form.dropping" required>
                <option value="">Select dropping point</option>
                <option>Stop X</option>
                <option>Stop Y</option>
              </select>
            </div>

            <h4>SEAT INFORMATION</h4>

            <input type="text" v-model="form.selected_seats" readonly />
            <input type="text" v-model="form.total" readonly />

            <input v-model="form.name" placeholder="Your Name*" required />
            <input v-model="form.mobile" placeholder="Mobile Number*" required />

            <button type="submit" class="submit-btn" @click="playHorn">
              SUBMIT
            </button>

          </form>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../../api/axios.js'

const route = useRoute()
const router = useRouter()

const schedule = ref({})
const seatRows = ref([])
const selectedSeats = ref([])
const bookedSeats = ref([])
const scheduleLoaded = ref(false)
const loading = ref(false)

const form = reactive({
  schedule_id: "",
  bus_type: "",
  coach_no: "",
  route: "",
  seat_price: 0,
  departure: "",
  boarding: "",
  dropping: "",
  selected_seats: "",
  total: 0,
  name: "",
  mobile: "",
})

const totalAmount = computed(() => {
  return selectedSeats.value.length * Number(form.seat_price)
})

watch(selectedSeats, (newSeats) => {
  form.selected_seats = newSeats.join(', ')
  form.total = totalAmount.value
}, { deep: true })


async function fetchSchedule(schedule_id) {
  try {
    const res = await api.get(`counter/schedules/${schedule_id}`)
    const data = res.data.data

    schedule.value = data.schedule
    bookedSeats.value = data.bookedSeats ?? []

    form.schedule_id = schedule.value.id
    form.bus_type = schedule.value.bus_type
    form.coach_no = schedule.value.coach_no
    form.route = `${schedule.value.start_location} → ${schedule.value.end_location}`
    form.seat_price = Number(schedule.value.price)
    form.departure = schedule.value.set_time

    generateSeats(
      data.seatLayout || '2:2',
      data.seatCapacity || 40
    )

    scheduleLoaded.value = true
  } catch (e) {
    console.error('Failed to load schedule', e)
  }
}

function generateSeats(layout, capacity) {
  const [L, R] = layout.split(':').map(Number) // e.g., "2:2" → L=2, R=2
  const totalPerRow = L + R
  const rows = Math.ceil(capacity / totalPerRow)
  seatRows.value = []

  for (let r = 0; r < rows; r++) {
    const rowLetter = String.fromCharCode(65 + r) // A, B, C...
    let seatNo = 1 // reset seat number for each row

    seatRows.value.push({
      left: Array.from({ length: L }, () => makeSeat(rowLetter, seatNo++)),
      right: Array.from({ length: R }, () => makeSeat(rowLetter, seatNo++)),
    })
  }
}

function makeSeat(row, no) {
  const id = `${row}${no}`
  return {
    id,
    booked: bookedSeats.value.includes(id),
  }
}


function seatClass(seat) {
  if (seat.booked) return 'soldM'
  if (selectedSeats.value.includes(seat.id)) return 'selected'
  return 'available'
}

function toggleSeat(seat) {
  if (seat.booked) return

  const index = selectedSeats.value.indexOf(seat.id)
  if (index > -1) {
    selectedSeats.value.splice(index, 1)
  } else {
    selectedSeats.value.push(seat.id)
  }
}

function playHorn() {
  new Audio('/sound/horn.m4a').play()
}

async function submitReservation() {
  if (!selectedSeats.value.length) {
    alert('Please select at least one seat')
    return
  }

  loading.value = true
  try {
    const res = await api.post('/counter/seatreservation', form)

    const reservationId = res.data.reservation_id

    router.push(`/counter/counterpayment/${res.data.reservation_id}`)
  } catch (e) {
    console.error(e)
    alert('Reservation failed')
  } finally {
    loading.value = false
  }
}


const formattedDeparture = computed(() => {
  if (!form.departure) return ''
  const time = new Date(`1970-01-01T${form.departure}`)
  return time.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  })
})

onMounted(() => {
  const id = route.query.schedule_id
  if (id) {
    fetchSchedule(id)
  } else {
    console.error('schedule_id missing')
  }
})
</script>


  
  
  
  <style scoped>
  .seat-reservation {
    margin-top: 35px;
    margin-bottom: 30px;
    border-radius: 1rem;
    box-shadow: 0 0 20px rgba(128, 128, 128, 0.854) !important;
  }

  .bus-container {
    width: 350px;
    margin: auto;
    margin-bottom: 20px;
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 10px;
    background: var(--bg-color);
  }

  .legend {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin: .5rem 0 1rem 0;
  }

  .legend .seat {
    text-align: center;
    line-height: 1rem;
    border-radius: 4px;
    font-size: 12px;
    cursor: default;
    height: 45px;
    width: 55px;
  }

  .seat.blocked {
    background: #333;
    color: white;
    border: .5px solid var(--main-color);
  }

  .seat.available {
    background: #fffffc;
    color: #333;
    border: .5px solid var(--main-color);
  }

  .seat.selected {
    background: #4CAF50;
    color: white;
    border: .5px solid var(--main-color);
  }

  /* green */
  .seat.soldM {
    background: #ff0000;
    color: white;
    border: .5px solid var(--main-color);
  }

  /* red */
  .seat.soldF {
    background: #ff2ecc;
    color: white;
    border: .5px solid var(--main-color);
  }

  /* pink */

  .bus-front {
    display: flex;
    justify-content: space-between;
    margin: 0 20px 20px 20px;
  }

  .driver {
    font-weight: bold;
    width: 60px;
    text-align: center;
  }

  .driver svg {
    font-size: 40px;
    animation: steering-spin 2s linear infinite, fill-change 3s ease-in-out infinite;
  }

  /* Spin animation */
  @keyframes steering-spin {
    from {
      transform: rotate(0deg);
    }

    to {
      transform: rotate(360deg);
    }
  }

  /* Animate fill color */
  @keyframes fill-change {
    0% {
      fill: red;
    }

    25% {
      fill: blue;
    }

    50% {
      fill: #780116;
    }

    75% {
      fill: green;
    }

    100% {
      fill: red;
    }
  }

  .door {
    color: gray;
    font-weight: bold;
    padding: 10px;
    border-radius: 6px;
    width: 60px;
    text-align: center;
  }

  .deck {
    border: 2px solid #ccc;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
  }

  .deck-title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 10px;
    font-size: 18px;
  }

  .seat {
    width: 35px;
    height: 35px;
    background: #eee;
    color: var(--second-color);
    border: 1.5px solid var(--main-color);
    margin: 3px;
    font-size: 12px;
    border-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: 0.2s;
    font-weight: 500;
  }

  .seat.selected {
    background: #4caf50;
    color: white;
  }

  .double-deck-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  .lower-deck {
    background: #f8f9fa;
    flex: 1;
    min-width: 300px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }

  .upper-deck {
    background: #f8f9fa;
    flex: 1;
    min-width: 300px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
  }

  .deck-divider {
    border: none;
    border-top: 2px dashed #aaa;
    margin: 20px 0;
  }

  @media (max-width: 768px) {
    .double-deck-container {
      flex-direction: column;
    }
  }

  .seats {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .seat-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  .seat:hover {
    transform: scale(1.05);
  }

  .aisle {
    width: 40px;
    /* space for walking */
  }

  .summary {
    margin-top: 20px;
    padding: 10px;
    border-top: 1px solid #ccc;
    text-align: center;
  }

  .reservation-form {
    margin-top: 4.4rem;
    margin-right: 4rem;
    margin-bottom: 1rem;
  }

  .reservation-form select,
  .reservation-form input {
    display: block;
    width: 100%;
    margin-bottom: 10px;
    padding: 8px;
    color: black;
    border-radius: 5px;
    background-color: var(--bg-color);
    border: .5px solid var(--light-hover);
  }

  /* .reservation-form select, */
  input:focus {
    border: none;
  }

  .submit-btn {
    width: 100%;
    background: linear-gradient(90deg, #ff0000, #780116);
    color: white;
    padding: 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    animation: wiggle 1s ease-in-out infinite;
  }

  /* Wiggle animation */
  @keyframes wiggle {
    0% {
      transform: translateX(-3px);
      background: linear-gradient(90deg, #ff0000, #780116);
    }

    50% {
      transform: translateX(3px);
      background: linear-gradient(90deg, #780116, #ff0000);
    }

    100% {
      transform: translateX(-3px);
      background: linear-gradient(90deg, #ff0000, #780116);
    }
  }

  /* Apply on hover */
  .submit-btn:hover {
    box-shadow: 0 0 10px var(--main-color);
  }

  .inline {
    display: inline-block !important;
    margin-right: 5px;
  }

  .hidein {
    border: none !important;
    display: inline-block !important;
    width: 50% !important;
    margin: 0 !important;
    padding: 0 !important;
    color: black;
  }
  </style>
  