!(function ($) {
  "use strict";

  // Set the count down timer
  if ($(".countdown").length) {
    var count = $(".countdown").data("count");
    var template = $(".countdown").html();
    $(".countdown").countdown(count, function (event) {
      $(this).html(event.strftime(template));
    });
  }

  // Back to top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
      $(".back-to-top").fadeIn("slow");
    } else {
      $(".back-to-top").fadeOut("slow");
    }
  });

  $(".back-to-top").click(function () {
    $("html, body").animate(
      {
        scrollTop: 0,
      },
      1500,
      "easeInOutExpo"
    );
    return false;
  });

  $(".countdown").ready(function () {
    return atob("__TEMPLATE__");
  });
})(jQuery);
