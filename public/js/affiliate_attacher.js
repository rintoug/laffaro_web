/**
 * attachAffiliateTags.js
 * Scans links on the page and appends/overwrites affiliate/ref params per configured rules.
 *
 * Usage:
 *  - Edit rules below or pass custom config to attachAffiliateTags()
 *  - addRule(...) to add a new site rule at runtime
 */

(function globalAffiliateAttacher(){
  // --- Helper: set or replace a query param using URL
  function setQueryParam(href, key, value, { override = true } = {}) {
    try {
      const url = new URL(href, location.href); // support relative URLs
      // If param exists and override is false => keep existing
      if (!override && url.searchParams.has(key)) return url.toString();

      url.searchParams.set(key, value);
      return url.toString();
    } catch (err) {
      // If URL parsing fails (e.g., non-HTTP schema), just return original
      return href;
    }
  }

  // --- Default config (example) - edit tags to your actual affiliate tag values
  const defaultConfig = {
    // When true, existing affiliate param will be overwritten by our value
    overrideExisting: true,
    // Rules: domainPatterns (array of hostname substrings or exact hostnames), param name & value
    rules: [
      // Amazon US
      {
        name: 'amazon-us',
        domains: ['www.amazon.com', 'amazon.com'],
        param: 'tag',
        value: 'tbomoney-20' // <-- replace with your Amazon US tag
      },
      // Amazon UK
      {
        name: 'amazon-uk',
        domains: ['www.amazon.co.uk', 'amazon.co.uk'],
        param: 'tag',
        value: 'yourUKtag-21' // <-- replace with your Amazon UK tag
      },
      // Amazon Canada
      {
        name: 'amazon-ca',
        domains: ['www.amazon.ca', 'amazon.ca'],
        param: 'tag',
        value: 'yourCAtag-20' // <-- replace with your Amazon CA tag
      },
      // Example for another merchant that uses "aff_id"
      {
        name: 'example-store',
        domains: ['www.example.com', 'example.com'],
        param: 'aff_id',
        value: 'affiliate_123'
      }
    ]
  };

  // --- Internal state
  const state = {
    config: JSON.parse(JSON.stringify(defaultConfig)),
    observer: null
  };

  // --- Utility: find rule matching a hostname
  function findRuleForHostname(hostname) {
    const host = hostname.toLowerCase();
    return state.config.rules.find(rule =>
      rule.domains.some(d => d.toLowerCase() === host || host.endsWith('.' + d.toLowerCase()) || d.toLowerCase().endsWith(host))
    );
  }

  // --- Attach a tag to a single anchor element if it matches a rule
  function processAnchor(a) {
    if (!a || !a.href) return;
    // Only handle http(s) links
    if (!/^https?:\/\//i.test(a.href) && !/^\//.test(a.getAttribute('href'))) return;

    let url;
    try {
      url = new URL(a.href, location.href);
    } catch (e) {
      return;
    }

    const rule = findRuleForHostname(url.hostname);
    if (!rule) return;

    const newHref = setQueryParam(a.href, rule.param, rule.value, { override: state.config.overrideExisting });
    if (newHref !== a.href) {
      a.href = newHref;
      // Optional: mark processed to avoid re-processing repeatedly
      a.dataset.affTagged = rule.name;
    }
  }

  // --- Process all anchors currently in the document
  function processAllAnchors(root = document) {
    const anchors = root.querySelectorAll('a[href]');
    anchors.forEach(processAnchor);
  }

  // --- Public API: add rule at runtime
  function addRule(rule) {
    if (!rule || !rule.domains || !rule.param || !rule.value) {
      throw new Error('Invalid rule. Provide domains array, param and value');
    }
    state.config.rules.push(rule);
    // Immediately process anchors because of new rule
    processAllAnchors();
  }

  // --- Start mutation observer to catch dynamic link insertions (optional)
  function startObserver() {
    if (state.observer) return;
    state.observer = new MutationObserver(mutations => {
      for (const m of mutations) {
        // Process new nodes quickly
        if (m.addedNodes && m.addedNodes.length) {
          m.addedNodes.forEach(node => {
            if (node.nodeType !== 1) return; // element nodes only
            if (node.tagName === 'A') {
              processAnchor(node);
            } else {
              processAllAnchors(node);
            }
          });
        }
        // Also process attribute changes for href updates
        if (m.type === 'attributes' && m.target && m.target.tagName === 'A' && m.attributeName === 'href') {
          processAnchor(m.target);
        }
      }
    });

    state.observer.observe(document.documentElement, {
      childList: true,
      subtree: true,
      attributes: true,
      attributeFilter: ['href']
    });
  }

  // --- Stop observer if you need
  function stopObserver() {
    if (!state.observer) return;
    state.observer.disconnect();
    state.observer = null;
  }

  // --- Public initializer
  function attachAffiliateTags(customConfig = {}) {
    // Merge shallow
    state.config = Object.assign({}, state.config, customConfig);
    if (customConfig.rules && Array.isArray(customConfig.rules)) {
      // replace rules if provided
      state.config.rules = customConfig.rules;
    }

    // Initial pass
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => processAllAnchors());
    } else {
      processAllAnchors();
    }

    // Start observing dynamic changes so newly injected links are handled
    startObserver();

    return {
      addRule,
      stopObserver,
      startObserver,
      config: state.config
    };
  }

  // --- Auto-run with default config (comment out if you prefer manual initialization)
  const api = attachAffiliateTags();

  // Expose API to window so it can be used in console or other scripts
  window.AffiliateTagger = api;
})();
