$(document).ready(function() {

    disabledLink();

      /* select2 js */
      $('#primary_colour, #secondary_colour').select2({
        templateResult: function (data, container) {
            if (data.element) {
              $(container).addClass($(data.element).attr("class"));
            }
            return data.text;
          },
          placeholder: "Select color",
      });

      insertOptionOnTextEditor('#design', '#fields');

      function insertOptionOnTextEditor(textEditor, selectfield)
      {
        if(textEditor == '') return;
        if(selectfield == '') return;

        $(selectfield).change(function(){
            var optionValue = $(selectfield).val();
            var textEditorContainer = $(textEditor);
            if(optionValue != '' || optionValue != '-'){
                textEditorContainer.summernote('editor.saveRange');
                textEditorContainer.summernote('editor.restoreRange');
                textEditorContainer.summernote('editor.focus');
                textEditorContainer.summernote('editor.insertText', optionValue);
            }
        });
      }

      function disabledLink()
      {
          $('*').each(function() {
              if ($(this).hasClass('disabled')) {
                  $(this).on('click', function(e){
                      e.preventDefault();
                  });
              }
          });
      }

      experiences();
      socials();
});

function experiences()
{
    $('#startDate').on('blur', function(){
        $('#startDate').datetimepicker('hide');
    });
    $('#endDate').on('blur', function(){
        $('#endDate').datetimepicker('hide');
    });

    $('#current').on('change', function(e){
        if($('#current:checked').length > 0){
            $('#endDate').parent().hide();
        }else{
            $('#endDate').parent().show();
        }
    });
    
    if($('#current:checked').length > 0){
        $('#endDate').parent().hide();
    }else{
        $('#endDate').parent().show();
    }
}

function socials()
{
    function formatIcon (icon) {
        return $('<span><i class="fa-fw ' + $(icon.element).data('icon') + '"></i> ' + icon.text + '</span>');
    };
    
    $('.icon_select').select2({
        placeholder : "Select icon",
        allowClear : true,
        templateSelection: formatIcon,
        templateResult: formatIcon
    });
}