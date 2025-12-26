<template>
  <div class="result-wrapper">
    <div class="container">

      <h4 class="result-title">
        Available Buses ({{ from }} → {{ to }})
      </h4>

      <!-- LOADING -->
      <div v-if="loading" class="text-center mt-5">
        <p>Loading buses...</p>
      </div>

      <!-- NO BUS -->
      <div v-if="!loading && buses.length === 0" class="no-bus">
        No Bus Available 😔
      </div>

      <!-- BUS LIST -->
      <div class="row g-4" v-if="buses.length">
        <div
          class="col-md-6"
          v-for="bus in buses"
          :key="bus.id"
        >
          <div class="bus-card">
            <div class="bus-header">
              <span class="route">
                {{ bus.start_location }} → {{ bus.end_location }}
              </span>
              <span class="price">
                ৳ {{ bus.price }}
              </span>
            </div>

            <div class="bus-body">
              <p><strong>Date:</strong> {{ bus.set_date }}</p>
              <p><strong>Time:</strong> {{ bus.set_time }}</p>
              <p><strong>Bus Type:</strong> {{ bus.bus_type }}</p>
              <p><strong>Coach No:</strong> {{ bus.coach_no }}</p>
              <p><strong>Duration:</strong> {{ bus.duration }}</p>
            </div>

            <div class="bus-footer">
              <button class="btn btn-book">
                Book Now
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "../../../api/axios.js";

const route = useRoute();

const from = route.query.from;
const to   = route.query.to;
const date = route.query.date;

const loading = ref(false);
const buses = ref([]);

const fetchBuses = async () => {
  loading.value = true;

  try {
    const res = await api.get("/counter/search-bus", {
      params: {
        from,
        to,
        date,
      },
    });

    buses.value = res.data.data ?? [];
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchBuses);
</script>

<style scoped>
.result-wrapper {
  padding: 60px 0;
  background: var(--section-bg-color);
}

.result-title {
  text-align: center;
  margin-bottom: 40px;
  color: var(--second-color);
}

.no-bus {
  text-align: center;
  padding: 60px;
  font-size: 18px;
  color: #777;
}

.bus-card {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,.08);
  height: 100%;
}

.bus-header {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px dashed #ddd;
  padding-bottom: 10px;
  margin-bottom: 10px;
}

.route {
  font-weight: 600;
  color: var(--second-color);
}

.price {
  font-weight: 600;
  color: var(--main-color);
}

.bus-body p {
  margin: 4px 0;
}

.bus-footer {
  text-align: right;
  margin-top: 15px;
}

.btn-book {
  background: var(--main-color);
  color: #fff;
  padding: 6px 20px;
  border-radius: 20px;
}

.btn-book:hover {
  background: var(--second-color);
}
</style>
