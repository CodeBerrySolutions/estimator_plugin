jQuery(document).ready(function($) {
    $('a.estimate-button').on('click', function (event) {
        let element = $(event.target);
        let checkbox = $('#check_' + element.attr('id'));
        let family = element.attr('family');
        let functionality = family.split('-')[0];

        let modalidad = family.split('-')[1];

        if (modalidad == 'one'){
            console.log('es One')
            $('.'+functionality).each(function (){
                console.log('freach')
                console.log($(this).attr('id'));
                $(this).prop('checked',false)
                $('#'+$(this).attr('name')).removeClass('active')
            })
            if (checkbox) {
                if (checkbox.prop('checked')) {
                    console.log('esta checek')
                    checkbox.prop('checked', false);
                } else {
                    console.log('no esta checek')
                    checkbox.prop('checked', true);

                }
                element.toggleClass('active')
                console.log(element.hasClass('active'))
            }
        }else{
            console.log('es Any')
            if (checkbox) {
                if (checkbox.prop('checked')) {
                    console.log('esta checek')
                    checkbox.prop('checked', false);
                } else {
                    console.log('no esta checek')
                    checkbox.prop('checked', true);

                }
                element.toggleClass('active')
            }
        }

    })

    $("#enviar_form").on('click',function (event) {
        var error_msg = 'Formulario invalido, por favor revise correctamente el formulario, las secciones con * son obligatorias.';
        // Stop form from submitting normally
        event.preventDefault();
        //console.log('pare el submit')
        // Get some values from elements on the page:
        var form = $('#estimate_form_frontend'),
            //data = form.serialize(),
            url = form.attr('url');
        let family_array = [];
        form.find('a').each(function (){
            let family = $(this).attr('family').split('-')[0];
            let modalty = $(this).attr('family').split('-')[1];
            if (modalty === 'one'){
                if($.inArray(family,family_array) === -1){
                    family_array.push(family);
                }
            }
        })
        //console.log(family_array);
        let validate_form=true;
        family_array.forEach((value, index) =>{
            let count_family_checked=0
            $('.'+value).each(function () {
                if ($(this).prop('checked')){
                    count_family_checked++;
                }
            })
            //console.log(count_family_checked)
            if (count_family_checked !== 1){
                validate_form =false;
                error_msg = 'Formulario invalido, por favor revise correctamente el formulario, las secciones con * son obligatorias.';
                return false;
            }
        })

        if(!validEmail($('input#client_email').val())){
            error_msg = 'Email not valid!'
            validate_form=false;
        }

        $('input.client').each(function (){
            console.log(this.value.length)
            if(this.value.length === 0){
                validate_form = false;
                error_msg = $('#label_'+this.name).html()+' is empty.'
            }
        })

        // Send the data using post
        //var posting = $.post(url, {s: term});

        if (validate_form){
            /*$.post({
                type: "POST",
                url: url,
                data: {data:data},
                success: function (response) {
                    //console.log(response)
                    alert(response)
                    //if request if made successfully then the response represent the data
                    form[0].reset();
                    $('.estimate-button').removeClass('active');
                }
            });*/
            form.submit();
        }
        else {
            alert(error_msg)
        }
        //Ajax Function to send a get request

    });
});

function validEmail(email){
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(email);
}
