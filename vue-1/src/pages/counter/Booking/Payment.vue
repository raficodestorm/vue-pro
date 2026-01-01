<template>
  <div class="container mt-5">
    <div v-if="loading" class="text-center">Processing payment...</div>

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

        <button class="btn btn-danger mt-3" @click="payNow" :disabled="paying">
          {{ paying ? "Processing..." : "PAY NOW" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import api from '../../../api/axios.js'
  
  const route = useRoute()
  const router = useRouter()
  
  const reservationId = route.params.id
  const reservation = ref({})
  const loading = ref(true)
  const paying = ref(false)
  
  async function fetchReservation() {
    try {
      const res = await api.get(`/counter/payment/${reservationId}`)
      reservation.value = res.data.reservation
    } catch (e) {
      console.error('Failed to fetch reservation', e)
      alert('Failed to load reservation data')
    } finally {
      loading.value = false
    }
  }
  
  // ✅ Pay Now button
  async function payNow() {
    if (paying.value) return
    paying.value = true
  
    try {
      const res = await api.post(`/counter/pay/${reservationId}`)
      // Success
      alert(res.data.message)
      router.push(`/counter/ticket/${res.data.ticket_id}`)
    } catch (err) {
      // Handle all errors
      if (err.response) {
        // API responded with error
        if (err.response.status === 409) {
          alert(err.response.data.message) // Seat conflict
        } else if (err.response.status === 401) {
          alert('Unauthorized. Please login as counter.')
        } else {
          alert('Payment failed. Try again.') // Generic failure
        }
      } else {
        // Network or other error
        alert('Payment failed. Try again.')
      }
    } finally {
      paying.value = false
    }
  }
  
  onMounted(fetchReservation)
  </script>
  
