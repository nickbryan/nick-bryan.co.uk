<?php

namespace App\Actions;

use App\ContentParser;
use App\Treehouse;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class TreehouseSaveAction
{
    /**
     * @var Treehouse
     */
    private $treehouse;

    public function __construct(Treehouse $treehouse)
    {
        $this->treehouse = $treehouse;
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->treehouse->saveData($request->getParsedBody());

        return $response->withStatus(201)->withJson(['status' => 'created']);
    }
}
