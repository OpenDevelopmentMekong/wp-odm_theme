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


gulp.task('default', function() {
  runSequence(
  	'build-styles'
  );
});

gulp.task('build-styles', function(){
	return gulp.src(LESS_PATH + 'odm.less')
	.pipe(less())
	.pipe(cleanCSS())
	.pipe(gulp.dest(DEST_PATH));
});

gulp.task('build-scripts', function(){

});

gulp.task('watch', function(){

});