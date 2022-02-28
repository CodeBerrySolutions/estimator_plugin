jQuery(document).ready(function ($) {
    $("body").on("change", "input[class~='check-estimate']", function () {
        console.log('----------------------')
        $("input[class~='check-estimate']").each(function (index) {
            let check = $(this)
            let fathers = check.attr('fathers').replace(RegExp("'", 'g'), `"`);
            let jsonFathers = JSON.parse(fathers);
            //alert(fathers);
            if (fathers !== '-1') {
                console.log(check.attr('id') + ' tengo padres')
                let visible_estric = true;
                let visible = false;
                let almostfather = false
                $.each(jsonFathers, function (index, value) {
                    console.log(check.attr('id') + ' Mi padre' + index)
                    if (!$('#check_' + index).prop('checked') && value == 1) {
                        console.log('mi padre no esta check y es requerido')
                        visible_estric = false;
                    }

                    if ($('#check_' + index).prop('checked') && value != 1) {
                        console.log('mi padre esta check y no es requerido')
                        visible = true;
                    }
                    if ($('#check_' + index).prop('checked')) {
                        almostfather = true;
                    }

                });
                let botton = $('#' + check.attr('locator'))
                if (almostfather) {
                    if (visible_estric) {
                        console.log('mis padres requeridos estan check')
                        if (visible) {
                            console.log('no tengo padres requeridos o todos ok y tengo almenos un padre check no requerido')
                            $('#' + check.attr('locator')).prop('hidden', false)
                        } else {
                            console.log('no tengo padres requeridos o todos ok y no tengo almenos un padre check no requerido')

                            check.prop('hidden', true)
                            botton.removeClass('active');
                            check.prop('checked', false)
                        }
                        $('#' + check.attr('locator')).prop('hidden', false)
                    } else {
                        console.log('mis padres requeridos no estan check')
                        $('#' + check.attr('locator')).prop('hidden', true)
                        $('#' + check.attr('locator')).removeClass('active');
                        check.prop('checked', false)
                    }
                } else {
                    console.log('no tengo padres activos')
                    $('#' + check.attr('locator')).prop('hidden', true)
                    $('#' + check.attr('locator')).removeClass('active');
                    check.prop('checked', false)
                }

            } else {
                console.log(check.attr('id') + ' no tengo padres')
                $('#' + check.attr('locator')).prop('hidden', false)
            }
        })
    });

    var count = 0
    $('a.estimate-button').on('click', function (event) {
        let element = $(event.target);
        let checkbox = $('#check_' + element.attr('id'));
        let family = element.attr('family');
        let functionality = family.split('_')[1];
        let modalidad = family.split('_')[0];
        let fathers = $(checkbox).attr('fathers').replace(RegExp("'", 'g'), `"`);
        let sons = $(checkbox).attr('sons').replace(RegExp("'", 'g'), `"`);
        let locator = $(checkbox).attr('locator');
        if (modalidad === 'single') {
            $('.question_' + functionality).each(function () {
                $(this).prop('checked', false).change()
                $('#' + $(this).attr('locator')).removeClass('active')
            })
            if (checkbox) {
                if (checkbox.prop('checked')) {
                    checkbox.prop('checked', false).change();
                    // updateQuestionary(false, sons);
                } else {
                    checkbox.prop('checked', true).change();
                    //updateQuestionary(true, sons);
                }
                element.toggleClass('active')
            }
        } else {
            if (checkbox) {
                if (checkbox.prop('checked')) {
                    checkbox.prop('checked', false).change();
                    //updateQuestionary(false, sons);
                } else {
                    checkbox.prop('checked', true).change();
                    // updateQuestionary(true, sons);
                }
                element.toggleClass('active')
            }
        }

    })

    function updateQuestionary(check, sons) {
        var son = sons.replace(RegExp("'", 'g'), `"`);
        var jsonSons = JSON.parse(son);

        if (check && sons !== '-1') {
            $.each(jsonSons, function (index, value) {
                var hijo = $('#' + index)
                let id = hijo.attr('id')
                if (id === index) {
                    console.log('entre es el hijo ' + index);
                    hijo.prop('hidden', !check).change()
                    let checkElement = $('#check_' + id);
                    if (checkElement.prop('checked')) {
                        checkElement.prop('checked', false).change();
                        hijo.toggleClass('active');
                    }
                    let sonshijo = checkElement.attr('sons');

                    if (sonshijo !== '-1') {
                        updateQuestionary(false, sonshijo);
                    }
                }
            });
        } else if (sons !== '-1') {
            $.each(jsonSons, function (index, value) {
                var hijo = $('#' + index)
                let id = hijo.attr('id')
                if (id === index) {
                    console.log('entre es el hijo ' + index);
                    hijo.prop('hidden', !check).change()
                    let checkElement = $('#check_' + id);
                    let sonshijo = checkElement.attr('sons');

                    if (sonshijo !== '-1') {
                        updateQuestionary(true, sonshijo);
                    }
                }
            });

        }
    }

    $("#enviar_form").on('click', function (event) {
        var error_msg = 'Formulario invalido, por favor revise correctamente el formulario, las secciones con * son obligatorias.';
        // Stop form from submitting normally
        event.preventDefault();
        //console.log('pare el submit')
        // Get some values from elements on the page:
        var form = $('#estimate_form_frontend');
        let family_array = [];
        form.find('a').each(function () {
            console.log($(this))
            let family = $(this).attr('family').split('_')[1];
            let modalty = $(this).attr('family').split('_')[0];
            let req = $(this).attr('req');
            console.log(req)
            if (req == 1) {
                console.log('entre '+ family)
                if ($.inArray(family, family_array) === -1) {
                    console.log('agregue '+family)
                    family_array.push(family);
                }
            }
        })
        console.log(family_array);
        let validate_form = true;
        family_array.forEach((value, index) => {
            let count_family_checked = 0
            $('.question_' + value).each(function () {
                if ($(this).prop('checked')) {
                    count_family_checked++;
                }
            })
            //console.log(count_family_checked)
            if (count_family_checked < 1) {
                validate_form = false;
                error_msg = 'Formulario invalido, por favor revise correctamente el formulario, las secciones con * son obligatorias.';
                return false;
            }
        })

        if (!validEmail($('input#client_email').val())) {
            error_msg = 'Email not valid!'
            validate_form = false;
        }

        $('input.client').each(function () {
            console.log(this)
            console.log(this.value)
            if (this.value.length === 0) {
                validate_form = false;
                error_msg = $('#label_' + this.id).html() + ' is empty.'
            }
        })

        // Send the data using post
        //var posting = $.post(url, {s: term});

        if (validate_form) {
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
        } else {
            alert(error_msg)
        }
        //Ajax Function to send a get request

    });
});

function validEmail(email) {
    console.log(email)
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(email);
}
