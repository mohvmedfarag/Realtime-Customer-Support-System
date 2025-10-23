<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Firebase App SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <!-- Firebase Database SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/session_sidebar.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    .sessions-wrapper {
        position: relative;
    }

    .btn-icon {
        width: 55px;
        height: 55px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s ease;
    }

    .btn-icon:hover {
        transform: scale(1.1);
    }

    .sessions-dropdown {
        position: absolute;
        width: 50%;
        max-height: 400px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        padding: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        overflow-y: auto;
        transform: scale(0.8);
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .sessions-dropdown.active {
        opacity: 1;
        transform: scale(1);
        pointer-events: auto;
    }

    .session-item {
        background: #f0f2f5;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
        text-align: center;
        flex: 0 0 auto;
        text-decoration: none;
        color: #333;
    }

    .session-item:hover {
        background: #e0e0e0;
    }

    form {
        display: flex;
        gap: 8px;
        padding: 10px;
        background: #fff;
        border-top: 1px solid #ddd;
        border-radius: 0 0 15px 15px;
        position: sticky;
        bottom: 0;
    }

    form input {
        flex: 1;
        border-radius: 6px;
        border: 1px solid #ccc;
        padding: 6px 10px;
    }

    form button {
        border-radius: 6px;
    }
</style>

<body>
    <div class="d-flex justify-content-between align-items-center" style="width: 80%; margin:auto; padding:10px;">
        <h3><a href="javascript:void(0)" onclick="document.getElementById('user-logout').submit()">Logout</a>
            <form action="{{ route('user.logout') }}" method="post" id="user-logout">@csrf</form>
        </h3>
        <div id="chatNotification" class="chat-notification">
            📩 تم استلام رسالة جديدة
        </div>
    </div>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-4">
            <h1>Chats</h1>
            <div>
                <a href="{{ route('detectImage') }}" class="btn btn-danger c-light mb-3">اكتشاف الاعطال</a>
                <a href="{{ route('allStarMessages') }}" class="btn btn-warning c-light mb-3">Star Messages</a>
                <a href="#" id="createSession" class="btn btn-primary mb-3">Create New Session</a>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- <table class="table" id="sessionsTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">status</th>
                    <th scope="col">Last Activity</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sessions as $session)
                    <tr id="row-{{ $session->id }}">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <th>{{ $session->id }}</th>
                        @if (!$session->name == null)
                            <td><a href="{{ route('chat', $session->uuid) }}">{{ $session->name }}</a></td>
                        @else
                            <td><a href="{{ route('chat', $session->uuid) }}">{{ $session->uuid }}</a></td>
                        @endif
                        <td>{{ $session->status }}</td>
                        <td>{{ $session->last_activity }}</td>
                        <td>
                            <a href="{{ route('session.edit', $session->id) }}" class="btn btn-info">Add Name</a>
                            <button onclick="deleteSession({{ $session->id }})" class="btn btn-danger">Delete</button>
                        </td>
                    </tr>

                @empty
                    There is no sessions
                @endforelse
            </tbody>
        </table> --}}

        <div class="sessions-wrapper">
            <div class="btn-icon" id="toggleSessions">
                <i class="fa-solid fa-headset fa-lg"></i>
            </div>

            <div id="sessionsDropdown" class="sessions-dropdown">
                @forelse ($sessions as $session)
                <a href="{{ route('chat', $session->uuid) }}" class="session-item">{{ $session->name }}</a>
                @empty
                There is no chats
                @endforelse
                <form method="POST" action="{{ route('testCreateSession') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Enter name of chat...">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </form>
            </div>
        </div>
    </div>

</body>
<script>
    const firebaseConfig = {
        databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
    };

    firebase.initializeApp(firebaseConfig);
    const database = firebase.database();

    const userSessions = @json($sessions);

    userSessions.forEach(session => {
        const messagesRef = database.ref(`chats/${session.uuid}/messages`);

        messagesRef.limitToLast(1).on('child_added', (snapshot) => {
            const message = snapshot.val();
            const messageId = snapshot.key; // message key (Unique)

            // get the last message in session from localStorage
            const lastSeenKey = `lastSeenMessage_${session.uuid}`;
            const lastSeenMessageId = localStorage.getItem(lastSeenKey);

            // if message is new and from agent
            if (message.sender === 'agent' && messageId !== lastSeenMessageId) {
                showNotification("📩 خدمة العملاء رد على رسالتك", session.uuid);

                // store id from this message to do not have any repetition
                localStorage.setItem(lastSeenKey, messageId);
            }
        });
    });

    function showNotification(text, uuid) {
        let notif = document.getElementById("chatNotification");
        notif.innerText = text;
        notif.style.display = "block";

        notif.onclick = () => {
            window.location.href = "{{ route('chat', ':uuid') }}".replace(':uuid', uuid);
        };
    }

    function deleteSession(id) {

        fetch("{{ url('/delete-session') }}/" + id, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    document.querySelector(`#row-${id}`).remove();
                    // alert(data.message);
                }
            })
            .catch(error => console.error(error));
    }

    document.getElementById("createSession").addEventListener("click", function(e) {
        e.preventDefault();

        fetch("{{ route('createSession') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    let session = data.session;

                    // append row in table
                    let row = `
                        <tr id="row-${session.id}">
                            <th scope="row">New</th>
                            <td>${session.id}</td>
                            <td><a href="chat/${session.uuid}">${session.uuid}</a></td>
                            <td>${session.status}</td>
                            <td>${session.last_activity}</td>
                            <td><button onclick="deleteSession(${session.id})" class="btn btn-danger">Delete</button></td>
                        </tr>`;
                    document.querySelector("#sessionsTable tbody").insertAdjacentHTML("beforeend", row);

                    // insert session into firebase
                    const sessionRef = database.ref("chats/" + session.uuid);
                    sessionRef.set({
                        uuid: session.uuid,
                        status: session.status,
                        last_activity: session.last_activity,
                        agent_id: session.agent_id,

                    });
                }
            })
            .catch(error => console.error(error));
    });
</script>
<script>
    const toggleBtn = document.getElementById("toggleSessions");
    const dropdown = document.getElementById("sessionsDropdown");

    toggleBtn.addEventListener("click", () => {
        dropdown.classList.toggle("active");
    });
</script>

</html>
