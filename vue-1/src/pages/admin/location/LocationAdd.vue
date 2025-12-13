<script setup>
import { ref } from "vue";
import api from "../../../api/axios.js";
import { useRouter } from "vue-router";

const router = useRouter();

// form fields
const district = ref("");
const division = ref("");

// ui state
const loading = ref(false);
const message = ref("");
const errors = ref({});

// save location
const saveLocation = async () => {
  loading.value = true;
  message.value = "";
  errors.value = {};

  try {
    await api.post("/admin/locations", {
      district: district.value,
      division: division.value,
    });

    message.value = "Location added successfully!";

    // reset form
    district.value = "";
    division.value = "";

    // optional: redirect after success
    setTimeout(() => {
      router.push("/admin/locations/index");
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
</script>

<template>
  <div class="container py-4 list-container">
    <div class="card-box shadow">

      <!-- Header -->
      <div class="header-bar">
        <h2>Add New Location</h2>
        <router-link to="/admin/locations" class="add-btn">
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
      <form @submit.prevent="saveLocation">

        <!-- District -->
        <div class="mb-3">
          <label class="form-label">District</label>
          <input
            type="text"
            v-model="district"
            class="form-control"
            placeholder="Enter district name"
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
            placeholder="Enter division name"
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
          {{ loading ? "Saving..." : "Save Location" }}
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
