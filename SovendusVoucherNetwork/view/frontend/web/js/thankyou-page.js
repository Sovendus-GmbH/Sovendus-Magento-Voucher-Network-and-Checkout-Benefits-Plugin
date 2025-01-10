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
  function handleCheckoutProductsConversion(checkoutProducts, getCookie2, setCookie2) {
    return __async(this, null, function* () {
      if (checkoutProducts) {
        const sovReqToken = yield getCookie2(sovReqTokenKey);
        const sovReqProductId = yield getCookie2(sovReqProductIdKey);
        if (sovReqToken && sovReqProductId) {
          yield setCookie2(sovReqTokenKey, "");
          yield setCookie2(sovReqProductIdKey, "");
          const pixelUrl = `https://press-order-api.sovendus.com/ext/${decodeURIComponent(
            sovReqProductId
          )}/image?sovReqToken=${decodeURIComponent(sovReqToken)}`;
          yield fetch(pixelUrl);
          return true;
        }
      }
      return false;
    });
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
  function sovendusThankYou() {
    return __async(this, null, function* () {
      const config = window.sovThankyouConfig;
      window.sovThankyouStatus = {};
      if (!config) {
        window.sovThankyouStatus.sovThankyouConfigFound = false;
        console.error("sovThankyouConfig is not defined");
        return;
      }
      window.sovThankyouStatus.sovThankyouConfigFound = true;
      const { optimizeId, checkoutProducts, voucherNetwork } = getSovendusConfig(
        config.settings,
        config.consumerCountry,
        config.consumerLanguage
      );
      handleVoucherNetwork(voucherNetwork, config);
      window.sovThankyouStatus.executedCheckoutProducts = yield handleCheckoutProductsConversion(
        checkoutProducts,
        getCookie,
        setCookie
      );
      handleOptimizeConversion(optimizeId, config);
    });
  }
  function handleOptimizeConversion(optimizeId, config) {
    var _a;
    if (optimizeId) {
      const script = document.createElement("script");
      script.type = "text/javascript";
      script.async = true;
      script.src = `https://www.sovopt.com/${optimizeId}/conversion/?ordervalue=${config.orderValue}&ordernumber=${config.orderId}&vouchercode=${(_a = config.usedCouponCodes) == null ? void 0 : _a[0]}&email=${config.consumerEmail}`;
      window.sovThankyouStatus.loadedOptimize = true;
    }
  }
  function handleVoucherNetwork(voucherNetworkConfig, config) {
    var _a;
    if ((voucherNetworkConfig == null ? void 0 : voucherNetworkConfig.trafficSourceNumber) && voucherNetworkConfig.trafficMediumNumber) {
      window.sovIframes = window.sovIframes || [];
      window.sovIframes.push({
        trafficSourceNumber: voucherNetworkConfig.trafficSourceNumber,
        trafficMediumNumber: voucherNetworkConfig.trafficMediumNumber,
        sessionId: config.sessionId,
        timestamp: config.timestamp,
        orderId: config.orderId,
        orderValue: config.orderValue,
        orderCurrency: config.orderCurrency,
        usedCouponCode: (_a = config.usedCouponCodes) == null ? void 0 : _a[0],
        iframeContainerId: config.iframeContainerId,
        integrationType: config.integrationType
      });
      window.sovConsumer = {
        consumerFirstName: config.consumerFirstName,
        consumerLastName: config.consumerLastName,
        consumerEmail: config.consumerEmail,
        consumerStreet: config.consumerStreet,
        consumerStreetNumber: config.consumerStreetNumber,
        consumerZipcode: config.consumerZipcode,
        consumerCity: config.consumerCity,
        consumerCountry: config.consumerCountry,
        consumerPhone: config.consumerPhone
      };
      const script = document.createElement("script");
      script.type = "text/javascript";
      script.async = true;
      script.src = `${window.location.protocol}//api.sovendus.com/sovabo/common/js/flexibleIframe.js`;
      document.body.appendChild(script);
      window.sovThankyouStatus.loadedVoucherNetwork = true;
    }
  }
  const getCookie = (name) => {
    var _a;
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
      return (_a = parts.pop()) == null ? void 0 : _a.split(";").shift();
    }
    return void 0;
  };
  const setCookie = (name) => {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    return "";
  };
  function getSovendusConfig(settings, country, language) {
    return {
      optimizeId: getOptimizeConfig(settings.optimize, country),
      voucherNetwork: getVoucherNetworkConfig(
        settings.voucherNetwork,
        country,
        language
      ),
      checkoutProducts: settings.checkoutProducts
    };
  }
  function getVoucherNetworkConfig(settings, country, language) {
    const languageSettings = getLanguageSettings(settings, country, language);
    if (!languageSettings || !languageSettings.isEnabled || !languageSettings.trafficMediumNumber || !languageSettings.trafficSourceNumber) {
      return void 0;
    }
    return languageSettings;
  }
  function getLanguageSettings(settings, country, language) {
    if (!country) {
      window.sovThankyouStatus.countryCodePassedOnByPlugin = false;
      return void 0;
    }
    window.sovThankyouStatus.countryCodePassedOnByPlugin = true;
    const countrySettings = settings.countries[country];
    const languagesSettings = countrySettings == null ? void 0 : countrySettings.languages;
    if (!languagesSettings) {
      return void 0;
    }
    const languagesSettingsList = Object.values(languagesSettings);
    if ((languagesSettingsList == null ? void 0 : languagesSettingsList.length) === 1) {
      const languageSettings = languagesSettingsList[0];
      return languageSettings;
    }
    if ((languagesSettingsList == null ? void 0 : languagesSettingsList.length) > 1) {
      const languageKey = language || detectLanguageCode();
      const languageSettings = languagesSettings[languageKey];
      if (!languageSettings) {
        return void 0;
      }
      return languageSettings;
    }
    return void 0;
  }
  function detectLanguageCode() {
    const htmlLang = document.documentElement.lang.split("-")[0];
    if (htmlLang) {
      return htmlLang;
    }
    return navigator.language.split("-")[0];
  }
  void sovendusThankYou();
})();
//# sourceMappingURL=thankyou-page.js.map
