<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication', [
            'requireIdentity' => false, // Don't require authentication by default
        ]);
        $this->loadComponent('Authorization.Authorization');

        // Load Identity helper for views
        $this->viewBuilder()->setHelpers(['Authentication.Identity']);

        if ($this->request->getParam('prefix') === 'Api') {
            $this->viewBuilder()->setClassName('Json');
        }
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        
        // Allow unauthenticated access to public actions
        $this->Authentication->allowUnauthenticated(['login', 'register']);
    }
}
