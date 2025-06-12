<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Carbon;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function process(Request $request)
    {
        $originalMessage = $request->input('message'); // Pesan asli
        $message = strtolower($originalMessage);       // Pesan untuk pencocokan
        $reply = '';

        // Daftar chit-chat
        $chitchat = [
            'hai' => 'Hai juga! 👋 Ada tugas yang ingin kamu catat?',
            'halo' => 'Halo! Aku siap bantu catat tugasmu.',
            'apa kabar' => 'Aku baik, terima kasih! Kamu gimana?',
            'siapa kamu' => 'Aku bot pengingat tugasmu. 😊',
            'terima kasih' => 'Sama-sama! Semangat belajar ya!',
        ];

        // Respons chit-chat jika cocok
        foreach ($chitchat as $key => $response) {
            if (str_contains($message, $key)) {
                $reply = $response;
                break;
            }
        }

        // Tambahkan tugas
        if (!$reply && str_starts_with($message, 'tambahkan tugas')) {
            $pattern = '/tambahkan tugas (.+?) deadline (\d{4}-\d{2}-\d{2})/i';
            if (preg_match($pattern, $originalMessage, $matches)) {
                $full = trim($matches[1]);
                $deadline = $matches[2];

                $parts = explode(' ', $full, 2);
                $subject = ucfirst(strtolower($parts[0]));
                $description = isset($parts[1]) ? ucfirst(strtolower($parts[1])) : '-';

                Task::create([
                    'subject' => $subject,
                    'description' => $description,
                    'deadline' => $deadline,
                    'date' => $deadline,
                ]);

                $reply = "✅ Tugas berhasil ditambahkan:\n"
                        . "📚 $subject - $description\n"
                        . "🗓️ Deadline: $deadline";
            } else {
                $reply = "⚠️ Format salah. Gunakan:\n"
                        . "`tambahkan tugas [mapel] deadline YYYY-MM-DD`";
            }
        }

        // Tugas hari ini
        elseif (!$reply && str_contains($message, 'tugas hari ini')) {
            $today = Carbon::today()->toDateString();
            $tasks = Task::where('deadline', $today)->get();
            $reply = $tasks->count() ? "📌 Tugas Hari Ini:\n" : "✅ Tidak ada tugas untuk hari ini.";

            foreach ($tasks as $i => $task) {
                $reply .= ($i+1) . ". {$task->subject} - {$task->description} (Deadline: {$task->deadline})\n";
            }
        }

        // Tugas besok
        elseif (!$reply && str_contains($message, 'tugas besok')) {
            $tomorrow = Carbon::tomorrow()->toDateString();
            $tasks = Task::where('deadline', $tomorrow)->get();
            $reply = $tasks->count() ? "📌 Tugas Besok:\n" : "✅ Tidak ada tugas untuk besok.";

            foreach ($tasks as $i => $task) {
                $reply .= ($i+1) . ". {$task->subject} - {$task->description} (Deadline: {$task->deadline})\n";
            }
        }

        // Semua tugas
        elseif (
            !$reply &&
            (str_contains($message, 'apa saja tugas') || str_contains($message, 'daftar tugas'))
        ) {
            $tasks = Task::orderBy('deadline')->get();
            if ($tasks->isEmpty()) {
                $reply = "✅ Belum ada tugas yang tercatat.";
            } else {
                $reply = "📋 Semua Tugas:\n";
                foreach ($tasks as $i => $task) {
                    $reply .= ($i+1) . ". {$task->subject} - {$task->description} (Deadline: {$task->deadline})\n";
                }
            }
        }

        // 🔹 Hapus tugas
        elseif (!$reply && str_starts_with($message, 'hapus tugas')) {
            $pattern = '/hapus tugas (\d+)/';
            if (preg_match($pattern, $message, $matches)) {
                $id = (int)$matches[1];
                $task = Task::find($id);
                if ($task) {
                    $task->delete();
                    $reply = "🗑️ Tugas {$id} berhasil dihapus.";
                } else {
                    $reply = "⚠️ Tugas {$id} tidak ditemukan.";
                }
            } else {
                $reply = "⚠️ Gunakan format: `hapus tugas [id]` (contoh: hapus tugas 3)";
            }
        }

        // 🔹 Tugas minggu ini
        elseif (!$reply && str_contains($message, 'minggu ini')) {
            $today = Carbon::today();
            $end = $today->copy()->addDays(6);
            $tasks = Task::whereBetween('deadline', [$today->toDateString(), $end->toDateString()])
                         ->orderBy('deadline')
                         ->get();

            if ($tasks->isEmpty()) {
                $reply = "✅ Tidak ada tugas minggu ini.";
            } else {
                $reply = "🗓️ Tugas Minggu Ini:\n";
                foreach ($tasks as $i => $task) {
                    $reply .= ($i+1) . ". {$task->subject} - {$task->description} (Deadline: {$task->deadline})\n";
                }
            }
        }

        // 🔹 Cari tugas berdasarkan kata kunci
        elseif (!$reply && str_starts_with($message, 'cari tugas')) {
            $keyword = trim(str_replace('cari tugas', '', $message));
            if ($keyword) {
                $tasks = Task::where('subject', 'like', "%$keyword%")
                            ->orWhere('description', 'like', "%$keyword%")
                            ->orderBy('deadline')
                            ->get();
                if ($tasks->isEmpty()) {
                    $reply = "🔍 Tidak ditemukan tugas dengan kata kunci \"$keyword\".";
                } else {
                    $reply = "🔍 Hasil pencarian \"$keyword\":\n";
                    foreach ($tasks as $i => $task) {
                        $reply .= ($i+1) . ". {$task->subject} - {$task->description} (Deadline: {$task->deadline})\n";
                    }
                }
            } else {
                $reply = "⚠️ Gunakan format: `cari tugas [kata kunci]` (contoh: cari tugas matematika)";
            }
        }

        // Perintah tidak dikenali
        if (!$reply) {
            $reply = "🤖 Perintah tidak dikenali. Coba salah satu:\n"
                    . "- `tambahkan tugas [mapel] deadline YYYY-MM-DD`\n"
                    . "- `tugas hari ini`, `tugas besok`, `tugas minggu ini`\n"
                    . "- `hapus tugas [id]`, `cari tugas [kata kunci]`\n"
                    . "- `apa saja tugas` atau `daftar tugas`";
        }

        session(['user_input' => $originalMessage]);

        return view('chat', compact('reply'));
    }
}
