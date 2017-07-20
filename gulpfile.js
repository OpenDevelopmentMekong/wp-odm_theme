var gulp = require('gulp'),
	less = require('gulp-less'),
	uglify = require('gulp-uglify'),
	concat = require('gulp-concat'),
	cleanCSS = require('gulp-clean-css'),
	runSequence = require('run-sequence');

var LESS_PATH = 'less/';
var JS_PATH = 'js/';
var LIB_PATH = 'lib/';
var CSS_PATH = 'css/';
var DEST_PATH = 'dist/'; //Temp path will change later
var CSS_DIST_PATH = DEST_PATH + 'css/';
var JS_DIST_PATH = DEST_PATH + 'js/';

gulp.task('default', function() {
  runSequence(
  	'build-less',
  	'build-styles',
    'build-scripts'
  );
});

gulp.task('build-less', function(){
	return gulp.src([
		LESS_PATH + 'odm.less',
		LESS_PATH + 'cambodia.less',
		LESS_PATH + 'thailand.less',
		LESS_PATH + 'laos.less',
		LESS_PATH + 'myanmar.less',
		LESS_PATH + 'vietnam.less'
	])
	.pipe(less())
	.pipe(cleanCSS())
	.pipe(gulp.dest(CSS_DIST_PATH));
});

gulp.task('build-styles', function(){
	return gulp.src([
    'lib/css/jquery.mCustomScrollbar.min.css',
    'lib/css/responsive.dataTables.css',
    'lib/css/sticky.css'
	])
	.pipe(cleanCSS())
	.pipe(concat('extra.min.css'))
	.pipe(gulp.dest(CSS_DIST_PATH));
});

gulp.task('build-scripts', function(){
	return gulp.src([
    'lib/js/*.js'
	])
	.pipe(uglify())
	.pipe(concat('scripts.min.js', {newLine: ';'}))
	.pipe(gulp.dest(JS_DIST_PATH));
});

gulp.task('watch', function(){
	runSequence(
  	'build-less',
  	'build-styles',
    'build-scripts'
  );
	gulp.watch(LESS_PATH + '/*less', ['build-less', 'build-styles']);
	gulp.watch(CSS_PATH + '/*.css', ['build-styles']);
	gulp.watch(LIB_PATH + '/js/*.js', ['build-scripts']);
});
