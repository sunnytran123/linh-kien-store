import os
import requests
from flask import Flask, request, jsonify

app = Flask(__name__)

VERIFY_TOKEN = os.getenv("VERIFY_TOKEN", "sunnytran")
PAGE_ACCESS_TOKEN = os.getenv("PAGE_ACCESS_TOKEN", "EAAO2AQXz2Q4BPLlAZBNbLkrVf9aAwd7YnjdEK7JkD315jmuZBDulS91psrHbeJL1bqyqXfeUZCzGZB5sDW9CtmKny3dBIQYp7eeQufcwHNbS3uMn2gWtuAIfLm25UZAC1GsUNfCGYniYyQa6dGkamFetVxhvb12amJfYvM7QgDhszqRoOWTuxjZAWJF0ucIEZBdgUNQa6NccQZDZD")  # EAAG...

# ─── PHẦN HELPER ────────────────────────────────────────────────

def call_send_api(payload):
    """Gửi POST đến Graph API"""
    url = 'https://graph.facebook.com/v18.0/me/messages'
    resp = requests.post(url, params={'access_token': PAGE_ACCESS_TOKEN}, json=payload)
    print("[Send API] status:", resp.status_code, resp.text)
    return resp.json()

def send_text(user_id, text):
    return call_send_api({"recipient": {"id": user_id}, "message": {"text": text}})

def send_image(user_id, image_url):
    attachment = {"type": "image", "payload": {"url": image_url, "is_reusable": True}}
    return call_send_api({"recipient": {"id": user_id}, "message": {"attachment": attachment}})

def send_quick_replies(user_id, text, replies):
    qr = [{"content_type": "text", "title": t, "payload": p} for t, p in replies]
    return call_send_api({"recipient": {"id": user_id}, "message": {"text": text, "quick_replies": qr}})

def send_buttons(user_id, text, buttons):
    btns = [{"type": t, **b} for t, b in buttons]
    payload = {"attachment": {"type": "template", "payload": {"template_type": "button", "text": text, "buttons": btns}}}
    return call_send_api({"recipient": {"id": user_id}, "message": payload})

# ─── XỬ LÝ WEBHOOK ─────────────────────────────────────────────

@app.route('/webhook', methods=['GET'])
def verify():
    token = request.args.get("hub.verify_token")
    if token == VERIFY_TOKEN:
        return request.args.get("hub.challenge")
    return "Unauthorized", 403

@app.route('/webhook', methods=['POST'])
def webhook():
    data = request.get_json()
    print("[Webhook received]:", data)

    for entry in data.get("entry", []):
        for ev in entry.get("messaging", []):
            user_id = ev["sender"]["id"]

            # Tin nhắn văn bản
            if ev.get("message") and "text" in ev["message"]:
                text = ev["message"]["text"].lower()

                if text == "hi":
                    send_text(user_id, "Chào bạn!")
                elif text == "image":
                    send_image(user_id, "https://example.com/myimage.jpg")
                elif text == "options":
                    send_quick_replies(user_id, "Chọn một:", [("Red", "PAYLOAD_RED"), ("Green", "PAYLOAD_GREEN")])
                elif text == "menu":
                    send_buttons(user_id, "Bạn muốn làm gì?",
                        [("web_url", {"title": "Visit Site", "url": "https://example.com"}),
                         ("postback", {"title": "Help", "payload": "HELP_PAYLOAD"})])
                else:
                    send_text(user_id, f"Bạn vừa nói: {text}")

            # Sự kiện nhấn nút quick reply hoặc postback
            elif ev.get("postback"):
                payload = ev["postback"]["payload"]
                send_text(user_id, f"Bạn đã chọn: {payload}")

    return "OK", 200
send_text(31251489424449821, "chào")
print("helloooo")
send_image(31251489424449821,"https://thucanhviet.com/wp-content/uploads/2018/03/Pom-2-thang-mat-cuc-xinh.jpg")
if __name__ == "__main__":
    app.run(port=5000, debug=True)
