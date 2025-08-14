(function(){
  // Utility: pick likely password inputs on login/register forms
  function detectInputs() {
    const uniques = new Set();
    const picks = [
      document.querySelector('input[name="pwd"]'),       // wp-login.php (login)
      document.querySelector('input[name="pass1"]'),     // wp-login.php (register/profile)
      document.querySelector('input[name="user_pass"]'), // some forms
      document.querySelector('input[type="password"]')   // fallback
    ].filter(Boolean);
    // Deduplicate
    return picks.filter(el => {
      const key = el.name + ':' + (el.id || '') + ':' + (el.form ? el.form.id || '' : '');
      if (uniques.has(key)) return false;
      uniques.add(key);
      return true;
    });
  }

  // Requirement checks (min length comes from PHP; default 6)
  const cfg = window.mqStrongPassCfg || {};
  const MIN_LEN = Math.max(1, parseInt(cfg.minLen || 6, 10));

  function evaluate(pw) {
    const val = String(pw || '');
    return {
      lower: /[a-z]/.test(val),
      upper: /[A-Z]/.test(val),
      num:   /\d/.test(val),
      spec:  /[^a-zA-Z0-9]/.test(val),
      len:   val.length >= MIN_LEN,
      empty: val.length === 0
    };
  }

  // Paint the SSR checklist + notice class based on current state
  function renderState(state) {
    const notice = document.getElementById('mq-pass-notice');
    const list   = document.getElementById('mq-pass-req');
    if (!notice || !list) return;

    // Reset classes to info by default (SSR state)
    notice.classList.remove('notice-error', 'notice-success');
    notice.classList.add('notice-info');

    // Color checklist items
    const setColor = (sel, ok) => {
      const li = list.querySelector('li[data-req="' + sel + '"]');
      if (!li) return;
      // default neutral for SSR
      if (state.empty) {
        li.style.color = '';
        return;
      }
      li.style.color = ok ? '#157347' /* green-ish */ : '#b32d2e' /* wp error red */;
    };

    setColor('lower', state.lower);
    setColor('upper', state.upper);
    setColor('num',   state.num);
    setColor('spec',  state.spec);
    setColor('len',   state.len);

    if (!state.empty) {
      const allGood = state.lower && state.upper && state.num && state.spec && state.len;
      if (allGood) {
        notice.classList.remove('notice-info', 'notice-error');
        notice.classList.add('notice-success');
      } else {
        // If not fulfilled, show red as requested
        notice.classList.remove('notice-info', 'notice-success');
        notice.classList.add('notice-error');
      }
    }
    // When empty, keep the original info (blue) SSR look.
  }

  // Attach listeners to inputs
  function attach(input) {
    if (!input) return;
    const onInput = () => renderState(evaluate(input.value));
    input.addEventListener('input', onInput);
    // Do NOT fire on load—keep SSR info state until the user types.
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Keep SSR info visible initially, then react as user types
    detectInputs().forEach(attach);
  });
})();
