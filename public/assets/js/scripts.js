"use strict";

!function (NioApp, $) {
  "use strict";

  NioApp.Package.name = "DashLite";
  NioApp.Package.version = "2.2";
  var $win = $(window),
    $body = $('body'),
    $doc = $(document),
    //class names
    _body_theme = 'nio-theme',
    _menu = 'nk-menu',
    _mobile_nav = 'mobile-menu',
    _header = 'nk-header',
    _header_menu = 'nk-header-menu',
    _sidebar = 'nk-sidebar',
    _sidebar_mob = 'nk-sidebar-mobile',
    //breakpoints
    _break = NioApp.Break;

  function extend(obj, ext) {
    Object.keys(ext).forEach(function (key) {
      obj[key] = ext[key];
    });
    return obj;
  } // ClassInit @v1.0


  NioApp.ClassBody = function () {
    NioApp.AddInBody(_sidebar);
  }; // ClassInit @v1.0


  NioApp.ClassNavMenu = function () {
    NioApp.BreakClass('.' + _header_menu, _break.lg, {
      timeOut: 0
    });
    NioApp.BreakClass('.' + _sidebar, _break.lg, {
      timeOut: 0,
      classAdd: _sidebar_mob
    });
    $win.on('resize', function () {
      NioApp.BreakClass('.' + _header_menu, _break.lg);
      NioApp.BreakClass('.' + _sidebar, _break.lg, {
        classAdd: _sidebar_mob
      });
    });
  }; // Code Prettify @v1.0


  NioApp.Prettify = function () {
    window.prettyPrint && prettyPrint();
  }; // Copied @v1.0


  NioApp.Copied = function () {
    var clip = '.clipboard-init',
      target = '.clipboard-text',
      sclass = 'clipboard-success',
      eclass = 'clipboard-error'; // Feedback

    function feedback(el, state) {
      var $elm = $(el),
        $elp = $elm.parent(),
        copy = {
          text: 'Copy',
          done: 'Copied',
          fail: 'Failed'
        },
        data = {
          text: $elm.data('clip-text'),
          done: $elm.data('clip-success'),
          fail: $elm.data('clip-error')
        };
      copy.text = data.text ? data.text : copy.text;
      copy.done = data.done ? data.done : copy.done;
      copy.fail = data.fail ? data.fail : copy.fail;
      var copytext = state === 'success' ? copy.done : copy.fail,
        addclass = state === 'success' ? sclass : eclass;
      $elp.addClass(addclass).find(target).html(copytext);
      setTimeout(function () {
        $elp.removeClass(sclass + ' ' + eclass).find(target).html(copy.text).blur();
        $elp.find('input').blur();
      }, 2000);
    } // Init ClipboardJS


    if (ClipboardJS.isSupported()) {
      var clipboard = new ClipboardJS(clip);
      clipboard.on('success', function (e) {
        feedback(e.trigger, 'success');
        e.clearSelection();
      }).on('error', function (e) {
        feedback(e.trigger, 'error');
      });
    } else {
      $(clip).css('display', 'none');
    }

    ;
  }; // CurrentLink Detect @v1.0


  NioApp.CurrentLink = function () {
    var _link = '.nk-menu-link, .menu-link, .nav-link',
      _currentURL = window.location.href,
      fileName = _currentURL.substring(0, _currentURL.indexOf("#") == -1 ? _currentURL.length : _currentURL.indexOf("#")),
      fileName = fileName.substring(0, fileName.indexOf("?") == -1 ? fileName.length : fileName.indexOf("?"));

    $(_link).each(function () {
      var self = $(this),
        _self_link = self.attr('href');

      if (fileName.match(_self_link)) {
        self.closest("li").addClass('active current-page').parents().closest("li").addClass("active current-page");
        self.closest("li").children('.nk-menu-sub').css('display', 'block');
        self.parents().closest("li").children('.nk-menu-sub').css('display', 'block');
      } else {
        self.closest("li").removeClass('active current-page').parents().closest("li:not(.current-page)").removeClass("active");
      }
    });
  }; // PasswordSwitch @v1.0


  NioApp.PassSwitch = function () {
    NioApp.Passcode('.passcode-switch');
  }; // Toastr Message @v1.0 


  NioApp.Toast = function (msg, ttype, opt) {
    var ttype = ttype ? ttype : 'info',
      msi = '',
      ticon = ttype === 'info' ? 'ni ni-info-fill' : ttype === 'success' ? 'ni ni-check-circle-fill' : ttype === 'error' ? 'ni ni-cross-circle-fill' : ttype === 'warning' ? 'ni ni-alert-fill' : '',
      def = {
        position: 'bottom-right',
        ui: '',
        icon: 'auto',
        clear: false
      },
      attr = opt ? extend(def, opt) : def;
    attr.position = attr.position ? 'toast-' + attr.position : 'toast-bottom-right';
    attr.icon = attr.icon === 'auto' ? ticon : attr.icon ? attr.icon : '';
    attr.ui = attr.ui ? ' ' + attr.ui : '';
    msi = attr.icon !== '' ? '<span class="toastr-icon"><em class="icon ' + attr.icon + '"></em></span>' : '', msg = msg !== '' ? msi + '<div class="toastr-text">' + msg + '</div>' : '';

    if (msg !== "") {
      if (attr.clear === true) {
        toastr.clear();
      }

      var option = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": attr.position + attr.ui,
        "closeHtml": '<span class="btn-trigger">Close</span>',
        "preventDuplicates": true,
        "showDuration": "1500",
        "hideDuration": "1500",
        "timeOut": "2000",
        "toastClass": "toastr",
        "extendedTimeOut": "3000"
      };
      toastr.options = extend(option, attr);
      toastr[ttype](msg);
    }
  }; // Toggle Screen @v1.0


  NioApp.TGL.screen = function (elm) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var ssize = $(this).data('toggle-screen');

        if (ssize) {
          $(this).addClass('toggle-screen-' + ssize);
        }
      });
    }
  }; // Toggle Content @v1.0


  NioApp.TGL.content = function (elm, opt) {
    var toggle = elm ? elm : '.toggle',
      $toggle = $(toggle),
      $contentD = $('[data-content]'),
      toggleBreak = true,
      toggleCurrent = false,
      def = {
        active: 'active',
        content: 'content-active',
        "break": toggleBreak
      },
      attr = opt ? extend(def, opt) : def;
    NioApp.TGL.screen($contentD);
    $toggle.on('click', function (e) {
      toggleCurrent = this;
      NioApp.Toggle.trigger($(this).data('target'), attr);
      e.preventDefault();
    });
    $doc.on('mouseup', function (e) {
      if (toggleCurrent) {
        var $toggleCurrent = $(toggleCurrent);

        if (!$toggleCurrent.is(e.target) && $toggleCurrent.has(e.target).length === 0 && !$contentD.is(e.target) && $contentD.has(e.target).length === 0) {
          NioApp.Toggle.removed($toggleCurrent.data('target'), attr);
          toggleCurrent = false;
        }
      }
    });
    $win.on('resize', function () {
      $contentD.each(function () {
        var content = $(this).data('content'),
          ssize = $(this).data('toggle-screen'),
          toggleBreak = _break[ssize];

        if (NioApp.Win.width > toggleBreak) {
          NioApp.Toggle.removed(content, attr);
        }
      });
    });
  }; // ToggleExpand @v1.0


  NioApp.TGL.expand = function (elm, opt) {
    var toggle = elm ? elm : '.expand',
      def = {
        toggle: true
      },
      attr = opt ? extend(def, opt) : def;
    $(toggle).on('click', function (e) {
      NioApp.Toggle.trigger($(this).data('target'), attr);
      e.preventDefault();
    });
  }; // Dropdown Menu @v1.0


  NioApp.TGL.ddmenu = function (elm, opt) {
    var imenu = elm ? elm : '.nk-menu-toggle',
      def = {
        active: 'active',
        self: 'nk-menu-toggle',
        child: 'nk-menu-sub'
      },
      attr = opt ? extend(def, opt) : def;
    $(imenu).on('click', function (e) {
      if (NioApp.Win.width < _break.lg || $(this).parents().hasClass(_sidebar)) {
        NioApp.Toggle.dropMenu($(this), attr);
      }

      e.preventDefault();
    });
  }; // Show Menu @v1.0


  NioApp.TGL.showmenu = function (elm, opt) {
    var toggle = elm ? elm : '.nk-nav-toggle',
      $toggle = $(toggle),
      $contentD = $('[data-content]'),
      toggleBreak = $contentD.hasClass(_header_menu) ? _break.lg : _break.xl,
      toggleOlay = _sidebar + '-overlay',
      toggleClose = {
        profile: true,
        menu: false
      },
      def = {
        active: 'toggle-active',
        content: _sidebar + '-active',
        body: 'nav-shown',
        overlay: toggleOlay,
        "break": toggleBreak,
        close: toggleClose
      },
      attr = opt ? extend(def, opt) : def;
    $toggle.on('click', function (e) {
      NioApp.Toggle.trigger($(this).data('target'), attr);
      e.preventDefault();
    });
    $doc.on('mouseup', function (e) {
      if (!$toggle.is(e.target) && $toggle.has(e.target).length === 0 && !$contentD.is(e.target) && $contentD.has(e.target).length === 0 && NioApp.Win.width < toggleBreak) {
        NioApp.Toggle.removed($toggle.data('target'), attr);
      }
    });
    $win.on('resize', function () {
      if (NioApp.Win.width < _break.xl || NioApp.Win.width < toggleBreak) {
        NioApp.Toggle.removed($toggle.data('target'), attr);
      }
    });
  }; // Animate FormSearch @v1.0


  NioApp.Ani.formSearch = function (elm, opt) {
    var def = {
        active: 'active',
        timeout: 400,
        target: '[data-search]'
      },
      attr = opt ? extend(def, opt) : def;
    var $elem = $(elm),
      $target = $(attr.target);

    if ($elem.exists()) {
      $elem.on('click', function (e) {
        e.preventDefault();
        var $self = $(this),
          the_target = $self.data('target'),
          $self_st = $('[data-search=' + the_target + ']'),
          $self_tg = $('[data-target=' + the_target + ']');

        if (!$self_st.hasClass(attr.active)) {
          $self_tg.add($self_st).addClass(attr.active);
          $self_st.find('input').focus();
        } else {
          $self_tg.add($self_st).removeClass(attr.active);
          setTimeout(function () {
            $self_st.find('input').val('');
          }, attr.timeout);
        }
      });
      $doc.on({
        keyup: function keyup(e) {
          if (e.key === "Escape") {
            $elem.add($target).removeClass(attr.active);
          }
        },
        mouseup: function mouseup(e) {
          if (!$target.find('input').val() && !$target.is(e.target) && $target.has(e.target).length === 0 && !$elem.is(e.target) && $elem.has(e.target).length === 0) {
            $elem.add($target).removeClass(attr.active);
          }
        }
      });
    }
  }; // Animate FormElement @v1.0


  NioApp.Ani.formElm = function (elm, opt) {
    var def = {
        focus: 'focused'
      },
      attr = opt ? extend(def, opt) : def;

    if ($(elm).exists()) {
      $(elm).each(function () {
        var $self = $(this);

        if ($self.val()) {
          $self.parent().addClass(attr.focus);
        }

        $self.on({
          focus: function focus() {
            $self.parent().addClass(attr.focus);
          },
          blur: function blur() {
            if (!$self.val()) {
              $self.parent().removeClass(attr.focus);
            }
          }
        });
      });
    }
  }; // Form Validate @v1.0


  NioApp.Validate = function (elm, opt) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var def = {
            errorElement: "span"
          },
          attr = opt ? extend(def, opt) : def;
        $(this).validate(attr);
      });
    }
  };

  NioApp.Validate.init = function () {
    NioApp.Validate('.form-validate', {
      errorElement: "span",
      errorClass: "invalid",
      errorPlacement: function errorPlacement(error, element) {
        error.appendTo(element.parent());
      }
    });
  }; // Dropzone @v1.0


  NioApp.Dropzone = function (elm, opt) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var def = {
            autoDiscover: false
          },
          attr = opt ? extend(def, opt) : def;
        $(this).addClass('dropzone').dropzone(attr);
      });
    }
  }; // Wizard @v1.0


  NioApp.Wizard = function () {
    var $wizard = $(".nk-wizard").show();
    $wizard.steps({
      headerTag: ".nk-wizard-head",
      bodyTag: ".nk-wizard-content",
      labels: {
        finish: "Submit",
        next: "Next",
        previous: "Prev",
        loading: "Loading ..."
      },
      onStepChanging: function onStepChanging(event, currentIndex, newIndex) {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
          return true;
        } // Needed in some cases if the user went back (clean up)


        if (currentIndex < newIndex) {
          // To remove error styles
          $wizard.find(".body:eq(" + newIndex + ") label.error").remove();
          $wizard.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }

        $wizard.validate().settings.ignore = ":disabled,:hidden";
        return $wizard.valid();
      },
      onFinishing: function onFinishing(event, currentIndex) {
        $wizard.validate().settings.ignore = ":disabled";
        return $wizard.valid();
      },
      onFinished: function onFinished(event, currentIndex) {
        window.location.href = "#";
      }
    }).validate({
      errorElement: "span",
      errorClass: "invalid",
      errorPlacement: function errorPlacement(error, element) {
        error.appendTo(element.parent());
      }
    });
  }; // DataTable @1.1

  NioApp.DataTable = function (elm, opt) {
    if ($(elm).exists()) {
      $(elm).each(function () {
        var auto_responsive = $(this).data('auto-responsive');
        var dom_normal = '<"row justify-between g-2"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"B>>><"datatable-wrap my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"li>>';
        var dom_separate = '<"row justify-between g-2"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"B>>><"my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"li>>';
        var dom = $(this).hasClass('is-separate') ? dom_separate : dom_normal;
        var def = {
            ordering: false,
            responsive: true,
            autoWidth: false,
            dom: dom,
            buttons: [{
              extend: 'pdfHtml5',
              customize: function (doc) {
                doc.content.splice(1, 0, {
                  margin: [0, 0, 0, 12],
                  alignment: 'center',
                  image: 'data:image/png;base64,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\n'
                });
              }
            }],
            language: {
              search: "",
              searchPlaceholder: "Type in to Search",
              lengthMenu: "<span class='d-none d-sm-inline-block'>Show</span><div class='form-control-select'> _MENU_ </div>",
              info: "_START_ -_END_ of _TOTAL_",
              infoEmpty: "No records found",
              infoFiltered: "( Total _MAX_  )",
              paginate: {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Prev"
              }
            }
          },
          attr = opt ? extend(def, opt) : def;
        attr = auto_responsive === false ? extend(attr, {
          responsive: false
        }) : attr;
        $(this).DataTable(attr);
      });
    }
  }; // BootStrap Extended

  NioApp.BS.ddfix = function (elm, exc) {
    var dd = elm ? elm : '.dropdown-menu',
      ex = exc ? exc : 'a:not(.clickable), button:not(.clickable), a:not(.clickable) *, button:not(.clickable) *';
    $(dd).on('click', function (e) {
      if (!$(e.target).is(ex)) {
        e.stopPropagation();
        return;
      }
    });

    if (NioApp.State.isRTL) {
      var $dMenu = $('.dropdown-menu');
      $dMenu.each(function () {
        var $self = $(this);

        if ($self.hasClass('dropdown-menu-right') && !$self.hasClass('dropdown-menu-center')) {
          $self.prev('[data-toggle="dropdown"]').dropdown({
            popperConfig: {
              placement: 'bottom-start'
            }
          });
        } else if (!$self.hasClass('dropdown-menu-right') && !$self.hasClass('dropdown-menu-center')) {
          $self.prev('[data-toggle="dropdown"]').dropdown({
            popperConfig: {
              placement: 'bottom-end'
            }
          });
        }
      });
    }
  }; // BootStrap Specific Tab Open
  NioApp.BS.tabfix = function (elm) {
    var tab = elm ? elm : '[data-toggle="modal"]';
    $(tab).on('click', function () {
      var _this = $(this),
        target = _this.data('target'),
        target_href = _this.attr('href'),
        tg_tab = _this.data('tab-target');

      var modal = target ? $body.find(target) : $body.find(target_href);

      if (tg_tab && tg_tab !== '#' && modal) {
        modal.find('[href="' + tg_tab + '"]').tab('show');
      } else if (modal) {
        var tabdef = modal.find('.nk-nav.nav-tabs');
        var link = $(tabdef[0]).find('[data-toggle="tab"]');
        $(link[0]).tab('show');
      }
    });
  }; // Dark Mode Switch @since v2.0
  NioApp.ModeSwitch = function () {
    var toggle = $('.dark-switch');

    if ($body.hasClass('dark-mode')) {
      toggle.addClass('active');
    } else {
      toggle.removeClass('active');
    }

    toggle.on('click', function (e) {
      e.preventDefault();
      $(this).toggleClass('active');
      $body.toggleClass('dark-mode');
    });
  }; // Knob @v1.0
  NioApp.Knob.init = function () {
    var knob = {
      "default": {
        readOnly: true,
        lineCap: "round"
      },
      half: {
        angleOffset: -90,
        angleArc: 180,
        readOnly: true,
        lineCap: "round"
      }
    };
    NioApp.Knob('.knob', knob["default"]);
    NioApp.Knob('.knob-half', knob.half);
  }; // Range @v1.0
  NioApp.Range.init = function () {
    NioApp.Range('.form-range-slider');
  };
  NioApp.Select2.init = function () {
    // NioApp.Select2('.select');
    NioApp.Select2('.form-select');
  }; // Slick Init @v1.0
  NioApp.Slider.init = function () {
    NioApp.Slick('.slider-init');
  }; // Dropzone Init @v1.0
  NioApp.Dropzone.init = function () {
    NioApp.Dropzone('.upload-zone', {
      url: "/images"
    });
  }; // DataTable Init @v1.0

  NioApp.DataTable.init = function () {
    NioApp.DataTable('.datatable-init', {
      responsive: {
        details: true
      }
    });
    $.fn.DataTable.ext.pager.numbers_length = 7;
  }; // Extra @v1.1

  NioApp.OtherInit = function () {
    NioApp.ClassBody();
    NioApp.PassSwitch();
    NioApp.CurrentLink();
    NioApp.LinkOff('.is-disable');
    NioApp.ClassNavMenu(); //v1.5

    NioApp.SetHW('[data-height]', 'height');
    NioApp.SetHW('[data-width]', 'width');
  }; // Animate Init @v1.0


  NioApp.Ani.init = function () {
    NioApp.Ani.formElm('.form-control-outlined');
    NioApp.Ani.formSearch('.toggle-search');
  }; // BootstrapExtend Init @v1.0


  NioApp.BS.init = function () {
    NioApp.BS.menutip('a.nk-menu-link');
    NioApp.BS.tooltip('.nk-tooltip');
    NioApp.BS.tooltip('.btn-tooltip', {
      placement: 'top'
    });
    NioApp.BS.tooltip('[data-toggle="tooltip"]');
    NioApp.BS.tooltip('.tipinfo,.nk-menu-tooltip', {
      placement: 'right'
    });
    NioApp.BS.popover('[data-toggle="popover"]');
    NioApp.BS.progress('[data-progress]');
    NioApp.BS.fileinput('.custom-file-input');
    NioApp.BS.modalfix();
    NioApp.BS.ddfix();
    NioApp.BS.tabfix();
  }; // Picker Init @v1.0


  NioApp.Picker.init = function () {
    NioApp.Picker.date('.date-picker');
    NioApp.Picker.dob('.date-picker-alt');
    NioApp.Picker.time('.time-picker');
  }; // Addons @v1


  NioApp.Addons.Init = function () {
    NioApp.Knob.init();
    NioApp.Range.init();
    NioApp.Select2.init();
    NioApp.Dropzone.init();
    NioApp.Slider.init();
    NioApp.DataTable.init();
  }; // Toggler @v1


  NioApp.TGL.init = function () {
    NioApp.TGL.content('.toggle');
    NioApp.TGL.expand('.toggle-expand');
    NioApp.TGL.expand('.toggle-opt', {
      toggle: false
    });
    NioApp.TGL.showmenu('.nk-nav-toggle');
    NioApp.TGL.ddmenu('.' + _menu + '-toggle', {
      self: _menu + '-toggle',
      child: _menu + '-sub'
    });
  };

  NioApp.BS.modalOnInit = function () {
    $('.modal').on('shown.bs.modal', function () {
      NioApp.Select2.init();
      NioApp.Validate.init();
    });
  }; // Initial by default
  /////////////////////////////


  NioApp.init = function () {
    NioApp.coms.docReady.push(NioApp.OtherInit);
    NioApp.coms.docReady.push(NioApp.Prettify);
    NioApp.coms.docReady.push(NioApp.ColorBG);
    NioApp.coms.docReady.push(NioApp.ColorTXT);
    NioApp.coms.docReady.push(NioApp.Copied);
    NioApp.coms.docReady.push(NioApp.Ani.init);
    NioApp.coms.docReady.push(NioApp.TGL.init);
    NioApp.coms.docReady.push(NioApp.BS.init);
    NioApp.coms.docReady.push(NioApp.Validate.init);
    NioApp.coms.docReady.push(NioApp.Picker.init);
    NioApp.coms.docReady.push(NioApp.Addons.Init);
    NioApp.coms.docReady.push(NioApp.Wizard);
    NioApp.coms.winLoad.push(NioApp.ModeSwitch);
  };

  NioApp.init();
  return NioApp;
}(NioApp, jQuery);