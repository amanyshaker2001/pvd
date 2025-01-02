<?php
require_once '../connection_info.php';

class Dimension {
    private string $name;
    public string $sql;
    public array $values;
    public string $category;
    private function get_sql(): string {
        switch ($this->name) {
            case 'rok':
                return 'rok';
            case 'mesiac':
                return 'mesiac';
            case 'den':
                return 'den';
            case 'vuc':
                return 'skratka_vuc';
            case 'mesto':
                return 'skratka_mesta';
            case 'okres':
                return 'skratka_okresu';
            case 'skupina':
                return 'id_s';
            case 'typ':
                return 'typ_vystupu';
            case 'produkt':
                return 'id_vystup';
        }
        return '';
    }
    private function get_category(): string {
        switch ($this->name) {
            case 'rok':
            case 'mesiac':
            case 'den':
                return 'čas';
            case 'vuc':
            case 'mesto':
            case 'okres':
                return 'priestor';
            case 'skupina':
            case 'typ':
            case 'produkt':
                return 'produkt';
        }
        return '';
    }
    public function __construct(string $name, mysqli $conn) {
        $this->name = $name;
        $this->sql = $this->get_sql();
        $this->category = $this->get_category();

        $stmt = $conn->prepare("SELECT $this->sql AS dim FROM tf3 GROUP BY dim");
        $stmt->execute();
        $result = $stmt->get_result();
        // echo 'total rows for '.$this->sql.': '.$result->num_rows.'<br>';
        while ($row = $result->fetch_assoc()) {
            // echo '...fetching dimension value<br>';
            $this->values[] = $row['dim'];
        }
        $stmt->close();
    }
}
?>
