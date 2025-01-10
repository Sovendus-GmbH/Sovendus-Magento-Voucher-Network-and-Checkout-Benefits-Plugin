var __async = (__this, __arguments, generator) => {
  return new Promise((resolve, reject) => {
    var fulfilled = (value) => {
      try {
        step(generator.next(value));
      } catch (e) {
        reject(e);
      }
    };
    var rejected = (value) => {
      try {
        step(generator.throw(value));
      } catch (e) {
        reject(e);
      }
    };
    var step = (x) => x.done ? resolve(x.value) : Promise.resolve(x.value).then(fulfilled, rejected);
    step((generator = generator.apply(__this, __arguments)).next());
  });
};
(function() {
  "use strict";
  const sovReqTokenKey = "sovReqToken";
  const sovReqProductIdKey = "sovReqProductId";
  function handleCheckoutProductsPage(checkoutProductsEnabled, pageHref, setCookie2) {
    return __async(this, null, function* () {
      if (checkoutProductsEnabled) {
        const sovReqToken = getParamFromUrl(pageHref, sovReqTokenKey);
        if (sovReqToken) {
          const sovReqProductId = getParamFromUrl(pageHref, sovReqProductIdKey);
          yield setCookie2(sovReqTokenKey, sovReqToken);
          if (sovReqProductId) {
            yield setCookie2(sovReqProductIdKey, sovReqProductId);
            return true;
          }
        }
      }
      return false;
    });
  }
  function getParamFromUrl(pageHref, key) {
    const url = new URL(pageHref);
    return url.searchParams.get(key) || void 0;
  }
  function getOptimizeConfig(settings, country) {
    if (settings.globalEnabled !== false && settings.useGlobalId !== false && settings.globalId) {
      return settings.globalId;
    }
    if (country && settings.countrySpecificIds) {
      const countryElement = settings.countrySpecificIds[country];
      return (countryElement == null ? void 0 : countryElement.isEnabled) ? countryElement == null ? void 0 : countryElement.optimizeId : void 0;
    }
    return void 0;
  }
  function main() {
    return __async(this, null, function* () {
      const pageSettings = window.sovPageConfig;
      window.sovPageStatus = {};
      if (typeof pageSettings !== "undefined") {
        window.sovPageStatus.sovPageConfigFound = true;
        const {
          optimizeId,
          checkoutProductsEnabled,
          voucherNetworkSwitzerlandEnabled
        } = getSovendusConfig(pageSettings.settings, pageSettings.country);
        handleOptimizePageScript(optimizeId);
        handleVoucherNetworkSwitzerland(voucherNetworkSwitzerlandEnabled);
        window.sovPageStatus.executedCheckoutProducts = yield handleCheckoutProductsPage(
          checkoutProductsEnabled,
          window.location.href,
          setCookie
        );
      } else {
        window.sovPageStatus.sovPageConfigFound = false;
        console.error("sovPageConfig is not defined");
      }
    });
  }
  function getSovendusConfig(settings, country) {
    var _a, _b, _c, _d;
    const countryCode = country || detectCountryCode();
    const vnSwitzerland = (_a = settings.voucherNetwork.countries.CH) == null ? void 0 : _a.languages;
    return {
      optimizeId: getOptimizeConfig(settings.optimize, countryCode),
      checkoutProductsEnabled: settings.checkoutProducts,
      voucherNetworkSwitzerlandEnabled: ((_b = vnSwitzerland == null ? void 0 : vnSwitzerland.DE) == null ? void 0 : _b.isEnabled) || ((_c = vnSwitzerland == null ? void 0 : vnSwitzerland.IT) == null ? void 0 : _c.isEnabled) || ((_d = vnSwitzerland == null ? void 0 : vnSwitzerland.FR) == null ? void 0 : _d.isEnabled)
    };
  }
  function setCookie(cookieName, value) {
    return __async(this, null, function* () {
      const path = "/";
      const expires = 60 * 60 * 24 * 30;
      const domain = window.location.hostname;
      const cookieString = `${cookieName}=${value};secure;samesite=strict;max-age=${expires};domain=${domain};path=${path}`;
      document.cookie = cookieString;
      return value || "";
    });
  }
  function handleVoucherNetworkSwitzerland(voucherNetworkSwitzerlandEnabled) {
    if (voucherNetworkSwitzerlandEnabled) {
      const script = document.createElement("script");
      script.type = "text/javascript";
      script.async = true;
      script.src = "https://api.sovendus.com/js/landing.js";
      document.body.appendChild(script);
      window.sovPageStatus.loadedVoucherNetworkSwitzerland = true;
    }
  }
  function detectCountryCode() {
    const getCountryCodeFromHtmlTag = () => {
      const lang = document.documentElement.lang;
      const countryCode = lang.split("-")[1];
      return countryCode ? countryCode.toUpperCase() : void 0;
    };
    const getCountryFromDomain = () => {
      const domainToCountry = {
        "de": "DE",
        "at": "AT",
        "ch": "CH",
        "uk": "GB",
        "co.uk": "GB",
        "com": void 0,
        "se": "SE",
        "no": "NO",
        "dk": "DK",
        "fi": "FI",
        "fr": "FR",
        "be": "BE",
        "nl": "NL",
        "it": "IT",
        "es": "ES",
        "pt": "PT",
        "pl": "PL",
        "cz": "CZ",
        "sk": "SK",
        "hu": "HU"
      };
      const domain = window.location.hostname;
      const domainParts = domain.split(".");
      const domainPart = domainParts[domainParts.length - 1];
      return domainPart ? domainToCountry[domainPart] : void 0;
    };
    const getCountryFromPagePath = () => {
      const path = window.location.pathname;
      const pathParts = path.split("/");
      const country = pathParts[1];
      return country == null ? void 0 : country.toUpperCase();
    };
    return getCountryCodeFromHtmlTag() || getCountryFromDomain() || getCountryFromPagePath();
  }
  function handleOptimizePageScript(optimizeId) {
    if (!optimizeId) {
      return;
    }
    window.sovPageStatus.loadedOptimize = true;
    const getbackDomain = "https://www.sovopt.com/";
    const script = document.createElement("script");
    script.src = `${getbackDomain}${optimizeId}`;
    script.type = "application/javascript";
    document.body.appendChild(script);
  }
  void main();
})();
//# sourceMappingURL=sovendus-page.js.map
