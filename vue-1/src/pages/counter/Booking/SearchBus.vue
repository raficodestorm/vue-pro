<template>
  <div class="search-wrapper">
    <div class="search-card">
      <h3 class="title">Search Your Journey</h3>

      <form @submit.prevent="handleSearch">
        <div class="row g-3">

          <div class="col-md-4">
            <label class="form-label">From</label>
            <select v-model="form.from" class="form-select">
            <option value="">-- Select Start Location --</option>
            <option v-for="loc in locations" :key="loc.id" :value="loc.district">
              {{ loc.district }}
            </option>
          </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">To</label>
            <select v-model="form.to" class="form-select">
            <option value="">-- Select end Location --</option>
            <option v-for="loc in locations" :key="loc.id" :value="loc.district">
              {{ loc.district }}
            </option>
          </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Journey Date</label>
            <input
              type="date"
              v-model="form.date"
              class="form-control"
              required
            />
          </div>

          <div class="col-12 text-center mt-4">
            <button class="btn btn-search">
              Search Bus
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../../../api/axios.js";

const router = useRouter();
// dropdown
const locations = ref([]);

const form = ref({
  from: "",
  to: "",
  date: "",
});

const handleSearch = () => {
  router.push({
    name: "busResult",
    query: {
      from: form.value.from,
      to: form.value.to,
      date: form.value.date,
    },
  });
};

// fetch locations
const fetchLocations = async () => {
  try {
    const res = await api.get("counter/locationfetch");
    locations.value = res.data.data;
  } catch (error) {
    console.error("Failed to fetch locations", error);
  }
};

onMounted(() => {
  fetchLocations();
});

</script>

<style scoped>
.search-wrapper {
  padding: 70px 15px;
  background: linear-gradient(135deg, var(--section-bg-color), #ffeaea);
}

.search-card {
  max-width: 900px;
  margin: auto;
  background: #fff;
  padding: 35px;
  border-radius: 14px;
  box-shadow: 0 15px 40px rgba(0,0,0,.1);
}

.title {
  text-align: center;
  margin-bottom: 30px;
  color: var(--second-color);
  font-weight: 600;
}

.form-control:focus {
  border-color: var(--main-color);
  box-shadow: 0 0 0 .15rem rgba(255,0,0,.25);
}

.btn-search {
  background: var(--main-color);
  color: #fff;
  padding: 10px 45px;
  border-radius: 30px;
  font-weight: 500;
}

.btn-search:hover {
  background: var(--second-color);
}
</style>
