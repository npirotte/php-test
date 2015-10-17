'use strict';

window.invoicingModule = {};(function (invoicingModule) {
  invoicingModule.invoiceFormCtrl = function (context) {
    var $context = $(context);
    var $autoComplete = $context.find('[data-autocomplete]');

    $autoComplete.each(function () {
      var $this = $(this);
      var url = config.base + '/api/' + $this.data('autocomplete') + '?q=%QUERY';

      var source = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: url,
          wildcard: '%QUERY'
        }
      });

      $this.attr('autocomplete', 'off');
      $this.typeahead(null, {
        name: 'companies',
        display: 'email',
        source: source,
        classNames: {
          menu: 'mdl-shadow--2dp',
          input: 'mdl-textfield__input'
        }
      });
      // overrive material floating label
      $this.on('focus blur', function () {
        $this.closest('.mdl-textfield').toggleClass('is-focused');
      });
      $this.on('typeahead:change', function () {
        $this.closest('.mdl-textfield').toggleClass('is-dirty');
      });
    });
  };
})(window.invoicingModule);