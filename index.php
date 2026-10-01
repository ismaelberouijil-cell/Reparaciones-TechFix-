<?php
declare(strict_types= 1);

enum reparacion: string

{
    case Pantalla = 'Reparar Pantalla';
    case Bateria = 'Reparar Bateria';

    case PlacaBase = 'Reparar Placa Base';

    public function etiqueta(): string
    {
        return match($this) {
            self::Pantalla => 'Reparar Pantalla',
            self::Bateria => 'Reparar Bateria',
            self::PlacaBase => 'Reparar Placa Base',
        };
    }
}
?>