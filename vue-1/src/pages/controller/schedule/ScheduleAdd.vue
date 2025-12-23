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
              <select class="form-control" v-model="form.route_code" @change="fetchRouteInfo">
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
              <select class="form-control" v-model="form.bus_type" @change="fetchCoaches">
                <option value="">--select bus type--</option>
                <option v-for="type in bustypes" :key="type.id" :value="type.type">
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
  import { ref, onMounted } from "vue";
  import api from "../../../api/axios.js"; 
  import { useRouter } from "vue-router";
  
  const router = useRouter();
  
  // ================= STATE =================
  const loading = ref(false);
  const errors = ref({});
  
  const routes = ref([]);
  const bustypes = ref([]);
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
  
  // ================= LOAD INITIAL DATA =================
  const loadCreateData = async () => {
    const res = await api.get("/schedules/create");
    routes.value = res.data.routes;
    bustypes.value = res.data.bustypes;
  };
  
  // ================= ROUTE INFO =================
  const fetchRouteInfo = async () => {
    if (!form.value.route_code) return;
  
    const res = await api.get(`/routes/info/${form.value.route_code}`);
    const route = res.data.data;
  
    if (route) {
      form.value.start_location = route.start_location;
      form.value.end_location = route.end_location;
      form.value.distance = route.distance;
      form.value.duration = route.duration;
    }
  };
  
  // ================= COACH SUGGESTION =================
  const fetchCoaches = async () => {
    if (!form.value.bus_type) return;
  
    const res = await api.get(`/buses/coaches/${form.value.bus_type}`);
    coaches.value = res.data.data;
  };
  
  // ================= SUBMIT =================
  const submitForm = async () => {
    loading.value = true;
    errors.value = {};
  
    try {
      await api.post("/schedules", form.value);
      router.push({ name: "scheduleindex" });
    } catch (e) {
      if (e.response?.status === 422) {
        errors.value = e.response.data.errors;
      }
    } finally {
      loading.value = false;
    }
  };
  
  onMounted(loadCreateData);
  </script>
  