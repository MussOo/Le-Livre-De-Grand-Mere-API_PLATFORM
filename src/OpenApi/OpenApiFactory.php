<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\SecurityScheme;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        $components = $openApi->getComponents();

        // Ajouter le schéma de sécurité "bearerAuth"
        $securityScheme = new SecurityScheme('http', 'bearer', null, null, 'bearer', 'JWT', null);
        $components->addSecuritySchemes('bearerAuth', $securityScheme);

        return $openApi;
    }
}
