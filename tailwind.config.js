module.exports = {
	content: [ './admin-core/assets/src/**/*.@(js|jsx)' ],
	theme: {
		extend: {
			colors: {
				wpcolor: '#f06335',
				wphovercolor: '#135e96',
				wphoverbgcolor: '#2271b117',
				wpcolorfaded: '#2271b120',
			},
			fontFamily: {
				inter: [ '"Inter"', 'sans-serif' ],
			},
		},
	},
	variants: {
		extend: {},
		scrollbar: [ 'rounded' ],
	},
	plugins: [ require( '@tailwindcss/forms' ) ],
};
