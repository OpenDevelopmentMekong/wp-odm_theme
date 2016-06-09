var gulp = require('gulp'),
	less = require('gulp-less'),
	uglify = require('gulp-uglify'),
	concat = require('gulp-concat'),
	cleanCSS = require('gulp-clean-css'),
	runSequence = require('run-sequence');

var LESS_PATH = 'less/';
var JS_PATH = 'js/';
var CSS_PATH = 'css/';
var DEST_PATH = 'dist/'; //Temp path will change later
var CSS_DIST_PATH = DEST_PATH + 'css/';
var JS_DIST_PATH = DEST_PATH + 'js/';


gulp.task('default', function() {
  runSequence(
  	'build-less',
  	'build-styles'
  );
});

gulp.task('build-less', function(){
	return gulp.src(LESS_PATH + 'odm.less')
	.pipe(less())
	.pipe(cleanCSS())
	.pipe(gulp.dest(DEST_PATH));
});

gulp.task('build-styles', function(){
	return gulp.src([

	])
	.pipe(cleanCSS())
	.pipe(concat('style.min.css'))
	.pipe(gulp.dest(DEST_PATH));
});

gulp.task('build-scripts', function(){
	return gulp.src([

	])
	.pipe(uglify())
	.pipe(concat('script.min.js', {newLine: ';'}))
	.pipe(gulp.dest(DEST_PATH));
});

gulp.task('watch', function(){
	gulp.watch(LESS_PATH + '/*less', ['build-less', 'build-styles']);
	gulp.watch(CSS_PATH + '/*.css', ['build-styles']);
	gulp.watch(JS_PATH + '/*.js', ['build-scripts']);
});