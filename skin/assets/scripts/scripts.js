/* global djcLocalization */
import RegenerateDataTransients from './regenerateDataTransients';

$(function() {

  // Rebuild thumbnail ajax action
  const regenerateDataTransients = new RegenerateDataTransients({
    ajaxAction: 'djc_rebuild_all_transients_ajax',
    nonceField: '#djc_rebuild_nonce',
    msgSelector: '.js-djc-msg',
  });

  $('.js-djc-rebuild').on('click', (event) => {
    event.preventDefault();

    const confirmAction = confirm(djcLocalization.confirmRebuildAction); // eslint-disable-line no-alert
    if (confirmAction) {
      regenerateDataTransients.rebuild();
    }
  });
  
});
