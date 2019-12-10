(function($) {
  'use strict';

  Drupal.behaviors.tcaNode = {
    attach: function (context, settings) {

      // Set the summary for the settings form.
      $('fieldset.tca-settings-form').drupalSetSummary(function() {
        var $tcaActive = $('.tca-active-setting input:checked');

        // Get the label of the selected action.
        var summary = $('label[for=' + $tcaActive.attr('id') + ']').text();
        return Drupal.checkPlain(summary);
      });

    }
  };

})(jQuery);
