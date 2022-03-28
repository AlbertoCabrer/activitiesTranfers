<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
    

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
img {
  max-width: 100%;
}
.carousel-container {
  width: 1100px;
  margin: 50px auto;
  position: relative;
  border-left-width: 4px;
  border-left-style: solid;
  border-left-color: #aaa;
}
@media (max-width: 768px) {
  .carousel-container {
    width: 95%;
  }
}
.inner-carousel {
  width: 100%;
  height: 400px;
  overflow: hidden;
}
.track {
  display: inline-flex;
  height: 100%;
  transition: transform 0.2s ease-in-out;
}
.card-container {
  width: 275px;
  height: 400px;
  flex-shrink: 0;
  padding-right: 10px;
}
@media (max-width: 768px) {
  .card-container {
    width: 184px;
  }
}
.card {
  width: 100%;
  height: 100%;
  /*display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;*/
  background-position: center bottom;
  background-size: center;
  background-repeat: no-repeat;
  border-radius: 10px;
}
.card1 {
  background-image: url("https://cdn.pixabay.com/photo/2018/08/21/23/29/fog-3622519_960_720.jpg");
}
.card2 {
  background-image: url("https://cdn.pixabay.com/photo/2016/11/14/04/45/elephant-1822636_960_720.jpg");
}
.card3 {
  background-image: url("https://cdn.pixabay.com/photo/2016/08/11/23/48/italy-1587287_960_720.jpg");
}
.card4 {
  background-image: url("https://cdn.pixabay.com/photo/2014/08/29/03/02/horse-430441_960_720.jpg");
}
.card5 {
  background-image: url("https://cdn.pixabay.com/photo/2014/12/08/17/52/mare-561221_960_720.jpg");
}
.card6 {
  background-image: url("https://cdn.pixabay.com/photo/2017/02/08/17/24/butterfly-2049567_960_720.jpg");
}
.card7 {
  background-image: url("https://cdn.pixabay.com/photo/2017/11/30/22/00/pumpkin-2989569_960_720.jpg");
}
.card8 {
  background-image: url("https://cdn.pixabay.com/photo/2016/07/24/22/25/woman-1539416_960_720.jpg");
}
.card9 {
  background-image: url("https://cdn.pixabay.com/photo/2017/11/15/09/28/music-player-2951399_960_720.jpg");
}
.card10 {
  background-image: url("https://cdn.pixabay.com/photo/2018/02/23/04/38/laptop-3174729_960_720.jpg");
}
.card11 {
  background-image: url("https://cdn.pixabay.com/photo/2016/03/09/09/30/woman-1245817_960_720.jpg");
}
.card12 {
  background-image: url("https://cdn.pixabay.com/photo/2016/11/08/05/31/adorable-1807544_960_720.jpg");
}

.nav button {
  position: absolute;
  top: 50%;
  transform: translatey(-50%);
  width: 60px;
  height: 60px;
  border-radius: 50%;
  outline: none;
  border: 1px solid #000;
  cursor: pointer;
}
.nav .prev {
  left: -30px;
  display: none;
}
.nav .prev.show {
  display: block;
}
.nav .next {
  right: -30px;
}
.nav .next.hide {
  display: none;
}


</style>
</head>


<body>



<div class="carousel-container">
  <div class="inner-carousel">
    <div class="track">
      <div class="card-container">
        <div class="card card1"></div>
      </div>
      <div class="card-container">
        <div class="card card2"></div>
      </div>
      <div class="card-container">
        <div class="card card3"></div>
      </div>
      <div class="card-container">
        <div class="card card4"></div>
      </div>
      <div class="card-container">
        <div class="card card5"></div>
      </div>
      <div class="card-container">
        <div class="card card6"></div>
      </div>
      <div class="card-container">
        <div class="card card7"></div>
      </div>
      <div class="card-container">
        <div class="card card8"></div>
      </div>
      <div class="card-container">
        <div class="card card9"></div>
      </div>
      <div class="card-container">
        <div class="card card10">1</div>
      </div>
      <div class="card-container">
        <div class="card card11">1</div>
      </div>
      <div class="card-container">
        <div class="card card12">12</div>
      </div>
    </div>
    <div class="nav">
      <button class="prev"><i class="fas fa-arrow-left fa-2x"></i></button>
      <button class="next"><i class="fas fa-arrow-right fa-2x"></i></button>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<script>
    const prev = document.querySelector(".prev");
const next = document.querySelector(".next");
const carousel = document.querySelector(".carousel-container");
const track = document.querySelector(".track");
let width = carousel.offsetWidth;
let index = 0;
window.addEventListener("resize", function () {
  width = carousel.offsetWidth;
});
next.addEventListener("click", function (e) {
  e.preventDefault();
  index = index + 1;
  prev.classList.add("show");
  track.style.transform = "translateX(" + index * -width + "px)";
  if (track.offsetWidth - index * width < index * width) {
    next.classList.add("hide");
  }
});
prev.addEventListener("click", function () {
  index = index - 1;
  next.classList.remove("hide");
  if (index === 0) {
    prev.classList.remove("show");
  }
  track.style.transform = "translateX(" + index * -width + "px)";
});
</script>
    
</body>
</html>