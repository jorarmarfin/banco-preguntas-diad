<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectCategoriesController extends Controller
{
    public function index()
    {
        return view('subject_categories.index');
    }
}
