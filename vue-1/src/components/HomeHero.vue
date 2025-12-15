<script setup>
  
</script>

<template>
  <div class="container-fluid master-background">
    <div class="row justify-content-between w-100">
      <div class="col-md-6">
        <div class="heading">
          <h1>RunStar is Near, <br /> Start your journey Here,</h1>
          <p>Book fast, Ride easy and cheer!</p>
        </div>
      </div>

      <div class="col-md-4 p-5 form-part position-relative">
        <div class="light1"></div>
        <div class="light2"></div>

        <slot></slot>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* ===============================
   HERO SECTION – RESPONSIVE CSS
   =============================== */

   .master-background {
  background-image: url("/image/bus-2.png");
  background-size: cover;
  background-position: center;
  min-height: 80vh;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: -60px;
  padding: 100px 10px 20px 10px;
  overflow-x: hidden;
}

/* ===============================
   HEADING
   =============================== */

.heading {
  margin-top: 6rem;
}

.heading h1 {
  color: #fffdf7;
  font-size: 3.3rem;
  font-family: "El Messiri", sans-serif;
  font-weight: bolder;
  line-height: 1.2;
}

.heading p {
  color: #fffdf7;
  font-family: "Roboto", sans-serif;
  font-size: 1.2rem;
}

/* ===============================
   FORM PART
   =============================== */

.form-part {
  position: relative;
}

/* ===============================
   DECORATIVE LIGHTS
   =============================== */

.light1,
.light2 {
  position: absolute;
  border-radius: 50%;
  background-color: white;
  animation: pulse 1.2s ease-in-out infinite;
}

.light1 {
  height: 0.7rem;
  width: 0.7rem;
  bottom: 28%;
  left: -44%;
}

.light2 {
  height: 0.5rem;
  width: 0.5rem;
  bottom: 28%;
  left: -1%;
}

@keyframes pulse {
  0%, 100% {
    box-shadow: 0 0 20px 5px rgb(247, 240, 209);
  }
  50% {
    box-shadow: 0 0 20px 15px rgb(247, 240, 209);
  }
}

/* ===============================
   TABLET & MOBILE
   =============================== */

@media (max-width: 768px) {
  .master-background {
    margin-top: 0;
    padding: 60px 15px 30px;
    min-height: auto;
  }

  .heading {
    margin-top: 2rem;
    text-align: center;
  }

  .heading h1 {
    font-size: 2rem;
  }

  .heading p {
    font-size: 1rem;
  }

  .form-part {
    padding: 20px !important;
    margin-top: 20px;
  }

  /* Hide decorative lights on mobile */
  .light1,
  .light2 {
    display: none;
  }
}

/* ===============================
   SMALL MOBILE DEVICES
   =============================== */

@media (max-width: 480px) {
  .heading h1 {
    font-size: 1.7rem;
  }

  .heading p {
    font-size: 0.95rem;
  }
}

</style>
