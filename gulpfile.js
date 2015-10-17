var gulp = require('gulp')
var concat = require('gulp-concat')
var babel = require('gulp-babel')

gulp.task('default', ['js'])

gulp.task('js', function () {
  return gulp.src(['./src/**/scripts/**/*.module.js', './src/**/scripts/**/*.js'])
    .pipe(concat('build.js'))
    .pipe(babel())
    .pipe(gulp.dest('./web/scripts/'))
})
