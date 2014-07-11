module.exports = function (grunt) {

    var DISTDIR = '../maniple';

    var files = {};

    grunt.file.recurse('src', function (abspath, rootdir, subdir, filename) {
        if (filename.match(/\.js$/)) {
            files[DISTDIR + (subdir ? '/' + subdir : '') + '/' + filename] = abspath;
        }
    });

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                // banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                files: files,
            }
        },
        jshint: {
            all: {
                src: [
                    "src/**/*.js", "Gruntfile.js",
                ]
            }
        }
    });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.registerTask('lint', ['jshint'] );

    // Default task(s).
    grunt.registerTask('default', ['jshint', 'uglify']);

    if(0)grunt.registerTask('install', ['jshint', 'uglify'], function (dir) {
        if (!dir) {
            grunt.fail.warn("Install directory not supplied");
            return false;
        }
        grunt.file.recurse('dist', function (abspath, rootdir, subdir, filename) {
            var src = abspath,
                dst = dir + (subdir ? '/' + subdir : '') + '/' + filename;
            grunt.file.copy(src, dst);
        });
    });
};
