<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="../css/style.css">
</head>
<body>



<div class="container mt-3 g-2">
    <div class="row">
        <div class="col-4">
            <div class="sticky-top pvd-sticky-side pvd-back">
                <div class="mb-3">
                    <h2>Zvoľte sledované veličiny</h2>
                </div>

                <form class="" action="./econ.php" method="post">
                    <div class="mb-3">
                        <!-- <label for="selectEcon" class="form-label">Vyberte sledovanú veličinu</label> -->
                        <select id="selectEcon" class="form-select" name="econ">
                            <option value="none" selected>Ekonomická veličina...</option>
                            <option value="prijem">prijem</option>
                            <option value="naklady">naklady</option>
                            <option value="zisk">zisk</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <!-- <label for="selectDimension1" class="form-label">Vyberte dimenziu 1</label> -->
                        <select id="selectTime" class="form-select" name="time">
                            <option value="none" selected>Dimenzia času...</option>
                            <option value="rok">Rok</option>
                            <option value="mesiac">Mesiac</option>
                            <option value="den">Deň</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <!-- <label for="selectDimension2" class="form-label">Vyberte dimenziu 2</label> -->
                        <select id="selectSpace" class="form-select" name="space">
                            <option value="none" selected>Dimenzia priestoru...</option>
                            <option value="mesto">Mesto</option>
                            <option value="vuc">vuc</option>
                            <option value="okres">okres</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <!-- <label for="selectDimension3" class="form-label">Vyberte dimenziu 3</label> -->
                        <select id="selectProduct" class="form-select" name="product">
                            <option value="none" selected>Dimenzia produktu...</option>
                            <option value="skupina">Skupina produktu</option>
                            <option value="typ">Typ produktu</option>
                            <option value="produkt">Produkt</option>
                        </select>
                    </div>
                    <div class="d-grid g-2 mb-3">
					 <button type="submit">Odoslať</button>
						<a href="../index.php" >Naspäť domov</a>
                        
                    </div>
                </form>
            </div>
        </div>
        <div class="col-8">
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/data.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <?php
            require_once './econ_analysis.php';

            if (!$_POST) {
                alert('info', 'Vyberte si ekonomickú veličinu na sledovanie a jej dimenzie.');
            } else {
                $analysis = new EconAnalysis(
                    $_POST['econ'],
                    [
                        $_POST['product'],
                        $_POST['space'],
                        $_POST['time'],
                    ]
                );
                $analysis->print_analysis();
            }
        ?>

        </div>
    </div>
</div>

<!-- bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
