$(document).ready(function () {

    // عند فتح جلسة جديدة أو قديمة، خزّن الـ ID بتاعها
    $(document).on("click", ".session-item, .topic-item[data-final='1']", function () {
        window.currentSessionId = $(this).data("id");
        $("#chatBody").removeClass("d-none");
        $("#chatFooter").removeClass("d-none");
        $("#chatBody").html(''); // ممكن هنا لاحقًا تجيب الرسائل القديمة
    });

    // إرسال رسالة
    $("#send-message").on("submit", function (e) {
        e.preventDefault();

        const message = $("#chatInput").val().trim();
        if (!message) return;

        if (!window.currentSessionId) {
            alert("يرجى اختيار جلسة أولاً قبل إرسال الرسالة");
            return;
        }
        console.log("Session ID before send:", window.currentSessionId);
        $.ajax({
            url: "/chat/session/send",
            method: "POST",
            data: {
                _token: window.csrfToken,
                session_id: window.currentSessionId,
                content: message
            },
            success: function () {
                $("#chatInput").val('');
                $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);
            },
            error: function (xhr) {
                let error = xhr.responseJSON?.message || "حدث خطأ أثناء إرسال الرسالة";
                alert(error);
            }
        });
    });
});

