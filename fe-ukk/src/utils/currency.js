const locale = 'id-ID';

export function toCurrencyLocale(val, baseCurr) {
    return Number(val).toLocaleString(locale, {
        style: 'currency', 
        currency: baseCurr || 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

export function toNumberLocale(val, digit) {
    digit = digit === undefined ? 2 : digit;
    return Number(val).toLocaleString(locale, { 
        minimumFractionDigits: digit, 
        maximumFractionDigits: digit 
    });
}

export function getSymbolCurrency(baseCurr) {
    return (0).toLocaleString(locale, {
        style: 'currency', 
        currency: baseCurr || 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).replace(/\d/g, '').trim();
}
