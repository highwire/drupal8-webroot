/**
 * @file
 * Attaches several event listener to a web page.
 */

(function ($, Drupal, drupalSettings) {

  /* eslint max-nested-callbacks: ["error", 4] */

  'use strict';

  Drupal.google_analytics = {};

  $(document).ready(function () {

    // Attach mousedown, keyup, touchstart events to document only and catch
    // clicks on all elements.
    $(document.body).on('mousedown keyup touchstart', function (event) {
      // Custom events, these might not be surrounded by a link.
      if (drupalSettings.google_analytics.trackCustomEvents) {
        // Custom events.
        $(drupalSettings.google_analytics.trackCustomEvents).each(function( ga_event, settings ) {
          // Catch the closest surrounding link of a clicked element.
          var selector = settings['ga_event_settings']['selector'];

          // Check if the element we clicked matches our selector.
          if ($(event.target).is(selector)) {
            var ga_event_settings = settings['ga_event_settings'];

            // Loop through our fields and check for %target.href
            // placeholder.
            $.each(ga_event_settings, function ( fieldName, value ) {
              if (value == '%target.href') {
                var targetHref = ($(event.target).closest('a')[0].href);
                ga_event_settings[fieldName] = targetHref;
              }
            });

            var category = ga_event_settings['event_category'];
            var action = ga_event_settings['event_action'];
            var label = ga_event_settings['event_label'];
            var value = ga_event_settings['event_value'];

            // Value must be an integer, if not, set to 0.
            if(Math.floor(value) == value && $.isNumeric(value)) {
            }
            else {
              value = 0;
            }

            // Send event.
            Drupal.google_analytics.sendEvent(category, action, label, value);
          }
        });
      }

      // Track any items with class .ga-custom-event and data-ga- attributes.
      $(event.target).closest('.ga-custom-event').each(function () {
        // Custom events standard tracking attributes.
        if (drupalSettings.google_analytics.trackStandardAttributes) {
          var $that = $(this);
          var category = $that.data('ga-category');
          var action = $that.data('ga-action');
          var label = $that.data('ga-label');
          var value = $that.data('ga-value');

          // Send event.
          Drupal.google_analytics.sendEvent(category, action, label, value);
        }
      });

      // Catch the closest surrounding link of a clicked element.
      $(event.target).closest('a,area').each(function () {

        // Is the clicked URL internal?
        if (Drupal.google_analytics.isInternal(this.href)) {
          // Skip 'click' tracking, if custom tracking events are bound.
          if ($(this).is('.colorbox') && (drupalSettings.google_analytics.trackColorbox)) {
            // Do nothing here. The custom event will handle all tracking.
            // console.info('Click on .colorbox item has been detected.');
          }
          // Is download tracking activated and the file extension configured
          // for download tracking?
          else if (drupalSettings.google_analytics.trackDownload && Drupal.google_analytics.isDownload(this.href)) {
            // Download link clicked.
            gtag('event', Drupal.google_analytics.getDownloadExtension(this.href).toUpperCase(), {
              event_category: 'Downloads',
              event_label: Drupal.google_analytics.getPageUrl(this.href),
              transport_type: 'beacon'
            });
          }
          else if (Drupal.google_analytics.isInternalSpecial(this.href)) {
            // Keep the internal URL for Google Analytics website overlay intact.
            // @todo: May require tracking ID
            gtag('config', drupalSettings.google_analytics.account, {
              page_path: Drupal.google_analytics.getPageUrl(this.href),
              transport_type: 'beacon'
            });
          }
        }
        else {
          if (drupalSettings.google_analytics.trackMailto && $(this).is("a[href^='mailto:'],area[href^='mailto:']")) {
            // Mailto link clicked.
            gtag('event', 'Click', {
              event_category: 'Mails',
              event_label: this.href.substring(7),
              transport_type: 'beacon'
            });
          }
          else if (drupalSettings.google_analytics.trackOutbound && this.href.match(/^\w+:\/\//i)) {
            if (drupalSettings.google_analytics.trackDomainMode !== 2 || (drupalSettings.google_analytics.trackDomainMode === 2 && !Drupal.google_analytics.isCrossDomain(this.hostname, drupalSettings.google_analytics.trackCrossDomains))) {
              // External link clicked / No top-level cross domain clicked.
              gtag('event', 'Click', {
                event_category: 'Outbound links',
                event_label: this.href,
                transport_type: 'beacon'
              });
            }
          }
        }
      });
    });

    // Track hash changes as unique pageviews, if this option has been enabled.
    if (drupalSettings.google_analytics.trackUrlFragments) {
      window.onhashchange = function () {
        gtag('config', drupalSettings.google_analytics.account, {
          page_path: location.pathname + location.search + location.hash
        });
      };
    }

    // Colorbox: This event triggers when the transition has completed and the
    // newly loaded content has been revealed.
    if (drupalSettings.google_analytics.trackColorbox) {
      $(document).on('cbox_complete', function () {
        var href = $.colorbox.element().attr('href');
        if (href) {
          gtag('config', drupalSettings.google_analytics.account, {
            page_path: Drupal.google_analytics.getPageUrl(href)
          });
        }
      });
    }

  });

  /**
   * Check whether the hostname is part of the cross domains or not.
   *
   * @param {string} hostname
   *   The hostname of the clicked URL.
   * @param {array} crossDomains
   *   All cross domain hostnames as JS array.
   *
   * @return {boolean} isCrossDomain
   */
  Drupal.google_analytics.isCrossDomain = function (hostname, crossDomains) {
    return $.inArray(hostname, crossDomains) > -1 ? true : false;
  };

  /**
   * Check whether this is a download URL or not.
   *
   * @param {string} url
   *   The web url to check.
   *
   * @return {boolean} isDownload
   */
  Drupal.google_analytics.isDownload = function (url) {
    var isDownload = new RegExp('\\.(' + drupalSettings.google_analytics.trackDownloadExtensions + ')([\?#].*)?$', 'i');
    return isDownload.test(url);
  };

  /**
   * Check whether this is an absolute internal URL or not.
   *
   * @param {string} url
   *   The web url to check.
   *
   * @return {boolean} isInternal
   */
  Drupal.google_analytics.isInternal = function (url) {
    var isInternal = new RegExp('^(https?):\/\/' + window.location.host, 'i');
    return isInternal.test(url);
  };

  /**
   * Check whether this is a special URL or not.
   *
   * URL types:
   *  - gotwo.module /go/* links.
   *
   * @param {string} url
   *   The web url to check.
   *
   * @return {boolean} isInternalSpecial
   */
  Drupal.google_analytics.isInternalSpecial = function (url) {
    var isInternalSpecial = new RegExp('(\/go\/.*)$', 'i');
    return isInternalSpecial.test(url);
  };

  /**
   * Extract the relative internal URL from an absolute internal URL.
   *
   * Examples:
   * - https://mydomain.com/node/1 -> /node/1
   * - https://example.com/foo/bar -> https://example.com/foo/bar
   *
   * @param {string} url
   *   The web url to check.
   *
   * @return {string} getPageUrl
   *   Internal website URL.
   */
  Drupal.google_analytics.getPageUrl = function (url) {
    var extractInternalUrl = new RegExp('^(https?):\/\/' + window.location.host, 'i');
    return url.replace(extractInternalUrl, '');
  };

  /**
   * Extract the download file extension from the URL.
   *
   * @param {string} url
   *   The web url to check.
   *
   * @return {string} getDownloadExtension
   *   The file extension of the passed url. e.g. 'zip', 'txt'
   */
  Drupal.google_analytics.getDownloadExtension = function (url) {
    var extractDownloadextension = new RegExp('\\.(' + drupalSettings.google_analytics.trackDownloadExtensions + ')([\?#].*)?$', 'i');
    var extension = extractDownloadextension.exec(url);
    return (extension === null) ? '' : extension[1];
  };

  /**
   * Helper function for validating and sending GA event.
   * 
   * @param {string} category
   *   The event category.
   * @param {string} action
   *   The event action.
   * @param {string} label
   *   The event label.
   * @param {int} value
   *   The event value.
   */
  Drupal.google_analytics.sendEvent = function (category, action, label, value) {

    // Validate required values.
    if (category.length === 0
        || $.type(category) !== 'string'
        || action.length === 0
        || $.type(action) !== 'string') {
      return;
    }

    var ga_values = {
      hitType: 'event',
      transport: 'beacon',
      eventCategory: category,
      eventAction: action
    };

    // Validate additonal values.
    if (label.length !== 0 && $.type(label) === "string") {
      ga_values.eventLabel = label;
    }
    if (value.length !== 0 && $.type(value) === "number") {
      ga_values.eventValue = value;
    }

    // Make sure ga is defined.
    if (typeof ga !== 'undefined') {
      ga('send', ga_values);
    }
    else if (typeof _gaq.push !== 'undefined') {
      _gaq.push([ '_trackEvent', category, action, label, value ]);
    }
  }

})(jQuery, Drupal, drupalSettings);
