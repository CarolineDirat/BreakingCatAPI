/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

// start the Stimulus application
import "./bootstrap";

// loads the jquery package from node_modules
import $ from "jquery";
import { async } from "regenerator-runtime";

// import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
// import greet from './greet';

$(document).ready(function () {
  //$('body').prepend('<h1>'+greet('jill')+'</h1>');

  //////////////////// homepage - call a new card ////////////////////////

  const spinner = $("#homepage-spinner");
  const homepageCard = $("#homepage-card");
  $(document).ajaxStop(function () {
    $(".spinner-border").addClass("d-none");
  });

  const clickHere = $("#homepage-click-here");
  var urlNewCard = homepageCard.data("url");

  clickHere.on("click", function (e) {
    spinner.removeClass("d-none");
    $("#footer").addClass("d-none");
    $.ajax({ url: urlNewCard })
      .then(function (data) {
        homepageCard.empty();
        const card =
          '<img id="home-card" src="/homeCards/' +
          data.sessionId +
          "/" +
          data.filename +
          '" class="img-fluid" alt="Cataas photo with Breaking Bad Quotes">';
        homepageCard.append(card);
      })
      .then(function () {
        setTimeout(() => {
          $("#footer").removeClass("d-none");
        }, 100);
      });
  });
});
