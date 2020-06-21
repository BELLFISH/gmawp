'use strict';

var breakPoint = 960;
var $window = $(window);
var $header = $('header');
var $headerSpBtn = $('.nav-btn');
var $wrapper = $('.wrapper');
var window_w;
$window.on('load', function () {
  setAspectRatio();
});
$window.on('load resize', function () {
  window_w = $window.outerWidth();
  setHeaderSp();
  setCrumbsOverflow();
}); // trigger
// --------------------------------------------------

$('input[name="submit"]').on('click', function () {
  window.location.href = '#contact';
});
$headerSpBtn.on('click', function () {
  var parent = $header;
  parent.removeClass('is-set');

  if (parent.hasClass('is-open')) {
    parent.removeClass('is-open').addClass('is-close');
  } else {
    parent.addClass('is-open').removeClass('is-close');
  }
}); // function
// --------------------------------------------------

var setHeaderSp = function setHeaderSp() {
  if (window_w < breakPoint) {
    $header.addClass('js-header-sp').addClass('is-set');
  } else {
    $header.removeClass('js-header-sp').removeClass('is-set');
  }
};

var setAspectRatio = function setAspectRatio() {
  $('.js-aspectRatio img').each(function (index, element) {
    var $this = $(element);
    var image_width = $this.width();
    var image_height = $this.height();
    var image = new Image();

    if (image_width / image_height >= 1) {
      $this.parent().addClass('landscape');
    } else {
      $this.parent().addClass('portrait');
    }
  });
};

var setCrumbsOverflow = function setCrumbsOverflow() {
  var $parent = $('.crumbs');
  var parent_w = $parent.outerWidth();
  var $target = $parent.find('li:last-child');
  var $target_prev = $target.prevAll();
  $target.css({
    'width': '0'
  });
  var widthTotal = 0;
  $target_prev.each(function (index, element) {
    var $this = $(element);
    var target_w = $this.outerWidth();

    for (var i = 0; i < $this.length; i++) {
      widthTotal = widthTotal + target_w;
    }
  });

  if (window_w < breakPoint) {
    $target.css({
      'width': 'calc(' + parent_w + 'px - ' + widthTotal + 'px - 16%)'
    });
  } else {
    $target.css({
      'width': 'auto'
    });
  }
};

var ANIMATE = {
  init: function init() {
    this.controller = new ScrollMagic.Controller(), this.animateSlideUp(), this.animateSlideDown();
  },
  animateSlideUp: function animateSlideUp() {
    var _this = this;

    this.ANIMATION_CLASS = 'animated';
    var $target = document.querySelectorAll('.js-animation-slideUp:not(.animated)');

    if ($target.length === null) {
      return;
    }

    var _loop = function _loop(i) {
      var scene = new ScrollMagic.Scene({
        triggerElement: $target[i],
        triggerHook: 'onEnter',
        reverse: false,
        offset: 140
      }).addTo(_this.controller);
      scene.on('enter', function () {
        $target[i].classList.add(_this.ANIMATION_CLASS);
      });
      scene.on('end', function () {
        scene.destroy(true);
      });
    };

    for (var i = 0; i < $target.length; i++) {
      _loop(i);
    }
  },
  animateSlideDown: function animateSlideDown() {
    var _this2 = this;

    this.ANIMATION_CLASS = 'animated';
    var $target = $('header');

    if ($target.length === null) {
      return;
    }

    var _loop2 = function _loop2(i) {
      var scene = new ScrollMagic.Scene({
        triggerElement: $target[i],
        triggerHook: 'onEnter',
        reverse: false,
        offset: 140
      }).addTo(_this2.controller);
      scene.on('enter', function () {
        $target[i].classList.add(_this2.ANIMATION_CLASS);
      });
      scene.on('end', function () {
        scene.destroy(true);
      });
    };

    for (var i = 0; i < $target.length; i++) {
      _loop2(i);
    }
  }
};
ANIMATE.init();