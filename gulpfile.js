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
		theme_js: [
			// Include scripts here
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

gulp.task('default', ['theme-sass', 'theme-js', 'admin-sass'], function(){
	gulp.watch(config.theme_sass, ['theme-sass']);
	gulp.watch(config.theme_js, ['theme-js']);
	gulp.watch(config.admin_sass, ['admin-sass']);
});