<?php
EstimationCbsApi::style( 'backend',true );
require_once( "pager.php" );

$p           = new Pager;
$response    = EstimationCbsApi::getResults();
$forms_array = json_decode( $response, true )['estimations'];
/* Show many results per page? */
$limit = $_GET['show_all']?count($forms_array) : 10;

/* Find the start depending on $_GET['page'] (declared if it's null) */
$start = $p->findStart( $limit );

/* Find the number of rows returned from a query; Note: Do NOT use a LIMIT clause in this query */
$count = count( $forms_array );

/* Find the number of pages based on $count and $limit */
$pages = $p->findPages( $count, $limit );

/* Now we use the LIMIT clause to grab a range of rows */
$result = array_slice( $forms_array, $start, $limit );

/* Now get the page list and echo it */
$pagelist = $p->pageList( $_GET['pager'], $pages );
/* Or you can use a simple "Previous | Next" listing if you don't want the numeric page listing */
//$next_prev = $p->nextPrev($_GET['page'], $pages);
//echo $next_prev;
/* From here you can do whatever you want with the data from the $result link. */

$lang = str_split(get_locale(),2)[0]
?>
<div class="col-md-12">
    <h1><?=$lang == "es"? 'Estimaciones de proyectos:':'Projects estimated:' ?></h1>
</div>
    <div class="col-md-12 text-center">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th><?=$lang == "es"? 'Cliente':'Client' ?></th>
                <th><?=$lang == "es"? 'Correo':'Email' ?></th>
                <th><?=$lang == "es"? 'Puntos de esfuerzo':'Effort unit' ?></th>
                <th><?=$lang == "es"? 'Horas':'Hours' ?></th>
                <th><?=$lang == "es"? 'Creado':'Created' ?></th>
                <th><?=$lang == "es"? 'Actualizado':'Updated' ?></th>
                <th><?=$lang == "es"? 'Acciones':'Actions' ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			$count = 0;
			foreach ( $result as $value ) {
				$count ++;
				$created_at = date( "d/m/Y", strtotime( $value['created_at'] ) );
				$updated_at = date( "d/m/Y", strtotime( $value['updated_at'] ) );
				$name       = $value['client_name'] . ' ' . $value['client_last_name'];
				$effort       = round( $value['total_hours'] );
				$hours      = round( $value['total_effort_unit'] );
				$email      = $value['client_email'];
				echo "<tr>
			<td>$count</td>
			<td>$name</td>
			<td><a href='mailto:$email'>$email</a></td>
			<td>$effort</td>
			<td>$hours</td>
			<td>$created_at</td>
			<td>$updated_at</td>
			<td>
			<a href='#' class='form-view-icon' lang='".get_locale()."' data-toggle='modal' data-target='#myModal'><span id='view-{$value['id']}' class='dashicons dashicons-forms' ></span></a>
			<a href='#' class='form-delete-icon'><span id='delete-{$value['id']}' class='dashicons dashicons-trash'></span></a>
			</td>
		</tr>";
			}
			echo "<tr><td colspan='6'>$pagelist</td><td>
<a href=\" ".$_SERVER['PHP_SELF']."?page=estimation-cbs%2Fadmin%2Fview.php&show_all=1\" title=\"First Page\">all</a>
</td></tr>";
			?>
            </tbody>
        </table>
        <!-- Modal -->

    </div>


    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" lang="<?=str_split(get_locale(),2)[0]?>"><?=$lang == "es"? 'Cargando...':'Loading...' ?></h4>
                </div>
                <div class="modal-body text-center" lang="<?=str_split(get_locale(),2)[0]?>">
                    <p><?=$lang == "es"? 'Cargando...':'Loading...' ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success close-btn" data-dismiss="modal"><?=$lang == "es"? 'Cerrar':'Close' ?></button>
                </div>
            </div>

        </div>
    </div>
<?php
