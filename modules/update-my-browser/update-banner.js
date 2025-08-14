(function (window, document) {
  "use strict";

  // Version data
  const BROWSER_DATA = {
    chrome: {
      name: "Google Chrome",
      current: "131", // 131 => testing - 160
      minimum: "120", // 120 => testing - 140
      critical: "110",
      downloadUrl: "https://www.google.com/chrome/",
    },
    firefox: {
      name: "Mozilla Firefox",
      current: "130",
      minimum: "120",
      critical: "110",
      downloadUrl: "https://www.mozilla.org/firefox/",
    },
    safari: {
      name: "Safari",
      current: "17.6",
      minimum: "16.0",
      critical: "15.0",
      downloadUrl: "https://www.apple.com/safari/",
    },
    edge: {
      name: "Microsoft Edge",
      current: "131",
      minimum: "120",
      critical: "110",
      downloadUrl: "https://www.microsoft.com/edge",
    },
    opera: {
      name: "Opera",
      current: "113",
      minimum: "105",
      critical: "95",
      downloadUrl: "https://www.opera.com/",
    },
  };

  // UA detection (Opera first)
  const BrowserDetect = {
    init() {
      const ua = navigator.userAgent || "";
      const vendor = (navigator.vendor || "").toLowerCase();
      let m;
      if ((m = ua.match(/\bOPR\/([\d.]+)/))) {
        this.browser = "opera";
        this.version = m[1];
      } else if ((m = ua.match(/\bEdg\/([\d.]+)/))) {
        this.browser = "edge";
        this.version = m[1];
      } else if ((m = ua.match(/\bFirefox\/([\d.]+)/))) {
        this.browser = "firefox";
        this.version = m[1];
      } else if (
        /Safari\//.test(ua) &&
        !/Chrome|CriOS|OPR|Edg/.test(ua) &&
        vendor.includes("apple")
      ) {
        m = ua.match(/\bVersion\/([\d.]+)/);
        this.browser = "safari";
        this.version = (m && m[1]) || "0";
      } else if (
        /Chrome\/[\d.]+/.test(ua) &&
        !/OPR|Edg/.test(ua) &&
        vendor.includes("google")
      ) {
        m = ua.match(/\bChrome\/([\d.]+)/);
        this.browser = "chrome";
        this.version = (m && m[1]) || "0";
      } else {
        this.browser = "unknown";
        this.version = "0";
      }
      return {
        browser: this.browser,
        version: parseFloat(this.version),
        isSupported: this.browser !== "unknown",
      };
    },
  };

  // Version status
  const StatusChecker = {
    getStatus(browser, version) {
      const d = BROWSER_DATA[browser];
      if (!d) return "unsupported";
      const cur = parseFloat(d.current),
        min = parseFloat(d.minimum),
        crit = parseFloat(d.critical);
      if (version >= cur) return "latest";
      if (version >= min) return "update";
      if (version >= crit) return "warning";
      return "critical";
    },
  };

  // Cookies
  const CookieManager = {
    set(name, value, days) {
      const expires = new Date(Date.now() + days * 864e5).toUTCString();
      document.cookie = `${name}=${value};expires=${expires};path=/`;
    },
    get(name) {
      const k = name + "=";
      return (
        document.cookie
          .split(";")
          .map((s) => s.trim())
          .find((c) => c.startsWith(k))
          ?.slice(k.length) || null
      );
    },
    exists(name) {
      return !!this.get(name);
    },
  };

  // UI binder
  const NotificationWidget = {
    headerFor(status) {
      const h = {
        update: {
          title: "Browser Update Available",
          subtitle:
            "A newer version of your browser is available for better performance",
        },
        warning: {
          title: "Browser Security Alert",
          subtitle: "Your browser needs an important security update",
        },
        critical: {
          title: "Critical Security Warning",
          subtitle: "Your browser is critically outdated and may be vulnerable",
        },
      };
      return h[status] || h.warning;
    },
    iconFor(status) {
      const icons = {
        update: "\uD83D\uDD27",
        warning: "\u26A0\uFE0F",
        critical: "\uD83D\uDEA8",
      }; // wrench, warning, siren
      return icons[status] || "\uD83D\uDD27";
    },
    messageFor(d, version, status) {
      const m = {
        update: `Your ${d.name} (v${version}) can be updated to v${d.current} for better performance and security.`,
        warning: `Your ${d.name} (v${version}) is outdated. Please update to v${d.current} for security.`,
        critical: `Your ${d.name} (v${version}) is critically outdated and may be insecure. Update to v${d.current} immediately.`,
      };
      return m[status] || m.warning;
    },
    show(browser, version, status, config = {}) {
      const el = document.getElementById("browser-notify-bar");
      if (!el) return;

      const d = BROWSER_DATA[browser];
      const headers = this.headerFor(status);

      const iconEl = el.querySelector(".bnu-icon");
      const msgEl = el.querySelector(".bnu-message");
      const nameEl = el.querySelector(".bnu-browser-name");
      const linkEl = el.querySelector(".bnu-download-link");
      const titleEl = el.querySelector(".bnu-header-title");
      const subtitleEl = el.querySelector(".bnu-header-subtitle");

      if (iconEl) iconEl.textContent = this.iconFor(status);
      if (msgEl) msgEl.textContent = this.messageFor(d, version, status);
      if (nameEl) nameEl.textContent = d.name;
      if (titleEl) titleEl.textContent = headers.title;
      if (subtitleEl) subtitleEl.textContent = headers.subtitle;

      const perBrowserOverride =
        (config.redirectUrlFor && config.redirectUrlFor[browser]) || null;
      const url = perBrowserOverride || config.redirectUrl || d.downloadUrl;
      if (linkEl) {
        linkEl.href = url;
        linkEl.textContent = `Update ${d.name}`;
      }

      // close button (bind once)
      const closeBtn = el.querySelector(".bnu-close");
      if (closeBtn && !closeBtn.dataset.bound) {
        closeBtn.dataset.bound = "1";
        closeBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          this.hide(true);
        });
      }

      // reveal
      el.style.display = "block";
      requestAnimationFrame(() => el.classList.add("bnu-visible"));
      document.body.classList.add("bnu-body-adjusted");
    },
    hide(persistent = false) {
      const el = document.getElementById("browser-notify-bar");
      if (!el) return;
      el.classList.remove("bnu-visible");
      el.style.display = "none";
      document.body.classList.remove("bnu-body-adjusted");
      if (persistent) CookieManager.set("browser_notify_hidden", "1", 1);
    },
  };

  // Entrypoint
  const BrowserNotify = {
    init(config = {}) {
      const bar = document.getElementById("browser-notify-bar");
      if (!bar) return;

      if (CookieManager.exists("browser_notify_hidden"))
        return NotificationWidget.hide();

      if (
        config.showOnMobile !== true &&
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
          navigator.userAgent
        )
      ) {
        return NotificationWidget.hide();
      }

      const det = BrowserDetect.init();
      if (!det.isSupported) return NotificationWidget.hide();

      const status = StatusChecker.getStatus(det.browser, det.version);
      if (status === "latest") return NotificationWidget.hide(); // keep hidden
      NotificationWidget.show(det.browser, det.version, status, config);
    },
  };
  // Auto-init
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => BrowserNotify.init());
  } else {
    BrowserNotify.init();
  }
  window.BrowserNotify = BrowserNotify;
})(window, document);
