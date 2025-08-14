(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    const banner = document.getElementById("mq-cookie-banner");
    const acceptBtn = document.getElementById("mq-cookie-accept");
    const rejectBtn = document.getElementById("mq-cookie-reject");

    // Check cookie
    if (document.cookie.includes("mq_cookie_choice=")) {
      banner.style.display = "none";
    }

    function setCookie(value) {
      document.cookie = `mq_cookie_choice=${value}; max-age=${
        24 * 60 * 60
      }; path=/`;
      banner.style.display = "none";
    }

    if (acceptBtn)
      acceptBtn.addEventListener("click", () => setCookie("accepted"));
    if (rejectBtn)
      rejectBtn.addEventListener("click", () => setCookie("rejected"));
  });
})();
