<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agent Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/agent_session.css') }}">
</head>
<body>
    <div class="d-flex justify-content-between align-items-center" style="width: 80%; margin:auto; padding:10px;">
        <a href="javascript:void(0)"
        onclick="document.getElementById('agent-logout').submit()">Logout</a>
        <form action="{{route('agent.logout')}}" method="post" id="agent-logout">@csrf</form>
        <h3>{{ $agent->name }}</h3>
    </div>
    <div class="d-flex justify-content-between align-items-center" style="width: 80%; margin:auto; padding:10px;">
    </div>
    <div class="agent-container">
        <div class="agent-header">
            <h2>لوحة خدمة العملاء</h2>
        </div>

        <div class="session-list">

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

<script type="module">
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import {
        getDatabase,
        ref,
        onChildAdded,
        onChildChanged,
        onChildRemoved
    }
    from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";

    const firebaseConfig = {
        apiKey: "AIzaSyAOxJoIvnPu2S96FKxWcwhrLhxMh6zFgYw",
        authDomain: "chatbot-4e187.firebaseapp.com",
        databaseURL: "https://chatbot-4e187-default-rtdb.europe-west1.firebasedatabase.app",
        projectId: "chatbot-4e187",
        storageBucket: "chatbot-4e187.firebasestorage.app",
        messagingSenderId: "21444430882",
        appId: "1:21444430882:web:33a67782492b5642d6ce7b",
        measurementId: "G-3FKT8N0NPQ"
    };

    const app = initializeApp(firebaseConfig);
    const db = getDatabase(app);
    const sessionsRef = ref(db, "sessions");
    const sessionList = document.querySelector(".session-list");

    const currentAgentId = "{{ auth()->id() }}";

    onChildAdded(sessionsRef, (snapshot) => {
        const session = snapshot.val();

        if (session.status !== "closed" && session.agent_id == currentAgentId) {
            renderSessionCard(session);
        }

        checkIfEmpty();
    });

    onChildChanged(sessionsRef, (snapshot) => {
        const session = snapshot.val();
        let sessionCard = document.querySelector(`[data-uuid="${session.uuid}"]`);

        if (!session.agent_id || session.agent_id != currentAgentId) {
            if (sessionCard) sessionCard.remove();
        } else {
            if (session.status === "closed") {
                if (sessionCard) sessionCard.remove();
            } else {
                renderSessionCard(session);
            }
        }

        checkIfEmpty();
    });

    onChildRemoved(sessionsRef, (snapshot) => {
        const sessionUUID = snapshot.key;
        const card = document.querySelector(`.session-card[data-uuid="${sessionUUID}"]`);
        if (card) {
            card.remove();
        }
        checkIfEmpty();
    });


    function renderSessionCard(session) {
        let sessionCard = document.querySelector(`[data-uuid="${session.uuid}"]`);

        if (!sessionCard) {
            // شيل الرسالة الفاضية لو فيه
            const emptyMessage = sessionList.querySelector(".empty-message");
            if (emptyMessage) emptyMessage.remove();

            sessionCard = document.createElement("div");
            sessionCard.classList.add("session-card");
            sessionCard.setAttribute("data-uuid", session.uuid);
            sessionList.appendChild(sessionCard);
        }

        sessionCard.innerHTML = `
        <div class="session-info">
            <span class="status-indicator online"></span>
            <p>هناك جلسة في انتظارك</p>
        </div>
        <div class="session-actions">
            <span class="session-status ${session.status}">${session.status}</span>
            <form method="GET" action="{{ route('getIntoChat') }}">
                <input type="hidden" name="uuid" value="${session.uuid}">
                <button type="submit" class="join-btn">انضم للمحادثة</button>
            </form>
        </div>
    `;
    }


    function checkIfEmpty() {
        const sessionCards = sessionList.querySelectorAll(".session-card");
        const emptyMessage = sessionList.querySelector(".empty-message");

        if (sessionCards.length === 0) {
            if (!emptyMessage) {
                const msg = document.createElement("p");
                msg.classList.add("empty-message");
                msg.textContent = "There are no sessions for now.";
                sessionList.appendChild(msg);
            }
        } else {
            if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    }
</script>

</html>
