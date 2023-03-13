$(document).ready(function() {
    blurModalButton('interestModal','interestTrigger');
    formSubmit($('#interestForm'), [$('#name'), $('#email')], ['text', 'email']);
    clearInputOnModalClose('interestModal',[$('#name'), $('#email')]);
});

function blurModalButton(modalID, triggerID){
    $('#'+modalID).on('shown.bs.modal', function (e) {
        $('#'+triggerID).one('focus', function (e) {
            $(this).blur();
        });
    });
}

function formSubmit(form, input, type){
    var form_elem = $(form);
    form_elem.submit(function(e){
        e.preventDefault();

        var validate = validateForm(input, type);

        if(validate.length > 0){
            Swal.fire(
                'Error',
                validate.join('<br>'),
                'error'
            );
        }else{
            $.ajax({
              type: "POST",
              url: "/",
              data: form_elem.serialize(),
              dataType: "json",
              encode: true
            }).done( function(data){
                if(data.validate_error == ''){
                    for(var key in data){
                        Swal.fire(
                            key.charAt(0).toUpperCase()
                            + key.slice(1),
                            data[key],
                            key
                        ).then(() => {
                            for(let i = 0; i < input.length; i++){
                                input[i].val('');
                            }                 
                            triggerModal('interestModal', 'hide');
                        });
                    }
                }else{
                    var errors = data.validate_error;
                    $.each(errors, function(key, value){
                        $('#'+key).focus();
                        $('#'+key).addClass('is-invalid');
                        $('#'+key).next('div').removeClass().addClass('has-feedback').html('<strong><span class="text-danger">'+value[0]+'</span></strong>');
                    });
                }
            });

        }

    });
}

function validateForm(input, type){
    var validate = [];
    for(let i = 0; i < input.length; i++){
        if(type[i] == 'text'){
            if(input[i].val() == ''){
                validate.push(input[i].attr('name')+' field is empty');
            }else if(!validateAlphabetOnly(input[i].val())){
                validate.push(input[i].attr('name')+' field contain non-alphabet.');
            }
        }
        if(type[i] == 'email'){
            if(!validateEmail(input[i].val())){
                validate.push(input[i].attr('name')+' field contains invalid email');
            }
        }
    }
    return validate;
}

function validateEmail(email){
    var pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    return pattern.test(email);
}

function validateAlphabetOnly(input){
    var pattern = /^[a-zA-Z ]*$/;
    return pattern.test(input);
}

function triggerModal(modal, action){
    $('#'+modal).modal(action);
}

function clearInputOnModalClose(modalID, input){
    $('#'+modalID).on('hide.bs.modal', function (e) {
        for(let i = 0; i < input.length; i++){
            input[i].val('');
            input[i].removeClass().addClass('form-control');
            input[i].next('div').removeClass().addClass('invalid-feedback');
        }
    });
}