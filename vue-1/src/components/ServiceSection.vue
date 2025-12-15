<template>
  <!-- All services section -->
  <section class="container-fluid mt-3 service-section">

    <!-- Title -->
    <div class="col-12 text-center mb-2">
      <h1 class="fw-bold service-title">
        Our Variations
      </h1>
    </div>

    <!-- First Row -->
    <div class="row g-3">
      <div class="col-md-6 p-5 service-col">
        <div class="service-card">
          <img class="img-fluid service-img" src="/image/bus-6.png" alt="Standard Non-AC">
        </div>
        <div class="service-ins">
          <h3>Standard Non-AC</h3>
          <p>Affordable travel with simple comfort.</p>
        </div>
      </div>

      <div class="col-md-6 p-5 service-col">
        <div class="service-card">
          <img class="img-fluid service-img" src="/image/bus-7.png" alt="AC Coach">
        </div>
        <div class="service-ins">
          <h3>AC Coach</h3>
          <p>Affordable travel with comfort.</p>
        </div>
      </div>
    </div>

    <!-- Second Row -->
    <div class="row g-3 mb-5">
      <div class="col-md-6 p-5 service-col">
        <div class="service-card">
          <img class="img-fluid service-img" src="/image/bus-8.png" alt="Luxurious">
        </div>
        <div class="service-ins">
          <h3>Luxurious</h3>
          <p>Affordable travel with super comfort.</p>
        </div>
      </div>

      <div class="col-md-6 p-5 service-col">
        <div class="service-card">
          <img class="img-fluid service-img" src="/image/bus-10.png" alt="Sleeper Coach">
        </div>
        <div class="service-ins">
          <h3>Sleeper Coach</h3>
          <p>Affordable travel with dreaming.</p>
        </div>
      </div>
    </div>

  </section>
</template>

<script setup>
/* No JS needed – static section */
</script>

<style scoped>
/* ===============================
   SERVICE SECTION
   =============================== */

.service-section {
  position: relative;
  z-index: 1;
}

/* ===============================
   TITLE
   =============================== */

.service-title {
  font-family: "El Messiri", sans-serif;
  color: var(--second-color);
}

/* ===============================
   CARD LAYOUT
   =============================== */

.service-col {
  position: relative;
}

.service-card {
  border-radius: 1rem;
  overflow: hidden;
  position: relative;
  z-index: 1; /* FIXED (no negative z-index) */
}

.service-img {
  border-radius: 1rem;
  width: 100%;
  height: 20rem;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.service-card:hover .service-img {
  transform: scale(1.1);
}

/* ===============================
   CARD INFO BOX
   =============================== */

.service-ins {
  width: 70%;
  text-align: center;
  border-radius: 1rem;
  position: absolute;
  bottom: -2%;
  left: 15%;
  background: var(--bg-color);
  padding: 1rem;
  border: 1px solid var(--main-color);
  z-index: 5;
  color: #220901;
  box-shadow: 0 10px 20px rgba(248, 40, 40, 0.317);
}

.service-ins h3 {
  font-family: "El Messiri", sans-serif;
  font-weight: 700;
  color: var(--second-color);
}

.service-ins p {
  font-family: "Poppins", sans-serif;
  font-size: 0.95rem;
  margin: 0;
  color: #555;
}

/* ===============================
   RESPONSIVE
   =============================== */

   @media (max-width: 768px) {
  .service-img {
    height: 12rem; /* ↓ reduced for mobile */
  }

  .service-ins {
    width: 83%;
    left: 7.5%;
    bottom: -8%;
  }
}

@media (max-width: 480px) {
  .service-img {
    height: 12rem; /* perfect for small phones */
  }

  .service-ins h3 {
    font-size: 1rem;
  }

  .service-ins p {
    font-size: 0.85rem;
  }
}

</style>
