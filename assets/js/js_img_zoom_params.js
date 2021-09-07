// require.resolve("js-image-zoom");
  var options1 = {
    width: 400,
    scale: 1.3,
    offset: {vertical: 0, horizontal: 10}
  };

  // If the width and height of the image are not known or to adjust the image to the container of it
  var options2 = {
    fillContainer: true,
    offset: {vertical: 0, horizontal: 10}
  };
  var image_container = document.getElementById("img-container");
  // console.log(options2);
  new ImageZoom(image_container, options1);
