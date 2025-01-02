<?php
function alert($type, $msg) {
    echo '<div class="alert alert-'.$type.' my-5" role="alert">';
    echo $msg;
    echo '</div>';
}

function econ_sql($econ): string {
    switch ($econ) {
        case 'none':
            alert('danger', 'Musíte zvoliť Ekonomickú veličinu!');
            die();
        case '1':
            return 'SUM(prijem)';
        case '2':
            return 'SUM(naklady)';
        case '3':
        default:
            return 'SUM(prijem)-SUM(naklady)';
    }
}

function econ_dim1($econ, $dims, $db, $dname) {
    echo '<h1>ONE DIMENSION</h1>';
    // $dimensions = array();
    // foreach ($dims as $value) {
    //     while ($row = $value->fetch_assoc()) {
    //
    //     }
    // }
    // $stmt = $db->prepare("SELECT ? AS dim, ? AS econ FROM TF3 GROUP BY dim");
    // echo '<table class="table table-hover table-responsive table-striped">';
    // $srmt = $db->prepare("SELECT ? AS dim, ? AS econ FROM TF3 GROUP BY dim");
    //
    // $srmt->bind_param("ss", $dname)
    // echo '</table>';

}

function econ_dim2($econ, $dims, $db, $dname) {
    echo '<h1>TWO DIMENSIONS</h1>';
}

function econ_dim3($econ, $dims, $db, $dname) {
    echo '<h1>THREE DIMENSIONS</h1>';

    $stmt = $db->prepare("SELECT ? AS res FROM tf3 WHERE ?=? AND ?=? AND ?=?");
    $economic = econ_sql($econ);
    $stmt->bind_param("sssssss", $economic, $dname['product'], $p, $dname['space'], $s, $dname['time'], $t);

    while ($rowP = $dims['product']->fetch_assoc()) {
        $p = $rowP[$dname['product']];
        echo '<h3>Dimenzia produktu: '.$p.'</h3>';
        echo '<table class="table table-hover table-responsive table-striped">';

        // first row is space labels
        echo '<thead><tr><th scope="col">#</th>';
        $dims['space']->data_seek(0);
        while ($rowS = $dims['space']->fetch_assoc()) {
            echo '<th scope="col">' . $rowS[$dname['space']] . '</th>';
        }
        echo '</tr></thead><tbody>';
        // return to the beginning
        $dims['space']->data_seek(0);
        $dims['time']->data_seek(0);
        while ($rowT = $dims['time']->fetch_assoc()) {
            $t = $rowT[$dname['time']];
            echo '<tr><th scope="row">'.$t.'</th>';
            $dims['space']->data_seek(0);
            while ($rowS = $dims['space']->fetch_assoc()) {
                $s = $rowS[$dname['space']];
                $pname = $dname['product'];
                $tname = $dname['time'];
                $sname = $dname['space'];
                $sql = "SELECT $economic AS res FROM TF3 WHERE $pname=$p AND $tname=$t AND $sname=$s";
                // $stmt->execute();
                // $stmt->bind_result($data);
                // $stmt->fetch();
                $data = $db->query($sql)->fetch_assoc()['res'];
                if (!$data) $data = 0;
                echo '<td>'.$data.'</td>';
                // $stmt->free_result();
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
}
?>
