<?php

class Pelikula {
    private string $isan;
    private string $izena;
    private int $urtea;
    private int $puntuazioa;

    public function __construct(string $isan, string $izena, int $urtea, int $puntuazioa) {
        $this->isan = $isan;
        $this->izena = $izena;
        $this->urtea = $urtea;
        $this->puntuazioa = $puntuazioa;
    }

    public function getIsan(): string { return $this->isan; }
    public function getIzena(): string { return $this->izena; }
    public function getUrtea(): int { return $this->urtea; }
    public function getPuntuazioa(): int { return $this->puntuazioa; }

    public function setIzena(string $izena): void { $this->izena = $izena; }
    public function setUrtea(int $urtea): void { $this->urtea = $urtea; }
    public function setPuntuazioa(int $puntuazioa): void { $this->puntuazioa = $puntuazioa; }

    
    public function toLine(): string {
        return implode(',', [$this->isan, $this->izena, $this->urtea, $this->puntuazioa]);
    }

    // Koma bidezko katetik Pelikula objektua sortu
    public static function fromLine(string $linea): Pelikula {
        $datos = explode(',', $linea);
        return new Pelikula($datos[0], $datos[1], (int)$datos[2], (int)$datos[3]);
    }
}

?>