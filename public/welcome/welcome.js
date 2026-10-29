$(document).ready(function() {
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    blurModalButton('interestModal','interestTrigger');
    formSubmit($('#interestForm'), [$('#name'), $('#email')], ['text', 'email']);
    clearInputOnModalClose('interestModal',[$('#name'), $('#email')]);

    // Mobile Navigation Toggle
    $('#techMobileToggle').on('click', function() {
        $('#techNavMenu').toggleClass('open');
    });

    // Close mobile nav when clicking a link
    $('#techNavMenu a').on('click', function() {
        $('#techNavMenu').removeClass('open');
    });

    // Scrollspy for active nav items
    $(window).on('scroll', function() {
        var scrollPos = $(document).scrollTop() + 100;
        $('.tech-nav-menu a').each(function() {
            var currLink = $(this);
            var refElement = $(currLink.attr("href"));
            if (refElement.length && refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
                $('.tech-nav-menu a').removeClass("active");
                currLink.addClass("active");
            }
        });
    });
});

/**
 * Copy string to clipboard and show tech toast alert
 */
function copyToClipboard(text, message) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            showTechToast(message || 'Copied to clipboard!');
        }).catch(function() {
            fallbackCopy(text, message);
        });
    } else {
        fallbackCopy(text, message);
    }
}

function fallbackCopy(text, message) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        showTechToast(message || 'Copied to clipboard!');
    } catch (err) {
        console.error('Fallback copy failed', err);
    }
    document.body.removeChild(textArea);
}

function showTechToast(msg) {
    $('.tech-toast').remove();
    var toast = $('<div class="tech-toast"><i class="fas fa-check-circle text-emerald-400"></i> ' + msg + '</div>');
    $('body').append(toast);
    setTimeout(function() {
        toast.fadeOut(300, function() {
            $(this).remove();
        });
    }, 2500);
}

function blurModalButton(modalID, triggerID){
    $('#'+modalID).on('shown.bs.modal', function (e) {
        $('#'+triggerID).one('focus', function (e) {
            $(this).blur();
        });
    });
}

function formSubmit(form, input, type){
    var form_elem = $(form);
    if (!form_elem.length) return;
    
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
            input[i].removeClass('is-invalid');
            input[i].next('div').removeClass().addClass('invalid-feedback');
        }
    });
}
