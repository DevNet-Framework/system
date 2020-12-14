<?php

namespace Application\Controllers;

use Artister\DevNet\Mvc\Controller;
use Artister\DevNet\Mvc\IActionResult;
use Artister\DevNet\Mvc\Filters\AuthorizeFilter;
use Artister\DevNet\Security\ClaimsPrincipal;
use Artister\DevNet\Security\ClaimsIdentity;
use Artister\DevNet\Security\ClaimType;
use Artister\DevNet\Security\Claim;
use Application\Models\UserForm;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->filter('index', AuthorizeFilter::class);
    }

    public function index() : IActionResult
    {
        $user = $this->HttpContext->User;
        $emailClaim = $user->findClaim(fn($claim) => $claim->Type == "Email");
        $email = $emailClaim ? $emailClaim->Value : null;
        $this->ViewData['Email'] = $email;
        return $this->view('account/index');
    }

    public function login(UserForm $form) : IActionResult
    {
        $user = $this->HttpContext->User;

        if ($user->isAuthenticated())
        {
            return $this->redirect('account/index');
        }

        if (!$form->isValide())
        {
            return $this->view('account/login');
        }

        $identity = new ClaimsIdentity('AuthenticationUser');
        $identity->addClaim(new Claim(ClaimType::Email, $form->Username));
        $identity->addClaim(new Claim(ClaimType::Role, 'Admin'));
        $user = new ClaimsPrincipal($identity);
        $authentication = $this->HttpContext->Authentication;
        $authentication->SignIn($user, $form->Remember);

        return $this->redirect('account/index');
    }

    public function logout() : IActionResult
    {
        $authentication = $this->HttpContext->Authentication;
        $authentication->SignOut();
        return $this->redirect('account/login');
    }

    public function register() : IActionResult
    {
        return $this->view('account/register');
    }
}