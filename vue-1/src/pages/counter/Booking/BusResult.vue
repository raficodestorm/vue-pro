<template>
  <div class="result-wrapper">
    <div class="container">

      <h4 class="result-title">
        Available Buses ({{ from }} → {{ to }})
      </h4>

      <!-- LOADING -->
      <div v-if="loading" class="text-center mt-5">
        <p class="loading-text">Searching buses...</p>
      </div>

      <!-- NO BUS -->
      <div v-if="!loading && buses.length === 0" class="no-bus">
        No Bus Available 😔
      </div>

      <!-- BUS LIST -->
      <div v-if="buses.length" class="bus-list">
        <div
          v-for="bus in buses"
          :key="bus.id"
          class="bus-card"
        >
          <!-- TOP -->
          <div class="bus-top">
            <div class="route-box">
              <span class="city">{{ bus.start_location }}</span>

              <div class="journey-line">
                <span class="dot"></span>
                <span class="progress"></span>
                <span class="dot"></span>
              </div>

              <span class="city">{{ bus.end_location }}</span>
            </div>

            <div class="price-box">
              <div class="price">
                ৳ {{ bus.price }}
              </div>
              
            </div>
          </div>

          <!-- DETAILS (ALWAYS VISIBLE) -->
          <div class="bus-details">
            <div class="detail">
              <strong>Date</strong>
              <span>{{ bus.set_date }}</span>
            </div>
            <div class="detail">
              <strong>Time</strong>
              <span>{{ bus.set_time }}</span>
            </div>
            <div class="detail">
              <strong>Duration</strong>
              <span>{{ bus.duration }}</span>
            </div>
            <div class="detail">
              <strong>Bus Type</strong>
              <span>{{ bus.bus_type }}</span>
            </div>
            <div class="detail">
              <strong>Coach</strong>
              <span>{{ bus.coach_no }}</span>
            </div>
            <div class="detail">
              <button
  type="button"
  class="btn-book"
  @click="$router.push({ name: 'SeatReservation', query: { schedule_id: bus.id } })"
  aria-label="Book seat for this bus"
>
  Book Seat
</button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import api from "../../../api/axios.js";

const route = useRoute();

const from = computed(() => route.query.from);
const to   = computed(() => route.query.to);
const date = computed(() => route.query.date);

const loading = ref(false);
const buses = ref([]);

const fetchBuses = async () => {
  loading.value = true;

  try {
    const res = await api.get("/counter/search-bus", {
      params: {
        from: from.value,
        to: to.value,
        date: date.value,
      },
    });

    buses.value = res.data.data || [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchBuses);
</script>

<style scoped>
/* =====================
   PAGE
===================== */
.result-wrapper {
  padding: 70px 0;
  background: linear-gradient(180deg, #f6f8ff, #eef2ff);
}

.result-title {
  text-align: center;
  margin-bottom: 45px;
  font-weight: 600;
  color: var(--second-color);
}

.loading-text {
  color: #666;
}

.no-bus {
  text-align: center;
  padding: 60px;
  font-size: 18px;
  color: #777;
}

/* =====================
   BUS CARD
===================== */
.bus-list {
  display: flex;
  flex-direction: column;
  gap: 26px;
}

.bus-card {
  background: #fff;
  border-radius: 20px;
  padding: 22px 28px;
  box-shadow: 0 18px 45px rgba(0,0,0,0.08);
}

/* =====================
   TOP ROW
===================== */
.bus-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* ROUTE */
.route-box {
  display: flex;
  align-items: center;
  gap: 18px;
}

.city {
  font-size: 17px;
  font-weight: 600;
  color: var(--second-color);
}

/* JOURNEY LINE */
.journey-line {
  position: relative;
  width: 120px;
  height: 2px;
  background: #ddd;
  overflow: hidden;
}

.progress {
  position: absolute;
  height: 100%;
  width: 40%;
  background: linear-gradient(
    90deg,
    var(--main-color),
    var(--second-color)
  );
  animation: travel 2.8s linear infinite;
}

.dot {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 9px;
  height: 9px;
  background: var(--main-color);
  border-radius: 50%;
}

.dot:first-child { left: -4px; }
.dot:last-child  { right: -4px; }

/* ACTION */
.price-box {
  text-align: right;
}

.price {
  font-size: 22px;
  font-weight: 700;
  color: var(--second-color);
  margin-bottom: 6px;
}

.btn-book {
  background: linear-gradient(135deg, var(--main-color), var(--second-color));
  color: #fff;
  border: none;
  padding: 5px 25px;
  border-radius: 30px;
  cursor: pointer;
  font-size: 14px;
  margin-top: 10px;
  float: right;
}

/* =====================
   DETAILS
===================== */
.bus-details {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 20px;
  margin-top: 22px;
  border-top: 1px dashed #ddd;
  padding-top: 18px;
}

.detail strong {
  display: block;
  font-size: 12px;
  color: #999;
}

.detail span {
  font-size: 14px;
  font-weight: 500;
}

/* =====================
   ANIMATION
===================== */
@keyframes travel {
  0%   { left: -40%; }
  100% { left: 100%; }
}


/* =====================
   RESPONSIVE
===================== */
@media (max-width: 768px) {
  .bus-top {
    flex-direction: column;
    align-items: flex-start;
    gap: 14px;
  }

  .price-box {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .bus-details {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
