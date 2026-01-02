<template>
  <div class="ticket-page">
    <div v-if="loading" class="text-center mt-5 no-print">
      Loading ticket...
    </div>

    <div v-else-if="ticket" class="ticket-wrapper">
      <div class="print-area">
        <div class="ticket">
          <div class="ticket-header">
            <div class="company">
              <span>RunStar</span>
            </div>
            <div class="bus-no">
              Bus No: {{ ticket.coach_no }}
            </div>
          </div>

          <div class="ticket-body">
            <div class="ticket-left">
              <div class="row">
                <span>Bill No: #{{ ticket.id }}</span>
              </div>
              <div class="row">
                <span>Name of Passenger: {{ ticket.name }}</span>
              </div>
              <div class="row">
                <span>Mobile: {{ ticket.mobile }}</span>
              </div>
              <div class="row">
                <span>Seat No: {{ ticket.selected_seats }}</span>
              </div>
              <div class="row">
                <span>Route: {{ ticket.route }}</span>
              </div>
              <div class="row">
                <span>Pickup Station: {{ ticket.boarding ?? 'Main Counter' }}</span>
              </div>
              <div class="row">
                <span>Departure: {{ ticket.departure }}</span>
              </div>
            </div>

            <div class="ticket-divider"></div>

            <div class="ticket-right">
              <p><strong>Bill No:</strong> #{{ ticket.id }}</p>
              <p><strong>Name:</strong> {{ ticket.name }}</p>
              <p><strong>Journey:</strong> {{ ticket.route }}</p>
              <p><strong>Seat:</strong> {{ ticket.selected_seats }}</p>
              <p><strong>Total:</strong> ৳{{ ticket.total }}</p>
              <div class="paid">✔ BILL PAID</div>
            </div>
          </div>

          <div class="ticket-footer">
            <span>Note: 40% charge for cancellation before 24 hours of departure.</span>
            <span>📞 +880-XXXXXXXXXX</span>
          </div>
        </div>
      </div>

      <div class="text-center mt-4 no-print">
        <button class="btn-print" @click="printTicket">
          🖨 Print Ticket
        </button>
      </div>
    </div>

    <div v-else class="text-center text-danger no-print">
      Ticket not found or not paid.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../../api/axios.js'

const route = useRoute()
const ticketId = route.params.id
const ticket = ref(null)
const loading = ref(true)

const fetchTicket = async () => {
  try {
    const res = await api.get(`/counter/ticket/${ticketId}`)
    ticket.value = res.data.ticket
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

const printTicket = () => {
  window.print()
}

onMounted(fetchTicket)
</script>

<style scoped>
span {
  display: inline-block;
}
.ticket-page {
  display: flex;
  justify-content: center;
  padding: 20px;
}
.ticket-wrapper {
  max-width: 900px;
  width: 100%;
}
.ticket {
  border: 1px solid #ddd;
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.ticket-header {
  background: #ff0000;
  color: #fff;
  display: flex;
  justify-content: space-between;
  padding: 15px;
}
.ticket-body {
  display: grid;
  grid-template-columns: 2fr 2px 1fr;
  background: white;
}
.ticket-left, .ticket-right {
  padding: 20px;
  font-size: 14px;
}
.row {
  margin-bottom: 8px;
}
.ticket-divider {
  border-left: 2px dashed #ccc;
}
.paid {
  margin-top: 10px;
  color: green;
  font-weight: bold;
}
.ticket-footer {
  background: #ff0000;
  color: #fff;
  display: flex;
  justify-content: space-between;
  padding: 10px 15px;
  font-size: 11px;
}
.btn-print {
  background: #ff0000;
  color: #fff;
  padding: 10px 25px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-weight: bold;
}

/* ================= PRINT SETTINGS ================= */
@media print {
  /* 1. Hide every single element on the page */
  :deep(body *) {
    visibility: hidden !important;
  }

  /* 2. Hide common layout wrappers completely to reclaim space */
  :deep(.sidebar), 
  :deep(.navbar), 
  :deep(.header), 
  :deep(.no-print),
  .no-print {
    display: none !important;
  }

  /* 3. Show only the print area and its children */
  .print-area,
  .print-area * {
    visibility: visible !important;
  }

  /* 4. Reset position to top-left of the paper */
  .print-area {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    margin: 0;
    padding: 0;
  }

  /* 5. Ensure colors (background-red) are printed */
  .ticket-header, .ticket-footer {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  
  /* Remove box shadow for a cleaner print */
  .ticket {
    box-shadow: none !important;
    border: 1px solid #000;
  }
}
</style>