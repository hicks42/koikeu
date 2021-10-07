/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import 'bootstrap';

// start the Stimulus application
import $ from 'jquery';
import { tooltip, Toast, Popover } from 'bootstrap';

// import './js/image_zoom';
// import './js/js_image_zoom';
// import './js/thumbnail_slider';

$(".custom-file-input").on("change", function (e) {
  var inputFile = e.currentTarget;
  $(inputFile)
    .parent()
    .find(".custom-file-label")
    .html(inputFile.files[0].name);
});
