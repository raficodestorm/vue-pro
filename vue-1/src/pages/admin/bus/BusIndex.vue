<script setup>
import { ref, onMounted } from "vue";
import axios from '../../../api/axios.js';
import { useRouter } from "vue-router";
import "../indexstyle.css"

const router = useRouter();
const buses = ref([]);
const loading = ref(true);

// Pagination states
const currentPage = ref(1);
const lastPage = ref(1);
const perPage = ref(10);
const total = ref(0);

// Fetch routes with pagination
const fetchBuses = async (page = 1) => {
  loading.value = true;
  try {
    const res = await axios.get("/admin/buses", {
      params: { page, per_page: perPage.value },
    });

    const paginated = res.data.data;
    buses.value = paginated.data;       // actual array
    currentPage.value = paginated.current_page;
    lastPage.value = paginated.last_page;
    perPage.value = paginated.per_page;
    total.value = paginated.total;

  } catch (error) {
    console.error("Failed to load Buses", error);
  } finally {
    loading.value = false;
  }
};

// Delete route
const deleteBus = async (id) => {
  if (!confirm("Are you sure you want to delete this bus?")) return;
  try {
    await axios.delete(`/admin/buses/${id}`);
    fetchBuses(currentPage.value);
  } catch (error) {
    console.error("Failed to delete bus", error);
  }
};

// Navigate to edit page
const editBus = (id) => {
  router.push(`/admin/bus/edit/${id}`);
};

onMounted(() => fetchBuses());
</script>

<template>
  <div class="list-container">

    <!-- Header -->
    <div class="header-bar">
      <h2>Bus List</h2>
      <router-link to="/admin/bus/add" class="add-btn">
        + Add New
      </router-link>
    </div>

    <!-- Card Box -->
    <div class="card-box shadow">

      <!-- Loading -->
      <div v-if="loading" class="text-center py-3">
        <div class="spinner-border text-danger"></div>
        <p>Loading buses...</p>
      </div>

      <!-- Routes Table -->
      <table v-else class="table table-bordered table-hover align-middle custom-table">
        <thead>
          <tr>
            <th>#</th>
              <th>Name</th>
              <th>Coach</th>
              <th>License</th>
              <th>Company</th>
              <th>Bus type</th>
              <th>Route</th>
              <th>Seat layout</th>
              <th>Seat capacity</th>
            <th width="180">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(bus, index) in buses" :key="bus.id">
            <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
            <td class="fw-semibold">{{ bus.name }}</td>
            <td class="fw-semibold">{{ bus.coach_no }}</td>
            <td class="fw-semibold">{{ bus.license }}</td>
            <td class="fw-semibold">{{ bus.company }}</td>
            <td class="fw-semibold">{{ bus.bus_type }}</td>
            <td class="fw-semibold">{{ bus.route }}</td>
            <td class="fw-semibold">{{ bus.seat_layout }}</td>
            <td class="fw-semibold">{{ bus.seat_capacity }}</td>
            <td>
              <div class="action-btns">
                <button @click="editBus(bus.id)" class="edit-btn">Edit</button>
                <button @click="deleteBus(bus.id)" class="delete-btn">Delete</button>
              </div>
            </td>
          </tr>

          <tr v-if="buses.length === 0">
            <td colspan="7" class="text-center">No Bus found</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <button
          class="btn btn-sm btn-outline-danger"
          :disabled="currentPage === 1"
          @click="fetchBuses(currentPage - 1)"
        >
          Previous
        </button>

        <span class="fw-semibold">
          Page {{ currentPage }} of {{ lastPage }} | Total: {{ total }}
        </span>

        <button
          class="btn btn-sm btn-outline-danger"
          :disabled="currentPage === lastPage"
          @click="fetchBuses(currentPage + 1)"
        >
          Next
        </button>
      </div>

    </div>
  </div>
</template>

