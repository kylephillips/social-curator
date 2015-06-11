var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefix = require('gulp-autoprefixer');
var livereload = require('gulp-livereload');
var notify = require('gulp-notify');
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

// Paths – Admin
var admin_scss = 'assets/scss/admin/*';
var admin_css = 'assets/css/admin/';
var admin_js_source = [
	'assets/js/admin/source/social-curator-admin.js',
	'assets/js/admin/source/social-curator-admin-modal.js'
]
var admin_js_compiled = 'assets/js/admin/';

// Paths - Public
var public_scss = 'assets/scss/public/*';
var public_css = 'assets/css/public/';
var public_js_source = [
	'assets/js/public/source/social-curator.js'
]
var public_js_compiled = 'assets/js/public/';


/**
* ------------------------------------------------------------------------
* Admin Tasks
* ------------------------------------------------------------------------
*/

// Styles
gulp.task('admin_styles', function(){
	return gulp.src(admin_scss)
		.pipe(sass({ outputStyle: 'compressed' }))
		.pipe(autoprefix('last 15 version'))
		.pipe(gulp.dest(admin_css))
		.pipe(livereload())
		.pipe(notify('Social Curator admin styles compiled & compressed.'));
});

// JS
gulp.task('admin_js', function(){
	return gulp.src(admin_js_source)
		.pipe(concat('social-curator-admin.js'))
		.pipe(gulp.dest(admin_js_compiled))
		.pipe(uglify())
		.pipe(gulp.dest(admin_js_compiled))
});


/**
* ------------------------------------------------------------------------
* Public Tasks
* ------------------------------------------------------------------------
*/

// Styles
gulp.task('public_styles', function(){
	return gulp.src(public_scss)
		.pipe(sass({ outputStyle: 'compressed' }))
		.pipe(autoprefix('last 15 version'))
		.pipe(gulp.dest(public_css))
		.pipe(livereload())
		.pipe(notify('Social Curator public styles compiled & compressed.'));
});

// JS
gulp.task('public_js', function(){
	return gulp.src(public_js_source)
		.pipe(concat('social-curator.js'))
		.pipe(gulp.dest(public_js_compiled))
		.pipe(uglify())
		.pipe(gulp.dest(public_js_compiled))
});


/**
* Watch Task
*/
gulp.task('watch', function(){
	livereload.listen(8000);
	gulp.watch(admin_scss, ['admin_styles']);
	gulp.watch(admin_js_source, ['admin_js']);
	gulp.watch(public_scss, ['public_styles']);
	gulp.watch(public_js_source, ['public_js']);
});


/**
* Default
*/
gulp.task('default', [
	'admin_styles', 
	'admin_js', 
	'public_styles', 
	'public_js', 
	'watch'
]);