/* global djcLocalization */
import RegenerateDataTransients from './regenerateDataTransients';

$(function() {

  // Rebuild thumbnail ajax action
  const regenerateDataTransients = new RegenerateDataTransients({
    ajaxAction: 'djc_rebuild_items_transients_ajax',
    nonceField: '#djc_rebuild_items_nonce',
    msgSelector: '.js-djc-msg',
  });

  $('.js-djc-rebuild').on('click', (event) => {
    event.preventDefault();

    const confirmAction = confirm(djcLocalization.confirmRebuildAction); // eslint-disable-line no-alert
    if (confirmAction) {
      regenerateDataTransients.rebuild();
    }
  });

  // Rebuild thumbnail ajax action
  const regenerateDataListTransients = new RegenerateDataTransients({
    ajaxAction: 'djc_rebuild_lists_transients_ajax',
    nonceField: '#djc_rebuild_lists_nonce',
    msgSelector: '.js-djc-msg',
  });

  $('.js-djc-rebuild-data-list').on('click', (event) => {
    event.preventDefault();
    
    const confirmAction = confirm(djcLocalization.confirmRebuildAction); // eslint-disable-line no-alert
    if (confirmAction) {
      const actionFilter = $(event.target).attr('data-action-filter');
      regenerateDataListTransients.rebuild(actionFilter);
    }
  });
  
});
