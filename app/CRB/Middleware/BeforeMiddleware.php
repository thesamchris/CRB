<?php

namespace CRB\Middleware;

use Slim\Middleware;

class BeforeMiddleware extends Middleware
{
  public function call()
  {
    $this->app->hook('slim.before', [$this, 'run']);
    $this->next->call();
  }

  public function run()
  {
    if (isset($_SESSION[$this->app->config->get('auth.session')])) {
      $session = $_SESSION[$this->app->config->get('auth.session')];
      $this->app->auth = $this->app->user->where('id', $session)->first();
    }

    $this->app->view()->appendData([
      'auth' => $this->app->auth,
      'baseUrl' => $this->app->config->get('app.url')
    ]);
  }
}