'use strict';

const breakPoint = 960;

const $window      = $(window);
const $header      = $('header');
const $headerSpBtn = $('.nav-btn');
const $wrapper     = $('.wrapper');

let window_w;

$window.on('load',() => {
  setAspectRatio();
});

$window.on('load resize',() => {
  window_w = $window.outerWidth();
  setHeaderSp();
  setCrumbsOverflow();
});

// trigger
// --------------------------------------------------
$('input[name="submit"]').on('click',() => {
  window.location.href = '#contact';
});

$headerSpBtn.on('click',() => {
  const parent = $header;
  parent.removeClass('is-set');
  if(parent.hasClass('is-open')){
    parent.removeClass('is-open').addClass('is-close');
  }else{
    parent.addClass('is-open').removeClass('is-close');
  }
});

// function
// --------------------------------------------------
const setHeaderSp = () => {
  if(window_w < breakPoint){
    $header.addClass('js-header-sp').addClass('is-set');
  }else{
    $header.removeClass('js-header-sp').removeClass('is-set');
  }
}

const setAspectRatio = () => {
  $('.js-aspectRatio img').each((index,element) => {
    const $this = $(element);
    const image_width  = $this.width();
    const image_height = $this.height();
    const image = new Image();
    if((image_width / image_height) >= 1) {
      $this.parent().addClass('landscape');
    }else{
      $this.parent().addClass('portrait');
    }
  })
}

const setCrumbsOverflow = () => {
  const $parent = $('.crumbs');
  const parent_w = $parent.outerWidth();
  const $target = $parent.find('li:last-child');
  const $target_prev = $target.prevAll();
  $target.css({'width':'0'});
  let widthTotal = 0;
  $target_prev.each((index,element) => {
    const $this = $(element);
    const target_w = $this.outerWidth();
    for(let i =0; i < $this.length; i++){
      widthTotal = widthTotal + target_w;
    }
  })
  if(window_w < breakPoint){
    $target.css({'width':'calc('+parent_w+'px - '+widthTotal+'px - 16%)'});
  }else{
    $target.css({'width':'auto'});
  }
}

const ANIMATE = {
  init: function(){
    this.controller = new ScrollMagic.Controller,
    this.animateSlideUp(),
    this.animateSlideDown()
  },
  animateSlideUp: function(){
    this.ANIMATION_CLASS = 'animated';
    let $target = document.querySelectorAll('.js-animation-slideUp:not(.animated)');
    if($target.length === null){ return; }
    for(let i = 0; i < $target.length; i++){
      const scene = new ScrollMagic.Scene({
        triggerElement: $target[i],
        triggerHook: 'onEnter',
        reverse: false,
        offset: 140
      })
      .addTo(this.controller);
      scene.on('enter',() => {
        $target[i].classList.add(this.ANIMATION_CLASS);
      });
      scene.on('end',() => {
        scene.destroy(true);
      });
    }
  },
  animateSlideDown: function(){
    this.ANIMATION_CLASS = 'animated';
    let $target = $('header');
    if($target.length === null){ return; }
    for(let i = 0; i < $target.length; i++){
      const scene = new ScrollMagic.Scene({
        triggerElement: $target[i],
        triggerHook: 'onEnter',
        reverse: false,
        offset: 140
      })
      .addTo(this.controller);
      scene.on('enter',() => {
        $target[i].classList.add(this.ANIMATION_CLASS);
      });
      scene.on('end',() => {
        scene.destroy(true);
      });
    }
  }
}
ANIMATE.init();





