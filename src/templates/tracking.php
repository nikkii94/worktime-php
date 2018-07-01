<?php include_once 'header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col">

                <h2 class="text-center">Kimutatás</h2>

                <div class="form-group">
                    <span class="switch">
                        <input type="checkbox" class="switch" id="enableColoring">
                        <label for="enableColoring">Színezés</label>
                    </span>
                </div>

                <table id="trackingTable" class="table table-hover table-bordered table-responsive-sm">
                    <?php
                    foreach ($data['trackingData'] as $employee) {

                        echo '
                                <tr class="thead thead-light">
                                    <th>'.$employee['name'].'</th>
                                    <th>Év</th>
                                    <th>Január</th>
                                    <th>Február</th>
                                    <th>Március</th>
                                    <th>Április</th>
                                    <th>Május</th>
                                    <th>Június</th>
                                    <th>Július</th>
                                    <th>Augusztus</th>
                                    <th>Szeptember</th>
                                    <th>Október</th>
                                    <th>November</th>
                                    <th>December</th>
                                </tr>
                            ';

                        $yearCount = 0;
                        foreach( $employee['data'] as $year => $types ) {

                            $yearCount++;
                            foreach( $types as $key => $months ){

                                if ( $key === 'total_worked_hour' ) {
                                    $rowName = 'Összes ledolgozott idő  (óra)';
                                    $compareTo = 160;
                                }
                                else if ( $key === 'total_worked_day' ) {
                                    $rowName = 'Összes ledolgozott nap';
                                    $compareTo = 20;
                                }
                                else if( $key === 'total_sunday_bonus' ){
                                    $rowName = 'Összes vasárnapi pótlék (óra)';
                                    $compareTo = 0;
                                }

                                echo '<tr>
                                            <th class="">'.$rowName.'</th>
                                            <th>'.$year.'</th>';

                                for ( $i = 1; $i<= 12; $i++ ){

                                    if ( $months[$i] === 0 && $key !== 'total_sunday_bonus' ){
                                        $class="table-danger";
                                    }
                                    else if ( $months[$i] < $compareTo ){
                                        $class="table-warning";
                                    }else{
                                        $class="table-success";
                                    }

                                    echo '<td data-class="'.$class.'">'.$months[$i].'</td>';
                                }

                                echo '</tr>';

                            }

                            if($yearCount < count($employee['data']) ){
                                echo '<tr class="divider"></tr>';
                            }

                        }

                    }
                    ?>
                </table>

            </div>
        </div>
    </div>

<?php include_once 'footer.php'; ?>