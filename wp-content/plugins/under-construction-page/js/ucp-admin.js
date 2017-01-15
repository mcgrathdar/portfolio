/*
 * Under Construction 
 * Main backend JS
 * (c) Web factory Ltd, 2015 - 2016
 */


jQuery(document).ready(function($) {
  old_settings = $('#ucp_form').serialize();
  
  $('#ucp_tabs').tabs({
    activate: function(event, ui) {
        Cookies.set('ucp_tabs_selected', $('#ucp_tabs').tabs('option', 'active'), { expires: 180 });
    },
    active: $('#ucp_tabs').tabs({ active: Cookies.get('ucp_tabs_selected') })
  }).show();
  
  $('#whitelisted_users').select2({ 'placeholder': 'Select whitelisted user(s)'});
  
  $('.ucp-thumb').on('click', function(e) {
    e.preventDefault();
    
    theme_id = $(this).data('theme-id');
    $('.ucp-thumb').removeClass('active');
    $(this).addClass('active');
    $('#theme_id').val(theme_id);
    
    return false;
  });
  
  $('.datepicker').AnyTime_picker({ format: "%Y-%m-%d %H:%i", firstDOW: 1, earliest: new Date(), labelTitle: "Select the date &amp; time when construction mode will be disabled" } );
  
  $('.clear-datepicker').on('click', function(e) {
    e.preventDefault();
    
    $(this).prevAll('input.datepicker').val('');
    
    return false;
  });
  
  $('.show-datepicker').on('click', function(e) {
    e.preventDefault();
    
    $(this).prevAll('input.datepicker').focus();
    
    return false;
  });
  
  $(document).on('click', '#ucp_preview', function(e) {
    if ($('#ucp_form').serialize() != old_settings) {
      if (!confirm('There are unsaved changes that will not be visible in the preview. Please save changes first.\nContinue?')) {
        e.preventDefault();
        return false;
      }      
    }
    
    return true;
  });
  
  $(document).on('click', '.change_tab', function(e) {
    $('#ucp_tabs').tabs('option', 'active', $(this).data('tab'));

    // get the link anchor and scroll to it
    target = this.href.split('#')[1];
    if (target) {
      $.scrollTo('#' + target, 500, {offset: {top:-50, left:0}});  
    }
  });
}); // on ready
