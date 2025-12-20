<template>
  <div class="container-fluid">
    <!-- Dashboard Stats -->
    <div class="row g-2">
      <div class="col-md-3 p-2">
      <div class="hello p-2">
        <h5>Passenger</h5>
        <h2>3005p</h2>
        <p>in Fabruary</p>
        <div class="style-hello"></div>
      </div>
    </div>

    <div class="col-md-3 p-2">
      <div class="hello p-2">
        <h5>Sale</h5>
        <h2>20500 Tk</h2>
        <p>in Fabruary</p>
        <div class="style-hello"></div>
      </div>
    </div>

    <div class="col-md-3 p-2">
      <div class="hello p-2">
        <h5>Completed Trips</h5>
        <h2>500</h2>
        <p>in Fabruary</p>
        <div class="style-hello"></div>
      </div>
    </div>

    <div class="col-md-3 p-2">
      <div class="hello p-2">
        <h5>Cost</h5>
        <h2>3500tk</h2>
        <p>in Fabruary</p>
        <div class="style-hello"></div>
      </div>
    </div>
    </div>

    <!-- Charts & Top 5 Branches -->
    <div class="row g-2 mt-3">
      <div class="col-md-6 p-2 p-graph">
        <div class="graph p-2">
          <div class="chart-container">
            <h4 class="chart-title text-center">Monthly Bookings Overview</h4>
            <canvas ref="monthlyChart"></canvas>
          </div>
          <div class="style-hello-graph"></div>
        </div>
      </div>

      <div class="col-md-6 p-2 p-top-saler">
        <div class="top-saler p-2">
          <h4 class="top-saler-table-title mb-4">🏆 Top 5 Branches</h4>
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Branch</th>
                <th>Manager</th>
                <th class="top-saler-text-center">Sales</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(branch, index) in topBranches" :key="index">
                <td>{{ branch.name }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <img
                      :src="branch.managerPhoto"
                      class="top-saler-profile-pic"
                    />
                    <span class="ms-2">{{ branch.manager }}</span>
                  </div>
                </td>
                <td class="text-center fw-bold top-saler-text-main">
                  {{ branch.sales }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="style-hello-topsale"></div>
      </div>
    </div>

    <!-- All Bookings Table -->
    <div class="row g-2 mx-1 mb-3">
      <div class="index-card shadow">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>All bookings</h3>
        </div>

        <div v-if="successMessage" class="alert alert-success">
          {{ successMessage }}
        </div>

        <table class="table table-bordered align-middle" id="table-same">
          <thead class="table-dark text-center">
            <tr>
              <th>ID</th>
              <th>Passenger</th>
              <th>Schedule id</th>
              <th>Coach no</th>
              <th>Route</th>
              <th>Seats</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(reserve, index) in reservations"
              :key="index"
              class="text-center"
            >
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ reserve.name }}</td>
              <td>{{ reserve.schedule_id }}</td>
              <td>{{ reserve.coach_no }}</td>
              <td>{{ reserve.route }}</td>
              <td>{{ reserve.selected_seats }}</td>
              <td>{{ reserve.total }}</td>
              <td class="badge bg-info">{{ reserve.status }}</td>
            </tr>
            <tr v-if="!reservations.length">
              <td colspan="8" class="text-center text-muted">
                No bookings found.
              </td>
            </tr>
          </tbody>
        </table>
        <!-- Pagination could be handled with a component or plugin -->
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
// import Chart from "chart.js/auto";
// import DashboardCard from "./DashboardCard.vue";

// Dummy reactive data (replace with API calls or props)
const totalPassengersThisMonth = ref(300);
const totalAmountThisMonth = ref(15000);
const totalTripsThisMonth = ref(120);
const currentMonth = new Date().toLocaleString("default", { month: "long" });
const successMessage = ref(""); // Could come from Vuex or API

const topBranches = ref([
  {
    name: "Dhaka Central",
    manager: "S A Rafi",
    managerPhoto: "https://i.pravatar.cc/50?img=1",
    sales: "1,200+",
  },
  {
    name: "Chittagong Hub",
    manager: "Rasel Khan",
    managerPhoto: "https://i.pravatar.cc/50?img=2",
    sales: "1,100+",
  },
  {
    name: "Sylhet Point",
    manager: "Mehedi Hasan",
    managerPhoto: "https://i.pravatar.cc/50?img=3",
    sales: "980+",
  },
  {
    name: "Rajshahi Zone",
    manager: "Anis Khan",
    managerPhoto: "https://i.pravatar.cc/50?img=4",
    sales: "920+",
  },
  {
    name: "Khulna Station",
    manager: "Tariq Rahman",
    managerPhoto: "https://i.pravatar.cc/50?img=5",
    sales: "850+",
  },
]);

const reservations = ref([
  {
    name: "Rafi",
    schedule_id: 101,
    coach_no: "A1",
    route: "Dhaka-Chittagong",
    selected_seats: 2,
    total: 2000,
    status: "Confirmed",
  },
  {
    name: "Mehedi",
    schedule_id: 102,
    coach_no: "B3",
    route: "Dhaka-Sylhet",
    selected_seats: 1,
    total: 1000,
    status: "Pending",
  },
  // Add more or fetch from API
]);

// Chart
const monthlyChart = ref(null);

onMounted(() => {
  if (!monthlyChart.value) return;
  const ctx = monthlyChart.value.getContext("2d");

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
      datasets: [
        {
          label: "Bookings",
          data: [120, 95, 110, 130, 125, 140, 100, 115, 90, 105, 98, 150],
          backgroundColor: "#ff0000",
          borderRadius: 5,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true, grid: { color: "#eee" } },
        x: { grid: { display: false } },
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: "#2c3e50",
          titleColor: "#fff",
          bodyColor: "#fff",
        },
      },
    },
  });
});
</script>

<style scoped>
/* Reuse your original styles */
.hello {
  border: 0.3px solid var(--main-color);
  background: linear-gradient(
    135deg,
    #ffffff 50%,
    #ff01013b 75%,
    #ff0101b4 100%
  );
  border-radius: 0.6rem;
  position: relative;
  margin-bottom: 10px;
  margin-top: 10px;
}
.style-hello,
.style-hello-graph {
  position: absolute;
  width: 100.1%;
  height: 50px;
  left: 0;
  bottom: -10px;
  z-index: -3;
  border: 2px solid var(--main-color);
  border-radius: 0.6rem;
  background-color: var(--main-color);
}
.p-top-saler,
.p-graph {
  position: relative;
}
.style-hello-topsale {
  position: absolute;
  width: 96.8%;
  height: 50px;
  left: 1.5%;
  bottom: -2px;
  z-index: -3;
  border: 0.3px solid var(--main-color);
  border-radius: 0.6rem;
  background-color: var(--main-color);
}
.top-saler-table-title {
  font-weight: 600;
  text-align: center;
  color: #333;
}
.top-saler-profile-pic {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #ddd;
}
.top-saler-text-main {
  color: #ff0000;
  font-weight: 500;
}
.table th {
  background: #f0f0f0;
}
.table-hover tbody tr:hover {
  background-color: #f8a9a9;
}
.graph,
.top-saler {
  background: var(--bg-color);
  border: 0.3px solid var(--main-color);
  border-radius: 15px;
  position: relative;
  height: 358px;
  width: 100%;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.chart-container {
  background: var(--bg-color);
  position: absolute;
  height: 300px;
  width: 95%;
}

.index-card{
  border: .5px solid var(--main-color);
  border-radius: 1rem;
  padding: 1rem;
  background-color: var(--bg-color);
}
/* ------------------------------
   Table Styling
------------------------------ */
#table-same {
  border-collapse: separate;
  border-spacing: 0;
  width: 100%;
  border-radius: 12px;
  overflow: hidden;
}

/* Table Header */
#table-same thead th {
  background-color: #ff0000;
  color: #fff;
  text-align: center;
  font-weight: 600;
  font-size: 15px;
  letter-spacing: 0.5px;
  padding: 12px 10px;
}

/* Table Body */
#table-same tbody td,
#table-same tbody th {
  text-align: center;
  vertical-align: middle;
  padding: 10px;
  border-bottom: 1px solid #eee;
  font-size: 14px;
  color: #444;
}

/* Hover Row Effect */
#table-same tbody tr:hover {
  background-color: #f6f6e1;
  transition: background-color 0.2s ease-in-out;
}

/* Empty State */
#table-same td.text-muted {
  color: #999;
  font-style: italic;
}

/* ------------------------------
   Buttons
------------------------------ */
/* .btn{
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  padding: 6px 14px;
  color: white;
  transition: all 0.2s ease-in-out;
} */

.btn-success {
  background-color: #780116;
  border-color: #780116;
}

.btn-success:hover {
  background-color: #ff0000;
  border-color: #ff0000;
}

.btn-info {
  background-color: #ff0000;
  border: none;
  color: #fff;
}

.btn-info:hover {
  background-color: #cc0000;
}

.btn-warning {
  background-color: #ffb300;
  border: none;
  color: #fff;
}

.btn-warning:hover {
  background-color: #ff9500;
}

.btn-danger {
  background-color: #780116;
  border: none;
}

.btn-danger:hover {
  background-color: #ff0000;
}

/* ------------------------------
   Table Title & Header Row
------------------------------ */
h2.text-center {
  font-weight: 700;
  color: #780116;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

/* ------------------------------
   Alert Message
------------------------------ */
.alert-success {
  background-color: #d1f7d1;
  border: 1px solid #9be79b;
  color: #217a21;
  font-weight: 500;
  border-radius: 10px;
  text-align: center;
}
</style>
