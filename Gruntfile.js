module.exports = function(grunt) {
 
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        pluginfiles : [
            '**.php',
            '!secret.php',
            'admin/css/**',
            'admin/js/**',
            'admin/**.php',
            'admin/partials/*.php',
            'includes/**',
            'public/**',
            '!public/scss/**',
            'languages/**',
            'images/**',
            '**.txt',
            '**.md',
            'fonts/**',
            'font-awesome/**',
            'plugin-update-checker/**'
        ],
        wp_readme_to_markdown: {
            dist: {
                files: {
                  'readme.md': 'readme.txt'
                },
            },
        },
        makepot: {
            target: {
                options: {
                    include: [],
                    type: 'wp-plugin',
                    potHeaders: { 
                        'report-msgid-bugs-to': 'info@pehaa.com' 
                    }
                }
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {                         // Dictionary of files
                    'admin/css/screen.css': 'admin/scss/screen.scss',
                    'admin/css/options.css': 'admin/scss/options.scss',
                    'public/css/style.css':'public/scss/style.scss'
                }
            },
            dev: {
                options: {
                    style: 'expanded'
                },
            files: {                         // Dictionary of files
                    'admin/css/screen.dev.css': 'admin/scss/screen.scss',
                    'admin/css/options.dev.css': 'admin/scss/options.scss',
                    'public/css/style.dev.css':'public/scss/style.scss'
                  }
            }
        },
        jshint: {
            files: [
                'admin/js/assets/**.js', 'public/js/assets/phtpb.js', '!admin/js/assets/datetimepicker.js'
            ],
            options: {
                expr: true,
                globals: {
                    jQuery: true,
                    console: true,
                    module: true,
                    document: true
                }
            }
        },
        uglify: {
            dist: {
                options: {
                    banner: '/*! <%= pkg.name %> <%= pkg.version %> phtpb-admin.min.js */\n',
                },
                files: {
                    'admin/js/phtpb-admin.min.js' : [
                        'admin/js/assets/datetimepicker.js',
                        'admin/js/assets/models/element.js',
                        'admin/js/assets/collections/elements.js',
                        'admin/js/assets/views/element-view.js',
                        'admin/js/assets/views/section-view.js',
                        'admin/js/assets/views/row-view.js',
                        'admin/js/assets/views/column-view.js',
                        'admin/js/assets/views/module-view.js',
                        'admin/js/assets/views/modal-view.js',
                        'admin/js/assets/views/modal-settings-view.js',
                        'admin/js/assets/views/modal-all-modules-view.js',
                        'admin/js/assets/views/modal-columns-layout-view.js',
                        'admin/js/assets/views/app-view.js',
                        'admin/js/assets/phtpb-admin.js'
                    ]
                }
            },
            publicdist: {
                options: {
                    banner: '/*! <%= pkg.name %> <%= pkg.version %> all.min.js */\n',
                },
                files: {
                    'public/js/all.min.js' : [
                        'public/js/assets/jquery.imagesloaded.js',
                        'public/js/assets/jquery.magnific-popup.js',
                        'public/js/assets/jquery.waypoints.js',
                        'public/js/assets/jquery.flexslider.js',
                        'public/js/assets/slick.js',
                        'public/js/assets/phtpb.js',
                    ]
                }
            },
        },
        compress: {
            main: {
                options: {
                  archive: '<%= pkg.name %>.zip',
                },
                files: [
                  {src: '<%= pluginfiles %>', dest: '' }
                ]
            }
        },
        zip: {
            src: [
                '**.php',
                'admin/css/**',
                'admin/js/**',
                'admin/**.php',
                'includes/**',
                'public/**',
                '!public/scss/**',
                'languages/**',
                'images/**',
                '**.txt',
                '**.md',
                'fonts/**',
                'font-awesome/**',
                'plugin-update-checker/**'
            ],
            dest : 'pht-page-builder.zip',
            compression: 'DEFLATE'
        },
        watch: {
            css: {
                files: [ 'admin/scss/*.scss', 'public/scss/*.scss' ],
                tasks: ['sass:dev', 'sass:dist']
            },
            jsjshint: {
                files: [ 'admin/js/assets/**.js', 'public/js/assets/phtpb.js' ],
                tasks: ['jshint', 'jshint']
            },
            js: {
                files: [ 'admin/js/assets/**.js', 'public/js/assets/*.js' ],
                tasks: ['uglify:dist', 'uglify:publicdist']
            }
        },
        "json-replace": {
            "options": {
                "space" : "\t",
                "replace" : {
                    "name" : '<%= pkg.plugin_name %>',
                    "slug" : '<%= pkg.name %>',
                    "version" : '<%= pkg.version %>',
                    "download_url" : '<%= pkg.download_url %>',
                    "sections" : {
                        "description" : '<%= pkg.description %>',
                        "changelog" : '<%= pkg.changelog %>',
                    },
                    "homepage" : '<%= pkg.repository.url %>',
                    "tested" : '<%= pkg.tested %>',
                    "author" : '<%= pkg.author %>',
                    "author_homepage" : '<%= pkg.author_url %>',
                }
            },
            "metadata": {
                "files" : [{
                    "src" : "metadata.json",
                    "dest" : "metadata.json"
                }]
            },
        },
    });
 

    //grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-curl');
    grunt.loadNpmTasks('grunt-phpdocumentor');
    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks( 'grunt-zip' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );
    grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
    grunt.loadNpmTasks('grunt-json-replace');
    grunt.loadNpmTasks('grunt-contrib-compress');
 
    grunt.registerTask('default', [
        //'makepot',
        'wp_readme_to_markdown',
        'sass:dist',
        'sass:dev',
        'jshint',
        'uglify:dist',
        'uglify:publicdist'
    ]);

    // Serve presentation locally
    grunt.registerTask( 'serve', ['watch'] );
 
};