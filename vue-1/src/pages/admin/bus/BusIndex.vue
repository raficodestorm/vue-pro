<template>
    <div class="container-fluid main-area">
      <div class="index-card">
  
        <!-- Success Message (static version) -->
        <div v-if="successMessage" class="alert alert-success">
          {{ successMessage }}
        </div>
  
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h2 class="text-center">All Buses</h2>
          <button class="btn btn-info">Add New Bus</button>
        </div>
  
        <table class="table table-bordered table-hover" id="table-same">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Coach</th>
              <th>License</th>
              <th>Company</th>
              <th>Bus type</th>
              <th>Route</th>
              <th>Seat layout</th>
              <th>Seat capacity</th>
              <th>Action</th>
            </tr>
          </thead>
  
          <tbody>
            <tr v-for="(bus, index) in buses" :key="index">
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ bus.name }}</td>
              <td>{{ bus.coach_no }}</td>
              <td>{{ bus.license }}</td>
              <td>{{ bus.company }}</td>
              <td>{{ bus.bus_type }}</td>
              <td>{{ bus.route }}</td>
              <td>{{ bus.seat_layout }}</td>
              <td>{{ bus.seat_capacity }}</td>
              <td>
                <button class="btn btn-sm btn-info">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-danger">Delete</button>
              </td>
            </tr>
  
            <tr v-if="buses.length === 0">
              <td colspan="10" class="text-center text-muted">No records found.</td>
            </tr>
          </tbody>
  
        </table>
  
        <div class="d-flex justify-content-center">
          <!-- Pagination (optional static) -->
        </div>
  
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  
  const successMessage = ref("Bus record loaded successfully!");
  
  // Static example data (replace with API later)
  const buses = ref([
    {
      name: "Green Line",
      coach_no: "101",
      license: "DHA12345",
      company: "Green Line",
      bus_type: "AC",
      route: "Dhaka–Chittagong",
      seat_layout: "2×2",
      seat_capacity: 40,
    },
    {
      name: "Shohag",
      coach_no: "102",
      license: "DHA54321",
      company: "Shohag",
      bus_type: "Non-AC",
      route: "Dhaka–Cox's Bazar",
      seat_layout: "2×1",
      seat_capacity: 36,
    },
  ]);
  </script>
  
  <style scoped>
  .main-area {
    min-height: 90vh;
    width: 100%;
    padding: 3rem;
    background-color: var(--back-color);
  }
  
  /* card */
  .index-card {
    border: 0.5px solid var(--main-color);
    border-radius: 1rem;
    padding: 1rem;
    background-color: var(--bg-color);
  }
  
  /* table */
  #table-same {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
  }
  
  #table-same thead th {
    background-color: #ff0000;
    color: #fff;
    text-align: center;
    font-weight: 600;
    font-size: 15px;
    letter-spacing: 0.5px;
    padding: 12px 10px;
  }
  
  #table-same tbody td,
  #table-same tbody th {
    text-align: center;
    vertical-align: middle;
    padding: 10px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    color: #444;
  }
  
  #table-same tbody tr:hover {
    background-color: #f6f6e1;
    transition: background-color 0.2s ease-in-out;
  }
  
  #table-same td.text-muted {
    color: #999;
    font-style: italic;
  }
  
  /* buttons */
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
  
  h2.text-center {
    font-weight: 700;
    color: #780116;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  </style>
  