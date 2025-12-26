<script setup>
import { ref, onMounted } from "vue";
import axios from '../../../api/axios.js';
import { useRouter } from "vue-router";
import "../indexstyle.css"

const router = useRouter();
const schedules = ref([]);
const loading = ref(true);

// Pagination states
const currentPage = ref(1);
const lastPage = ref(1);
const perPage = ref(10);
const total = ref(0);

// Fetch routes with pagination
const fetchSchedules = async (page = 1) => {
  loading.value = true;
  try {
    const res = await axios.get("/controller/schedules", {
      params: { page, per_page: perPage.value },
    });

    const paginated = res.data.data;
    schedules.value = paginated.data;       // actual array
    currentPage.value = paginated.current_page;
    lastPage.value = paginated.last_page;
    perPage.value = paginated.per_page;
    total.value = paginated.total;

  } catch (error) {
    console.error("Failed to load Schedule", error);
  } finally {
    loading.value = false;
  }
};

// Delete route
const deleteSchedule = async (id) => {
  if (!confirm("Are you sure you want to delete this schedule?")) return;
  try {
    await axios.delete(`/controller/schedules/${id}`);
    fetchSchedules(currentPage.value);
  } catch (error) {
    console.error("Failed to delete schedule", error);
  }
};

// Navigate to edit page
const editSchedule = (id) => {
  router.push(`/controller/schedule/edit/${id}`);
};

onMounted(() => fetchSchedules());
</script>

<template>
  <div class="list-container">

    <!-- Header -->
    <div class="header-bar">
      <h2>Schedules</h2>
      <router-link to="/controller/schedule/add" class="add-btn">
        + Add New
      </router-link>
    </div>

    <!-- Card Box -->
    <div class="card-box shadow">

      <!-- Loading -->
      <div v-if="loading" class="text-center py-3">
        <div class="spinner-border text-danger"></div>
        <p>Loading schedules...</p>
      </div>

      <!-- Routes Table -->
      <table v-else class="table table-bordered table-hover align-middle custom-table">
        <thead>
          <tr>
            <th>#</th>
              <th>Date</th>
              <th>Time</th>
              <th>Route</th>
              <th>Start</th>
              <th>End</th>
              <th>Bustype</th>
              <th>Coach</th>
              <th>Price</th>
              <th>status</th>
            <th width="180">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(schedule, index) in schedules" :key="schedule.id">
            <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
            <td class="fw-semibold">{{ schedule.set_date }}</td>
            <td class="fw-semibold">{{ schedule.set_time }}</td>
            <td class="fw-semibold">{{ schedule.route_code }}</td>
            <td class="fw-semibold">{{ schedule.start_location }}</td>
            <td class="fw-semibold">{{ schedule.end_location }}</td>
            <td class="fw-semibold">{{ schedule.bus_type }}</td>
            <td class="fw-semibold">{{ schedule.coach_no }}</td>
            <td class="fw-semibold">{{ schedule.price }}</td>
            <td class="fw-semibold">{{ schedule.status }}</td>
            <td>
              <div class="action-btns">
                <button @click="editSchedule(schedule.id)" class="edit-btn">Edit</button>
                <button @click="deleteSchedule(schedule.id)" class="delete-btn">Delete</button>
              </div>
            </td>
          </tr>

          <tr v-if="schedules.length === 0">
            <td colspan="7" class="text-center">No Schedule found</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <button
          class="btn btn-sm btn-outline-danger"
          :disabled="currentPage === 1"
          @click="fetchschedules(currentPage - 1)"
        >
          Previous
        </button>

        <span class="fw-semibold">
          Page {{ currentPage }} of {{ lastPage }} | Total: {{ total }}
        </span>

        <button
          class="btn btn-sm btn-outline-danger"
          :disabled="currentPage === lastPage"
          @click="fetchschedules(currentPage + 1)"
        >
          Next
        </button>
      </div>

    </div>
  </div>
</template>

