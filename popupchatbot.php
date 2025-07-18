<?php
// Có thể thêm xử lý PHP nếu cần ở đây
?>
<style>
#chatBubbleBtn {
    position: fixed;
    bottom: 40px;
    right: 40px;
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
#chatBubbleBox {
    position: fixed;
    bottom: 100px;
    right: 32px;
    z-index: 10000;
    display: none;
    max-width: 350px;
    width: 90vw;
    box-shadow: 0 4px 24px rgba(140,195,74,0.13);
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
}
#chatBubbleBox .header {
    background: #8BC34A;
    color: #fff;
    padding: 16px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}
#chatBubbleBox .header .close-btn {
    margin-left: auto;
    cursor: pointer;
    font-size: 1.3rem;
}
#chatBubbleMessages {
    background: #f9f9f9;
    padding: 16px;
    height: 340px;
    overflow-y: auto;
    font-size: 1rem;
}
#chatBubbleForm {
    display: flex;
    border-top: 1px solid #eee;
    background: #fff;
}
#chatBubbleInput {
    flex: 1;
    border: none;
    padding: 10px;
    font-size: 1rem;
    outline: none;
    background: transparent;
}
#chatBubbleForm button {
    background: #8BC34A;
    color: #fff;
    border: none;
    padding: 0 2px;
    font-size: 1.1rem;
    cursor: pointer;
    width: 50px;
}
.bubble-message {
    margin-bottom: 12px;
    display: flex;
}
.bubble-message.user {
    justify-content: flex-end;
}
.bubble-message.bot {
    justify-content: flex-start;
}
.bubble-message .message-content {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 1rem;
    line-height: 1.5;
}
.bubble-message.user .message-content {
    background: #8BC34A;
    color: #fff;
    border-bottom-right-radius: 4px;
}
.bubble-message.bot .message-content {
    background: #e8f5e9;
    color: #333;
    border-bottom-left-radius: 4px;
}
@media (max-width: 600px) {
    #chatBubbleBox {
        right: 2vw;
        bottom: 2vw;
        max-width: 98vw;
    }
}
</style>
<div id="chatBubbleBtn">
    <i class="fas fa-robot"></i>
</div>
<div id="chatBubbleBox">
    <div class="header">
        <i class="fas fa-robot"></i> Chatbot
        <span class="close-btn" id="closeChatBubble">&times;</span>
    </div>
    <div id="chatBubbleMessages"></div>
    <form id="chatBubbleForm" autocomplete="off">
        <input type="text" id="chatBubbleInput" placeholder="Nhập câu hỏi..." required>
        <button type="submit"><i class="fas fa-paper-plane"></i></button>
    </form>
</div>
<script>
function openChatBubble() {
    document.getElementById('chatBubbleBox').style.display = 'block';
    // Nếu chưa có tin nhắn bot nào, hiển thị lời chào
    if (!document.querySelector('#chatBubbleMessages .bot')) {
        appendBubbleMsg('Chào bạn, tôi có thể giúp gì cho bạn?', 'bot');
    }
}
document.getElementById('chatBubbleBtn').onclick = openChatBubble;
document.getElementById('closeChatBubble').onclick = function(){document.getElementById('chatBubbleBox').style.display = 'none';};
window.addEventListener('mousedown', function(e){
    var box = document.getElementById('chatBubbleBox');
    if(box.style.display==='block' && !box.contains(e.target) && e.target.id!=='chatBubbleBtn'){
        box.style.display='none';
    }
});
const chatBubbleForm = document.getElementById('chatBubbleForm');
const chatBubbleInput = document.getElementById('chatBubbleInput');
const chatBubbleMessages = document.getElementById('chatBubbleMessages');
function appendBubbleMsg(content, sender) {
    const msgDiv = document.createElement('div');
    msgDiv.className = 'bubble-message ' + sender;
    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    contentDiv.textContent = content;
    msgDiv.appendChild(contentDiv);
    chatBubbleMessages.appendChild(msgDiv);
    chatBubbleMessages.scrollTop = chatBubbleMessages.scrollHeight;
}
window.addEventListener('DOMContentLoaded', function() {
    // Bỏ các tin nhắn mẫu set cứng, không append gì khi load
});
chatBubbleForm.onsubmit = async function(e){
    e.preventDefault();
    const userMsg = chatBubbleInput.value.trim();
    if(!userMsg) return;
    appendBubbleMsg(userMsg, 'user');
    chatBubbleInput.value = '';
    appendBubbleMsg('Đang trả lời...', 'bot');
    try{
        // Gửi request đến API Flask
        const response = await fetch('http://127.0.0.1:5000/api/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: userMsg })
        });
        const data = await response.json();
        // Xóa "Đang trả lời..."
        chatBubbleMessages.lastChild.remove();
        // Nếu trả về HTML (thẻ sản phẩm), chèn innerHTML, nếu không thì textContent
        if(data && data.response) {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'bubble-message bot';
            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';
            // Nếu có thẻ div hoặc html, dùng innerHTML, ngược lại dùng textContent
            if(/<\/?[a-z][\s\S]*>/i.test(data.response)) {
                contentDiv.innerHTML = data.response;
            } else {
                contentDiv.textContent = data.response;
            }
            msgDiv.appendChild(contentDiv);
            chatBubbleMessages.appendChild(msgDiv);
            chatBubbleMessages.scrollTop = chatBubbleMessages.scrollHeight;
        } else {
            appendBubbleMsg('Xin lỗi, không nhận được phản hồi từ server.', 'bot');
        }
    }catch(err){
        chatBubbleMessages.lastChild.remove();
        appendBubbleMsg('Có lỗi xảy ra: ' + err, 'bot');
    }
};
</script> 