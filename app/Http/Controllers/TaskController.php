<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function chat(Request $request)
    {
        $input = strtolower($request->input('message'));

        if (str_contains($input, 'tambahkan tugas')) {
            preg_match('/tugas (.+?) (.+)/', $input, $match);
            $subject = $match[1] ?? 'Umum';
            $desc = $match[2] ?? 'Tugas belum jelas';

            Task::create([
                'subject' => ucfirst($subject),
                'description' => $desc,
                'date' => Carbon::today()
            ]);

            $reply = "Tugas berhasil ditambahkan.";
        } elseif (str_contains($input, 'apa saja tugas saya')) {
            $tasks = Task::where('date', Carbon::today())->get();
            $reply = $tasks->isEmpty() ? 'Tidak ada tugas hari ini.' :
                $tasks->map(fn($t) => "- {$t->subject}: {$t->description}")->implode("\n");
        } else {
            $reply = "Perintah tidak dikenali. Coba ketik 'Tambahkan tugas...' atau 'Apa saja tugas saya'";
        }

        return view('chat', compact('reply'));
    }
}
