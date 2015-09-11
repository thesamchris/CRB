<?php

$app->get('/login', function() use ($app) {
  $app->render('auth/login.php');
})->name('login');

$app->post('/login', function()  use ($app) {

  $request = $app->request;

  $identifier = $request->post('identifier');
  $password = $request->post('password');

  $v = $app->validation;

  $v->validate([
    'Identifier' => [$identifier, 'required'],
    'Password' => [$password, 'required'],
  ]);

  if ($v->passes()) {
    $user = $app->user
    ->where('username', $identifier)
    ->orWhere('email', $identifier)
    ->where('active', true)
    ->first();

    if ($user && $app->hash->passwordCheck($password, $user->password)) {

      $_SESSION[$app->config->get('auth.session')] = $user->id;

      $app->flash('success', 'You are now signed in!');
      $app->response->redirect($app->urlFor('home'));

    } else {
      $app->flash('danger', 'Could not log you in!');
      $app->response->redirect($app->urlFor('login'));
    }
  }

  $app->render('auth/login.php', [
    'errors' => $v->errors(),
    'request' => $request
  ]);

})->name('login.post');
