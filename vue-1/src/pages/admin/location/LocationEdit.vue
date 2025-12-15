<script setup>
import { ref, onMounted } from "vue";
import api from "../../../api/axios.js";
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();

// route param
const locationId = route.params.id;

// form fields
const district = ref("");
const division = ref("");

// ui state
const loading = ref(false);
const pageLoading = ref(true);
const message = ref("");
const errors = ref({});

/**
 * Fetch existing location data
 */
const fetchLocation = async () => {
  pageLoading.value = true;
  try {
    const res = await api.get(`/admin/locations/${locationId}`);
    const data = res.data.data;

    district.value = data.district;
    division.value = data.division ?? "";

  } catch (error) {
    message.value = "Failed to load location data.";
  } finally {
    pageLoading.value = false;
  }
};

/**
 * Update location
 */
const updateLocation = async () => {
  loading.value = true;
  message.value = "";
  errors.value = {};

  try {
    await api.put(`/admin/locations/${locationId}`, {
      district: district.value,
      division: division.value,
    });

    message.value = "Location updated successfully!";

    setTimeout(() => {
      router.push("/admin/location/index");
    }, 800);

  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
    } else if (error.response?.status === 401) {
      message.value = "Unauthorized! Please login again.";
    } else {
      message.value = "Something went wrong. Please try again.";
    }
  } finally {
    loading.value = false;
  }
};

onMounted(fetchLocation);
</script>

<template>
  <div class="container py-4 list-container">

    <div v-if="pageLoading" class="text-center py-5">
      <div class="spinner-border text-danger"></div>
      <p class="mt-2">Loading location...</p>
    </div>

    <div v-else class="card-box shadow">

      <!-- Header -->
      <div class="header-bar">
        <h2>Edit Location</h2>
        <router-link to="/admin/location/index" class="add-btn">
          ← Back
        </router-link>
      </div>

      <!-- Message -->
      <div
        v-if="message"
        class="alert"
        :class="message.includes('success') ? 'alert-success' : 'alert-danger'"
      >
        {{ message }}
      </div>

      <!-- Form -->
      <form @submit.prevent="updateLocation">

        <!-- District -->
        <div class="mb-3">
          <label class="form-label">District</label>
          <input
            type="text"
            v-model="district"
            class="form-control"
          />
          <small class="text-danger">{{ errors.district }}</small>
        </div>

        <!-- Division -->
        <div class="mb-3">
          <label class="form-label">Division (Optional)</label>
          <input
            type="text"
            v-model="division"
            class="form-control"
          />
          <small class="text-danger">{{ errors.division }}</small>
        </div>

        <!-- Submit -->
        <button
          type="submit"
          class="btn text-white"
          :disabled="loading"
          :style="{
            background: 'linear-gradient(90deg, var(--main-color), var(--second-color))',
            borderRadius: '6px',
            fontWeight: '600'
          }"
        >
          {{ loading ? "Updating..." : "Update Location" }}
        </button>

      </form>
    </div>
  </div>
</template>

<style scoped>
.list-container {
  background: var(--back-color);
  min-height: 100vh;
}

.header-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.header-bar h2 {
  font-weight: 700;
  color: var(--second-color);
}

.card-box {
  background: var(--bg-color);
  padding: 25px;
  border-radius: 12px;
  border: 1px solid #ddd;
}

.form-control:focus {
  border-color: var(--main-color);
  box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.2);
}

small {
  font-weight: 500;
}
</style>
