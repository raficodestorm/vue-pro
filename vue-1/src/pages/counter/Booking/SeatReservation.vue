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
          <!-- Front -->
          <div class="bus-front">
            <div class="door">Door</div>
            <div class="driver" @click="playHorn" style="cursor:pointer;">
              <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px">
                <path
                  d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
              </svg>
            </div>
          </div>

          <!-- Seats -->
          <div class="seats">
            <div
              v-for="(row, rowIndex) in seatRows"
              :key="rowIndex"
              class="seat-row"
            >
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
              <p><strong>Bus type:</strong> {{ schedule.bus_type }}</p>
              <p><strong>Coach No:</strong> {{ schedule.coach_no }}</p>
              <p><strong>Route:</strong> {{ schedule.start_location }} → {{ schedule.end_location }}</p>
              <p><strong>Fare per Seat:</strong> ৳ {{ Number(schedule.price).toFixed(2) }}</p>
              <p><strong>Departure:</strong> {{ departureTime }}</p>

              <p class="inline"><strong>Bus type:</strong></p>
            <input class="hidein" type="text" v-model="bus_type" value="{{ $schedule->bus_type }}" readonly><br>
            <p class="inline"><strong>Coach No:</strong></p>
            <input class="hidein" type="text" v-model="coach_no" value="{{ $schedule->coach_no }}" readonly><br>
            <p class="inline"><strong>Route:</strong></p>
            <input class="hidein" type="text" v-model="route"
              value="{{ $schedule->start_location }} to {{ $schedule->end_location }}" readonly> <br>

            <p class="inline"><strong>Fare per Seat: ৳</strong></p>
            <input class="hidein" type="text" v-model="seat_price" value="{{ number_format($schedule->price, 2) }}"
              readonly> <br>
            <p class="inline"><strong>Departure:</strong></p>
            <input class="hidein" type="text" id="departure" v-model="departure"
              value="{{ date('H:i', strtotime($schedule->set_time)) }}" readonly><br>

            <p class="inline"><strong>Boarding point:</strong></p>
            <select id="boarding" v-model="boarding" required>
              <option value="">Select boarding point</option>
              @foreach($boardingCounters as $boardcounter)
              <option value="{{ $boardcounter->name }}" data-distance="{{ $boardcounter->distance }}">
                {{ $boardcounter->name }}
              </option>
              @endforeach
            </select>

            <p class="inline"><strong>Dropping point:</strong></p>
            <select v-model="dropping" required>
              <option value="">Select dropping point</option>
              
              <option value="{{ $dropcounter->name }}">
                {{ $dropcounter->name }}
              </option>
              
            </div>

            <h4>SEAT INFORMATION</h4>

            <input type="text" v-model="selected_seats" :value="selectedSeats.join(', ')" readonly />
            <input type="text" v-model="total" :value="totalAmount" readonly />

            <input v-model="name" placeholder="Your Name*" required />
            <input v-model="mobile" placeholder="Mobile Number*" required />

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
import { ref, reactive, computed, onMounted } from 'vue'
import api from '../../../api/axios.js'

/* ---------------- STATE ---------------- */
const schedule = reactive({})
const seatRows = ref([])
const selectedSeats = ref([])
const seatLayout = ref('')
const seatCapacity = ref(0)
const bookedSeats = ref([])

const form = ref({
    schedule_id: "",
    bus_type: "",
    coach_no: "",
    route: "",
    seat_price: "",
    departure: "",
    boarding: "",
    dropping: "",
    selected_seats: "",
    total: "",
    name: "",
    mobile: "",
  });
const departureTime = ref('')
const scheduleLoaded = ref(false)

/* ---------------- COMPUTED ---------------- */
const totalAmount = computed(() =>
  selectedSeats.value.length * (schedule.price || 0)
)

/* ---------------- FETCH ---------------- */
async function fetchSchedule(schedule_id) {
  try {
    const res = await api.get(`counter/schedules/${schedule_id}`)
    const data = res.data.data || res.data

    if (!data?.schedule) {
      console.error('Invalid API response', data)
      return
    }

    Object.assign(schedule, {
      ...data.schedule,
      price: Number(data.schedule.price) || 0
    })

    seatLayout.value = data.seatLayout
    seatCapacity.value = data.seatCapacity
    bookedSeats.value = data.bookedSeats || []

    departureTime.value = schedule.set_time
    generateSeats()
  } catch (error) {
    console.error('Schedule fetch failed:', error)
  } finally {
    scheduleLoaded.value = true
  }
}

/* ---------------- SEAT GENERATION ---------------- */
function generateSeats() {
  if (!seatLayout.value || !seatCapacity.value) return

  const [leftCount, rightCount] = seatLayout.value.split(':').map(Number)
  const seatsPerRow = leftCount + rightCount
  const totalRows = Math.ceil(seatCapacity.value / seatsPerRow)

  const rows = []
  let seatNo = 1
  const startLetter = 'A'.charCodeAt(0)

  for (let r = 0; r < totalRows; r++) {
    const rowLetter = String.fromCharCode(startLetter + r)
    const left = []
    const right = []

    for (let i = 0; i < leftCount && seatNo <= seatCapacity.value; i++) {
      left.push({
        id: `${rowLetter}${seatNo}`,
        booked: bookedSeats.value.includes(`${rowLetter}${seatNo}`)
      })
      seatNo++
    }

    for (let i = 0; i < rightCount && seatNo <= seatCapacity.value; i++) {
      right.push({
        id: `${rowLetter}${seatNo}`,
        booked: bookedSeats.value.includes(`${rowLetter}${seatNo}`)
      })
      seatNo++
    }

    rows.push({ left, right })
  }

  seatRows.value = rows
}

/* ---------------- UI LOGIC ---------------- */
function seatClass(seat) {
  if (seat.booked) return 'soldM'
  if (selectedSeats.value.includes(seat.id)) return 'selected'
  return 'available'
}

function toggleSeat(seat) {
  if (seat.booked) return
  const index = selectedSeats.value.indexOf(seat.id)
  index > -1
    ? selectedSeats.value.splice(index, 1)
    : selectedSeats.value.push(seat.id)
}

function playHorn() {
  new Audio('/sound/horn.m4a').play()
}

/* ---------------- SUBMIT ---------------- */

const submitReservation = async () => {
    loading.value = true;
    errors.value = {};
  
    try {
      await api.post("/controller/seatreservation", form.value);
      router.push({ name: "payment" });
    } catch (e) {
      if (e.response?.status === 422) {
        errors.value = e.response.data.errors;
      }
    } finally {
      loading.value = false;
    }
  };

/* ---------------- MOUNT ---------------- */
onMounted(() => {
  const id = new URLSearchParams(window.location.search).get('schedule_id')
  if (id) fetchSchedule(id)
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
  }

  .hidein {
    border: none !important;
    display: inline-block !important;
    width: 50% !important;
    margin: 0 !important;
    padding: 0 !important;
  }
  </style>
  