export function getIntVal(value, fallback = 0) {
	if (isNaN(parseInt(value))) return fallback;
	else return parseInt(value);
}
