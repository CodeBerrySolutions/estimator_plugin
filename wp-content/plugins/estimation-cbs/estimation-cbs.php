<?php
/*
Plugin Name: Estimación de proyectos
Plugin URI: www.codeberrysolutions.com
Description: Estimación automática de proyectos para utilizar por nuevos clientes que acceden a la web de  Codeberry Solutions. puede usar el sortcode [estimate_form] para mostrar el formulario.
Version:1.0.0
Author:Codeberry Solutions
Author URI:www.codeberrysolutions.com
License:-
*/

if (!defined('ECBS_VER')) {
    define('ECBS_VER', '1.0.0');
}

//defined('ABSPATH') or die("Bye bye");
class EstimationCbsApi
{
    public const URL = 'https://api.codeberrysolutions.com/api';
    public const END_POINT = '/estimate';
    public const END_POINT_QUESTIONS = '/questionary';
    public const END_POINT_ESTIMATE = '/estimate';
    public const END_POINT_NEW_ESTIMATE = '/estimate/create?es=';

    static function style($prefix, $admin = false)
    {
        wp_register_script($prefix . 'cbs-js_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js');
        wp_enqueue_script('jquery');
        wp_enqueue_script($prefix . 'cbs-js_bootstrap');
        wp_register_style($prefix . 'cbs_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css');
        wp_enqueue_style($prefix . 'cbs_bootstrap');
        if ($admin) {
            wp_register_style($prefix . 'cbs_style', plugin_dir_url(__FILE__) . 'admin/css/style.css');
            wp_enqueue_style($prefix . 'cbs_style');
            wp_register_script($prefix . 'cbs-js_view', plugin_dir_url(__FILE__) . 'admin/js/view.js');
            wp_enqueue_script($prefix . 'cbs-js_view');
        } else {
            wp_register_style($prefix . 'cbs_style', plugin_dir_url(__FILE__) . 'public/css/style.css');
            wp_enqueue_style($prefix . 'cbs_style');
        }
    }

    static function getResults()
    {
        $response = wp_remote_get(self::URL . self::END_POINT_ESTIMATE, ['timeout' => 10]);
        $body = wp_remote_retrieve_body($response);
        $http_code = wp_remote_retrieve_response_code($response);
        if ($http_code != 200) {
            throw new HttpException($http_code);
        } else {
            return $body;
        }
    }

    static function getResult($id)
    {
        $response = wp_remote_get(self::URL . self::END_POINT_NEW_ESTIMATE . '/' . $id, ['timeout' => 10]);
        $body = wp_remote_retrieve_body($response);
        $http_code = wp_remote_retrieve_response_code($response);
        if ($http_code != 200) {
            throw new HttpException($http_code);
        } else {
            return $body;
        }
    }

    static function setData($postData)
    {
        $url = self::URL . self::END_POINT_NEW_ESTIMATE.str_split(get_locale(),2)[0];
        $body['estimation'] = json_encode($postData);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        if ($info['http_code'] != 200) {
            throw new HttpException($response);
        } else {
            return json_decode($response, true);
        }
    }

    static function getQuestions()
    {
        $url = self::URL . self::END_POINT_QUESTIONS;

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'timeout' => 100,
            'data_format' => 'body',
        ];
        $response = wp_remote_get($url, $options);
        $body = wp_remote_retrieve_body($response);
        $http_code = wp_remote_retrieve_response_code($response);
        if ($http_code != 200) {
            throw new HttpException($http_code);
        } else {
            return json_decode($body, true);
        }

    }

}

/**
 * Activate the plugin.
 */
function estimation_cbs_activate()
{
    // Trigger our function that registers the custom post type plugin.

    // Clear the permalinks after the post type has been registered.

}

register_activation_hook(__FILE__, 'estimation_cbs_activate');

/**
 * Activate the plugin.
 */
function estimation_cbs_desactivate()
{
    // Trigger our function that registers the custom post type plugin.

    // Clear the permalinks after the post type has been registered.

}

register_deactivation_hook(__FILE__, 'estimation_cbs_desactivate');

add_action('admin_menu', 'estimation_admin_page');
function estimation_admin_page()
{
    add_menu_page(
        'Estimations',
        'Projects estimated',
        'manage_options',
        plugin_dir_path(__FILE__) . 'admin/view.php',
        null,
        'dashicons-chart-line',
        20
    );
}

function estimateForm()
{
    EstimationCbsApi::style('frontend');
    wp_enqueue_script('cbs_custom_script', plugin_dir_url(__FILE__) . 'public/js/functions.js');
    wp_enqueue_style('cbs_custom_style', plugin_dir_url(__FILE__) . 'public/css/style.css');

    global $wp;
    $lang = strtolower(substr(get_locale(), 0, 2)) == 'es' ? 'es' : 'en';
    $current_url = add_query_arg($wp->query_string, '', home_url($wp->request));
    if (!empty($_POST) && isset($_POST['estimate-form'])) {
        array_shift($_POST);
        $response = EstimationCbsApi::setData($_POST);
        $estimation = $response['estimation'];
        ?>
        <div class="col-md-12">
            <div class="card"
                 style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;border: none">
                <div class="card-head">
                    <span class="dashicons dashicons-saved"
                          style="width: 80px;height: 80px;font-size: 80px;color: white;border-radius: 100%;border: 3px solid white;"></span>
                    <h1 style="color: white;font-weight: normal"><?= $lang == 'es' ? 'Felicidades!' : 'Success!' ?></h1>
                </div>
                <div class="card-body" style="text-align: center;">
                    <h4 style="text-align: center;color: green;font-weight: normal"><?= $lang == 'es' ? 'Felicidades' : 'Congratulations' ?> <?= $estimation['client_name'] . ' ' . $estimation['client_last_name'] ?>
                        , <?= $lang == 'es' ? ' tu aplicación ha sido
                        estimada con éxito. ' : ' your app has been
                        successfully estimated. ' ?></h4>
                    <div class="col-md-12" style="margin-top: 20px;text-align: left">
                        <h6  style="color: green;font-weight: normal"><?= $lang == 'es' ? 'Total de horas: ' : 'Total hours:  ' ?><?= '<span style="color: black">' . $estimation['estimate'][0] . '</span>' ?></h6>
                        <h6 style="color: green;font-weight: normal"><?= $lang == 'es' ? 'Total de unidades de esfuerzo: ' : 'Total effort unit:  ' ?> <?= '<span style="color: black">' . round($estimation['estimate']['min']) .'-'.round($estimation['estimate']['max']). '</span>' ?></h6>

                    </div>
                    <a class="btn estimate-button-return" href="<?= $current_url ?>"
                       style="color: black;"><?= $lang == 'es' ? 'Regresar ' : 'Return ' ?></a>
                </div>
            </div>
        </div>
    <?php } else {
        // get current url with query string.
        $response = EstimationCbsApi::getQuestions();
        ?>

        <form class="form" id="estimate_form_frontend" method="post" action="#">
            <div class="row estimate-text text-center">
                <input id="estimate-form" type="hidden" name="estimate-form"
                       value="estimate_form_frontend"/>
                <div class="col-md-12">
                    <h1><?= $lang == 'es' ? '¡Déjanos estimar su aplicación !': 'Let\'s estimate your app!' ?></h1>
                </div>
                <?php foreach ($response['questions'] as $question) { ?>

                    <div class="col-md-12">
                        <div class="col-md-12"><p><?= $question['title_' . $lang] ?></p></div>
                        <div class="form-group group-flex">
                            <?php foreach ($question['answers'] as $answer) {
                                if ($answer['sons']) {
                                    $pos = 0;
                                    foreach ($answer['sons'] as $son) {
                                        $answer['sons'][$pos]['question_id'] = $son['question_id'];
                                        $pos++;
                                    }
                                }
                                if ($answer['fathers']) {
                                    $pos = 0;
                                    foreach ($answer['fathers'] as $father) {
                                        $answer['fathers'][$pos]['question_id'] = $father['question_id'];
                                        $pos++;
                                    }
                                }
                                ?>

                                <input id="check_<?= $question['id'] . '_' . $answer['id'] ?>"
                                       sons="<?= aTojS('sons', $answer) ?>" fathers="<?= aTojF('fathers', $answer) ?>"
                                       type="checkbox" hidden
                                       class="<?= $question['type'] ? 'question_' . $question['id'] : 'question_' . $question['id'] ?> check-estimate"
                                       name="<?= 'Questions[' . $question['id'] . '_' . $answer['id'] . ']' ?>"
                                       locator="<?= $question['id'] . '_' . $answer['id'] ?>"/>
                                <a id="<?= $question['id'] . '_' . $answer['id'] ?>" class="btn estimate-button"
                                   family="<?= $question['type'] ? 'single_' . $question['id'] : 'multiple_' . $question['id'] ?>"
                                   req="<?= $question['required'] ? 1 : 0 ?>" <?= $answer['fathers'] ? 'hidden' : '' ?>><?= $answer['title_' . $lang] ?></a>
                            <?php } ?>
                        </div>
                        <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                    </div>
                <?php } ?>

                <div class="row col-md-12">
                    <div class="col-md-12"><p>Client Contact <span style="color:red;">*</span></p></div>
                    <div class="form-group group-flex col-md-6">
                        <label id="label_client_name" for="client_name">Name</label><input id="client_name" type="text"
                                                                                           class="client form-control"
                                                                                           family="client"
                                                                                           placeholder="Name"
                                                                                           name="Client[client_name]"
                                                                                           required/>
                    </div>
                    <div class="form-group group-flex col-md-6">
                        <label id="label_client_last_name" for="client_last_name">Last name</label><input
                                id="client_last_name" type="text"
                                class="form-control client"
                                name="Client[client_last_name]" placeholder="Last Name" required/>
                    </div>
                    <div class="form-group group-flex col-md-12">
                        <label id="label_client_email" for="client_email">Email</label><input id="client_email"
                                                                                              type="email"
                                                                                              class="form-control client"
                                                                                              name="Client[client_email]"
                                                                                              placeholder="name@gmail.com"
                                                                                              required/>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <input id="enviar_form" class="btn estimate-button-submit offset-md-3" type="button"
                           value="Send"/>
                </div>
            </div>
        </form>
        <?php
    }
}

function aTojS($key, $array)
{
    return $array[$key] ? preg_replace("/\"/", "'", preg_replace("/[\[]/", "{", preg_replace("/[\]]/", "}", preg_replace("[\{||\}]", "", json_encode(array_map(function ($index) {
        $required = $index['required'] ? '1' : '0';
        return ["{$index['question_id']}_{$index['depend_id']}" => $required];
    }, $array[$key])))))) : '-1';
}

function aTojF($key, $array)
{
    return $array[$key] ? preg_replace("/\"/", "'", preg_replace("/[\[]/", "{", preg_replace("/[\]]/", "}", preg_replace("[\{||\}]", "", json_encode(array_map(function ($index) {
        $required = $index['required'] ? '1' : '0';
        return ["{$index['question_id']}_{$index['answer_id']}" => $required];
    }, $array[$key])))))) : '-1';
}

add_shortcode('estimate_form', 'estimateForm');

