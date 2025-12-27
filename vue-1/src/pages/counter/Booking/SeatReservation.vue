<template>
    <div class="seat-reservation container">
      <div class="row">
        <!-- LEFT SIDE - Seat Layout -->
        <div class="col-md-6">
          <div class="legend">
            <span class="seat soldM">Booked</span>
            <span class="seat blocked">Blocked</span>
            <span class="seat available">Available</span>
            <span class="seat selected">Selected</span>
          </div>
  
          <div class="bus-container" v-if="scheduleLoaded">
            <!-- Top row: Door + Driver -->
            <div class="bus-front">
              <div class="door">Door</div>
              <div class="driver" @click="playHorn" style="cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px">
                  <path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-40-84v-120q-60-12-102-54t-54-102H164q12 109 89.5 185T440-164Zm80 0q109-12 186.5-89.5T796-440H676q-12 60-54 102t-102 54v120ZM164-520h116l120-120h160l120 120h116q-15-121-105-200.5T480-800q-121 0-211 79.5T164-520Z"/>
                </svg>
              </div>
            </div>
  
            <!-- Seat Layout -->
            <div class="seats">
              <div v-for="(row, rowIndex) in seatRows" :key="rowIndex" class="seat-row">
                <div v-for="seat in row.left" :key="seat.id" class="seat"
                     :class="seatClass(seat)"
                     @click="toggleSeat(seat)">
                  {{ seat.id }}
                </div>
                <div class="aisle"></div>
                <div v-for="seat in row.right" :key="seat.id" class="seat"
                     :class="seatClass(seat)"
                     @click="toggleSeat(seat)">
                  {{ seat.id }}
                </div>
              </div>
            </div>
  
            <!-- Footer -->
            <div class="summary">
              <h4>Selected Seats:</h4>
              <p>{{ selectedSeats.length ? selectedSeats.join(', ') : 'None' }}</p>
            </div>
          </div>
          <div v-else class="text-center mt-5">Loading seat info...</div>
        </div>
  
        <!-- RIGHT SIDE - Reservation Form -->
        <div class="col-md-6" v-if="scheduleLoaded">
          <div class="reservation-form">
            <form @submit.prevent="submitReservation">
              <input type="hidden" name="schedule_id" :value="schedule.id">
  
              <h4 class="mb-3 text-center" style="color:#780116;">BUS INFORMATION</h4>
              <div class="ticket-card p-3 mb-3">
                <p class="inline"><strong>Bus type:</strong></p>
                <input class="hidein" type="text" :value="schedule.bus_type" readonly><br>
  
                <p class="inline"><strong>Coach No:</strong></p>
                <input class="hidein" type="text" :value="schedule.coach_no" readonly><br>
  
                <p class="inline"><strong>Route:</strong></p>
                <input class="hidein" type="text" :value="`${schedule.start_location} to ${schedule.end_location}`" readonly><br>
  
                <p class="inline"><strong>Fare per Seat: ৳</strong></p>
                <input class="hidein" type="text" :value="schedule.price.toFixed(2)" readonly><br>
  
                <p class="inline"><strong>Departure:</strong></p>
                <input class="hidein" type="text" v-model="departureTime" readonly><br>
  
                <p class="inline"><strong>Boarding point:</strong></p>
                <select v-model="selectedBoarding" @change="updateDeparture" required>
                  <option value="">Select boarding point</option>
                  <option v-for="board in boardingCounters" :key="board.id" :value="board.name" :data-distance="board.distance">
                    {{ board.name }}
                  </option>
                </select>
  
                <p class="inline"><strong>Dropping point:</strong></p>
                <select v-model="selectedDropping" required>
                  <option value="">Select dropping point</option>
                  <option v-for="drop in droppingCounters" :key="drop.id" :value="drop.name">
                    {{ drop.name }}
                  </option>
                </select>
              </div>
  
              <h4>SEAT INFORMATION:</h4>
              <span>Selected Seats:</span>
              <input type="text" :value="selectedSeats.join(', ')" readonly>
  
              <span>Total amount:</span>
              <input type="text" :value="totalAmount" readonly>
  
              <input type="text" v-model="customerName" placeholder="Your Name*" required>
              <input type="text" v-model="customerMobile" placeholder="Mobile Number*" required>
  
              <button type="submit" class="submit-btn" @click="playHorn" style="cursor:pointer;">
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
  import api from '../../../api/axios.js' // your axios instance
  
  // ---------- Reactive state ----------
  const schedule = reactive({})
  const seatRows = ref([])
  const selectedSeats = ref([])
  const boardingCounters = ref([])
  const droppingCounters = ref([])
  const selectedBoarding = ref('')
  const selectedDropping = ref('')
  const customerName = ref('')
  const customerMobile = ref('')
  const departureTime = ref('')
  const seatLayout = ref('')
  const seatCapacity = ref(0)
  const busType = ref('')
  const bookedSeats = ref([])
  const scheduleLoaded = ref(false)
  
  const totalAmount = computed(() => selectedSeats.value.length * schedule.price)
  
  // ---------- Methods ----------
  async function fetchSchedule(schedule_id) {
    try {
      const res = await api.get(`counter/schedules/${schedule_id}`)
      const data = res.data.data
      // Fill reactive state
      Object.assign(schedule, data.schedule)
      seatLayout.value = data.seatLayout
      seatCapacity.value = data.seatCapacity
      busType.value = data.schedule.bus_type.toLowerCase()
      departureTime.value = schedule.set_time
      generateSeats()
      scheduleLoaded.value = true
    } catch (err) {
      console.error(err)
    }
  }
  
  // Generate seat rows
  function generateSeats() {
    const [leftCount, rightCount] = seatLayout.value.split(':').map(Number)
    const seatsPerRow = leftCount + rightCount
    const totalRows = Math.ceil(seatCapacity.value / seatsPerRow)
    const rows = []
    let seatNumber = 1
    const startLetter = 'A'.charCodeAt(0)
  
    for (let r = 0; r < totalRows; r++) {
      const rowLetter = String.fromCharCode(startLetter + r)
      const left = []
      const right = []
  
      for (let i = 0; i < leftCount && seatNumber <= seatCapacity.value; i++) {
        left.push({ id: `${rowLetter}${seatNumber}`, booked: bookedSeats.value.includes(`${rowLetter}${seatNumber}`) })
        seatNumber++
      }
      for (let i = 0; i < rightCount && seatNumber <= seatCapacity.value; i++) {
        right.push({ id: `${rowLetter}${seatNumber}`, booked: bookedSeats.value.includes(`${rowLetter}${seatNumber}`) })
        seatNumber++
      }
      rows.push({ left, right })
    }
    seatRows.value = rows
  }
  
  // Seat CSS class
  function seatClass(seat) {
    if (seat.booked) return 'soldM'
    if (selectedSeats.value.includes(seat.id)) return 'selected'
    return 'available'
  }
  
  // Toggle seat selection
  function toggleSeat(seat) {
    if (seat.booked) return
    const index = selectedSeats.value.indexOf(seat.id)
    if (index > -1) selectedSeats.value.splice(index, 1)
    else selectedSeats.value.push(seat.id)
  }
  
  // Update departure time based on boarding distance
  function updateDeparture() {
    const selected = boardingCounters.value.find(b => b.name === selectedBoarding.value)
    const distance = selected ? Number(selected.distance) : 0
    const base = new Date(`1970-01-01T${schedule.set_time}:00`)
    base.setMinutes(base.getMinutes() + distance)
    departureTime.value = base.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })
  }
  
  // Optional horn sound
  function playHorn() {
    const audio = new Audio('/sound/horn.m4a')
    audio.play()
  }
  
  // Submit reservation
  function submitReservation() {
    const payload = {
      schedule_id: schedule.id,
      selected_seats: selectedSeats.value,
      total: totalAmount.value,
      name: customerName.value,
      mobile: customerMobile.value,
      boarding: selectedBoarding.value,
      dropping: selectedDropping.value
    }
    console.log('Reservation payload:', payload)
    alert('Reservation submitted! (Check console)')
  }
  
  // On mounted
  onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search)
    const schedule_id = urlParams.get('schedule_id')
    if (schedule_id) fetchSchedule(schedule_id)
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
  