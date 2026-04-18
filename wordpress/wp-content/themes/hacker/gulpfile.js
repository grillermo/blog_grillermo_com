'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    cssBase64 = require('gulp-css-base64'),
    autoprefixer = require('gulp-autoprefixer');

/**
 * Default Task
 */
gulp.task('default', ['styles']);

/**
 * Styles Task
 */
gulp.task('styles', function () {

    gulp.src('./scss/style.scss')
        .pipe(sass({
            outputStyle: 'expanded',
            precision: 8
        }).on('error', sass.logError))
        .pipe(autoprefixer({
            // cascade: true, //should Autoprefixer use Visual Cascade, if CSS is uncompressed.
            browsers: ['last 30 versions'] //['chrome 32']
        }))
        .pipe(cssBase64({
            extensionsAllowed: ['.gif', '.png', '.svg']
        }))
        .pipe(gulp.dest('./'));

    gulp.src('./scss/editor-style.scss')
        .pipe(sass({
            outputStyle: 'expanded',
            precision: 8
        }).on('error', sass.logError))
        .pipe(autoprefixer({
            // cascade: true, //should Autoprefixer use Visual Cascade, if CSS is uncompressed.
            browsers: ['last 30 versions'] //['chrome 32']
        }))
        .pipe(cssBase64({
            extensionsAllowed: ['.gif', '.png', '.svg']
        }))
        .pipe(gulp.dest('./'));
});

/**
 * Development mode
 */
gulp.task('dev', function () {
    gulp.watch(['./scss/**/*.scss'], ['styles']);
});