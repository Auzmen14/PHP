<?php 
require_once "pelikula.php";

class PelikulaKudeatzailea {

    private array $pelikulak = [];
    private string $abisua = "";
    private array $bilaketaEmaitzak = [];

    public function __construct(array $pelikulakHasierakoak = []) {
        $this->pelikulak =$pelikulakHasierakoak;
    }

    public function getPelikulak(): array {
        return $this->pelikulak;
    }

    public function getAbisua(): string {
        return $this->abisua;
    }

    public function getBilaketaEmaitzak(): array {
        return $this->bilaketaEmaitzak;
    }

    /**
     * PISTA 1: ISAN bidez bilatu
     * - Erabili `foreach` bat `$this->pelikulak` array-a zeharkatzeko.
     * - Konparatu `$pelikula->getIsan()` eta sartutako `$isan`.
     * - Aurkitzen baduzu, bueltatu `$pelikula` objektua.
     * - Amaieraraino iristen bazara aurkitu gabe, bueltatu `null`.
     */
    public function bilatuIsan(string $isan): ?Pelikula {
    foreach ($this->pelikulak as $pelikula) {
        // Comparamos el ISAN de la película con el que buscamos
        if ($pelikula->getIsan() === $isan) {
            return $pelikula; // Si lo encuentra, devuelve el objeto y sale de la función
        }
    }
    
    // Si recorre todo el array y no lo encuentra, devuelve null
    return null; 
}

    /**
     * PISTA 2: Testua garbitu (maiuskulak eta azentuak kendu)
     * - Erabili `mb_strtolower($testua, 'UTF-8')` dena minuskulaz jartzeko.
     * - Erabili `strtr($testua, $orpoak)` azentuak kentzeko. 
     *   Adibidez: $orpoak = ['á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ñ'=>'n'];
     * - Bueltatu testu garbia.
     */
    private function garbituTestua(string $testua): string {
    
    $testuaMinusculaz = mb_strtolower($testua, 'UTF-8');
    
   
    $orpoak = ['á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ü'=>'u', 'ñ'=>'n'];
    
    
    return strtr($testuaMinusculaz, $orpoak);
}
    /**
     * PISTA 3: Formularioaren logika nagusia
     * Hemen baldintza guztiak kudeatu behar dituzu:
     * 
     * 1. Baldintza: Izena eta ISAN biak hutsik daude? (`$izena === "" && $isan === ""`)
     *    -> `$this->abisua = "Warning: ..."`
     * 
     * 2. Baldintza: ISAN hutsik dago baina Izena BETETA dago?
     *    -> Garbitu bilatutako izena `garbituTestua()` erabiliz.
     *    -> `foreach` bidez pelikula guztiak zeharkatu, haien izenak garbitu eta `str_contains()` erabili.
     *    -> Aurkitutakoak `$this->bilaketaEmaitzak[]` array-an gorde.
     * 
     * 3. ISAN beteta badago, lehenik bilatu: `$peli = $this->bilatuIsan($isan);`
     * 
     * 4. Baldintza: ISANa jada badago ETA Izena HUTSIK dago?
     *    -> EZABATU pelikula zerrendatik (erabili `unset($this->pelikulak[$index])` eta `array_values()`).
     * 
     * 5. Baldintza: ISANa jada badago ETA eremu guztiak beteta daude?
     *    -> EGUNERATU pelikularen izena, urtea eta puntuazioa `set` metodoekin.
     * 
     * 6. Baldintza: ISANa EZ badago zerrendan:
     *    -> Egiaztatu ISANak presisoki 8 digitu dituela: `strlen($isan) === 8 && ctype_digit($isan)`
     *    -> Digituak 8 badira eta eremu guztiak beteta badaude: GORDE berria (`$this->pelikulak[] = new Pelikula(...)`).
     *    -> Bestela: Muestra un Warning indicando el error.
     */
    public function prozesatuFormularioa(string $izena, string$isan, string $urtea, string$puntuazioa): void {
        // ZURE KODEA HEMEN
    }
}
?>