<?php

$app->get('/register', function() use ($app) {
 $app->render('auth/register.php');
})->name(('register'));

$app->post('/register', function() use ($app) {

  $request = $app->request;

  $email = $request->post('email');
  $username = $request->post('username');
  $password = $request->post('password');
  $passwordConfirm = $request->post('password_confirm');

  // CRB details
  $companyName = $request->post('company_name');
  $companyAddress = $request->post('company_address');
  $telephoneNumber = $request->post('telephone_number');

  $v = $app->validation;

  $v->validate([
    'Email' => [$email, 'required|email'],
    'Username' => [$username, 'required|alnumDash|max(20)'],
    'Password' => [$password, 'required|min(6)'],
    'Confirm password' => [$passwordConfirm, 'required|matches(Password)'],
    'Company name' => [$companyName, 'required'],
    'Company address' => [$companyAddress, 'required'],
    'Telephone number' => [$telephoneNumber, 'required|number'],
  ]);

  if ($v->passes()) {
    $app->user->create([
      'email' => $email,
      'username' => $username,
      'password' => $app->hash->password($password),
      'company_name' => $companyName,
      'company_address' => $companyAddress,
      'telephone_number' => $telephoneNumber,
    ]);

    $app->flash('success', 'You have been registered!');
    $app->response->redirect($app->urlFor('home'));
  }

  $app->render('auth/register.php', [
    'errors' => $v->errors(),
    'request' => $request
  ]);



})->name('register.post');