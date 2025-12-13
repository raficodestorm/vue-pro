<script setup>
import { ref, onMounted } from "vue";
import axios from '../../../api/axios.js';
import { useRouter } from "vue-router";
import "../indexstyle.css"

const router = useRouter();
const routes = ref([]);
const loading = ref(true);

// Pagination states
const currentPage = ref(1);
const lastPage = ref(1);
const perPage = ref(10);
const total = ref(0);

// Fetch routes with pagination
const fetchRoutes = async (page = 1) => {
  loading.value = true;
  try {
    const res = await axios.get("/admin/routes", {
      params: { page, per_page: perPage.value },
    });

    const paginated = res.data.data;
    routes.value = paginated.data;       // actual array
    currentPage.value = paginated.current_page;
    lastPage.value = paginated.last_page;
    perPage.value = paginated.per_page;
    total.value = paginated.total;

  } catch (error) {
    console.error("Failed to load routes", error);
  } finally {
    loading.value = false;
  }
};

// Delete route
const deleteRoute = async (id) => {
  if (!confirm("Are you sure you want to delete this route?")) return;
  try {
    await axios.delete(`/admin/routes/${id}`);
    fetchRoutes(currentPage.value);
  } catch (error) {
    console.error("Failed to delete route", error);
  }
};

// Navigate to edit page
const editRoute = (id) => {
  router.push(`/admin/route/edit/${id}`);
};

onMounted(() => fetchRoutes());
</script>

<template>
  <div class="list-container">

    <!-- Header -->
    <div class="header-bar">
      <h2>Route List</h2>
      <router-link to="/admin/route/add" class="add-btn">
        + Add New
      </router-link>
    </div>

    <!-- Card Box -->
    <div class="card-box shadow">

      <!-- Loading -->
      <div v-if="loading" class="text-center py-3">
        <div class="spinner-border text-danger"></div>
        <p>Loading routes...</p>
      </div>

      <!-- Routes Table -->
      <table v-else class="table table-bordered table-hover align-middle custom-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Route Code</th>
            <th>Start Location</th>
            <th>End Location</th>
            <th>Distance</th>
            <th>Duration</th>
            <th width="180">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(route, index) in routes" :key="route.id">
            <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
            <td class="fw-semibold">{{ route.route_code }}</td>
            <td class="fw-semibold">{{ route.start_location }}</td>
            <td class="fw-semibold">{{ route.end_location }}</td>
            <td class="fw-semibold">{{ route.distance || '-' }}</td>
            <td class="fw-semibold">{{ route.duration || '-' }}</td>
            <td>
              <div class="action-btns">
                <button @click="editRoute(route.id)" class="edit-btn">Edit</button>
                <button @click="deleteRoute(route.id)" class="delete-btn">Delete</button>
              </div>
            </td>
          </tr>

          <tr v-if="routes.length === 0">
            <td colspan="7" class="text-center">No routes found</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <button
          class="btn btn-sm btn-outline-danger"
          :disabled="currentPage === 1"
          @click="fetchRoutes(currentPage - 1)"
        >
          Previous
        </button>

        <span class="fw-semibold">
          Page {{ currentPage }} of {{ lastPage }} | Total: {{ total }}
        </span>

        <button
          class="btn btn-sm btn-outline-danger"
          :disabled="currentPage === lastPage"
          @click="fetchRoutes(currentPage + 1)"
        >
          Next
        </button>
      </div>

    </div>
  </div>
</template>

