export function getIntVal(value, fallback = 0) {
	if (isNaN(parseInt(value))) return fallback;
	else return parseInt(value);
}

/**
 * This is a JS version of the same function in class-multilanguage.php
 * @param {object} theme_options
 * @param {string} key
 */
export function isTranslationEnabledForField(theme_options, key) {
	return (
		key + "_enable_translation" in theme_options &&
		theme_options[key + "_enable_translation"] == 1
	);
}

/**
 * This is a JS version of the same function in class-multilanguage.php
 * @param {object} theme_options
 * @param {string} key
 */
export function getTranslateableThemeOptionsKey(theme_options, key) {
	if (
		!window.mailTemplateDesigner.isMultiLanguageSite ||
		!isTranslationEnabledForField(theme_options, key)
	)
		return key;

	if (
		window.mailTemplateDesigner.currentLanguage &&
		window.mailTemplateDesigner.currentLanguage != ""
	)
		return key + "_" + window.mailTemplateDesigner.currentLanguage;

	return key;
}

/**
 * This is a JS version of the same function in class-multilanguage.php
 * @param {object} theme_options
 * @param {string} key
 */
export function getTranslateableThemeOption(theme_options, key) {
	if (
		!window.mailTemplateDesigner.isMultiLanguageSite ||
		!isTranslationEnabledForField(theme_options, key)
	)
		return theme_options[key];

	const translateable_key = getTranslateableThemeOptionsKey(
		theme_options,
		key
	);
	if (translateable_key in theme_options)
		return theme_options[translateable_key];

	return theme_options[key];
}
