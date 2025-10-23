<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Feedback</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for stars -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .feedback-card {
        max-width: 480px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        font-family: "Cairo", sans-serif;
    }

    .feedback-title {
        font-size: 18px;
        color: #0f172a;
        font-weight: 600;
        margin-bottom: 16px;
        text-align: center;
    }

    .feedback-buttons {
        display: flex;
        justify-content: center;
        gap: 14px;
        margin-bottom: 16px;
    }

    .feedback-option {
        padding: 12px 18px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #334155;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s ease;
    }

    .feedback-option i {
        font-size: 18px;
    }

    .feedback-option.helpful:hover {
        background: #dcfce7;
        border-color: #86efac;
        color: #166534;
    }

    .feedback-option.not-helpful:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #991b1b;
    }

    input[type="radio"] {
        display: none;
    }

    input[type="radio"]:checked+.feedback-option.helpful {
        background: #22c55e;
        color: #fff;
        border-color: #22c55e;
    }

    input[type="radio"]:checked+.feedback-option.not-helpful {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
    }

    .feedback-textarea {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px;
        font-size: 14px;
        resize: vertical;
        min-height: 80px;
        margin-bottom: 14px;
    }

    .feedback-submit {
        width: 100%;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .feedback-submit:hover {
        background: #1d4ed8;
    }
</style>

<body>
    <div class="feedback-card">
        <h4 class="feedback-title">هل كانت التجربة مفيدة؟</h4>

        <form method="POST" action="{{ route('rateUser.store') }}">
            @csrf
            <input type="hidden" name="session_id" value="{{ $session_id }}">
            <input type="hidden" name="agent_id" value="{{ $agent_id }}">
            <div class="feedback-buttons">
                <label>
                    <input type="radio" name="helpful" value="1">
                    <div class="feedback-option helpful">
                        <i class="fas fa-thumbs-up"></i>

                    </div>
                </label>

                <label>
                    <input type="radio" name="helpful" value="0">
                    <div class="feedback-option not-helpful">
                        <i class="fas fa-thumbs-down"></i>

                    </div>
                </label>
            </div>

            <textarea name="comment" class="feedback-textarea" placeholder="أضف ملاحظاتك (اختياري)"></textarea>

            <button type="submit" class="feedback-submit">إرسال التقييم</button>
        </form>
    </div>
</body>

</html>
