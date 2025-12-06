<script setup></script>

<template>
  <div class="container-fluid mt-3">

    <h1 class="text-center fw-bold mb-3" style="font-family:'El Messiri';">
      Our Variations
    </h1>

    <div class="row g-3">
      <ServiceCard img="/image/bus-6.png" title="Standard Non-AC" desc="Affordable travel with simple comfort." />
      <ServiceCard img="/image/bus-7.png" title="AC Coach" desc="Affordable travel with comfort." />
    </div>

    <div class="row g-3 mb-5">
      <ServiceCard img="/image/bus-8.png" title="Luxurious" desc="Affordable travel with super comfort." />
      <ServiceCard img="/image/bus-10.png" title="Sleeper Coach" desc="Affordable travel with dreaming." />
    </div>

  </div>
</template>

<script setup>
  
</script>

<style>
  .service-col {
  position: relative;
}

.service-card {
  border-radius: 1rem;
  z-index: -1;
  overflow: hidden;
}

.service-img {
  border-radius: 1rem;
  width: 100%;
  height: 20rem !important;
  transition: transform 0.5s ease;
}

.service-card:hover .service-img {
  transform: scale(1.1);
}

.service-ins {
  width: 70%;
  text-align: center;
  height: auto;
  border-radius: 1rem;
  position: absolute;
  bottom: -2%;
  left: 15%;
  background: var(--bg-color);
  padding: 1rem;
  border: 1px solid var(--main-color);
  z-index: 999;
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

@media (max-width: 768px) {
  .service-ins {
    width: 80%;
    left: 10%;
    bottom: -8%;
  }
}
</style>
