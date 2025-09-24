<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectChaptersController extends Controller
{
    public function index($id)
    {
        return view('subject_chapters.index', ['subject_id' => $id]);
    }
}
