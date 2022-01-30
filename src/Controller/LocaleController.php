<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/change-locale', name: 'change_locale')]
    public function changeLocale(Request $request): RedirectResponse
    {
        $currentLocale = $request->getLocale();
        $chosenLocale = $request->query->get('locale');

        if($chosenLocale !== $currentLocale) {
            $request->getSession()->set('_locale', $chosenLocale);
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer !== null ? $referer : 'product_list');
    }

}
