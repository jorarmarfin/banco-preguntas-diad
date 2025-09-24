<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectQuestionController extends Controller
{
    public function index($id)
    {
        return view('subject_questions.index', ['topic_id' => $id]);
    }
}
