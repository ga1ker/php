<?php

class tiendaNube extends MarketplaceBase {
    protected string $marketplaceName = 'tiendaNube';

    public function supportingListing(): bool
    {
        return true;
    }

    public function listProducts(): array {
        // aquí ya debo hacer la implentación real jeje
    }

    public function createProduct(array $product): array {}
    public function updateProduct(string $id, array $product): array {}
    public function deleteProduct(string $id): bool {}
}