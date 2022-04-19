<?php

require_once __DIR__ . '/../vendor/autoload.php';

use UtopiaOverhaul\App;
use UtopiaOverhaul\BaseController;
use UtopiaOverhaul\Route;
use UtopiaOverhaul\Method;
use UtopiaOverhaul\Param;

use Utopia\Request;
use Utopia\Response;
use UtopiaOverhaul\Validator\ValidatorInteger;
use UtopiaOverhaul\Validator\ValidatorText;

trait UserResource
{
    protected array $user = [
        '$id' => 'torsten',
        'name' => 'Torsten Dittmann',
        'email' => 'torsten@appwrite.io'
    ];
}


#[Route('/')]
#[Method(Method::GET)]
class IndexController extends BaseController
{
    use UserResource;

    public function action(Request $request, Response $response): void
    {
        $response->json([
            'hello' => 'world',
            'user' => $this->user
        ]);
    }
}

#[Route('/about')]
#[Method(Method::GET)]
class AboutController extends BaseController
{
    #[Param]
    #[ValidatorText('12')]
    public string $paramOne;

    #[Param]
    #[ValidatorInteger]
    public ?int $paramTwo = 123;

    #[Param]
    #[ValidatorText('255')]
    public ?string $paramThree = "optional";

    public string $notExposed;

    public function action(Request $request, Response $response): void
    {
        $response->json([
            'param1' => $this->paramOne,
            'param2' => $this->paramTwo,
            'param3' => $this->paramThree,
        ]);
    }
}

$app = new App();
$app
    ->register(IndexController::class)
    ->register(AboutController::class);
$app->start();
$request = new Request();
$response = new Response();
$app->run($request, $response);
