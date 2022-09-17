let accMo = document.getElementsByClassName("mo__ac");
let i;
for (i = 0; i < accMo.length; i++) {
  accMo[i].addEventListener("click", function () {
    if (window.innerWidth < 960) {
      this.classList.toggle("mo__active");
    } else {
    }
  });
}

let pobToggle = document.getElementsByClassName("personal-order-box");
let b;
for (b = 0; b < pobToggle.length; b++) {
  pobToggle[b].addEventListener("click", function () {
    this.classList.toggle("active");
  });
}

$.fn.nextOrFirst = function (selector) {
  var next = this.next(selector);
  return next.length ? next : this.prevAll(selector).last();
};

$.fn.prevOrLast = function (selector) {
  var prev = this.prev(selector);
  return prev.length ? prev : this.nextAll(selector).last();
};

function slider() {
  var activeSlide = $(".slider__active");

  activeSlide
    .removeClass("slider__active")
    .nextOrFirst()
    .addClass("slider__active");
}
setInterval(slider, 6000);

function controls() {
  var control = $(".slider__controls");

  control.on("click", ".slider__prev", function () {
    $(".slider__active")
      .removeClass("slider__active")
      .prevOrLast()
      .addClass("slider__active");
  });

  control.on("click", ".slider__next", function () {
    $(".slider__active")
      .removeClass("slider__active")
      .nextOrFirst()
      .addClass("slider__active");
  });
}
controls();

$(".slider__point").on("click", function () {
  $(".slider__slide").removeClass("slider__active");
  var slide = $(this).data("slide");
  $('.slider__slide[data-slide="' + slide + '"]').addClass("slider__active");
  console.log(slide);
});
// Toggle menu suffix[Mo]

let toggleMo = document.getElementsByClassName("main-menu")[0];
let buttonMo = document.getElementsByClassName("burger")[0];

buttonMo.addEventListener("click", function () {
  toggleMo.classList.toggle("menu-show");
});

// Toggle filters

let toggleFilter = document.getElementsByClassName("filter-toggle")[0];
let filterWrap = document.getElementsByClassName("filter")[0];

toggleFilter.addEventListener(
  "click",
  function () {
    filterWrap.classList.toggle("filter-show");
    toggleFilter.classList.toggle("filter-toggle-show");
  }
);
