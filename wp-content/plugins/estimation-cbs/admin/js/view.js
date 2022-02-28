jQuery(document).ready(function ($) {
    $('.close-btn').on('click', function (event) {
        $('#myModal').modal('hide');
        var lang = $('.modal-title').attr('lang').split('_')[0];
        if(lang == 'es'){
            $('.modal-body').html('Cargando...')
            $('.modal-title').html('Cargando...')
        }else{
            $('.modal-body').html('Loading...')
            $('.modal-title').html('Loading...')
        }

    })

    $('a.form-view-icon').on('click', function (event) {
        let element = $(event.target);

        let str_id = element.attr('id');
        console.log(element.parent())
        console.log(element.parent().attr('lang'))
        var str_lang = element.parent().attr('lang').split('_')[0];
        let id = str_id.split('-')[1];
        console.log(id+''+str_lang)
        if (id) {
            $.ajax({
                url: "https://api.codeberrysolutions.com/api/estimate/"+id+"?lang="+str_lang,
                type: 'GET',
                dataType: 'json',
                contentType: 'application/json',
                processData: false,
                success: function (data) {
                        console.log(data['estimation']['estimate'])
                    let estimations = data['estimation']['results'];
                    let form = data['estimation']['lang'] == 'es' ?'Formulario':'Form' ;
                    let hourslabel = data['estimation']['lang'] == 'es' ? 'Horas estimadas:':'Estimate hours:';
                    let effortlabel = data['estimation']['lang'] == 'es' ? 'Puntos de esfuerzos:':'Effort point:';

                        $('.modal-title').html("<h4>"+form+": " + data['estimation']['client_name'] +" "+data['estimation']['client_last_name'] +"</h4>")
                    let body = "";
                    for (var key in estimations) {

                        body += "<p>"+estimations[key]['question']+" : <span style='color: green'>"+estimations[key]['answers']+"</span></p>"
                    }
                    body +="<p>"+hourslabel+" <span style='color: green'>"+data['estimation']['estimate'][0]+"</span>   "+effortlabel+" <span style='color: green'>"+data['estimation']['estimate'][1]+"</span></p>"
                    body +="<p>Min:<span style='color: green'>"+data['estimation']['estimate']['min']+"</span>   Max: <span style='color: green'>"+data['estimation']['estimate']['max']+"</span></p>"
                        $('.modal-body').html(
                            body
                        )
                        $('#myModal').modal('show');
                },
                error: function(e){
                    console.log(e)
                    alert("Cannot get data");
                }
            });

        } else {
            alert('Datos invalido, por favor intente mas tarde.')
        }
    })

    $('a.form-delete-icon').on('click', function (event) {
        if(confirm('Are you sure?')){
            let element = $(event.target);

            let str_id = element.attr('id');
            let id = str_id.split('-')[1];

            if (id) {
                $.ajax({
                    url: 'https://api.codeberrysolutions.com/api/estimate/' + id,
                    type: 'DELETE',
                    success: function (result) {
                        location.reload();
                        alert('delete success!')
                    }
                });

            }
        }

    })
})