<?php

namespace Bundles\Main\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The app's main controller.
 */
class MainController extends BaseController
{
    /**
     * The app homepage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        return $this->render('Main/Main/index.html.php', ['name' => $request->get('name')]);
    }
}
