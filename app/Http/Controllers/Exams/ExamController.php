<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        return view('exams.index');
    }
    public function show($id)
    {
        return view('exams.show', ['id' => $id]);
    }
}
