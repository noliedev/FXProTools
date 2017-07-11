var gulp = require('gulp'),
	sass = require('gulp-sass'),
	concat = require('gulp-concat'),
	plumber = require('gulp-plumber'),
	notify = require('gulp-notify'),
	cssnano = require('gulp-cssnano'),
	flatten = require('gulp-flatten'),
	sourcemaps = require('gulp-sourcemaps'),
	uglify = require('gulp-uglify'),
	cssreplace = require('gulp-replace'),
	livereload = require('gulp-livereload');

var theme_location = './wp-content/themes/fxprotools-theme',
	config = {
		admin_sass: theme_location + '/assets/sass/admin/**/*.scss',
		theme_sass: theme_location + '/assets/sass/theme/**/*.scss',
		vendor_css: theme_location + '/vendors/**/*.css',
		vendor_assets: theme_location + '/vendors/**/*.{png,jpg,gif,eot,ttf,woff,eof,svg}',
		theme_js: [
			// Vendor Files - Common
			theme_location + '/assets/js/core/jquery-3.1.1.min.js',
			theme_location + '/assets/js/core/bootstrap.min.js',
			theme_location + '/assets/js/core/bootstrap.min.js',
			theme_location + '/assets/js/core/bootstrap.min.js',
			theme_location + '/vendors/metisMenu/jquery.metisMenu.js',
			theme_location + '/vendors/slimscroll/jquery.slimscroll.min.js',
			theme_location + '/vendors/pace/pace.min.js',
			// Script - Dashboard
			theme_location + '/vendors/flot/jquery.flot.js',
		    theme_location + '/vendors/flot/jquery.flot.tooltip.min.js',
		    theme_location + '/vendors/flot/jquery.flot.spline.js',
		    theme_location + '/vendors/flot/jquery.flot.resize.js',
		    theme_location + '/vendors/flot/jquery.flot.pie.js',
		    theme_location + '/vendors/flot/jquery.flot.symbol.js',
		    theme_location + '/vendors/flot/jquery.flot.time.js',
			// Common Scripts
			theme_location + '/assets/js/common.js',
			// theme_location + '/assets/js/dashboard.js'
			// Custom Scripts
			// Add your scripts here
		],
		output: theme_location
	};

// ------------
// THEME - SASS
// ------------
gulp.task('theme-sass', function(){
	gulp.src(config.theme_sass)
		.pipe(plumber())
		.pipe(sass({
			outputStyle: 'compressed'
		}))
		.pipe(cssreplace('/*!', '/*'))
		.pipe(concat('style.css'))
		.pipe(gulp.dest(config.output))
		.pipe(notify('SASS processed'));
});

// ----------
// THEME - JS
// ----------
gulp.task('theme-js', function(){
	gulp.src(config.theme_js)
		.pipe(plumber())
		.pipe(uglify({
			mangle: true,
		}))
		.pipe(concat('bundle.js'))
		.pipe(gulp.dest(config.output+'/assets/js'))
		.pipe(notify('JS processed'));
});

// ------------
// VENDOR - CSS
// ------------
// gulp.task('vendor-css', function(){
// 	gulp.src(config.vendor_css)
// 		.pipe(concat('vendor.bundle.css'))
// 		.pipe(cssnano())
// 		.pipe(gulp.dest(config.output+'/dist'));
// });

// ---------------
// VENDOR - ASSETS
// ---------------
// gulp.task('vendor-assets', function(){
// 	gulp.src(config.vendor_assets)
// 		.pipe(flatten())
// 		.pipe(gulp.dest(config.output+'/dist'));
// });

// ------------
// ADMIN - SASS
// ------------
gulp.task('admin-sass', function(){
	gulp.src(config.admin_sass)
		.pipe(sass({
			outputStyle: 'compressed'
		}))
		.pipe(cssreplace('/*!', '/*'))
		.pipe(concat('style-admin.css'))
		.pipe(gulp.dest(config.output))
		.pipe(notify('SASS processed'));
});


gulp.task('default', ['theme-sass', 'theme-js'], function(){
	gulp.watch(config.theme_sass, ['theme-sass']);
	gulp.watch(config.theme_js, ['theme-js']);
});

gulp.task('admin', ['admin-sass'], function(){
	gulp.watch(config.admin_sass, ['admin-sass']);
});