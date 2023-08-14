/**
 * @file
 * Javascript utility for the admin settings form.
 */

(function ($) {
  'use strict';

/**
 * Provide the summary information for the tracking settings vertical tabs.
 */
  Drupal.behaviors.trackingSettingsSummary = {
    attach: function () {
      // Make sure this behavior is processed only if drupalSetSummary is defined.
      if (typeof jQuery.fn.drupalSetSummary == 'undefined') {
        return;
      }

      $('#edit-page-vis-settings').drupalSetSummary(function (context) {
        var $radio = $('input[name="visibility_pages"]:checked', context);
        if (!$('textarea[name="pages"]', context).val()) {
          return Drupal.t('Not restricted');
        }
        else {
          if ($radio.val() == 0) {
            return Drupal.t('All pages with exceptions');
          }
          else {
            return Drupal.t('Restricted to certain pages');
          }
        }
      });

      $('#edit-role-vis-settings').drupalSetSummary(function (context) {
        var vals = [];
        var role_type = $('input[name="roles_type"]:checked', context).val();
        $('input[type="checkbox"]:checked', context).each(function () {
          vals.push($.trim($(this).next('label').text()));
        });
        if (!vals.length) {
          return Drupal.t('Not restricted');
        }
        else if (role_type === "1" || role_type === 1) {
          return Drupal.t('Excepted: @roles', {'@roles': vals.join(', ')});
        }
        else {
          return vals.join(', ');
        }
      });
    }
  };
})(jQuery);
