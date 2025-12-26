<template>
  <div class="container mt-5">
    <div class="card shadow p-4">
      <h3 class="mb-4 text-center text-danger">Edit Schedule</h3>

      <!-- Validation Errors -->
      <div v-if="Object.keys(errors).length" class="alert alert-danger">
        <ul class="mb-0">
          <li v-for="(error, index) in errors" :key="index">
            {{ error[0] }}
          </li>
        </ul>
      </div>

      <form @submit.prevent="updateForm">
        <div class="row">

          <div class="col-md-6 mb-3">
            <label class="form-label">Date</label>
            <input type="date" v-model="form.set_date" class="form-control" required />
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Time</label>
            <input type="time" v-model="form.set_time" class="form-control" required />
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Route Code</label>
            <select class="form-control" v-model="form.route_code">
              <option value="">--select route--</option>
              <option v-for="route in routes" :key="route.id" :value="route.route_code">
                {{ route.route_code }}
              </option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Price</label>
            <input type="number" v-model="form.price" class="form-control" required />
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Start Location</label>
            <input type="text" v-model="form.start_location" class="form-control" readonly />
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">End Location</label>
            <input type="text" v-model="form.end_location" class="form-control" readonly />
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Distance</label>
            <input type="text" v-model="form.distance" class="form-control" readonly />
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Duration</label>
            <input type="text" v-model="form.duration" class="form-control" readonly />
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Bus Type</label>
            <select class="form-control" v-model="form.bus_type">
              <option value="">--select bus type--</option>
              <option v-for="type in types" :key="type.id" :value="type.type">
                {{ type.type }}
              </option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Coach No</label>
            <input
              type="text"
              v-model="form.coach_no"
              class="form-control"
              list="coachSuggestions"
              required
            />
            <datalist id="coachSuggestions">
              <option v-for="coach in coaches" :key="coach" :value="coach" />
            </datalist>
          </div>

          <div class="col-12 text-center mt-3">
            <button class="btn btn-danger px-4" :disabled="loading">
              {{ loading ? "Updating..." : "Update Schedule" }}
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../../../api/axios.js";

const router = useRouter();
const route = useRoute();
const scheduleId = route.params.id;

// ================= STATE =================
const loading = ref(false);
const errors = ref({});
const routes = ref([]);
const types = ref([]);
const coaches = ref([]);

// ================= FORM =================
const form = ref({
  set_date: "",
  set_time: "",
  route_code: "",
  start_location: "",
  end_location: "",
  distance: "",
  duration: "",
  price: "",
  bus_type: "",
  coach_no: "",
});

// ================= FETCH DATA =================
const fetchRoutes = async () => {
  const res = await api.get("/controller/routesfetch");
  routes.value = res.data.data ?? [];
};

const fetchTypes = async () => {
  const res = await api.get("/controller/typesfetch");
  types.value = res.data.data ?? [];
};

const fetchSchedule = async () => {
  const res = await api.get(`/controller/schedules/${scheduleId}`);
  Object.assign(form.value, res.data.data);

  // preload coach list
  fetchCoachesByType(form.value.bus_type);
};

const fetchRouteDetails = async (code) => {
  if (!code) return;

  const res = await api.get(`/controller/routes/${code}`);
  const r = res.data.data;

  form.value.start_location = r.start_location;
  form.value.end_location = r.end_location;
  form.value.distance = r.distance;
  form.value.duration = r.duration;
};

const fetchCoachesByType = async (type) => {
  if (!type) {
    coaches.value = [];
    return;
  }

  const res = await api.get(`/controller/buses/coach/${type}`);
  coaches.value = res.data.data ?? [];
};

// ================= UPDATE =================
const updateForm = async () => {
  loading.value = true;
  errors.value = {};

  try {
    await api.put(`/controller/schedules/${scheduleId}`, form.value);
    router.push({ name: "allschedule" });
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors;
    }
  } finally {
    loading.value = false;
  }
};

// ================= WATCHERS =================
watch(() => form.value.route_code, fetchRouteDetails);

watch(() => form.value.bus_type, (newType) => {
  form.value.coach_no = "";
  fetchCoachesByType(newType);
});

// ================= INIT =================
onMounted(async () => {
  await Promise.all([
    fetchRoutes(),
    fetchTypes(),
    fetchSchedule(),
  ]);
});
</script>
