var path = require("path"),
	webpack = require("webpack"),
	exec = require("child_process").exec,
	NODE_ENV = process.env.NODE_ENV || "development",
	MiniCssExtractPlugin = require("mini-css-extract-plugin"),
	dist = path.join(
		__dirname,
		"/js/template-designer/",
		NODE_ENV === "production" ? "dist" : "dev"
	);

module.exports = {
	mode: NODE_ENV,
	entry: "./js/template-designer/src/index.js",
	output: {
		path: dist,
		filename: "[name].js",
	},
	externals: {
		react: "React",
		"react-dom": "ReactDOM",
		jquery: "jQuery",
		wp: "wp",
		_: "_",
		wpApiSettings: "wpApiSettings",
	},
	devtool: "#source-map",
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(disposables)/,
				use: "babel-loader?cacheDirectory",
			},
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					"css-loader",
					{
						loader: "postcss-loader",
						options: {
							config: {
								ctx: {
									clean: {},
								},
							},
						},
					},
					"sass-loader",
				],
			},
		],
	},
	resolve: {
		extensions: [".js", ".jsx"],
		modules: ["node_modules", "js/template-designer/src"],
	},
	plugins: [
		new webpack.DefinePlugin({
			// NODE_ENV is used inside React to enable/disable features that should only be used in development
			"process.env": {
				NODE_ENV: JSON.stringify(NODE_ENV),
				env: JSON.stringify(NODE_ENV),
			},
		}),
		new MiniCssExtractPlugin("[name].css"),
	],
};
