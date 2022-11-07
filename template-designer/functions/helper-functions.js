export function getIntVal(value, fallback = 0) {
	if (isNaN(parseInt(value))) return fallback;
	else return parseInt(value);
}

/**
 * This is a JS version of the same function in class-multilanguage.php
 * @param {object} theme
 * @param {string} key
 */
export function isTranslationEnabledForField(theme, key) {
	return (
		key + "_enable_translation" in theme &&
		theme[key + "_enable_translation"] == 1
	);
}

/**
 * This is a JS version of the same function in class-multilanguage.php
 * @param {object} theme
 * @param {string} key
 */
export function getTranslateableThemeOptionsKey(theme, key) {
	if (
		!window.mailTemplateDesigner.isMultiLanguageSite ||
		!isTranslationEnabledForField(theme, key)
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
 * @param {object} theme
 * @param {string} key
 */
export function getTranslateableThemeOption(theme, key) {
	if (
		!window.mailTemplateDesigner.isMultiLanguageSite ||
		!isTranslationEnabledForField(theme, key)
	)
		return theme[key];

	const translateable_key = getTranslateableThemeOptionsKey(
		theme,
		key
	);
	if (translateable_key in theme)
		return theme[translateable_key];

	return theme[key];
}
