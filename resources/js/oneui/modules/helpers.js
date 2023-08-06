/*
 *  Document   : helpers.js
 *  Author     : pixelcave
 *  Description: Various helpers for plugin inits or helper functionality
 *
 */

// Helper variables
let jqSparklineResize = false;
let jqSparklineTimeout;

// Helpers
export default class Helpers {
  /*
   * Run helpers
   *
   */
  static run(helpers, options = {}) {
    let helperList = {
      // Bootstrap
      'bs-tooltip': () => this.bsTooltip(),
      'bs-popover': () => this.bsPopover(),

      // OneUI
      'one-toggle-class': () => this.oneToggleClass(),
      'one-year-copy': () => this.oneYearCopy(),
      // 'one-ripple': () => this.oneRipple(),
      'one-print': () => this.onePrint(),
      'one-table-tools-sections': () => this.oneTableToolsSections(),
      'one-table-tools-checkable': () => this.oneTableToolsCheckable(),

      // JavaScript
      // 'js-ckeditor': () => this.jsCkeditor(),
      'js-ckeditor5': () => this.jsCkeditor5(),
      // 'js-simplemde': () => this.jsSimpleMDE(),
      // 'js-highlightjs': () => this.jsHighlightjs(),
      // 'js-flatpickr': () => this.jsFlatpickr(),

      // jQuery
      // 'jq-appear': () => this.jqAppear(),
      'jq-magnific-popup': () => this.jqMagnific(),
      'jq-slick': () => this.jqSlick(),
      // 'jq-datepicker': () => this.jqDatepicker(),
      // 'jq-masked-inputs': () => this.jqMaskedInputs(),
      'jq-select2': () => this.jqSelect2(),
      'jq-notify': (options) => this.jqNotify(options),
      'jq-easy-pie-chart': () => this.jqEasyPieChart(),
      'jq-maxlength': () => this.jqMaxlength(),
      'jq-rangeslider': () => this.jqRangeslider(),
      // 'jq-sparkline': () => this.jqSparkline(),
      // 'jq-validation': () => this.jqValidation(),
    };

    if (helpers instanceof Array) {
      for (let index in helpers) {
        if (helperList[helpers[index]]) {
          helperList[helpers[index]](options);
        }
      }
    } else {
      if (helperList[helpers]) {
        helperList[helpers](options);
      }
    }
  }

  /*
   ********************************************************************************************
   *
   * Init helpers for Bootstrap plugins
   *
   *********************************************************************************************
   */

  /*
   * Bootstrap Tooltip, for more examples you can check out https://getbootstrap.com/docs/5.3/components/tooltips/
   *
   * Helpers.run('bs-tooltip');
   *
   * Example usage:
   *
   * <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" title="Tooltip Text">Example</button> or
   * <button type="button" class="btn btn-primary js-bs-tooltip" title="Tooltip Text">Example</button>
   *
   */
  static bsTooltip() {
    let elements = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]:not(.js-bs-tooltip-enabled), .js-bs-tooltip:not(.js-bs-tooltip-enabled)'));

    window.helperBsTooltips = elements.map(el => {
      // Add .js-bs-tooltip-enabled class to tag it as activated
      el.classList.add('js-bs-tooltip-enabled');

      // Init Bootstrap Tooltip
      return new bootstrap.Tooltip(el, {
        container: el.dataset.bsContainer || '#page-container',
        animation: el.dataset.bsAnimation && el.dataset.bsAnimation.toLowerCase() == 'true' ? true : false,
      })
    });
  }

  /*
   * Bootstrap Popover, for more examples you can check out https://getbootstrap.com/docs/5.3/components/popovers/
   *
   * Helpers.run('bs-popover');
   *
   * Example usage:
   *
   * <button type="button" class="btn btn-primary" data-bs-toggle="popover" title="Popover Title" data-bs-content="This is the content of the Popover">Example</button> or
   * <button type="button" class="btn btn-primary js-bs-popover" title="Popover Title" data-bs-content="This is the content of the Popover">Example</button>
   *
   */
  static bsPopover() {
    let elements = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]:not(.js-bs-popover-enabled), .js-bs-popover:not(.js-bs-popover-enabled)'));

    window.helperBsPopovers = elements.map(el => {
      // Add .js-bs-popover-enabled class to tag it as activated
      el.classList.add('js-bs-popover-enabled');

      // Init Bootstrap Popover
      return new bootstrap.Popover(el, {
        container: el.dataset.bsContainer || '#page-container',
        animation: el.dataset.bsAnimation && el.dataset.bsAnimation.toLowerCase() == 'true' ? true : false,
        trigger: el.dataset.bsTrigger || 'hover focus',
      })
    });
  }

  /*
   ********************************************************************************************
   *
   * JS helpers to add custom functionality
   *
   *********************************************************************************************
   */

  /*
   * Toggle class on element click
   *
   * Helpers.run('one-toggle-class');
   *
   * Example usage (on button click, "exampleClass" class is toggled on the element with id "elementID"):
   *
   * <button type="button" class="btn btn-primary" data-toggle="class-toggle" data-target="#elementID" data-class="exampleClass">Toggle</button>
   *
   * or
   *
   * <button type="button" class="btn btn-primary js-class-toggle" data-target="#elementID" data-class="exampleClass">Toggle</button>
   *
   */
  static oneToggleClass() {
    let elements = document.querySelectorAll('[data-toggle="class-toggle"]:not(.js-class-toggle-enabled), .js-class-toggle:not(.js-class-toggle-enabled)');

    elements.forEach(el => {
      el.addEventListener('click', () => {
        // Add .js-class-toggle-enabled class to tag it as activated
        el.classList.add('js-class-toggle-enabled');

        // Get all classes
        let cssClasses = el.dataset.class ? el.dataset.class.split(' ') : false;

        // Toggle class on target elements
        document.querySelectorAll(el.dataset.target).forEach(targetEl => {
          if (cssClasses) {
            cssClasses.forEach(cls => {
              targetEl.classList.toggle(cls);
            });
          }
        });
      });
    });
  }

  /*
   * Add the correct copyright year to an element
   *
   * Helpers.run('one-year-copy');
   *
   * Example usage (it will get populated with current year if empty or will append it to specified year if needed):
   *
   * <span data-toggle="year-copy"></span> or
   * <span data-toggle="year-copy">2018</span>
   *
   */
  static oneYearCopy() {
    let elements = document.querySelectorAll('[data-toggle="year-copy"]:not(.js-year-copy-enabled)');

    elements.forEach(el => {
      let date = new Date();
      let currentYear = date.getFullYear();
      let baseYear = el.textContent || currentYear;

      // Add .js-year-copy-enabled class to tag it as activated
      el.classList.add('js-year-copy-enabled');

      // Set the correct year
      el.textContent = (parseInt(baseYear) >= currentYear) ? currentYear : baseYear + '-' + currentYear.toString().substr(2, 2);
    });
  }



  /*
   * Print Page functionality
   *
   * Helpers.run('one-print');
   *
   */
  static onePrint() {
    // Store all #page-container classes
    let lPage = document.getElementById('page-container');
    let pageCls = lPage.className;

    console.log(pageCls);

    // Remove all classes from #page-container
    lPage.classList = '';

    // Print the page
    window.print();

    // Restore all #page-container classes
    lPage.classList = pageCls;
  }

  /*
   * Table sections functionality
   *
   * Helpers.run('one-table-tools-sections');
   *
   * Example usage:
   *
   * Please check out the Table Helpers page for complete markup examples
   *
   */
  static oneTableToolsSections() {
    let tables = document.querySelectorAll('.js-table-sections:not(.js-table-sections-enabled)');

    tables.forEach(table => {
      // Add .js-table-sections-enabled class to tag it as activated
      table.classList.add('js-table-sections-enabled');

      // When a row is clicked in tbody.js-table-sections-header
      table.querySelectorAll('.js-table-sections-header > tr').forEach(tr => {
        tr.addEventListener('click', e => {
          if (e.target.type !== 'checkbox'
            && e.target.type !== 'button'
            && e.target.tagName.toLowerCase() !== 'a'
            && e.target.parentNode.nodeName.toLowerCase() !== 'a'
            && e.target.parentNode.nodeName.toLowerCase() !== 'button'
            && e.target.parentNode.nodeName.toLowerCase() !== 'label'
            && !e.target.parentNode.classList.contains('custom-control')) {
            let tbody = tr.parentNode;
            let tbodyAll = table.querySelectorAll('tbody');

            if (!tbody.classList.contains('show')) {
              if (tbodyAll) {
                tbodyAll.forEach(tbodyEl => {
                  tbodyEl.classList.remove('show');
                  tbodyEl.classList.remove('table-active');
                });
              }
            }

            tbody.classList.toggle('show');
            tbody.classList.toggle('table-active');
          }
        });
      });
    });
  }

  /*
   * Checkable table functionality
   *
   * Helpers.run('one-table-tools-checkable');
   *
   * Example usage:
   *
   * Please check out the Table Helpers page for complete markup examples
   *
   */
  static oneTableToolsCheckable() {
    let tables = document.querySelectorAll('.js-table-checkable:not(.js-table-checkable-enabled)');

    tables.forEach(table => {
      // Add .js-table-checkable-enabled class to tag it as activated
      table.classList.add('js-table-checkable-enabled');

      // When a checkbox is clicked in thead
      table.querySelector('thead input[type=checkbox]').addEventListener('click', e => {
        // Check or uncheck all checkboxes in tbody
        table.querySelectorAll('tbody input[type=checkbox]').forEach(checkbox => {
          checkbox.checked = e.currentTarget.checked;

          // Update Row classes
          this.tableToolscheckRow(checkbox, e.currentTarget.checked);
        });
      });

      // When a checkbox is clicked in tbody
      table.querySelectorAll('tbody input[type=checkbox], tbody input + label').forEach(checkbox => {
        checkbox.addEventListener('click', e => {
          let checkboxHead = table.querySelector('thead input[type=checkbox]');

          // Adjust checkbox in thead
          if (!checkbox.checked) {
            checkboxHead.checked = false
          } else {
            if (table.querySelectorAll('tbody input[type=checkbox]:checked').length === table.querySelectorAll('tbody input[type=checkbox]').length) {
              checkboxHead.checked = true;
            }
          }

          // Update Row classes
          this.tableToolscheckRow(checkbox, checkbox.checked);
        });
      });

      // When a row is clicked in tbody
      table.querySelectorAll('tbody > tr').forEach(tr => {
        tr.addEventListener('click', e => {
          if (e.target.type !== 'checkbox'
            && e.target.type !== 'button'
            && e.target.tagName.toLowerCase() !== 'a'
            && e.target.parentNode.nodeName.toLowerCase() !== 'a'
            && e.target.parentNode.nodeName.toLowerCase() !== 'button'
            && e.target.parentNode.nodeName.toLowerCase() !== 'label'
            && !e.target.parentNode.classList.contains('custom-control')) {
            let checkboxHead = table.querySelector('thead input[type=checkbox]');
            let checkbox = e.currentTarget.querySelector('input[type=checkbox]');

            // Update row's checkbox status
            checkbox.checked = !checkbox.checked;

            // Update Row classes
            this.tableToolscheckRow(checkbox, checkbox.checked);

            // Adjust checkbox in thead
            if (!checkbox.checked) {
              checkboxHead.checked = false
            } else {
              if (table.querySelectorAll('tbody input[type=checkbox]:checked').length === table.querySelectorAll('tbody input[type=checkbox]').length) {
                checkboxHead.checked = true;
              }
            }
          }
        });
      });
    });
  }

  // Checkable table functionality helper - Checks or unchecks table row
  static tableToolscheckRow(checkbox, checkedStatus) {
    if (checkedStatus) {
      checkbox.closest('tr').classList.add('table-active');
    } else {
      checkbox.closest('tr').classList.remove('table-active');
    }
  }




  static jsCkeditor5() {
    let ckeditor5Inline = document.querySelector('#js-ckeditor5-inline');
    let ckeditor5Full = document.querySelector('#js-ckeditor5-classic');



    // Init full text editor
    if (ckeditor5Full) {
      ClassicEditor
        .create(document.querySelector('#js-ckeditor5-classic'))
        .then(editor => {
          window.editor = editor;
        })
        .catch(error => {
          console.error('There was a problem initializing the classic editor.', error);
        });
    }
  }








  /*
   * Magnific Popup functionality, for more examples you can check out http://dimsemenov.com/plugins/magnific-popup/
   *
   * Helpers.run('jq-magnific-popup');
   *
   * Example usage:
   *
   * Please check out the Gallery page in Components for complete markup examples
   *
   */
  static jqMagnific() {
    // Gallery init
    jQuery('.js-gallery:not(.js-gallery-enabled)').each((index, element) => {
      // Add .js-gallery-enabled class to tag it as activated and init it
      jQuery(element).addClass('js-gallery-enabled').magnificPopup({
        delegate: 'a.img-lightbox',
        type: 'image',
        gallery: {
          enabled: true
        }
      });
    });
  }

  /*
   * Slick init, for more examples you can check out http://kenwheeler.github.io/slick/
   *
   * Helpers.run('jq-slick');
   *
   * Example usage:
   *
   * <div class="js-slider">
   *   <div>Slide #1</div>
   *   <div>Slide #2</div>
   *   <div>Slide #3</div>
   * </div>
   *
   */
  static jqSlick() {
    // Get each slider element (with .js-slider class)
    jQuery('.js-slider:not(.js-slider-enabled)').each((index, element) => {
      let el = jQuery(element);

      // Add .js-slider-enabled class to tag it as activated and init it
      el.addClass('js-slider-enabled').slick({
        arrows: el.data('arrows') || false,
        dots: el.data('dots') || false,
        slidesToShow: el.data('slides-to-show') || 1,
        centerMode: el.data('center-mode') || false,
        autoplay: el.data('autoplay') || false,
        autoplaySpeed: el.data('autoplay-speed') || 3000,
        infinite: typeof el.data('infinite') === 'undefined' ? true : el.data('infinite')
      });
    });
  }




  /*
   * Select2, for more examples you can check out https://github.com/select2/select2
   *
   * Helpers.run('jq-select2');
   *
   * Example usage:
   *
   * <select class="js-select2 form-control" style="width: 100%;" data-placeholder="Choose one..">
   *   <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
   *   <option value="1">HTML</option>
   *   <option value="2">CSS</option>
   *   <option value="3">Javascript</option>
   * </select>
   *
   */
  static jqSelect2() {
    // Init Select2 (with .js-select2 class)
    jQuery('.js-select2:not(.js-select2-enabled)').each((index, element) => {
      let el = jQuery(element);

      // Add .js-select2-enabled class to tag it as activated and init it
      el.addClass('js-select2-enabled').select2({
        placeholder: el.data('placeholder') || false,
        tags: el.data('tags') || false,
        dropdownParent: document.querySelector(el.data('container') || '#page-container'),
      });
    });
  }

  /*
   * Bootstrap Notify, for more examples you can check out http://bootstrap-growl.remabledesigns.com/
   *
   * Helpers.run('jq-notify');
   *
   * Example usage:
   *
   * Please check out the Notifications page for examples
   *
   */
  static jqNotify(options = {}) {
    if (jQuery.isEmptyObject(options)) {
      // Init notifications (with .js-notify class)
      jQuery('.js-notify:not(.js-notify-enabled)').each((index, element) => {
        // Add .js-notify-enabled class to tag it as activated and init it
        jQuery(element).addClass('js-notify-enabled').on('click.pixelcave.helpers', e => {
          let el = jQuery(e.currentTarget);

          // Create notification
          jQuery.notify({
            icon: el.data('icon') || '',
            message: el.data('message'),
            url: el.data('url') || ''
          },
            {
              element: 'body',
              type: el.data('type') || 'info',
              placement: {
                from: el.data('from') || 'top',
                align: el.data('align') || 'right'
              },
              allow_dismiss: true,
              newest_on_top: true,
              showProgressbar: false,
              offset: 20,
              spacing: 10,
              z_index: 1033,
              delay: 5000,
              timer: 1000,
              animate: {
                enter: 'animated fadeIn',
                exit: 'animated fadeOutDown'
              },
              template: `<div data-notify="container" class="col-11 col-sm-4 alert alert-{0} alert-dismissible" role="alert">
  <p class="mb-0">
    <span data-notify="icon"></span>
    <span data-notify="title">{1}</span>
    <span data-notify="message">{2}</span>
  </p>
  <div class="progress" data-notify="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar progress-bar-{0}" style="width: 0%;"></div>
  </div>
  <a href="{3}" target="{4}" data-notify="url"></a>
  <a class="p-2 m-1 text-dark" href="javascript:void(0)" aria-label="Close" data-notify="dismiss">
    <i class="fa fa-times"></i>
  </a>
</div>`
            });
        });
      });
    } else {
      // Create notification
      jQuery.notify({
        icon: options.icon || '',
        message: options.message,
        url: options.url || ''
      },
        {
          element: options.element || 'body',
          type: options.type || 'info',
          placement: {
            from: options.from || 'top',
            align: options.align || 'right'
          },
          allow_dismiss: (options.allow_dismiss === false) ? false : true,
          newest_on_top: (options.newest_on_top === false) ? false : true,
          showProgressbar: options.show_progress_bar ? true : false,
          offset: options.offset || 20,
          spacing: options.spacing || 10,
          z_index: options.z_index || 1033,
          delay: options.delay || 5000,
          timer: options.timer || 1000,
          animate: {
            enter: options.animate_enter || 'animated fadeIn',
            exit: options.animate_exit || 'animated fadeOutDown'
          },
          template: `<div data-notify="container" class="col-11 col-sm-4 alert alert-{0} alert-dismissible" role="alert">
  <p class="mb-0">
    <span data-notify="icon"></span>
    <span data-notify="title">{1}</span>
    <span data-notify="message">{2}</span>
  </p>
  <div class="progress" data-notify="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar progress-bar-{0}" style="width: 0%;"></div>
  </div>
  <a href="{3}" target="{4}" data-notify="url"></a>
  <a class="p-2 m-1 text-dark" href="javascript:void(0)" aria-label="Close" data-notify="dismiss">
    <i class="fa fa-times"></i>
  </a>
</div>`
        });
    }
  }

  /*
   * Easy Pie Chart, for more examples you can check out http://rendro.github.io/easy-pie-chart/
   *
   * Helpers.run('jq-easy-pie-chart');
   *
   * Example usage:
   *
   * <div class="js-pie-chart pie-chart" data-percent="25" data-line-width="2" data-size="100">
   *   <span>..Content..</span>
   * </div>
   *
   */
  static jqEasyPieChart() {
    // Init Easy Pie Charts (with .js-pie-chart class)
    jQuery('.js-pie-chart:not(.js-pie-chart-enabled)').each((index, element) => {
      let el = jQuery(element);

      // Add .js-pie-chart-enabled class to tag it as activated and init it
      el.addClass('js-pie-chart-enabled').easyPieChart({
        barColor: el.data('bar-color') || '#777777',
        trackColor: el.data('track-color') || '#eeeeee',
        lineWidth: el.data('line-width') || 3,
        size: el.data('size') || '80',
        animate: el.data('animate') || 750,
        scaleColor: el.data('scale-color') || false
      });
    });
  }

  /*
   * Bootstrap Maxlength, for more examples you can check out https://github.com/mimo84/bootstrap-maxlength
   *
   * Helpers.run('jq-maxlength');
   *
   * Example usage:
   *
   * <input type="text" class="js-maxlength form-control" maxlength="20">
   *
   */
  static jqMaxlength() {
    // Init Bootstrap Maxlength (with .js-maxlength class)
    jQuery('.js-maxlength:not(.js-maxlength-enabled)').each((index, element) => {
      let el = jQuery(element);

      // Add .js-maxlength-enabled class to tag it as activated and init it
      el.addClass('js-maxlength-enabled').maxlength({
        alwaysShow: el.data('always-show') ? true : false,
        threshold: el.data('threshold') || 10,
        warningClass: el.data('warning-class') || 'badge bg-warning',
        limitReachedClass: el.data('limit-reached-class') || 'badge bg-danger',
        placement: el.data('placement') || 'bottom',
        preText: el.data('pre-text') || '',
        separator: el.data('separator') || '/',
        postText: el.data('post-text') || ''
      });
    });
  }

  /*
   * Ion Range Slider, for more examples you can check out https://github.com/IonDen/ion.rangeSlider
   *
   * Helpers.run('jq-rangeslider');
   *
   * Example usage:
   *
   * <input type="text" class="js-rangeslider form-control" value="50">
   *
   */
  static jqRangeslider() {
    // Init Ion Range Slider (with .js-rangeslider class)
    jQuery('.js-rangeslider:not(.js-rangeslider-enabled)').each((index, element) => {
      let el = jQuery(element);

      // Add .js-rangeslider-enabled class to tag it as activated and init it
      jQuery(element).addClass('js-rangeslider-enabled').ionRangeSlider({
        input_values_separator: ';',
        skin: el.data('skin') || 'round'
      });
    });
  }




}
