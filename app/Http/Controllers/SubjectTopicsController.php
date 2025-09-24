<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectTopicsController extends Controller
{
    public function index($id)
    {
        return view('subject_topics.index', ['chapter_id' => $id]);
    }
}
