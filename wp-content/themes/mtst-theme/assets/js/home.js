const swiperFotos = function(){
  return swiper = new Swiper('.swiper-foto', {
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

swiperFotos();

const swiperVideos = function(){
  return swiper = new Swiper('.swiper-videos', {
    // Default parameters
    slidesPerView: 1,
    spaceBetween: 10,
    speed: 500,
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true,
    },
  })
}

swiperVideos();

const swiperFotosMobile = function(){
  return swiper = new Swiper('.swiper-fotos-mob', {
    // Default parameters
    slidesPerView: 1,
    spaceBetween: 10,
    speed: 500,
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true,
    },
  })
}

swiperFotosMobile();

const swiperBanners = function(){
  return swiper = new Swiper('.swiper-banners', {
    // Default parameters
    slidesPerView: 1,
    spaceBetween: 10,
    speed: 500,
    loop: true,
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

swiperBanners();

$(".nav-link").hover(function () {
  $(".nav-link.menu-home").toggleClass("active");
});