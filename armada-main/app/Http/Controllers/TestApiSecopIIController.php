<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestApiSecopIIController extends Controller
{
  public function index()
  {
      return view('content.apps.test');
  }
}
