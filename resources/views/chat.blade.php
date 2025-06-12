<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Chatbot Reminder Tugas</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Inter', sans-serif;
        }

        .chat-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 700px;
            margin: 60px auto;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bot-msg, .user-msg {
            padding: 12px 16px;
            border-radius: 16px;
            margin-bottom: 12px;
            max-width: 80%;
            word-wrap: break-word;
            animation: slideIn 0.4s ease;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .bot-msg {
            background: #e0f7fa;
            align-self: flex-start;
        }

        .user-msg {
            background: #d1c4e9;
            align-self: flex-end;
            text-align: right;
        }

        .chat-messages {
            display: flex;
            flex-direction: column;
            min-height: 100px;
        }

        input[type="text"] {
            border-radius: 12px;
        }

        button {
            border-radius: 12px;
        }

        .examples {
            font-size: 0.9em;
            color: #666;
        }

        .chat-box {
            width: 90%;
            max-width: 600px;
            margin: 30px auto;
        }
    </style>
</head>
<body>

<div class="chat-box">
    <h3 class="text-center mb-4">🤖 Chatbot Reminder Tugas</h3>

    <div class="chat-messages">
        @if (session('user_input'))
            <div class="d-flex align-items-start justify-content-end mb-3">
                <div class="user-msg text-end">
                    {{ session('user_input') }}
                </div>
                <img src="/user.png" class="ms-2 rounded-circle" width="40">
            </div>
        @endif

        @if (isset($reply))
            <div class="d-flex align-items-start mb-3">
                <img src="/bot.png" class="me-2 rounded-circle" width="40">
                <div class="bot-msg">
                    <strong>Bot</strong><br>
                    {!! nl2br(e($reply)) !!}
                </div>
            </div>
        @endif
    </div>

    <form action="{{ url('/') }}" method="POST" class="d-flex mt-4">
        @csrf
        <input type="text" name="message" class="form-control me-2 shadow-sm" placeholder="Tulis perintah di sini..." required autofocus>
        <button type="submit" class="btn btn-primary shadow-sm">Kirim</button>
    </form>

    <div class="mt-4 examples">
        💡 Contoh perintah:<br>
            - Tambahkan tugas matematika deadline 2025-06-15<br>
            - Tugas hari ini<br>
            - Tugas minggu ini<br>
            - Cari tugas matematika<br>
            - Hapus tugas 2
    </div>
</div>

<footer class="text-center text-muted mt-5 py-4" style="background-color: #f8f9fa;">
    <div>
        <div class="mb-2">
            Made with ❤️ by <strong>22076031, Chatbot Reminder Tugas</strong>
        </div>
        <div class="mb-2">
            <a href="https://github.com/alisazzahra" target="_blank" class="text-muted me-3" style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                    <path d="M8 0C3.58 0 0 3.58 0 8a8 8 0 0 0 5.47 7.59c.4.07.55-.17.55-.38
                        0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13
                        -.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07
                        -1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.13 0 0 .67-.21
                        2.2.82a7.6 7.6 0 0 1 2-.27c.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82
                        2.2-.82.44 1.11.16 1.93.08 2.13.51.56.82 1.27.82 2.15 0 3.07-1.87
                        3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0
                        .21.15.46.55.38A8 8 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                </svg>
                GitHub
            </a>
            <a href="mailto:zemailforzera@gmail.com" class="text-muted ms-2" style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761
                        8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.143l-6.57-4.027L8 9.586l-1.239-.757zM16
                        4.697l-5.803 3.546L16 11.801V4.697z"/>
                </svg>
                Contact
            </a>
        </div>
        <div style="font-size: 0.85em;">
            &copy; {{ date('Y') }} - All right reserved.
        </div>
    </div>
</footer>

</body>
</html>
