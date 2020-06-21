// setting
// --------------------------------------------------
const config = {
  proxyURL     : 'http://gmawp.wp/',
  serverPrefix : 'gma',
  startPath    : '/',
  // scssアウトプット形式 compressed or expanded
  scssOutPut   : 'compressed',
  // css圧縮するか
  cssMinify    : true,
}

const srcDir = 'src';
const pubDir = 'app/public/wp-content/themes/theme-gma';

const src = {
  php  : srcDir+'/**/*.php',
  scss : srcDir+'/**/*.scss',
  js   : [srcDir+'/**/*.js','!'+srcDir+'/**/assets/lib/*.js'],
  img  : [srcDir+'/**/*.jpg',srcDir+'/**/*.gif',srcDir+'/**/*.png'],
  svg  : srcDir+'/**/*.svg',
  copy : [
    srcDir+'/style.css',
    srcDir+'/**/assets/lib/*.css',
    srcDir+'/**/assets/lib/*.js',
  ],
}

const pub = [
  pubDir+'/**/*.php',
  pubDir+'/**/*.css',
  pubDir+'/**/*.js',
  pubDir+'/**/*.jpg',
  pubDir+'/**/*.gif',
  pubDir+'/**/*.png',
  pubDir+'/**/*.svg',
]

// plugin
// --------------------------------------------------
const gulp = require('gulp');

const del     = require('del');
const header  = require('gulp-header');
const replace = require('gulp-replace');
const gulpIf  = require('gulp-if');
const plumber = require('gulp-plumber');
const notify  = require('gulp-notify');
const rename  = require('gulp-rename');

const connectPHP  = require('gulp-connect-php');
const browserSync = require('browser-sync');

const sass         = require('gulp-sass');
const sassGlob     = require('gulp-sass-glob');
const autoPrefixer = require('gulp-autoprefixer');
const cleanCSS     = require('gulp-clean-css');

const babel = require('gulp-babel');

const imgMin    = require('gulp-imagemin');
const imgMinJPG = require('imagemin-jpeg-recompress');
const imgMinGIF = require('imagemin-gifsicle');
const imgMinPNG = require('imagemin-pngquant');
const svgMin    = require('gulp-svgmin');

// server
// --------------------------------------------------
gulp.task('server',(done) => {
  connectPHP.server({
    base: srcDir,
    watchOptions: {
      debounceDelay: 1000,
    },
  },function(){
    browserSync({
      proxy: config.proxyURL,
      logPrefix: config.serverPrefix,
      startPath: config.startPath,
      browser: '/Applications/Google Chrome.app',
      notify: false,
    });
  });
  done();
});

gulp.task('reload',(done) => {
  browserSync.reload();
  done();
});

// task
// --------------------------------------------------

// PHP
gulp.task('php',(done) => {
  gulp.src(src.php)
    .pipe(rename(function(path){ path.dirname+=''; }))
    .pipe(gulp.dest(pubDir));
  done();
});

// SCSS
gulp.task('css',(done) => {
  gulp.src(src.scss)
    .pipe(plumber({ errorHandler: notify.onError('CSS Error!! <%= error.message %>') }))
    .pipe(sassGlob())
    .pipe(sass({
      outputStyle: config.scssOutPut,
    }))
    .pipe(autoPrefixer({
      gird: true,
    }))
    .pipe(replace(/@charset "UTF-8";/g,''))
    .pipe(header('@charset "UTF-8";\n\n'))
    .pipe(gulpIf(config.cssMinify,cleanCSS({
      rebase: false,
      format: 'keep-breaks',
    })))
    .pipe(rename(function(path){ path.dirname+=''; }))
    .pipe(gulp.dest(pubDir));
  done();
});

// JS
gulp.task('js',(done) => {
  gulp.src(src.js)
    .pipe(plumber({ errorHandler: notify.onError('JavaScript Error!! <%= error.message %>') }))
    .pipe(babel({
      presets: ['@babel/preset-env']
    }))
    .pipe(rename(function(path){ path.dirname+=''; }))
    .pipe(gulp.dest(pubDir));
  done();
});

// JPG GIF PNG
gulp.task('img',(done) => {
  gulp.src(src.img)
    .pipe(imgMin([
      imgMinJPG(),
      imgMinGIF({
        interlaced: false,
        optimizationLevel: 3,
        colors: 180,
      }),
      imgMinPNG(),
    ]))
    .pipe(rename(function(path){ path.dirname+=''; }))
    .pipe(gulp.dest(pubDir));
  done();
});

// SVG
gulp.task('svg',(done) => {
  gulp.src(src.svg)
    .pipe(svgMin())
    .pipe(rename(function(path){ path.dirname+=''; }))
    .pipe(gulp.dest(pubDir));
  done();
});

// copy
gulp.task('copy',(done) => {
  gulp.src(src.copy)
    .pipe(rename(function(path){ path.dirname+=''; }))
    .pipe(gulp.dest(pubDir));
  done();
});

// 監視
// --------------------------------------------------
gulp.task('watchDevFiles',(done) => {
  gulp.watch(src.php,gulp.task('php'));
  gulp.watch(src.scss,gulp.task('css'));
  gulp.watch(src.js,gulp.task('js'));
  gulp.watch(src.img,gulp.task('img'));
  gulp.watch(src.svg,gulp.task('svg'));
  gulp.watch(src.copy,gulp.task('copy'));
  done();
});

gulp.task('watchPubFiles',(done) => {
  gulp.watch(pub,gulp.task('reload'));
  done();
});

// 実行
// --------------------------------------------------

// 削除
gulp.task('dele',(done) => {
  return del(pub,{force:true});
  done();
});

// default
gulp.task('default',gulp.series(
  gulp.parallel(
    'php','css','js','img','svg','copy'
  ),
  'server','watchDevFiles','watchPubFiles'
));