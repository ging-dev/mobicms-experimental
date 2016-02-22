/*!
 * mobiCMS http://mobicms.net
 */

module.exports = function (grunt) {
    require('time-grunt')(grunt);
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // Компилируем SASS файлы
        sass: {
            default: {
                options: {
                    style: 'expanded',
                    sourcemap: 'none',
                    noCache: true
                },
                files: {
                    'assets/template/css/mobicms.css': '_sources/themes/default/sass-mobicms/mobicms.scss'
                }
            }
        },

        // Обрабатываем CSS префиксы вендоров
        autoprefixer: {
            default: {
                files: {
                    'assets/template/css/mobicms.css': 'assets/template/css/mobicms.css',
                    'assets/template/css/editors/sceditor/theme.css': 'assets/template/css/editors/sceditor/theme.css',
                    'assets/template/css/editors/sceditor/editor.css': 'assets/template/css/editors/sceditor/editor.css',
                    'assets/template/css/editors/codemirror/theme.css': 'assets/template/css/editors/codemirror/theme.css'
                }
            }
        },

        // Минимизируем CSS файлы
        cssmin: {
            default: {
                files: {
                    'assets/template/css/mobicms.min.css': ['assets/template/css/mobicms.css'],
                    'assets/template/css/editors/sceditor/theme.min.css': ['assets/template/css/editors/sceditor/theme.css'],
                    'assets/template/css/editors/sceditor/editor.min.css': ['assets/template/css/editors/sceditor/editor.css'],
                    'assets/template/css/editors/codemirror/theme.min.css': ['assets/template/css/editors/codemirror/theme.css']
                }
            }
        },

        // Собираем и минимизируем JS файл
        uglify: {
            theme_default: {
                options: {
                    warnings: true,
                    compress: true,
                    mangle: true,
                    banner: '/*!\n * Bootstrap v3.3.1 (http://getbootstrap.com) | Copyright 2011-2014 Twitter, Inc. | Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)\n * js-cookie v2.0.1 (https://github.com/js-cookie/js-cookie) | Copyright 2013 Klaus Hartl and awesome contributors | Released under the MIT license\n * mobiCMS Content Management System (http://mobicms.net) | For copyright and license information, please see the LICENSE.txt\n */\n'
                },

                files: [{
                    src: [
                        '_sources/themes/default/js/js-cookie/js.cookie.js',
                        '_sources/themes/default/js/mobicms/toggle.js',
                        '_sources/themes/default/js/bootstrap/collapse.js',
                        '_sources/themes/default/js/bootstrap/dropdown.js',
                        '_sources/themes/default/js/bootstrap/transition.js'
                    ],
                    dest: 'assets/template/js/mobicms.min.js'
                }]
            }
        },

        // Очищаем папки и удаляем файлы
        clean: {
            dist: ['dist'],

            distributive: ['distributive'],

            sweep: [
                // Удаляем лишние файлы HtmlPurifier
                'distributive/system/vendor/ezyang/htmlpurifier/*',
                '!distributive/system/vendor/ezyang/htmlpurifier/library',
                '!distributive/system/vendor/ezyang/htmlpurifier/README',
                '!distributive/system/vendor/ezyang/htmlpurifier/LICENSE',

                // Удаляем лишние файлы GeShi
                'distributive/system/vendor/geshi/geshi/*',
                '!distributive/system/vendor/geshi/geshi/src',
                '!distributive/system/vendor/geshi/geshi/README.md',
                'distributive/system/vendor/geshi/geshi/src/*',
                '!distributive/system/vendor/geshi/geshi/src/geshi',
                '!distributive/system/vendor/geshi/geshi/src/geshi.php',
                'distributive/system/vendor/geshi/geshi/src/geshi/*',
                '!distributive/system/vendor/geshi/geshi/src/geshi/php.php',

                // Удаляем лишние файлы class.upload
                'distributive/system/vendor/verot/class.upload.php/test',
                'distributive/system/vendor/verot/class.upload.php/composer.json',

                // Удаляем лишние файлы container-interop
                'distributive/system/vendor/container-interop/container-interop/*',
                '!distributive/system/vendor/container-interop/container-interop/src',
                '!distributive/system/vendor/container-interop/container-interop/README.md',
                '!distributive/system/vendor/container-interop/container-interop/LICENSE',

                // Удаляем лишние файлы Zend Framework
                'distributive/system/vendor/zendframework/zend-code/*',
                '!distributive/system/vendor/zendframework/zend-code/src',
                '!distributive/system/vendor/zendframework/zend-code/README.md',
                '!distributive/system/vendor/zendframework/zend-code/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-config/*',
                '!distributive/system/vendor/zendframework/zend-config/src',
                '!distributive/system/vendor/zendframework/zend-config/README.md',
                '!distributive/system/vendor/zendframework/zend-config/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-crypt/*',
                '!distributive/system/vendor/zendframework/zend-crypt/src',
                '!distributive/system/vendor/zendframework/zend-crypt/README.md',
                '!distributive/system/vendor/zendframework/zend-crypt/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-di/*',
                '!distributive/system/vendor/zendframework/zend-di/src',
                '!distributive/system/vendor/zendframework/zend-di/README.md',
                '!distributive/system/vendor/zendframework/zend-di/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-escaper/*',
                '!distributive/system/vendor/zendframework/zend-escaper/src',
                '!distributive/system/vendor/zendframework/zend-escaper/README.md',
                '!distributive/system/vendor/zendframework/zend-escaper/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-eventmanager/*',
                '!distributive/system/vendor/zendframework/zend-eventmanager/src',
                '!distributive/system/vendor/zendframework/zend-eventmanager/README.md',
                '!distributive/system/vendor/zendframework/zend-eventmanager/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-feed/*',
                '!distributive/system/vendor/zendframework/zend-feed/src',
                '!distributive/system/vendor/zendframework/zend-feed/README.md',
                '!distributive/system/vendor/zendframework/zend-feed/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-http/*',
                '!distributive/system/vendor/zendframework/zend-http/src',
                '!distributive/system/vendor/zendframework/zend-http/README.md',
                '!distributive/system/vendor/zendframework/zend-http/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-hydrator/*',
                '!distributive/system/vendor/zendframework/zend-hydrator/src',
                '!distributive/system/vendor/zendframework/zend-hydrator/README.md',
                '!distributive/system/vendor/zendframework/zend-hydrator/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-loader/*',
                '!distributive/system/vendor/zendframework/zend-loader/src',
                '!distributive/system/vendor/zendframework/zend-loader/README.md',
                '!distributive/system/vendor/zendframework/zend-loader/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-mail/*',
                '!distributive/system/vendor/zendframework/zend-mail/src',
                '!distributive/system/vendor/zendframework/zend-mail/README.md',
                '!distributive/system/vendor/zendframework/zend-mail/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-math/*',
                '!distributive/system/vendor/zendframework/zend-math/src',
                '!distributive/system/vendor/zendframework/zend-math/README.md',
                '!distributive/system/vendor/zendframework/zend-math/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-mime/*',
                '!distributive/system/vendor/zendframework/zend-mime/src',
                '!distributive/system/vendor/zendframework/zend-mime/README.md',
                '!distributive/system/vendor/zendframework/zend-mime/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-session/*',
                '!distributive/system/vendor/zendframework/zend-session/src',
                '!distributive/system/vendor/zendframework/zend-session/README.md',
                '!distributive/system/vendor/zendframework/zend-session/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-stdlib/*',
                '!distributive/system/vendor/zendframework/zend-stdlib/src',
                '!distributive/system/vendor/zendframework/zend-stdlib/README.md',
                '!distributive/system/vendor/zendframework/zend-stdlib/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-uri/*',
                '!distributive/system/vendor/zendframework/zend-uri/src',
                '!distributive/system/vendor/zendframework/zend-uri/README.md',
                '!distributive/system/vendor/zendframework/zend-uri/LICENSE.md',

                'distributive/system/vendor/zendframework/zend-validator/*',
                '!distributive/system/vendor/zendframework/zend-validator/src',
                '!distributive/system/vendor/zendframework/zend-validator/README.md',
                '!distributive/system/vendor/zendframework/zend-validator/LICENSE.md'
            ]
        },

        // Копируем файлы из исходников
        copy: {
            distributive: {
                files: [
                    {
                        expand: true,
                        src: [
                            '**/**',
                            'modules/.htaccess',
                            'system/.htaccess',
                            'uploads/.htaccess',
                            '.htaccess',
                            '!_sources/**',
                            '!dist/**',
                            '!distributive/**',
                            '!node_modules/**',
                            '!system/cache/**/*',
                            '!system/config/Database.php',
                            '!system/logs/**/*',
                            '!Gruntfile.js',
                            '!package.json',
                            '!composer.*'
                        ],
                        dest: 'distributive/'
                    }
                ]
            }
        },

        // Сжимаем файлы
        compress: {
            theme_default: {
                options: {
                    mode: 'gzip'
                },

                files: [
                    {
                        src: ['assets/template/css/mobicms.min.css'],
                        dest: 'assets/template/css/mobicms.min.css.gz'
                    },
                    {
                        src: ['assets/template/css/editors/sceditor/theme.min.css'],
                        dest: 'assets/template/css/editors/sceditor/theme.min.css.gz'
                    },
                    {
                        src: ['assets/template/css/editors/sceditor/editor.min.css'],
                        dest: 'assets/template/css/editors/sceditor/editor.min.css.gz'
                    },
                    {
                        src: ['assets/template/css/editors/codemirror/theme.min.css'],
                        dest: 'assets/template/css/editors/codemirror/theme.min.css.gz'
                    },
                    {
                        src: ['assets/template/js/mobicms.min.js'],
                        dest: 'assets/template/js/mobicms.min.js.gz'
                    }
                ]
            },

            editors: {
                options: {
                    mode: 'gzip'
                },

                files: [
                    {
                        src: ['assets/js/sceditor/jquery.sceditor.xhtml.min.js'],
                        dest: 'assets/js/sceditor/jquery.sceditor.xhtml.min.js.gz'
                    }
                ]
            },

            dist: {
                options: {
                    archive: 'dist/mobicms-<%= pkg.version %>.zip'
                },

                files: [
                    {
                        expand: true,
                        dot: true,
                        cwd: 'distributive/',
                        src: ['**']
                    }
                ]
            }
        },

        // Обновляем зависимости
        devUpdate: {
            main: {
                options: {
                    updateType: 'force',
                    semver: false
                }
            }
        }
    });

    // Загружаем нужные модули
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-dev-update');

    // Общая задача, выполняет все действия, кроме "upd"
    grunt.registerTask('default', [
        'theme_default',
        'misc'
    ]);

    grunt.registerTask('distributive', [
        'clean:distributive',
        'copy:distributive',
        'clean:sweep',
        'clean:dist',
        'compress:dist',
        'clean:distributive'
    ]);

    // Сборка темы по-умолчанию
    grunt.registerTask('theme_default', [
        'sass:default',           // Компиляция SASS
        'autoprefixer:default',   // Обработка вендорных префиксов CSS
        'cssmin:default',         // Минимизация CSS
        'uglify:theme_default',   // Сборка и минимизация JS
        'compress:theme_default'  // GZ компрессия
    ]);

    // Различные служебные задачи
    grunt.registerTask('misc', [
        'compress:editors'        // GZ компрессия
    ]);

    // Обновление Dev Dependencies
    grunt.registerTask('upd', [
        'devUpdate:main'
    ]);
};
