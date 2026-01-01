<template>
    <div class="container mt-5">
      <div v-if="loading" class="text-center">Loading ticket...</div>
  
      <div v-else-if="ticket">
        <h3 class="mb-4 text-center text-success">Your Ticket</h3>
        <div class="card p-4">
          <p><strong>Name:</strong> {{ ticket.name }}</p>
          <p><strong>Mobile:</strong> {{ ticket.mobile }}</p>
          <p><strong>Bus Type:</strong> {{ ticket.bus_type }}</p>
          <p><strong>Coach No:</strong> {{ ticket.coach_no }}</p>
          <p><strong>Route:</strong> {{ ticket.route }}</p>
          <p><strong>Departure:</strong> {{ ticket.departure }}</p>
          <p><strong>Selected Seats:</strong> {{ ticket.selected_seats }}</p>
          <p><strong>Total:</strong> ৳{{ ticket.total }}</p>
          <p><strong>Status:</strong> {{ ticket.status }}</p>
        </div>
      </div>
  
      <div v-else>
        <p class="text-center text-danger">Ticket not found or not paid yet.</p>
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
  
  async function fetchTicket() {
    try {
      const res = await api.get(`/counter/ticket/${ticketId}`)
      ticket.value = res.data.ticket
    } catch (e) {
      console.error('Failed to fetch ticket', e)
    } finally {
      loading.value = false
    }
  }
  
  onMounted(fetchTicket)
  </script>
  