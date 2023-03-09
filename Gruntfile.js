module.exports = function ( grunt ) {
	const autoprefixer = require( 'autoprefixer' );
	const flexibility = require( 'postcss-flexibility' );
	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		copy: {
			main: {
				options: {
					mode: true,
				},
				src: [
					'**',
					'!.git/**',
					'!.gitignore',
					'!.gitattributes',
					'!*.sh',
					'!*.zip',
					'!eslintrc.json',
					'!README.md',
					'!Gruntfile.js',
					'!package.json',
					'!package-lock.json',
					'!composer.json',
					'!composer.lock',
					'!phpcs.xml',
					'!phpcs.xml.dist',
					'!phpunit.xml.dist',
					'!node_modules/**',
					'!vendor/**',
					'!tests/**',
					'!scripts/**',
					'!config/**',
					'!tests/**',
					'!bin/**',
				],
				dest: 'fraktjakt-shipping-for-dokan/',
			},
		},
		compress: {
			main: {
				options: {
					archive:
						'fraktjakt-shipping-for-dokan-<%= pkg.version %>.zip',
					mode: 'zip',
				},
				files: [
					{
						src: [ './fraktjakt-shipping-for-dokan/**' ],
					},
				],
			},
		},
		clean: {
			main: [ './fraktjakt-shipping-for-dokan' ],
			zip: [ '*.zip' ],
		},
		makepot: {
			target: {
				options: {
					domainPath: '/',
					mainFile: 'fraktjakt-shipping-for-dokan.php',
					potFilename: 'languages/fraktjakt-shipping-for-dokan.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true,
					},
					type: 'wp-plugin',
					updateTimestamp: true,
				},
			},
		},
		/* Minify Js and Css */
		cssmin: {
			options: {
				keepSpecialComments: 0,
			},
			css: {
				files: [
					{
						expand: true,
						cwd: 'assets/css',
						src: [ '*.css' ],
						dest: 'assets/min-css',
						ext: '.min.css',
					},
				],
			},
		},
		uglify: {
			js: {
				options: {
					compress: {
						drop_console: true, // <-
					},
				},
				files: [
					{
						expand: true,
						cwd: 'assets/js',
						src: [ '*.js' ],
						dest: 'assets/min-js',
						ext: '.min.js',
					},
				],
			},
		},
		postcss: {
			options: {
				map: false,
				processors: [ flexibility, autoprefixer( { cascade: false } ) ],
			},
			style: {
				expand: true,
				src: [ 'assets/css/**.css', '!assets/css/**-rtl.css' ],
			},
		},

		rtlcss: {
			options: {
				// rtlcss options
				config: {
					preserveComments: true,
					greedy: true,
				},
				// generate source maps
				map: false,
			},
			dist: {
				files: [
					{
						expand: true,
						cwd: 'assets/css',
						src: [ '*.css', '!*-rtl.css' ],
						dest: 'assets/css/',
						ext: '-rtl.css',
					},
					/* New Admin UI */
					{
						expand: true,
						cwd: 'admin-core/assets/build',
						src: [ '*.css', '!*-rtl.css' ],
						dest: 'admin-core/assets/build/',
						ext: '-rtl.css',
					},
					{
						expand: true,
						cwd: 'admin-core/assets/css',
						src: [ '*.css', '!*-rtl.css' ],
						dest: 'admin-core/assets/css/',
						ext: '-rtl.css',
					},
				],
			},
		},
		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt',
				},
			},
		},
		addtextdomain: {
			options: {
				textdomain: 'fraktjakt-shipping-for-dokan',
				updateDomains: true,
			},
			target: {
				files: {
					src: [
						'*.php',
						'**/*.php',
						'!node_modules/**',
						'!php-tests/**',
						'!bin/**',
						'!classes/class-wc-am-client.php',
					],
				},
			},
		},
		strip_code: {
			strip_woo: {
				options: {
					blocks: [
						{
							start_block: '/* Start-strip-code */',
							end_block: '/* End-strip-code */',
						},
					],
				},
				src: [ './fraktjakt-shipping-for-dokan/plugin-loader.php' ],
			},
		},
	} );

	/* Load Tasks */
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-compress' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-text-replace' );
	grunt.loadNpmTasks( 'grunt-postcss' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-rtlcss' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-strip-code' );
	// Autoprefix
	grunt.registerTask( 'style', [ 'postcss:style' ] );
	grunt.registerTask( 'rtl', [ 'rtlcss' ] );
	/* Read File Generation task */
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );

	grunt.registerTask( 'release', [
		'clean:zip',
		'copy',
		//Create anuual variation by changing the product ID.
		'compress:main',
		'clean:main',
	] );

	grunt.registerTask( 'textdomain', [ 'addtextdomain' ] );
	grunt.registerTask( 'i18n', [ 'addtextdomain', 'makepot' ] );
	// Generate Read me file
	grunt.registerTask( 'readme', [ 'wp_readme_to_markdown' ] );

	// min all
	grunt.registerTask( 'minify', [
		'style',
		'cssmin:css',
		'uglify:js',
	] );
};
