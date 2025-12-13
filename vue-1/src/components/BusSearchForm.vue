<script setup>
import { ref } from "vue";

const from = ref("");
const to = ref("");
const date = ref("");

const locationsFrom = ref([]);
const locationsTo = ref([]);

async function fetchLocations(query, listRef) {
  if (!query) return;

  const { data } = await axios.get(`/locations?q=${query}`);
  listRef.value = data;
}

function submitForm() {
  const payload = {
    from: from.value,
    to: to.value,
    date: date.value,
  };

  axios.post("/bus/search", payload).then((res) => {
    console.log("Search Results → ", res.data);
  });
}
</script>

<template>
  <div class="card shadow-sm p-4 rounded-3">
    <h5 class="fw-bold mb-3 text-center" style="color: #220901">
      Find Your Bus
    </h5>

    <form @submit.prevent="submitForm">
      <!-- From -->
      <div class="mb-3">
        <label class="form-label fw-semibold">From</label>
        <input
          v-model="from"
          @input="fetchLocations(from, locationsFrom)"
          list="fromList"
          class="form-control rounded-3"
          placeholder="start location"
          required
        />
        <datalist id="fromList">
          <option v-for="loc in locationsFrom" :value="loc" />
        </datalist>
      </div>

      <!-- To -->
      <div class="mb-3">
        <label class="form-label fw-semibold">To</label>
        <input
          v-model="to"
          @input="fetchLocations(to, locationsTo)"
          list="toList"
          class="form-control rounded-3"
          placeholder="end location"
          required
        />
        <datalist id="toList">
          <option v-for="loc in locationsTo" :value="loc" />
        </datalist>
      </div>

      <!-- Date -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Date of Journey</label>
        <input
          type="date"
          class="form-control rounded-3"
          v-model="date"
          required
        />
      </div>

      <div class="d-grid">
        <button type="submit" class="btn bus-src rounded-3 fw-semibold">
          Search Buses
        </button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.card {
  background-color: #fffffc75;
}
.bus-src {
  background: linear-gradient(90deg, #ff0000, #780116);
  color: white;
  border: none;
}
</style>
