import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

// Port 5176 is exposed in .ddev/config.yaml (web_extra_exposed_ports).
const ddevServer = process.env.IS_DDEV_PROJECT === "true"
	? {
		host: "0.0.0.0",
		port: 5176,
		strictPort: true,
		origin: `${process.env.DDEV_PRIMARY_URL_WITHOUT_PORT}:5176`,
		cors: { origin: process.env.DDEV_PRIMARY_URL },
	}
	: {};

export default defineConfig({
	plugins: [
		laravel({
			input: [
				"resources/assets/css/vendors.css",
				"resources/assets/sass/app.scss",
				"resources/assets/js/app.js",
				"resources/assets/js/controllers/equivalenceSetsController.js",
				"resources/assets/js/controllers/gameFlavorsController.js",
			],
			refresh: true,
		}),
	],
	server: ddevServer,
	build: { sourcemap: true },
});
