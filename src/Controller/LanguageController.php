<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LanguageController
{
    #[Route('/language/{locale}', name: 'change_language')]
    public function changeLanguage(string $locale, SessionInterface $session): RedirectResponse
    {
        $session->set('_locale', $locale);
        return new RedirectResponse($_SERVER['HTTP_REFERER'] ?? '/');
    }
}