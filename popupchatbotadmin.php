<?php
// Có thể thêm xử lý PHP nếu cần ở đây
?>
<style>
#chatAdminBubbleBtn {
    position: fixed;
    bottom: 40px;
    right: 110px;
    z-index: 99999;
    cursor: pointer;
    background: #8BC34A;
    color: #fff;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 16px rgba(140,195,74,0.22);
    font-size: 2rem;
    transition: box-shadow 0.2s;
}
#chatAdminBubbleBox {
    position: fixed;
    bottom: 100px;
    right: 102px;
    z-index: 10000;
    display: none;
    max-width: 350px;
    width: 90vw;
    box-shadow: 0 4px 24px rgba(140,195,74,0.13);
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
}
#chatAdminBubbleBox .header {
    background: #8BC34A;
    color: #fff;
    padding: 16px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}
#chatAdminBubbleBox .header .close-btn {
    margin-left: auto;
    cursor: pointer;
    font-size: 1.3rem;
}
#chatAdminBubbleMessages {
    background: #f9f9f9;
    padding: 16px;
    height: 340px;
    overflow-y: auto;
    font-size: 1rem;
}
#chatAdminBubbleForm {
    display: flex;
    border-top: 1px solid #eee;
    background: #fff;
}
#chatAdminBubbleInput {
    flex: 1;
    border: none;
    padding: 10px;
    font-size: 1rem;
    outline: none;
    background: transparent;
}
#chatAdminBubbleForm button {
    background: #8BC34A;
    color: #fff;
    border: none;
    padding: 0 2px;
    font-size: 1.1rem;
    cursor: pointer;
    width: 50px;
}
.bubble-message-admin {
    margin-bottom: 12px;
    display: flex;
}
.bubble-message-admin.user {
    justify-content: flex-end;
}
.bubble-message-admin.bot {
    justify-content: flex-start;
}
.bubble-message-admin .message-content {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 1rem;
    line-height: 1.5;
}
.bubble-message-admin.user .message-content {
    background: #8BC34A;
    color: #fff;
    border-bottom-right-radius: 4px;
}
.bubble-message-admin.bot .message-content {
    background: #e8f5e9;
    color: #333;
    border-bottom-left-radius: 4px;
}
@media (max-width: 600px) {
    #chatAdminBubbleBox {
        right: 2vw;
        bottom: 2vw;
        max-width: 98vw;
    }
}
</style>
<div id="chatAdminBubbleBtn">
    <i class="fas fa-robot"></i>
</div>
<div id="chatAdminBubbleBox">
    <div class="header">
        <i class="fas fa-robot"></i> Chatbot Admin
        <span class="close-btn" id="closeChatAdminBubble">&times;</span>
    </div>
    <div id="chatAdminBubbleMessages"></div>
    <form id="chatAdminBubbleForm" autocomplete="off">
        <input type="text" id="chatAdminBubbleInput" placeholder="Nhập câu hỏi quản trị..." required>
        <button type="submit"><i class="fas fa-paper-plane"></i></button>
    </form>
</div>
<script>
function openChatAdminBubble() {
    document.getElementById('chatAdminBubbleBox').style.display = 'block';
    // Nếu chưa có tin nhắn bot nào, hiển thị lời chào
    if (!document.querySelector('#chatAdminBubbleMessages .bot')) {
        appendAdminBubbleMsg('Chào admin, bạn cần hỗ trợ gì?', 'bot');
    }
}
document.getElementById('chatAdminBubbleBtn').onclick = openChatAdminBubble;
document.getElementById('closeChatAdminBubble').onclick = function(){document.getElementById('chatAdminBubbleBox').style.display = 'none';};
window.addEventListener('mousedown', function(e){
    var box = document.getElementById('chatAdminBubbleBox');
    if(box.style.display==='block' && !box.contains(e.target) && e.target.id!=='chatAdminBubbleBtn'){
        box.style.display='none';
    }
});
const chatAdminBubbleForm = document.getElementById('chatAdminBubbleForm');
const chatAdminBubbleInput = document.getElementById('chatAdminBubbleInput');
const chatAdminBubbleMessages = document.getElementById('chatAdminBubbleMessages');
function appendAdminBubbleMsg(content, sender) {
    const msgDiv = document.createElement('div');
    msgDiv.className = 'bubble-message-admin ' + sender;
    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    contentDiv.textContent = content;
    msgDiv.appendChild(contentDiv);
    chatAdminBubbleMessages.appendChild(msgDiv);
    chatAdminBubbleMessages.scrollTop = chatAdminBubbleMessages.scrollHeight;
}
window.addEventListener('DOMContentLoaded', function() {
    // Không append gì khi load
});
chatAdminBubbleForm.onsubmit = async function(e){
    e.preventDefault();
    const userMsg = chatAdminBubbleInput.value.trim();
    if(!userMsg) return;
    appendAdminBubbleMsg(userMsg, 'user');
    chatAdminBubbleInput.value = '';
    appendAdminBubbleMsg('Đang trả lời...', 'bot');
    try{
        // Gửi request đến API Flask cho admin
        const response = await fetch('http://127.0.0.1:5000/api/adminchat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: userMsg })
        });
        const data = await response.json();
        // Xóa "Đang trả lời..."
        chatAdminBubbleMessages.lastChild.remove();
        // Nếu trả về HTML (thẻ sản phẩm), chèn innerHTML, nếu không thì textContent
        if(data && (data.bao_cao || data.response)) {
            const msg = data.bao_cao || data.response;
            const msgDiv = document.createElement('div');
            msgDiv.className = 'bubble-message-admin bot';
            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';
            if(/<\/?[a-z][\s\S]*>/i.test(msg)) {
                contentDiv.innerHTML = msg;
            } else {
                contentDiv.textContent = msg;
            }
            msgDiv.appendChild(contentDiv);
            chatAdminBubbleMessages.appendChild(msgDiv);
            chatAdminBubbleMessages.scrollTop = chatAdminBubbleMessages.scrollHeight;
        } else {
            appendAdminBubbleMsg('Xin lỗi, không nhận được phản hồi từ server.', 'bot');
        }
    }catch(err){
        chatAdminBubbleMessages.lastChild.remove();
        appendAdminBubbleMsg('Có lỗi xảy ra: ' + err, 'bot');
    }
};
</script> 