var gulp = require('gulp');
var compiler = require('gulp-hogan-compile');

var paths = {
	templates: ['themes/2014/templates/liveblog/*.mustache'],
};

gulp.task('templates', function() {
    return gulp.src(paths.templates)
		.pipe(compiler('liveblog-templates.js', {
			'wrapper': false
		}))
		.pipe(gulp.dest('themes/2014/js'));
});

// Rerun the task when a file changes
gulp.task('watch', function () {
    gulp.watch(paths.templates, ['templates']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['templates', 'watch']);
