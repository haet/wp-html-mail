module.exports = {
	entry: "./js/template-designer/src/index.jsx",
	output: {
		path: __dirname,
		filename: "./js/template-designer/dist/bundle.js",
	},
	module: {
		rules: [
			{
				test: /.jsx$/,
				exclude: /node_modules/,
				options: {
					presets: [["env", "react"]],
				},
			},
		],
	},
};
