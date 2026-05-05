<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;

class GuestCartService
{
    const COOKIE_NAME = 'guest_cart';
    const LIFETIME_MINUTES = 30 * 24 * 60; // 30 días

    private ?array $cache = null;

    private function read(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }
        $value = request()->cookie(self::COOKIE_NAME);
        $decoded = $value ? json_decode($value, true) : null;
        return $this->cache = is_array($decoded) ? $decoded : [];
    }

    private function write(array $items): void
    {
        $this->cache = array_values($items);
        Cookie::queue(self::COOKIE_NAME, json_encode($this->cache), self::LIFETIME_MINUTES);
    }

    public function items(): array
    {
        return $this->read();
    }

    public function count(): int
    {
        return (int) array_sum(array_column($this->read(), 'cantidad'));
    }

    public function total(): float
    {
        return (float) array_sum(array_map(
            fn($i) => $i['cantidad'] * (float) $i['precio_actual'],
            $this->read()
        ));
    }

    public function add(int $productoId, int $cantidad, float $precio): void
    {
        $items = $this->read();
        $index = $this->indexOf($items, $productoId);

        if ($index !== false) {
            $items[$index]['cantidad'] += $cantidad;
            $items[$index]['precio_actual'] = $precio;
        } else {
            $items[] = ['producto_id' => $productoId, 'cantidad' => $cantidad, 'precio_actual' => $precio];
        }

        $this->write($items);
    }

    public function update(int $productoId, int $cantidad, float $precio): void
    {
        $items = $this->read();
        $index = $this->indexOf($items, $productoId);

        if ($index !== false) {
            $items[$index]['cantidad'] = $cantidad;
            $items[$index]['precio_actual'] = $precio;
            $this->write($items);
        }
    }

    public function remove(int $productoId): void
    {
        $items = array_values(array_filter(
            $this->read(),
            fn($i) => (int) $i['producto_id'] !== $productoId
        ));
        $this->write($items);
    }

    public function clear(): void
    {
        $this->write([]);
    }

    public function forget(): void
    {
        $this->cache = [];
        Cookie::queue(Cookie::forget(self::COOKIE_NAME));
    }

    private function indexOf(array $items, int $productoId): int|false
    {
        foreach ($items as $i => $item) {
            if ((int) $item['producto_id'] === $productoId) {
                return $i;
            }
        }
        return false;
    }
}
