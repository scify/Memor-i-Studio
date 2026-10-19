import js from "@eslint/js";
import globals from "globals";

export default [
	{ ignores: ["public/**", "vendor/**", "node_modules/**", "storage/**"] },
	js.configs.recommended,
	{
		languageOptions: {
			ecmaVersion: "latest",
			sourceType: "module",
			globals: { ...globals.browser, ga: "readonly" },
		},
	},
	{ files: ["vite.config.js", "eslint.config.js"], languageOptions: { globals: globals.node } },
];
