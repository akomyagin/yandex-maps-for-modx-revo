<?php
$vehicle = $builder->createVehicle(
    include __DIR__.'/transport.snippets.php.return.php',
    [
        xPDOTransport::UNIQUE_KEY => 'name',
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
    ]
);
$builder->putVehicle($vehicle);
return $vehicle;
