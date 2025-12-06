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
/* তোমার একই CSS এখানে পেস্ট করা হয়েছে */
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
}

.heading {
  margin-top: 6rem;
}
.heading h1 {
  color: #fffdf7;
  font-size: 3.3rem;
  font-family: "El Messiri", sans-serif;
  font-weight: bolder;
}
.heading p {
  color: #fffdf7;
  font-family: "Roboto", sans-serif;
  font-size: larger;
}

.light1,
.light2 {
  position: absolute;
  border-radius: 50%;
  background-color: white;
  animation: light1 .2s ease-in-out infinite;
}

.light1 {
  height: .7rem;
  width: .7rem;
  bottom: 28%;
  left: -44%;
}

.light2 {
  height: .5rem;
  width: .5rem;
  bottom: 28%;
  left: -1%;
}

@keyframes light1 {
  0%, 100% { box-shadow: 0 0 20px 5px rgb(247, 240, 209); }
  50% { box-shadow: 0 0 20px 15px rgb(247, 240, 209); }
}

@keyframes light2 {
  0%, 100% { box-shadow: 0 0 20px 4px rgb(247, 240, 209); }
  50% { box-shadow: 0 0 20px 10px rgb(247, 240, 209); }
}
</style>
