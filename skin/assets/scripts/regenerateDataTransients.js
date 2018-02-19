/* global djcLocalization */

export default class RegenerateDataTransients {
  constructor(options) {
    this.ajaxAction = options.ajaxAction;
    this.nonceField = options.nonceField;
    this.msgSelector = options.msgSelector;

    this.$nonceField = $(this.nonceField);
    this.$msg = $(this.msgSelector);
  }

  rebuild(filter) {
    let actionFilter = '';

    if (typeof filter !== 'undefined') {
      actionFilter = filter;
    }

    const data = {
      action: this.ajaxAction,
      djcRebuildNonce: this.$nonceField.val(),
      actionFilter,
    };
    
    $.post(djcLocalization.ajaxurl, data, (response) => {
      this.setMsg(response);
    }, 'json');
  }

  setMsg(data) {
    if (typeof data === 'undefined') {
      return false;
    }

    this.$msg.html(`<div class="notice notice-${data.status}"><p>${data.msg}</p></div>`);
    return false;
  }
}
