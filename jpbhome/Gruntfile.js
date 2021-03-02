// Grunt file
module.exports = function(grunt) {
    // load plug ins
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-postcss');

    //Initialize the config object
    grunt.initConfig({
        less: {
            development: {
                files: {
                    "./public/css/editor.css": "./app/less/editor.less",
                    "./public/css/print.css": "./app/less/print.less",
                    "./public/css/styles.css": "./app/less/styles.less"
                }
            }
        },
        postcss: {
            options: {
                map: false,
                processors: [
                    require('autoprefixer')({browsers: ['last 2 versions']}),
                    require('cssnano')()
                ]
            },
            dist: {
                src: "./public/css/*.css"
            }
        },
        watch: {
            less: {
                files: ['./app/less/*.less'], // watch files
                tasks: ['less', 'postcss:dist'], // tasks to run
                options: {
                    livereload: true    // reloads the browser
                }
            },
        }
    });

    // Task definition
    grunt.registerTask('default', ['watch']);
};
