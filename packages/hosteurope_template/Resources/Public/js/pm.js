const theme = {
  palette: {
    headerBackground: "#fff",
    headerForeground: "#0091d3",
    primary: "#63B770",
    primaryContrast: "#ffffff",
    primaryGradient: "#1a355d",
    secondary: "#63B770",
    tertiary: "#63B770",
    highlight: "#e0234b",
    highlightContrast: "#ffffff",
    cta: "#63b770",
    ctaContrast: "#3c3c3d",
    contrast: "#fff",
    grey01: "#1a1a1a",
    grey02: "#282828",
    grey03: "#333",
    grey04: "#484848",
    grey05: "#666",
    grey06: "#848484",
    grey07: "#909090",
    grey08: "#999",
    grey09: "#ccc",
    grey10: "#eee",
    grey11: "#f2f2f2",
    grey12: "#f2f2f2",
    red01: "#cc0000",
    red02: "#f4c7c7",
    yellow01: "#EDD000",
    yellow02: "#FAF1B2",
    green01: "#63b770",
    green02: "#63b770"
  },
  typography: {
    content: "?MuseoSansRounded-500?,sans-serif",
    display: "?MuseoSansRounded-500?,sans-serif",
    sizing: 1
  },
  spacing: 1,
  curvature: 2
};

const de_msg = {
  "cookie.accept": "Akzeptieren",
  "cookie.decline": "Ablehnen",
  "cookie.confirm": "Akzeptieren",
  "cookie.ribbon.title": "Wir verwenden Cookies",
  "cookie.ribbon.description": "Unsere Website verwendet Cookies, die uns helfen, unsere Website zu verbessern und unseren Kunden den bestmöglichen Service zu bieten. Indem Sie auf 'Akzeptieren' klicken, erklären Sie sich mit unseren Cookie-Richtlinien einverstanden.",
  "cookie.inline.title": "Datenschutz-Optionen",
  "cookie.inline.description": "Unsere Website verwendet Cookies, die uns helfen, unsere Website zu verbessern, den bestmöglichen Service zu bieten und ein optimales Kundenerlebnis zu ermöglichen. Hier können Sie Ihre Einstellungen verwalten. Indem Sie auf 'Ja' klicken, erklären Sie sich damit einverstanden, dass Ihre Cookies für diesen Zweck verwendet werden.",
  "cookie.privacyPolicy": "Erfahren Sie mehr.",
  "cookie.settings.toggle.close": "Einstellungen verbergen",
  "cookie.settings.toggle.open": "Meine Einstellungen verwalten",
  "cookie.modal.title": "Wir verwenden Cookies",
  "cookie.modal.description": "Unsere Website verwendet Cookies, die uns helfen, unsere Website zu verbessern und unseren Kunden den bestmöglichen Service zu bieten. Indem Sie auf 'Akzeptieren' klicken, erklären Sie sich mit unseren Cookie-Richtlinien einverstanden.",
  "cookie.modal.settings": "Unsere Website verwendet Cookies, die uns helfen, unsere Website zu verbessern, den bestmöglichen Service zu bieten und ein optimales Kundenerlebnis zu ermöglichen. Hier können Sie Ihre Einstellungen verwalten. Indem Sie auf 'Ja' klicken, erklären Sie sich damit einverstanden, dass Ihre Cookies für diesen Zweck verwendet werden.",
  "cookie.toggle.analytics.title": "Analysen",
  "cookie.toggle.analytics.text": "Tools, die anonyme Daten über Website-Nutzung und -Funktionalität sammeln. Wir nutzen die Erkenntnisse, um unsere Produkte, Dienstleistungen und das Benutzererlebnis zu verbessern.",
  "cookie.toggle.essential.title": "Grundlegendes",
  "cookie.toggle.essential.text": "Tools, die wesentliche Services und Funktionen ermöglichen, einschließlich Identitätsprüfung, Servicekontinuität und Standortsicherheit. Diese Option kann nicht abgelehnt werden.",
  "cookie.toggle.marketing.title": "Werbung",
  "cookie.toggle.marketing.text": "Anonyme Informationen, die wir sammeln, um Ihnen nützliche Produkte und Dienstleistungen empfehlen zu können.",
  "cookie.toggle.support.title": "Support",
  "cookie.toggle.support.text": "Tools, die interaktive Services wie Chat-Support und Kunden-Feedback-Tools unterstützen."
};
const en_msg = {
  "cookie.accept": "Accept",
  "cookie.decline": "Decline",
  "cookie.confirm": "Accept",
  "cookie.ribbon.title": "We serve cookies",
  "cookie.ribbon.description": "Our Website uses cookies, which help us to improve our site and enables us to deliver the best possible service. By clicking 'accept' you are agreeing to our cookie policy.",
  "cookie.inline.title": "Privacy Options",
  "cookie.inline.description": "Our Website uses cookies, which help us to improve our site and enables us to deliver the best possible service and customer experience. Here you can manage your customer experiences. By clicking yes to any of these options I consent to my cookies beeing used for this purpose.",
  "cookie.privacyPolicy": "Find out more.",
  "cookie.settings.toggle.close": "Hide settings",
  "cookie.settings.toggle.open": "Manage my settings",
  "cookie.modal.title": "Manage your cookies",
  "cookie.modal.description": "We use cookies to ensure that we give you the best experience on the Host Europe website. If you continue without changing your settings, we'll assume that you are happy to receive all cookies on the Host Europe website. However, if you would like to, you can change your cookie settings at any time.",
  "cookie.modal.settings": "Our Website uses cookies, which help us to improve our site and enables us to deliver the best possible service and customer experience. Here you can manage your customer experiences. By clicking yes to any of these options I consent to my cookies beeing used for this purpose.",
  "cookie.toggle.analytics.title": "Analytics",
  "cookie.toggle.analytics.text": "Tools that collect anonymous data about how visitors use our site and how it performs. We use this to improve our products, services and user experience.",
  "cookie.toggle.essential.title": "Essentials",
  "cookie.toggle.essential.text": "Tools that enable essential services and functonality, including identify verification, service continuity and site security, opt out is not availabe.",
  "cookie.toggle.marketing.title": "Advertising",
  "cookie.toggle.marketing.text": "Anonymous information we collect to help us recommend more relevant products and services for you.",
  "cookie.toggle.support.title": "Support",
  "cookie.toggle.support.text": "Tools that power interactive services such as chat support and customer feedback tools.",
};

const de_privacyPolicyUrl = 'https://www.hosteurope.de/AGB/Datenschutzerklaerung/';
const en_privacyPolicyUrl = 'https://www.hosteurope.de/en/terms-and-conditions/privacy/';

var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
if (lang === 'de-DE' || lang === 'de') {
  lang = 'de';
} else {
  lang = 'en';
}
var messages = lang === 'de' ? de_msg : en_msg;
var privacyPolicyUrl = lang === 'de' ? de_privacyPolicyUrl : en_privacyPolicyUrl;

document.addEventListener("@upm/loaded", function () {
  window.privacyManager.privacyManager({
    waitForTealium: true,
    bannerDomSelector: '#bannerPrivacy',
    inlineDomSelector: '#inlinePrivacy',
    cookiesToPreserve: ['OPTOUTMULTI', '__cfduid', 'pwinteraction'],
    privacyPolicyUrl: privacyPolicyUrl,
    theme: theme,
    messages: messages,
    invalidateOptPre: '2024-07-08',
    categories: ['analytics', 'support', 'marketing'],
    tealium: {
      profile: 'hosteurope', // tealium profile for the brand
      env: 'prod' // tealium environment: 'dev' or 'prod'
    }
  });
});