<?php
declare(strict_types=1);

/**
 * Tipos de reparación disponibles.
 */
enum TipoReparacion: string
{
    case Pantalla = 'pantalla';
    case Bateria = 'bateria';
    case PlacaBase = 'placa_base';

    public function descripcion(): string
    {
        return match ($this) {
            self::Pantalla => 'Reparación de pantalla',
            self::Bateria => 'Cambio de batería',
            self::PlacaBase => 'Reparación de placa base',
        };
    }
}

/**
 * Orden de trabajo inmutable.
 */
final readonly class OrdenTrabajo
{
    public function __construct(
        public int $numero,
        public string $cliente,
        public TipoReparacion $tipo,
        public float $manoObra,
        public float $recambios
    ) {
    }

    public function total(): float
    {
        return calcularPresupuesto(
            manoObra: $this->manoObra,
            recambios: $this->recambios
        );
    }
}
