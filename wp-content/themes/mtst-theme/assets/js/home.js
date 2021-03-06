const swiperFotos = function(){
  return swiper = new Swiper('.swiper', {
    // Default parameters
    slidesPerView: 1,
    spaceBetween: 10,
    speed: 500,
    autoplay: {
      delay: 4000,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    // Responsive breakpoints
    breakpoints: {
      // when window width is >= 640px
      640: {
        slidesPerView: 1,
        spaceBetween: 10,
      },
      pagination: {
        el: '.swiper-pagination',
      },    
    }
  })
}

swiperFotos()

