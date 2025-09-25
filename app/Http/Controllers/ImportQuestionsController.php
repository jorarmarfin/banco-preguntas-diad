<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportQuestionsController extends Controller
{
    public function index()
    {
        return view('import_questions.index');
    }
}
