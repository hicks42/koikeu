var theImage = document.getElementById('featured');
// console.log(theImage);
var currentImage = theImage.src;
var defaultOptions = {
  width: 400,
  height: 250,
  zoomWidth: 500,
  img: currentImage,
  offset: { vertical: 0, horizontal: 10 },
  zoomPosition: "original"
};
var options;
resetOptions();

var container = document.getElementById('img_container');

window.imageZoom = new ImageZoom(container, options);

function resetOptions() {
  options = JSON.parse(JSON.stringify(defaultOptions)); // widely supported deep copy
}
