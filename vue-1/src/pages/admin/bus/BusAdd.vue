<script setup>
  import { ref, onMounted } from "vue";
  import api from "../../../api/axios.js";
  
  // ================= Form Fields =================
  const name = ref("");
  const coach_no = ref(null);
  const license = ref("");
  const company = ref("");
  const bus_type = ref("");
  const route = ref("");
  const seat_layout = ref("");
  const seat_capacity = ref(null);
  
  // ================= UI State =================
  const loading = ref(false);
  const message = ref("");
  const errors = ref({});
  
  // ================= Dropdown Data =================
  const routes = ref([]);
  const types = ref([]);
  
  // ================= Fetch Routes =================
  const fetchRoutes = async () => {
    try {
      const res = await api.get("admin/routesfetch");
      console.log("Routes response:", res.data);
      routes.value = res.data.data ?? [];
    } catch (err) {
      console.error("Failed to fetch routes", err);
    }
  };
  
  // ================= Fetch Types =================
  const fetchTypes = async () => {
    try {
      const res = await api.get("admin/typesfetch");
      types.value = res.data.data ?? [];
    } catch (err) {
      console.error("Failed to fetch bus types", err);
    }
  };
  
  // ================= Lifecycle =================
  onMounted(async () => {
    await Promise.all([fetchRoutes(), fetchTypes()]);
  });
  
  // ================= Save Bus =================
  const saveBus = async () => {
    loading.value = true;
    message.value = "";
    errors.value = {};
  
    try {
      await api.post("admin/buses", {
        name: name.value,
        coach_no: coach_no.value,
        license: license.value,
        company: company.value,
        bus_type: bus_type.value,
        route: route.value,
        seat_layout: seat_layout.value,
        seat_capacity: seat_capacity.value,
      });
  
      message.value = "Bus added successfully!";
  
      // reset form
      name.value = "";
      coach_no.value = null;
      license.value = "";
      company.value = "";
      bus_type.value = "";
      route.value = "";
      seat_layout.value = "";
      seat_capacity.value = null;
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
    
    <div class="container-fluid main-area">
      <!-- Header Section -->
      <div class="header-bar">
          <h2>Add New Bus</h2>
    
          <router-link to="/admin/bus/index" class="back-btn">
            All Buses
          </router-link>
        </div>
      <div class="index-card shadow">
  
        <div class="card-body p-4">
  
          <!-- Success / Error Message -->
          <div v-if="message" class="alert alert-info mb-3">
            {{ message }}
          </div>
  
          <!-- Form -->
          <form @submit.prevent="saveBus">
  
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Bus Name</label>
                <input
                  v-model="name"
                  type="text"
                  class="form-control"
                  placeholder="Enter bus name"
                  required
                />
              </div>
  
              <div class="col-md-6">
                <label class="form-label">Coach Number</label>
                <input
                  v-model.number="coach_no"
                  type="number"
                  class="form-control"
                  placeholder="e.g. 101"
                  required
                />
              </div>
            </div>
  
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">License Number</label>
                <input
                  v-model="license"
                  type="text"
                  class="form-control"
                  placeholder="e.g. DHA-12345"
                  required
                />
              </div>
  
              <div class="col-md-6">
                <label class="form-label">Company Name</label>
                <input
                  v-model="company"
                  type="text"
                  class="form-control"
                  placeholder="e.g. Green Line"
                  required
                />
              </div>
            </div>
  
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Bus Type</label>
                <select v-model="bus_type" class="form-control" required>
                  <option value="">-- select bus type --</option>
                  <option v-for="t in types" :key="t.id ?? t.type" :value="t.type">
                    {{ t.type }}
                  </option>
                </select>
              </div>
  
              <div class="col-md-6">
                <label class="form-label">Route</label>
                <select v-model="route" class="form-control" required>
                  <option value="">-- select route --</option>
                  <option
                    v-for="r in routes"
                    :key="r.id ?? r.route_code"
                    :value="r.route_code"
                  >
                    {{ r.route_code }}
                  </option>
                </select>
              </div>
            </div>
  
            <div class="row mb-4">
              <div class="col-md-6">
                <label class="form-label">Seat Layout</label>
                <input
                  v-model="seat_layout"
                  type="text"
                  class="form-control"
                  placeholder="2 : 2"
                  required
                />
              </div>
  
              <div class="col-md-6">
                <label class="form-label">Seat Capacity</label>
                <input
                  v-model.number="seat_capacity"
                  type="number"
                  class="form-control"
                  placeholder="36 / 40"
                  required
                />
              </div>
            </div>
  
            <div class="text-end">
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
                {{ loading ? "Saving..." : "Save Bus" }}
              </button>
            </div>
  
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <style scoped>
  .main-area {
    min-height: 90vh;
    width: 100%;
    padding: 3rem;
    background-color: var(--back-color);
  }
  
  .index-card {
    border: 0.5px solid var(--main-color);
    border-radius: 1rem;
    padding: 1rem;
    background-color: var(--bg-color);
  }
  /* Header */
  .header-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .header-bar h2 {
      font-weight: 700;
      color: var(--second-color);
    }
    /* Back Button */
    .back-btn {
      padding: 8px 18px;
      background: var(--second-color);
      color: var(--bg-color);
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }
  </style>
  