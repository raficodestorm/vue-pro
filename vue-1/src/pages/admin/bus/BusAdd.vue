<script setup>
    import { ref } from "vue";
    
    // Static sample data (because you wanted a static page)
    const types = ref([
      { type: "AC" },
      { type: "Non-AC" },
      { type: "Sleeper" }
    ]);
    
    const routes = ref([
      { route_code: "DHK-CTG" },
      { route_code: "DHK-KHU" },
      { route_code: "CTG-SYL" }
    ]);
    
    // Form fields
    const form = ref({
      name: "",
      coach_no: "",
      license: "",
      company: "",
      bus_type: "",
      route: "",
      seat_layout: "",
      seat_capacity: ""
    });
    
    const saveBus = () => {
      alert("Bus Saved Successfully (Static Mode)");
    };
    </script>
    
    <template>
      <div class="container-fluid main-area">
        <div class="index-card shadow">
    
          <!-- Header -->
          <div
            class="card-header text-white fw-bold p-2 mb-3 text-center py-3 rounded-top-4"
            style="background-color: #ff0000"
          >
            <h4 class="mb-0">🚌 Add New Bus</h4>
          </div>
    
          <div class="card-body p-4">
    
            <!-- Form -->
            <form @submit.prevent="saveBus">
    
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Bus Name</label>
                  <input v-model="form.name" type="text" class="form-control" placeholder="Enter bus name" required />
                </div>
    
                <div class="col-md-6">
                  <label class="form-label">Coach Number</label>
                  <input v-model="form.coach_no" type="number" class="form-control" placeholder="e.g. 101" required />
                </div>
              </div>
    
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">License Number</label>
                  <input v-model="form.license" type="text" class="form-control" placeholder="e.g. DHA-12345" required />
                </div>
    
                <div class="col-md-6">
                  <label class="form-label">Company Name</label>
                  <input v-model="form.company" type="text" class="form-control" placeholder="e.g. Green Line" required />
                </div>
              </div>
    
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Bus Type</label>
                  <select v-model="form.bus_type" class="form-control">
                    <option value="">--select bus type--</option>
                    <option v-for="t in types" :key="t.type" :value="t.type">
                      {{ t.type }}
                    </option>
                  </select>
                </div>
    
                <div class="col-md-6">
                  <label class="form-label">Route</label>
                  <select v-model="form.route" class="form-control">
                    <option value="">--select route--</option>
                    <option v-for="r in routes" :key="r.route_code" :value="r.route_code">
                      {{ r.route_code }}
                    </option>
                  </select>
                </div>
              </div>
    
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label">Seat Layout</label>
                  <input v-model="form.seat_layout" type="text" class="form-control" placeholder="2:2" required />
                </div>
    
                <div class="col-md-6">
                  <label class="form-label">Seat Capacity</label>
                  <input v-model="form.seat_capacity" type="text" class="form-control" placeholder="36/40" required />
                </div>
              </div>
    
              <div class="text-end">
                <button type="submit" class="btn btn-success px-4">Save Bus</button>
                <button type="button" class="btn btn-secondary px-4">Cancel</button>
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
    </style>
    