<?php

namespace Gocanto\SimpleAdmin;

use App\Http\Controllers\Controller;
use App\User;

class SimpleAdminController extends Controller
{
    /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
      $users = User::all();

      return view('simpleAdmin::admin')->with('users', $users);
  }
}
