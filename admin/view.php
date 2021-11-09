<?php
EstimationCbsApi::style( 'backend',true );
require_once( "pager.php" );

$p           = new Pager;
$response    = EstimationCbsApi::getResults();
$forms_array = json_decode( $response, true );
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


?>
<div class="col-md-12">
    <h1>Projects estimated:</h1>
</div>
    <div class="col-md-12 text-center">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Correo</th>
                <th>Días</th>
                <th>Horas</th>
                <th>Creado</th>
                <th>Actualizado</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
			<?php
			$count = 0;
			foreach ( $result as $item => $value ) {
				$count ++;
				$created_at = date( "d/m/Y", strtotime( $value['createdAt'] ) );
				$updated_at = date( "d/m/Y", strtotime( $value['updatedAt'] ) );
				$name       = $value['client_name'] . ' ' . $value['client_last_name'];
				$days       = round( $value['result_days'] );
				$hours      = round( $value['result_hours'] );
				$email      = $value['client_email'];
				echo "<tr>
			<td>$count</td>
			<td>$name</td>
			<td><a href='mailto:$email'>$email</a></td>
			<td>$days</td>
			<td>$hours</td>
			<td>$created_at</td>
			<td>$updated_at</td>
			<td>
			<a href='#' class='form-view-icon' data-toggle='modal' data-target='#myModal'><span id='view-{$value['id']}' class='dashicons dashicons-forms'></span></a>
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
                    <h4 class="modal-title">Loading...</h4>
                </div>
                <div class="modal-body text-center">
                    <p>Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success close-btn" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<?php
