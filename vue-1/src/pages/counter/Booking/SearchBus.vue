<template>
  <div class="search-wrapper">
    <div class="search-card">
      <h3 class="title">Search Your Journey</h3>

      <form @submit.prevent="handleSearch">
        <div class="row g-3">

          <div class="col-md-4">
            <label class="form-label">From</label>
            <input
              type="text"
              v-model="form.from"
              class="form-control"
              placeholder="Starting location"
              required
            />
          </div>

          <div class="col-md-4">
            <label class="form-label">To</label>
            <input
              type="text"
              v-model="form.to"
              class="form-control"
              placeholder="Destination"
              required
            />
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
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

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
