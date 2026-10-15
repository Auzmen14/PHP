<?php 
require_once "pelikula.php";

class PelikulaKudeatzailea {

    private array $pelikulak = [];
    private string $abisua = "";
    private array $bilaketaEmaitzak = [];

    public function __construct(array $pelikulakHasierakoak = []) {
        $this->pelikulak = $pelikulakHasierakoak;
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

    // ISAN bidez bilatu
    public function bilatuIsan(string $isan): ?Pelikula {
        foreach ($this->pelikulak as $pelikula) {
            if ($pelikula->getIsan() === $isan) {
                return $pelikula;
            }
        }
        return null; 
    }

    // Testua garbitu (maiuskulak eta azentuak kendu)
    private function garbituTestua(string $testua): string {
        $testuaMinusculaz = mb_strtolower($testua, 'UTF-8');
        $orpoak = ['á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ü'=>'u', 'ñ'=>'n'];
        return strtr($testuaMinusculaz, $orpoak);
    }

    // Formularioaren logika nagusia
    public function prozesatuFormularioa(string $izena, string $isan, string $urtea, string $puntuazioa): void {
        $izena = trim($izena);
        $isan = trim($isan);

        // 1. Izena eta ISAN hutsik badaude: Warning
        if ($isan === '' && $izena === '') {
            $this->abisua = "Warning: Izena eta ISAN eremuak ezin dira biak hutsik egon.";
            return;
        }

        // 2. ISAN hutsik badago (eta izena beteta): Izendun bilaketa (tildeak/maiuskulak kontuan hartu gabe)
        if ($isan === '' && $izena !== '') {
            $bilatutakoaGarbi = $this->garbituTestua($izena);
            foreach ($this->pelikulak as $peli) {
                $peliIzenaGarbi = $this->garbituTestua($peli->getIzena());
                if (str_contains($peliIzenaGarbi, $bilatutakoaGarbi)) {
                    $this->bilaketaEmaitzak[] = $peli;
                }
            }
            if (empty($this->bilaketaEmaitzak)) {
                $this->abisua = "Warning: Ez da aurkitu izen hori duen pelikularik.";
            }
            return;
        }

        // ISAN zenbakia sartu bada, bilatu zerrendan
        $dagoenPelikula = $this->bilatuIsan($isan);

        // 3. ISAN zerrendan badago ETA Izena HUTSIK badago: EZABATU
        if ($dagoenPelikula !== null && $izena === '') {
            foreach ($this->pelikulak as $index => $peli) {
                if ($peli->getIsan() === $isan) {
                    unset($this->pelikulak[$index]);
                    $this->pelikulak = array_values($this->pelikulak); // Indizeak berregituratu
                    $this->abisua = "Pelikula zerrendatik ezabatu da.";
                    return;
                }
            }
        }

        // 4. ISAN zerrendan badago ETA eremu guztiak beteta badaude: EGUNERATU
        if ($dagoenPelikula !== null && $izena !== '' && $urtea !== '' && $puntuazioa !== '') {
            $dagoenPelikula->setIzena($izena);
            $dagoenPelikula->setUrtea((int)$urtea);
            $dagoenPelikula->setPuntuazioa((int)$puntuazioa);
            $this->abisua = "Pelikularen informazioa eguneratu da.";
            return;
        }

        // 5. ISAN zerrendan EZ badago: ERREGISTRO BERRIA (8 digitu baditu)
        if ($dagoenPelikula === null) {
            if (strlen($isan) === 8 && ctype_digit($isan)) {
                if ($izena !== '' && $urtea !== '' && $puntuazioa !== '') {
                    $this->pelikulak[] = new Pelikula($isan, $izena, (int)$urtea, (int)$puntuazioa);
                    $this->abisua = "Pelikula berria erregistratu da.";
                } else {
                    $this->abisua = "Warning: Erregistro berria sortzeko eremu guztiak bete behar dira.";
                }
            } else {
                $this->abisua = "Warning: ISAN zenbakia ez dago zerrendan eta zehatz-mehatz 8 digitu izan behar ditu.";
            }
            return;
        }
    }
}
?>