module.exports = function(grunt){
	const sass = require('node-sass');
	grunt.initConfig({

		pkg: grunt.file.readJSON( 'package.json' ),

		watch: {
			sass: {
				files: [ 'assets/css/src/**/*' ],
				tasks: [ 'sass', 'postcss', 'australianStylesheets', 'cssmin' ]
			},
			scripts: {
				files: [ 'assets/js/src/**/*', 'assets/js/admin/**/*' ],
				tasks: [ 'concat', 'terser' ]
			}
		},

		sass: {
			options: {
				implementation: sass,
				sourceMap: true
			},
			dist: {
				files: [
					{
						'assets/css/style.css': 'assets/css/src/style.scss'
					},
					{
						'assets/css/admin.css': 'assets/css/src/admin.scss'
					},
					{
						'blocks/**/block.css': 'blocks/**/block.scss'
					}
				]
			}
		},

		concat: {
			options: {
				seperator: ";",
				stripBanners: true
			},
			theme: {
				src: [ 'assets/js/src/**/*.js' ],
				dest: 'assets/js/scripts.js'
			},
			admin: {
				src: [ 'assets/js/admin/**/*.js' ],
				dest: 'assets/js/admin.js'
			},
		},

		cssmin: {
			css: {
				files: [{
					expand: true,
					cwd: 'assets/css/',
					src: [ '*.css', '!*.min.css' ],
					dest: 'assets/css/',
					ext: '.min.css'
				}]
			}
		},

		postcss: {
			options: {
				map: true,
			},
			dist: {
				src: 'assets/css/*.css'
			}
		},

		australianStylesheets: {
			no_dest_single: {
				src: 'assets/css/style.css'
			}
		},

		terser: {
			options: {},
			js: {
				files: [{
					expand: true,
					cwd: 'assets/js/',
					src: [ '*.js', '!*.min.js' ],
					dest: 'assets/js/',
					ext: '.min.js',
					extDot: 'last'
				}]
			},
		},

	});

	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-postcss' );
	grunt.loadNpmTasks( 'grunt-australian-stylesheets' );
	grunt.loadNpmTasks( 'grunt-terser' );
	grunt.registerTask( 'build', [ 'sass', 'postcss', 'australianStylesheets', 'cssmin', 'concat', 'terser' ] );
	grunt.registerTask( 'default', [ 'build', 'watch' ] );
};
