$('.column').imagesLoaded(function () {
  $("#exzoom").exzoom({
    autoPlay: false,
  // thumbnail nav options
    "navWidth": 60,
    "navHeight": 60,
    "navItemNum": 5,
    "navItemMargin": 7,
    "navBorder": 1,
  // autoplay
    "autoPlay": true,
  // autoplay interval in milliseconds
    "autoPlayTimeout": 2000
  });
  $("#exzoom").removeClass('hidden')
});
