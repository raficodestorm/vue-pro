<script setup>
    import { ref, onMounted } from "vue";
    import { useRoute, useRouter } from "vue-router";
    import api from "../../../api/axios.js";
    
    const routeParam = useRoute();
    const router = useRouter();
    const busId = routeParam.params.id;
    
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
    
    // ================= Fetch Bus =================
    const fetchBus = async () => {
      try {
        const res = await api.get(`admin/buses/${busId}`);
        const bus = res.data.data;
    
        name.value = bus.name;
        coach_no.value = bus.coach_no;
        license.value = bus.license;
        company.value = bus.company;
        bus_type.value = bus.bus_type;
        route.value = bus.route;
        seat_layout.value = bus.seat_layout;
        seat_capacity.value = bus.seat_capacity;
      } catch (err) {
        console.error("Failed to load bus", err);
        message.value = "Failed to load bus data.";
      }
    };
    
    // ================= Update Bus =================
    const updateBus = async () => {
      loading.value = true;
      message.value = "";
      errors.value = {};
    
      try {
        await api.put(`admin/buses/${busId}`, {
          name: name.value,
          coach_no: coach_no.value,
          license: license.value,
          company: company.value,
          bus_type: bus_type.value,
          route: route.value,
          seat_layout: seat_layout.value,
          seat_capacity: seat_capacity.value,
        });
    
        message.value = "Bus updated successfully!";
    
        setTimeout(() => {
          router.push("/admin/bus/index");
        }, 1000);
      } catch (error) {
        if (error.response?.status === 422) {
          errors.value = error.response.data.errors || {};
        } else if (error.response?.status === 404) {
          message.value = "Bus not found.";
        } else {
          message.value = "Something went wrong. Please try again.";
        }
      } finally {
        loading.value = false;
      }
    };
    
    // ================= Lifecycle =================
    onMounted(async () => {
      await Promise.all([fetchRoutes(), fetchTypes(), fetchBus()]);
    });
    </script>
    
    <template>
      <div class="container-fluid main-area">
        <!-- Header -->
        <div class="header-bar">
          <h2>Edit Bus</h2>
    
          <router-link to="/admin/bus/index" class="back-btn">
            All Buses
          </router-link>
        </div>
    
        <div class="index-card shadow">
          <div class="card-body p-4">
    
            <!-- Message -->
            <div v-if="message" class="alert alert-info mb-3">
              {{ message }}
            </div>
    
            <!-- Form -->
            <form @submit.prevent="updateBus">
    
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Bus Name</label>
                  <input v-model="name" type="text" class="form-control" required />
                </div>
    
                <div class="col-md-6">
                  <label class="form-label">Coach Number</label>
                  <input v-model.number="coach_no" type="number" class="form-control" required />
                </div>
              </div>
    
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">License Number</label>
                  <input v-model="license" type="text" class="form-control" required />
                </div>
    
                <div class="col-md-6">
                  <label class="form-label">Company Name</label>
                  <input v-model="company" type="text" class="form-control" required />
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
                    <option v-for="r in routes" :key="r.id ?? r.route_code" :value="r.route_code">
                      {{ r.route_code }}
                    </option>
                  </select>
                </div>
              </div>
    
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label">Seat Layout</label>
                  <input v-model="seat_layout" type="text" class="form-control" required />
                </div>
    
                <div class="col-md-6">
                  <label class="form-label">Seat Capacity</label>
                  <input v-model.number="seat_capacity" type="number" class="form-control" required />
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
                  {{ loading ? "Updating..." : "Update Bus" }}
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
    
    .back-btn {
      padding: 8px 18px;
      background: var(--second-color);
      color: var(--bg-color);
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
    }
    </style>
    