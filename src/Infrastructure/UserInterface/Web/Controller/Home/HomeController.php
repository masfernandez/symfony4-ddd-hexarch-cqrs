<?php
/**
 * Copyright (c) 2018. Miguel Ángel Sánchez Fernández.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\UserInterface\Web\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package App\Infrastructure\UserInterface\Web\Controller\Home
 */
class HomeController extends Controller
{
    /**
     * @return Response
     */
    public function action()
    {
        return $this->render('web/home/home.html.twig');
    }
}