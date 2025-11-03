const topicsHistory = [];

// فتح الشات
$("#toggleSessions").on("click", function () {
    $("#chatPopup").fadeToggle();
});

// إغلاق الشات
$("#closeChat").on("click", function () {
    $("#chatPopup").fadeOut();
});

$(document).on("click", ".back-btn", function () {
    if (topicsHistory.length > 0) {
        const previousState = topicsHistory.pop();
        $("#topicsList").fadeOut(200, function () {
            $(this).html(previousState.html).fadeIn(200);
        });
    } else {
        // إذا لم يكن هناك تاريخ، ارجع للصفحة الرئيسية
        location.reload();
    }
});

// عند الضغط على topic أو session
$(document).on("click", ".topic-item", function () {
    const topicId = $(this).data("id");
    const topicName = $(this).data("name");
    const isFinal = $(this).data("final");

    // 1️⃣ لو الموضوع نهائي → أنشئ session جديدة
    if (isFinal == 1) {
        $.ajax({
            url: window.createSessionFromTopicUrl,
            method: "POST",
            data: {
                _token: window.csrfToken,
                topic_id: topicId,
                topic_name: topicName
            },
            success: function () {
                $("#topicsSection").fadeOut(300, function () {
                    // إظهار الشات بعد الإخفاء
                    $("#chatBody").removeClass("d-none").html(`
                                <div class="chat-message">تم إنشاء جلسة جديدة بعنوان: <b>${topicName}</b></div>
                            `);
                    $("#chatFooter").removeClass("d-none").hide().fadeIn(
                        300);
                });
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.error || "حدث خطأ أثناء إنشاء الجلسة");
            }
        });
        return;
    }

    if (isFinal == 0) {
        // حفظ الحالة الحالية للرجوع لها
        const currentTopics = $("#topicsList").html();
        topicsHistory.push({
            html: currentTopics,
            title: "المواضيع الرئيسية"
        });

        $.ajax({
            url: `/chat-topics/${topicId}/children`,
            method: "GET",
            success: function (response) {
                const children = response.children;

                if (children.length === 0) {
                    alert("لا توجد مواضيع فرعية لهذا الموضوع");
                    return;
                }

                let html = `
                    <div class="back-btn" style="cursor: pointer;">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                `;

                children.forEach(child => {
                    html += `
                        <div class="topic-item" data-id="${child.id}" data-name="${child.title}" data-final="${child.is_final}">
                            ${child.title}</div>
                        `;
                });

                $("#topicsList").fadeOut(200, function () {
                    $(this).html(html).fadeIn(200);
                });
            },
            error: function (xhr) {
                console.error('Error loading sub topics:', xhr);
                alert("حدث خطأ أثناء تحميل المواضيع الفرعية");
            }
        });
        return;
    }



    // 2️⃣ لو اختار جلسة موجودة → عرض الرسائل القديمة
    if ($(this).hasClass("session-item")) {
        const sessionId = $(this).data("id");
        const sessionName = $(this).data("name");

        $("#topicsSection").fadeOut(300, function () {
            $("#chatBody").removeClass("d-none").html(`
                        <div class="chat-message text-muted">جاري تحميل الرسائل...</div>
                    `);
            $("#chatFooter").removeClass("d-none").hide().fadeIn(300);
        });

        $.ajax({
            url: `/messages/${sessionId}/show`,
            method: "GET",
            success: function (messages) {
                if (messages.length === 0) {
                    $("#chatBody").html(`
                        <div class="chat-message text-muted">لا توجد رسائل في هذه الجلسة.</div>
                    `);
                    return;
                }

                let chatHTML = `
                    <div class="chat-message text-center text-muted mb-2">
                        المحادثة السابقة (${sessionName})
                    </div>`;

                messages.forEach(msg => {
                    const isLong = msg.is_long; // من الداتا بيز
                    let contentHTML = msg.content;

                    // لو الرسالة طويلة، نعرض أول 150 حرف فقط
                    if (isLong) {
                        const shortText = msg.content.substring(0, 150);
                        contentHTML = `
                            <span class="short-text">${shortText}...</span>
                            <span class="full-text d-none">${msg.content}</span>
                            <button class="show-more-btn btn btn-link p-0" style="font-size: 13px;">عرض المزيد</button>
                        `;
                    }

                    if (msg.sender === 'user') {
                        chatHTML += `
                            <div class="chat-message user">
                                ${contentHTML}
                            </div>
                        `;
                    } else {
                        chatHTML += `
                            <div class="chat-message">
                                ${contentHTML}
                            </div>
                        `;
                    }
                });


                $("#chatBody").html(chatHTML);
                $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);
            },
            error: function () {
                $("#chatBody").html(`
                    <div class="chat-message text-danger">حدث خطأ أثناء تحميل الرسائل.</div>
                `);
            }
        });
    }
});

$(document).on("click", ".show-more-btn", function () {
    const parent = $(this).closest(".chat-message");
    const shortText = parent.find(".short-text");
    const fullText = parent.find(".full-text");

    if (shortText.hasClass("d-none")) {
        shortText.removeClass("d-none");
        fullText.addClass("d-none");
        $(this).text("عرض المزيد");
    } else {
        shortText.addClass("d-none");
        fullText.removeClass("d-none");
        $(this).text("عرض أقل");
    }
});


// إنشاء جلسة جديدة يدويًا
$("#session-form").on("submit", function (e) {
    e.preventDefault();

    const sessionName = $(this).find('input[name="name"]').val().trim();
    if (!sessionName) {
        alert("يرجى إدخال اسم للجلسة");
        return;
    }

    $.ajax({
        url: window.createNewSession,
        method: "POST",
        data: {
            _token: window.csrfToken,
            name: sessionName
        },
        success: function (response) {
            // إخفاء المواضيع
            $("#topicsSection").fadeOut(300, function () {
                // عرض الشات بعد الإخفاء
                $("#chatBody").removeClass("d-none").html(`
                        <div class="chat-message">تم إنشاء جلسة جديدة بعنوان: <b>${response.session.name}</b></div>
                    `);
                $("#chatFooter").removeClass("d-none").hide().fadeIn(300);
            });
        },
        error: function (xhr) {
            if (xhr.status === 409) {
                // المستخدم لديه جلسة بنفس الاسم — نفتح القديمة
                const sessionId = xhr.responseJSON.session_id;
                $("#topicsSection").fadeOut(300, function () {
                    $("#chatBody").removeClass("d-none").html(`
                                    <div class="chat-message">تم فتح الجلسة القديمة: <b>${sessionName}</b></div>
                                    <div class="chat-message text-muted">${xhr.responseJSON.message}</div>
                                `);
                    $("#chatFooter").removeClass("d-none").hide().fadeIn(
                        300);
                });

                // تحميل الرسائل القديمة
                $.ajax({
                    url: `/messages/${sessionId}/show`,
                    method: "GET",
                    success: function (messages) {
                        let chatHTML = `<div class="chat-message text-center text-muted mb-2">
                                            المحادثة السابقة (${sessionName})
                                        </div>`;
                        messages.forEach(msg => {
                            if (msg.sender === 'user') {
                                chatHTML +=
                                    `<div class="chat-message user">${msg.content}</div>`;
                            } else {
                                chatHTML +=
                                    `<div class="chat-message">${msg.content}</div>`;
                            }
                        });
                        $("#chatBody").append(chatHTML);
                        $("#chatBody").scrollTop($("#chatBody")[0]
                            .scrollHeight);
                    }
                });
            } else {
                alert(xhr.responseJSON?.message || "حدث خطأ أثناء إنشاء الجلسة");
            }
        }
    });
});


// minimize and maximize chat popup
document.addEventListener("DOMContentLoaded", function () {
    const chatPopup = document.getElementById("chatPopup");
    const topicsSection = document.getElementById('topicsSection');
    const topicsList = document.getElementById('topicsList');
    const topicItems = document.querySelectorAll('.topic-item');
    const expandBtn = document.getElementById("expandChat");
    const minimizeBtn = document.getElementById("minimizeChat");
    const chatBody = document.getElementById('chatBody');

    expandBtn.addEventListener("click", function () {
        chatPopup.classList.add("expanded");
        expandBtn.classList.add("d-none");
        topicsSection.classList.add('expanded');
        chatBody.classList.add('expanded');
        topicsList.classList.add('expanded');

        topicItems.forEach( item => {
            item.classList.add('expanded');
        });
        minimizeBtn.classList.remove("d-none");
    });

    minimizeBtn.addEventListener("click", function () {
        chatPopup.classList.remove("expanded");
        topicsSection.classList.remove("expanded");
        topicsList.classList.remove("expanded");
        topicItems.forEach( item => {
            item.classList.remove('expanded');
        });
        chatBody.classList.remove('expanded');
        minimizeBtn.classList.add("d-none");
        expandBtn.classList.remove("d-none");
    });
});

// Sidebar toggle functionality
const toggleBtn = document.getElementById('toggleBtn');
const sidebar = document.getElementById('sidebar');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
});
