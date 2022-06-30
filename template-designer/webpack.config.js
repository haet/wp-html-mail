const path = require('path');
const { ModuleFederationPlugin } = require('webpack').container;
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const rootDir = path.resolve( __dirname );

const NODE_ENV = process.env.NODE_ENV || "development";

const paths = {
	srcDir: path.resolve( rootDir, 'wp-html-mail' ),
	buildDir: path.resolve( rootDir, ( NODE_ENV === "production" ? "dist" : "dev" ) ),
};
console.log("srcDir: " + paths.srcDir);
console.log("buildDir: " + paths.buildDir);

module.exports = {
	...defaultConfig,
	plugins: [
		...defaultConfig.plugins,
    new ModuleFederationPlugin({
      name: "wphtmlmail",
      library: { type: "var", name: "wphtmlmail" },
      remotes: {
        wphtmlmailwoocommerce: "wphtmlmailwoocommerce",
      },
      shared: ["react", "react-dom"],
    }),
  ],
	resolve: {
		...defaultConfig.resolve,
		// alias directories to paths you can use in import() statements
		alias: {
			components: path.join( paths.srcDir, 'components' ),
		},
	},
};

