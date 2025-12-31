<template>
  <div class="container mt-5">
    <div v-if="loading">Loading reservation...</div>

    <div v-else>
      <h3 class="mb-4 text-center text-danger">Payment Details</h3>
      <div class="card p-4">
        <p><strong>Name:</strong> {{ reservation.name }}</p>
        <p><strong>Mobile:</strong> {{ reservation.mobile }}</p>
        <p><strong>Bus Type:</strong> {{ reservation.bus_type }}</p>
        <p><strong>Coach No:</strong> {{ reservation.coach_no }}</p>
        <p><strong>Route:</strong> {{ reservation.route }}</p>
        <p><strong>Selected Seats:</strong> {{ reservation.selected_seats }}</p>
        <p><strong>Total:</strong> ৳{{ reservation.total }}</p>

        <button class="btn btn-danger mt-3" @click="payNow">
          PAY NOW
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../../api/axios.js'

const route = useRoute()
const reservation = ref({})
const loading = ref(true)

async function fetchReservation() {
  try {
    const res = await api.get(`/counter/payment/${id}`)
    reservation.value = res.data.reservation
  } catch (e) {
    console.error('Failed to fetch reservation', e)
  } finally {
    loading.value = false
  }
}

function payNow() {
  alert('Redirect to payment gateway or process payment here!')
  // You can integrate Stripe, SSLCOMMERZ, or any gateway here
}

onMounted(fetchReservation)
</script>
