jQuery(document).ready(function ($) {
    $('.close').on('click', function (event) {
        $('#myModal').modal('hide');
    })

    $('a.form-view-icon').on('click', function (event) {
        let element = $(event.target);

        let str_id = element.attr('id');
        let id = str_id.split('-')[1];

        if (id) {
            $.get("https://api.codeberrysolutions.com/estimations/" + id, (data, status) => {
                //alert("Data: " + data + "\nStatus: " + status);
                if (status === 'success') {
                    let platform_ios = data.platform_ios? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let platform_android = data.platform_android? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let platform_web = data.platform_web? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"

                    var user_registration = '';
                    if (data.user_registration_yes){
                        user_registration = "<span style='color: green'>Yes</span>"
                    }else if(data.user_registration_no){
                        user_registration = "<span style='color: red'>No</span>"
                    }else{
                        user_registration = "<span style='color: orange'>Not sure</span>"
                    }

                    let user_content_user_profile = data.user_content_user_profile? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let user_content_upload_media = data.user_content_upload_media? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let user_content_ratings = data.user_content_ratings? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let user_content_save_favorites = data.user_content_save_favorites? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let user_content_marketplace = data.user_content_marketplace? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"

                    let community_chat = data.community_chat? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let community_forums = data.community_forums? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let community_social_sharing = data.community_social_sharing? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let community_push = data.community_push? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"

                    let utilities_free_text_search = data.utilities_free_text_search? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let utilities_geolocation = data.utilities_geolocation? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let utilities_custom_location = data.utilities_custom_location? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let utilities_calendar_events = data.utilities_calendar_events? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let utilities_offline_mode = data.utilities_offline_mode? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"

                    let monetization_advertising = data.monetization_advertising? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let monetization_in_app_payment = data.monetization_in_app_payment? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let monetization_subscriptions = data.monetization_subscriptions? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let monetization_freemium_content = data.monetization_freemium_content? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"

                    var integrations = '';
                    if (data.integrations_internal){
                        integrations = "<span style='color: green'>Internal</span>"
                    }else if(data.integrations_external){
                        integrations = "<span style='color: green'>External</span>"
                    }else if(data.integrations_both){
                        integrations = "<span style='color: green'>Internal and External</span>"
                    }else if(data.integrations_neither){
                        integrations = "<span style='color: red'>Neither</span>"
                    }else{
                        integrations = "<span style='color: orange'>Not sure</span>"
                    }

                    let administration_cms = data.administration_cms? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let administration_user_management = data.administration_user_management? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let administration_moderate_content = data.administration_moderate_content? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"
                    let administration_analytic_suite = data.administration_analytic_suite? "<span style='color: green'>Yes</span>":"<span style='color: red'>No</span>"

                    var security = '';
                    if (data.security_basic){
                        security = "<span style='color: green'>Basic</span>"
                    }else if(data.security_encrypted) {
                        security = "<span style='color: green'>Encrypted</span>"
                    }else{
                        security = "<span style='color: orange'>Not sure</span>"
                    }

                    var ui = '';
                    if (data.ui_bare_bones){
                        ui = "<span style='color: green'>Bare bones</span>"
                    }else if(data.ui_standard) {
                        ui = "<span style='color: orange'>Standart</span>"
                    }else{
                        ui = "<span style='color: green'>Beautifull</span>"
                    }

                    var number_of_pages = '';
                    if (data.number_of_pages_less_10){
                        number_of_pages = "<span style='color: orange'>Less 10</span>"
                    }else if(data.number_of_pages_10_30){
                        number_of_pages = "<span style='color: green'>10 - 30</span>"
                    }else if(data.number_of_pages_30_100){
                        number_of_pages = "<span style='color: green'>30 - 100</span>"
                    }else{
                        number_of_pages = "<span style='color: green'>100+</span>"
                    }

                    var lang = '';
                    if (data.lang_1){
                        lang = "<span style='color: orange'>1</span>"
                    }else if(data.lang_2) {
                        lang = "<span style='color: green'>2</span>"
                    }else{
                        lang = "<span style='color: green'>More 3</span>"
                    }
                    $('.modal-title').html("<h4>Form: " + data.client_name + "</h4>")

                    $('.modal-body').html(
                        "<p>IOS: " + platform_ios + " | Android: " + platform_android +" | Web: " + platform_web + "</p>" +
                        "<p>User registration: " + user_registration + "</p>" +
                        "<p>User profile: " + user_content_user_profile + " | Upload media: " + user_content_upload_media + " | Ratings: " + user_content_ratings + " | Save favorites: " + user_content_save_favorites + " | Marketplace: " + user_content_marketplace + "</p>" +
                        "<p>Chat: " + community_chat + " | Forums: " + community_forums + " | Social sharing: " + community_social_sharing + " | Push: " + community_push + "</p>" +
                        "<p>Free text search: " + utilities_free_text_search + " | Geolocation: " + utilities_geolocation + " | Custom location: " + utilities_custom_location + " | Calendar events: " + utilities_calendar_events + " | Offline mode: " + utilities_offline_mode + "</p>" +
                        "<p>Advertising: " + monetization_advertising + " | In app payment: " + monetization_in_app_payment + " | Subscriptions: " + monetization_subscriptions + " | Premium content: " + monetization_freemium_content + "</p>" +
                        "<p>Integrations: " + integrations + "</p>" +
                        "<p>CMS: " + administration_cms + " | User management: " + administration_user_management + " | Moderate content: " + administration_moderate_content + " | Analytic suite: " + administration_analytic_suite + "</p>" +
                        "<p>Security: " + security + "</p>" +
                        "<p>Ui: " + ui + "</p>" +
                        "<p>Number of pages: " + number_of_pages + "</p>" +
                        "<p>Lang: " + lang + "</p>"
                    )
                    $('#myModal').modal('show');

                }

                //$('#myModal').modal('show');
                //$('#myModal').modal('hide');
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
                    url: 'https://api.codeberrysolutions.com/estimations/' + id,
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