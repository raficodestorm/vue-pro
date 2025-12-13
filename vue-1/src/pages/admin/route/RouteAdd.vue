<script setup>
import { ref, onMounted } from "vue";
import api from "../../../api/axios.js";

// form fields
const route_code = ref("");
const start_location = ref("");
const end_location = ref("");
const distance = ref("");
const duration = ref("");

// ui state
const loading = ref(false);
const message = ref("");
const errors = ref({});

// dropdown
const locations = ref([]);

// fetch locations
const fetchLocations = async () => {
  try {
    const res = await api.get("/locations");
    locations.value = res.data.data;
  } catch (error) {
    console.error("Failed to fetch locations", error);
  }
};

onMounted(fetchLocations);

// save route
const saveRoute = async () => {
  loading.value = true;
  message.value = "";
  errors.value = {};

  try {
    await api.post("/routes", {
      route_code: route_code.value,
      start_location: start_location.value,
      end_location: end_location.value,
      distance: distance.value,
      duration: duration.value,
    });

    message.value = "Route added successfully!";

    // reset form
    route_code.value = "";
    start_location.value = "";
    end_location.value = "";
    distance.value = "";
    duration.value = "";
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
    } else if (error.response?.status === 401) {
      message.value = "Unauthorized! Please login again.";
    } else {
      message.value = "Something went wrong. Please try again.";
    }
    console.error(error);
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="container py-4" :style="{ backgroundColor: 'var(--section-bg-color)' }">
    <div class="card p-4 shadow-sm" :style="{ borderRadius: 'var(--radius)' }">
      <h3 class="mb-4">Add New Route</h3>

      <div
        v-if="message"
        class="alert"
        :class="message.includes('success') ? 'alert-success' : 'alert-danger'"
      >
        {{ message }}
      </div>

      <form @submit.prevent="saveRoute">
        <!-- Route Code -->
        <div class="mb-3">
          <label class="form-label">Route Code</label>
          <input type="text" v-model="route_code" class="form-control" />
          <small class="text-danger">{{ errors.route_code }}</small>
        </div>

        <!-- Start Location -->
        <div class="mb-3">
          <label class="form-label">Start Location</label>
          <select v-model="start_location" class="form-select">
            <option value="">-- Select Start Location --</option>
            <option v-for="loc in locations" :key="loc.id" :value="loc.district">
              {{ loc.district }}
            </option>
          </select>
          <small class="text-danger">{{ errors.start_location }}</small>
        </div>

        <!-- End Location -->
        <div class="mb-3">
          <label class="form-label">End Location</label>
          <select v-model="end_location" class="form-select">
            <option value="">-- Select End Location --</option>
            <option v-for="loc in locations" :key="loc.id" :value="loc.district">
              {{ loc.district }}
            </option>
          </select>
          <small class="text-danger">{{ errors.end_location }}</small>
        </div>

        <!-- Distance -->
        <div class="mb-3">
          <label class="form-label">Distance (Optional)</label>
          <input type="text" v-model="distance" class="form-control" />
        </div>

        <!-- Duration -->
        <div class="mb-3">
          <label class="form-label">Duration (Optional)</label>
          <input type="text" v-model="duration" class="form-control" />
        </div>

        <button
          type="submit"
          class="btn text-white"
          :disabled="loading"
          :style="{
            background: 'linear-gradient(90deg, var(--main-color), var(--second-color))',
            borderRadius: 'var(--radius)',
            fontWeight: '600'
          }"
        >
          {{ loading ? "Saving..." : "Save Route" }}
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
h3 {
  color: var(--second-color);
}

.form-control:focus,
.form-select:focus {
  border-color: var(--main-color);
  box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.2);
}

small {
  font-weight: 500;
}
</style>
