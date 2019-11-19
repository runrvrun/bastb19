// Navigation toggle
/* $('.toggle').click(function() {
  if ($('#overlay.open')) {
    $(this).toggleClass('active');
    $('#overlay').toggleClass('open');
  }
}); */

var sWidth = $(window).width();
var sHeight = $(window).height();

$(function() {
  // Dropdown submenu toggle on hover (desktop screen only)
  $('ul.nav li.dropdown').hover(function() {
    if ((sWidth >= 1024 ) && (sHeight >= 768)) {
      $(this).find('.dropdown-menu')
        .stop(true, true)
        .fadeIn(500);
    }
  }, function() {
    if ((sWidth >= 1024 ) && (sHeight >= 768)) {
      $(this).find('.dropdown-menu')
        .stop(true, true)
        .fadeOut(500);
    }
  });

  // Datatables
  if ($("table.sortable").length > 0)
    $("table.sortable").dataTable({
      "iDisplayLength": 5,
      "aLengthMenu": [5, 10, 25, 50, 100],
      "sPaginationType": "full_numbers",
      "aoColumns": [
        null,
        null,
        null,
        {
          "bSortable": false
        }
      ]
    });

  // Uniform
  if ($("input[type=checkbox]").length > 0 || $("input[type=radio]").length > 0)
    $("input[type=checkbox], input[type=radio]").not('.ibutton').uniform();

  // YouTube Player
  if ($(".player").length > 0) {
    $(".player").mb_YTPlayer();

    $('#video-play').click(function(e) {
      e.preventDefault();
      if ($(this).hasClass('fa-play')) {
        $('.player').playYTP();
      } else {
        $('.player').pauseYTP();
      }
      $(this).toggleClass('fa-play fa-pause');
      return false;
    });

    $('#video-volume').click(function(e) {
      e.preventDefault();
      $('.player').toggleVolume();
      $(this).toggleClass('fa-volume-off fa-volume-up');
      return false;
    });
  }

});