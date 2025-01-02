<?php
require_once './dimension.php';
require_once '../utils/utils.php';

class EconAnalysis {
    private string $econ;
    private mysqli $conn;
    public string $econ_sql;
    public string $econ_name;
    public array $dimensions;
    public int $dimension_count;


    private function get_econ_sql(): string {
        switch ($this->econ) {
            case 'prijem':
                return 'ROUND(SUM(prijem),2)';
            case 'naklady':
                return 'ROUND(SUM(naklady),2)';
            case 'zisk':
                return 'ROUND(SUM(prijem)-SUM(naklady),2)';
        }
        return '';
    }

    private function get_econ_name(): string {
        switch ($this->econ) {
            case 'prijem':
                return 'prijem';
            case 'naklady':
                return 'naklady';
            case 'zisk':
                return 'zisk';
        }
        return '';
    }

    private function one_dimensional(): void {
        $stmt = $this->conn->prepare("SELECT $this->econ_sql FROM tf3 WHERE ".$this->dimensions[0]->sql."=?");
        $stmt->bind_param("s", $value);

        echo "<h2 class=\"my-2\">Jednodimenzionálna analýza pre veličinu <strong>$this->econ_name</strong>.</h2>";
        echo "<h5>Dimenzia ".$this->dimensions[0]->category.": <strong>".$this->dimensions[0]->sql."</strong></h5><hr class=\"my-4\">";

        $graph_container = "graph";
        $table = "table";
        $graph_name = ucfirst($this->econ_name);

        echo<<<HTML
        <div class="accordion" id="accordion-$table">
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading-$table">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-$table" aria-expanded="true" aria-controls="collapse-$table">
                Tabuľka
              </button>
            </h2>
            <div id="collapse-$table" class="accordion-collapse collapse show" aria-labelledby="heading-$table" data-bs-parent="#accordion-$table">
              <div class="accordion-body">
        HTML;
        echo '<table id="'.$table.'" class="table table-hover table-striped table-responsive">';
        echo '<thead><tr><th scope="column">'.ucfirst($this->dimensions[0]->sql).'</th>';
        echo '<th scope="column">'.ucfirst($this->econ_name).'</th></tr></thead><tbody>';
        // echo '<tbody>';

        foreach ($this->dimensions[0]->values as $value) {
            echo '<tr><th scope="row">'.$value.'</th>';
            $stmt->execute();
            $stmt->bind_result($data);
            $stmt->fetch();
            echo '<td>'.$data.'</td></tr>';
        }

        echo<<<HTML
                      </tbody>
                    </table>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading-$graph_container">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-$graph_container" aria-expanded="false" aria-controls="collapse-$graph_container">
                  Graf
                </button>
              </h2>
              <div id="collapse-$graph_container" class="accordion-collapse collapse" aria-labelledby="heading-$graph_container" data-bs-parent="#accordion-$table">
                <div id="$graph_container" class="accordion-body">
                </div>
                <script type="text/javascript">
                    Highcharts.chart('$graph_container', {
                        data: {
                            table: '$table'
                        },
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: '$graph_name'
                        },
                        yAxis: {
                            allowDecimals: true,
                            title: {
                                text: 'Eur'
                            }
                        }
                        });
                </script>
              </div>
            </div>
        </div>
        HTML;


        $stmt->close();
    }

    private function two_dimensional(): void {
        $stmt = $this->conn->prepare("SELECT $this->econ_sql FROM tf3 WHERE ".$this->dimensions[1]->sql."=? AND ".$this->dimensions[0]->sql."=?");
        $stmt->bind_param("ss", $row_dim, $col_dim);

        echo "<h2 class=\"my-2\">Dvojdimenzionálna analýza pre veličinu <strong>$this->econ_name</strong>.</h2>";
        echo "<h5>Dimenzia ".$this->dimensions[1]->category.": <strong>".$this->dimensions[1]->sql."</strong></h5>";
        echo "<h5>Dimenzia ".$this->dimensions[0]->category.": <strong>".$this->dimensions[0]->sql."</strong></h5><hr class=\"my-4\">";

        $graph_container = "graph";
        $table = "table";
        $graph_name = ucfirst($this->econ_name);

        echo<<<HTML
        <div class="accordion" id="accordion-$table">
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading-$table">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-$table" aria-expanded="true" aria-controls="collapse-$table">
                Tabluľka
              </button>
            </h2>
            <div id="collapse-$table" class="accordion-collapse collapse show" aria-labelledby="heading-$table" data-bs-parent="#accordion-$table">
              <div class="accordion-body">
        HTML;
        echo '<table id="'.$table.'" class="table table-hover table-striped table-responsive mt-4 mx-4">';
        echo '<thead><tr><td></td>';
        foreach ($this->dimensions[0]->values as $value) {
            echo '<th scope="col">'.ucfirst($value).'</th>';
        }
        echo '</tr></thead><tbody>';

        foreach($this->dimensions[1]->values as $row_dim) {
            echo '<tr><th scope="row">'.ucfirst($row_dim).'</th>';
            foreach($this->dimensions[0]->values as $col_dim) {
                $stmt->execute();
                $stmt->bind_result($data);
                $stmt->fetch();
                echo "<td>$data</td>";
            }
            echo '</tr>';
        }

        echo<<<HTML
                      </tbody>
                    </table>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading-$graph_container">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-$graph_container" aria-expanded="false" aria-controls="collapse-$graph_container">
                  Graf
                </button>
              </h2>
              <div id="collapse-$graph_container" class="accordion-collapse collapse" aria-labelledby="heading-$graph_container" data-bs-parent="#accordion-$table">
                <div id="$graph_container" class="accordion-body">
                </div>
                <script type="text/javascript">
                    Highcharts.chart('$graph_container', {
                        data: {
                            table: '$table'
                        },
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: '$graph_name'
                        },
                        yAxis: {
                            allowDecimals: true,
                            title: {
                                text: 'Eur'
                            }
                        }
                        });
                </script>
              </div>
            </div>
        </div>
        HTML;

        $stmt->close();
    }

    private function three_dimensional(): void {
        $stmt = $this->conn->prepare("SELECT $this->econ_sql FROM tf3 WHERE ".$this->dimensions[2]->sql."=? AND ".$this->dimensions[1]->sql."=? AND ".$this->dimensions[0]->sql."=?");
        $stmt->bind_param("sss", $grp_dim, $row_dim, $col_dim);

        echo "<h2 class=\"my-2\">Trojdimenzionálna analýza pre veličinu <strong>$this->econ_name</strong>.</h2>";
        echo "<h5>Dimenzia ".$this->dimensions[2]->category.": <strong>".$this->dimensions[2]->sql."</strong></h5>";
        echo "<h5>Dimenzia ".$this->dimensions[1]->category.": <strong>".$this->dimensions[1]->sql."</strong></h5>";
        echo "<h5>Dimenzia ".$this->dimensions[0]->category.": <strong>".$this->dimensions[0]->sql."</strong></h5>";

        foreach ($this->dimensions[2]->values as $grp_dim) {
            $graph_container = "graph-" . $grp_dim;
            $table = "table-" . $grp_dim;
            $graph_name = ucfirst($this->econ_name);

            echo '<hr class="my-4"><h4>'.ucfirst($this->dimensions[2]->sql).': '.$grp_dim.'</h4>';
            echo<<<HTML
            <div class="accordion" id="accordion-$table">
              <div class="accordion-item">
                <h2 class="accordion-header" id="heading-$table">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-$table" aria-expanded="true" aria-controls="collapse-$table">
                    Tabluľka
                  </button>
                </h2>
                <div id="collapse-$table" class="accordion-collapse collapse show" aria-labelledby="heading-$table" data-bs-parent="#accordion-$table">
                  <div class="accordion-body">
            HTML;
            echo '<table id="'.$table.'" class="table table-hover table-striped table-responsive caption-top my-4">';
            echo '<thead><tr><td></td>';
            foreach ($this->dimensions[0]->values as $value) {
                echo '<th scope="col">'.ucfirst($value).'</th>';
            }
            echo '</tr></thead><tbody>';

            foreach ($this->dimensions[1]->values as $row_dim) {
                echo '<tr><th scope="row">'.ucfirst($row_dim).'</th>';
                foreach ($this->dimensions[0]->values as $col_dim) {
                    $stmt->execute();
                    $stmt->bind_result($data);
                    $stmt->fetch();
                    echo "<td>$data</td>";
                }
                echo '</tr>';
            }
            echo<<<HTML
                          </tbody>
                        </table>
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading-$graph_container">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-$graph_container" aria-expanded="false" aria-controls="collapse-$graph_container">
                      Graf
                    </button>
                  </h2>
                  <div id="collapse-$graph_container" class="accordion-collapse collapse" aria-labelledby="heading-$graph_container" data-bs-parent="#accordion-$table">
                    <div id="$graph_container" class="accordion-body">
                    </div>
                    <script type="text/javascript">
                        Highcharts.chart('$graph_container', {
                            data: {
                                table: '$table'
                            },
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: '$graph_name'
                            },
                            yAxis: {
                                allowDecimals: true,
                                title: {
                                    text: 'Eur'
                                }
                            }
                            });
                    </script>
                  </div>
                </div>
            </div>
            HTML;
        }

        $stmt->close();
    }

    public function dump(): void {
        echo '<h1>DUMPING EconAnalysis OBJECT</h1>';
        echo '$econ_sql = ' . $this->econ_sql;
        echo '<br>$econ_name = ' . $this->econ_name;
        echo '<br>$dimension_count = ' . $this->dimension_count;
        echo '<br><strong>array $dimensions:</strong>';
        foreach ($this->dimensions as $value) {
            // echo '<br>  $name = ' . $value->name;
            echo '<br>  $sql = ' . $value->sql;
            echo '<br>  $category = ' . $value->category;
            echo '<br>  $values:';
            foreach ($value->values as $key => $val) {
                echo '<br>    ' . $key . ' => ' . $val;
            }
        }
    }

    function __construct(string $econ, array $dims) {
        require '../connection_info.php';
        $this->conn = new mysqli($server, $name, $password, $database);
        $this->econ = $econ;
        $this->econ_sql = $this->get_econ_sql();
        $this->econ_name = $this->get_econ_name();
        $this->dimension_count = 0;

        foreach ($dims as $value) {
            if ($value !== 'none') {
                $this->dimensions[] = new Dimension($value, $this->conn);
                $this->dimension_count++;
            }
        }
    }

    function print_analysis(): void {
        if ($this->econ_sql === '') {
            alert('danger', 'Zvoľte si ekonomickú veličinu na sledovanie.');
        }
        switch ($this->dimension_count) {
            case 1:
                $this->one_dimensional();
                break;
            case 2:
                $this->two_dimensional();
                break;
            case 3:
                $this->three_dimensional();
                break;
            default:
                alert('danger', 'Zvoľte si aspoň jednu dimenziu na sledovanie.');
                break;
        }
    }

    function __destruct() {
        $this->conn->close();
    }
}
?>
