<?php

require_once __DIR__.'/../vendor/autoload.php';

use Utopia\Request;
use Utopia\Response;
use UtopiaOverhaul\App;
use UtopiaOverhaul\BaseController;
use UtopiaOverhaul\Method;
use UtopiaOverhaul\Param;
use UtopiaOverhaul\Route;
use UtopiaOverhaul\Validator\Integer;
use UtopiaOverhaul\Validator\Text;

trait UserResource
{
    private bool $database;

    protected function getDatabase(): bool
    {
        $this->database ??= true;

        return $this->database;
    }
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
            'counter' => $this->getDatabase(),
        ]);
    }
}

#[Route('/about')]
#[Method(Method::GET)]
class AboutController extends BaseController
{
    #[Param]
    #[Text('12')]
    public string $paramOne;

    #[Param]
    #[Integer]
    public ?int $paramTwo = 123;

    #[Param]
    #[Text('255')]
    public ?string $paramThree = 'optional';

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
