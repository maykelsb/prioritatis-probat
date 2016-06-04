module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        resourcesPath: 'bower_components',
        clean: {
            css: ['web/css/bootstrap*'],
            fonts: ['web/fonts/*'],
            js: ['web/js/*']
        },
        
        copy: {
            css_bootstrap: {
                expand: true,
                cwd: '<%= resourcesPath %>/bootstrap/dist/css',
                src: '**/bootstrap.min.css',
                dest: 'web/css'
            },
            fonts_bootstrap: {
                expand: true,
                cwd: '<%= resourcesPath %>/bootstrap/dist/fonts',
                src: '**',
                dest: 'web/fonts'
            },
            js_bootstrap: {
                expand: true,
                cwd: '<%= resourcesPath %>/bootstrap/dist/js',
                src: '**/*.min.js',
                dest: 'web/js'
            },
            js_jquery: {
                expand: true,
                cwd: '<%= resourcesPath %>/jquery/dist',
                src: '**/*.min.js',
                dest: 'web/js'
            },
            js_bootbox: {
                expand: true,
                cwd: '<%= resourcesPath %>/bootbox.js',
                src: '**/bootbox.js',
                dest: 'web/js'
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    
    grunt.registerTask('default', function(){
        grunt.task.run('clean'),
        grunt.task.run('copy')
    });
};