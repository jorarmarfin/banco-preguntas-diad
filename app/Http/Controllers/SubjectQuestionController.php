<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectQuestionController extends Controller
{
    public function index($id)
    {
        return view('subjects_questions.index', compact('id'));
    }
}
