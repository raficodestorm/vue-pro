<template>
    <div class="container mt-5">
      <div class="card shadow p-4">
        <h3 class="mb-4 text-center text-danger">Create Schedule</h3>
  
        <!-- Validation Errors -->
        <div v-if="Object.keys(errors).length" class="alert alert-danger">
          <ul class="mb-0">
            <li v-for="(error, index) in errors" :key="index">
              {{ error[0] }}
            </li>
          </ul>
        </div>
  
        <form @submit.prevent="submitForm">
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
              <input type="number" v-model="form.price" step="0.01" class="form-control" required />
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
              <select class="form-control" v-model="form.bus_type" @change="fetchTypes">
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
                autocomplete="off"
                required
              />
              <datalist id="coachSuggestions">
                <option v-for="coach in coaches" :key="coach" :value="coach" />
              </datalist>
            </div>
  
            <div class="col-12 text-center mt-3">
              <button class="btn btn-danger px-4" :disabled="loading">
                {{ loading ? "Saving..." : "Save Schedule" }}
              </button>
            </div>
  
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from "vue";
  import api from "../../../api/axios.js"; 
  import { useRouter } from "vue-router";
  
  const router = useRouter();
  
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
  // ================= ROUTE INFO =================
  const fetchRoutes = async () => {
    try {
      const res = await api.get("/controller/routesfetch");
      console.log("Routes response:", res.data);
      routes.value = res.data.data ?? [];
    } catch (err) {
      console.error("Failed to fetch routes", err);
    }
  };

  // ================= Fetch BusTypes =================
  const fetchTypes = async () => {
    try {
      const res = await api.get("/controller/typesfetch");
      types.value = res.data.data ?? [];
    } catch (err) {
      console.error("Failed to fetch bus types", err);
    }
  };


  const fetchRouteDetails = async (code) => {
  if (!code) return;

  try {
    const res = await api.get(`/controller/routes/${code}`);
    const route = res.data.data;

    form.value.start_location = route.start_location;
    form.value.end_location   = route.end_location;
    form.value.distance       = route.distance;
    form.value.duration       = route.duration;

  } catch (err) {
    console.error("Failed to load route info", err);
  }
};


const fetchCoachesByType = async (type) => {
  if (!type) {
    coaches.value = [];
    return;
  }

  try {
    const res = await api.get(`/controller/buses/coach/${type}`);
    coaches.value = res.data.data ?? [];
  } catch (err) {
    console.error("Failed to fetch coaches", err);
  }
};
  
  // ================= SUBMIT =================
  const submitForm = async () => {
    loading.value = true;
    errors.value = {};
  
    try {
      await api.post("/controller/schedules", form.value);
      router.push({ name: "scheduleindex" });
    } catch (e) {
      if (e.response?.status === 422) {
        errors.value = e.response.data.errors;
      }
    } finally {
      loading.value = false;
    }
  };
  
  // ================= Lifecycle =================
  onMounted(async () => {
    await Promise.all([fetchRoutes(), fetchTypes()]);
  });

  watch(
  () => form.value.route_code,
  (newCode) => {
    fetchRouteDetails(newCode);
  }
);
watch(
  () => form.value.bus_type,
  (newType) => {
    form.value.coach_no = "";  // reset previous selection
    fetchCoachesByType(newType); // fetch related coach_no from API
  }
);
  </script>
  