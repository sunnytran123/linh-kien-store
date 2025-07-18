from flask import Flask, request, jsonify
from openai import OpenAI
import mysql.connector
import json

app = Flask(__name__)

# Khởi tạo client OpenAI
client = OpenAI(api_key="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")

# Định nghĩa các công cụ (tools) cho function calling
tools = [
    {
        "type": "function",
        "function": {
            "name": "classify_user_request",
            "description": "Phân loại yêu cầu của người dùng là tư vấn chung, tìm kiếm sản phẩm, hoặc yêu cầu thêm thông tin nếu không rõ ràng.",
            "parameters": {
                "type": "object",
                "properties": {
                    "request_type": {
                        "type": "string",
                        "enum": ["consultation", "product_search"],
                        "description": "Loại yêu cầu: tư vấn chung, tìm kiếm sản phẩm, hoặc cần thêm thông tin."
                    },
                    "message": {
                        "type": "string",
                        "description": "Tin nhắn gốc của người dùng."
                    },
                    "additional_info_needed": {
                        "type": "string",
                        "description": "Thông tin bổ sung cần yêu cầu từ người dùng nếu request_type là need_more_info."
                    }
                },
                "required": ["request_type", "message"],
                "additionalProperties": False
            }
        }
    }
]


def classify_user_request(query):
    """Sử dụng function calling để phân loại yêu cầu người dùng"""
    try:
        prompt = f"""Câu yêu cầu của người dùng là: '{query}'
        Bạn là bot hỗ trợ tư vấn mua sắm quần áo và linh kiện. Hãy phân tích yêu cầu của người dùng và xác định yêu c dụ: các danh mục sản phẩm, thông tin về shop), trả về request_type='consultation'.
        - Nếu người dùng muốn tìm kiếm sản phẩm (ví dụ: tìm váy nữ, giá dưới 500k, tìm kiếm quần áo màu đỏ), trả về request_type='product_search'.

        Trả về kết quả phân loại theo định dạng JSON của công cụ classify_user_request."""

        response = client.chat.completions.create(
            model="gpt-4o-mini-2024-07-18",
            messages=[
                {"role": "system",
                 "content": "Bạn là một trợ lý phân loại yêu cầu người dùng chính xác và chuyên nghiệp."},
                {"role": "user", "content": prompt}
            ],
            tools=tools,
            tool_choice={"type": "function", "function": {"name": "classify_user_request"}},
            temperature=0.5
        )

        tool_call = response.choices[0].message.tool_calls[0]
        arguments = json.loads(tool_call.function.arguments)
        return arguments
    except Exception as e:
        print(f"Lỗi phân loại yêu cầu: {e}")
        return {
            "request_type": "need_more_info",
            "message": query,
            "additional_info_needed": "Xin lỗi, tôi chưa hiểu ý bạn! Bạn cần tìm kiếm thông tin về sản phẩm nào?"
        }

import openai

def handle_consultation_query(query):
    """Xử lý câu hỏi tư vấn chung và tạo câu truy vấn MySQL cho sản phẩm, khuyến mãi và danh mục"""
    try:
        prompt = f"""Bạn là một chuyên viên tư vấn bán quần áo túi nam và nữ. Khách hàng hỏi: {query}

        Bạn dựa vào câu hỏi của khách hàng để tạo ra câu truy vấn MySQL tìm kiếm các thông tin theo yêu cầu của khách hàng. 
        #Chú ý: không cần trả lời câu hỏi của khách hàng mà chỉ cần tạo ra câu truy vấn mysql select tìm kiếm các thông tin theo yêu cầu của khách hàng.
        #Lưu ý: 
        - Câu truy vấn cần giới hạn tối đa 5 kết quả.
        - Đảm bảo không có ký tự đặc biệt trong câu truy vấn.
        # Trả về câu truy vấn MySQL, không cần giải thích gì thêm.
        Cơ sở dữ liệu `shoplinhkien` bao gồm các bảng sau:

    - *sanpham*: Sản phẩm quần áo túi của nam và nữ, gồm `sanphamid` (ID, khóa chính), `tensanpham` (tên sản phẩm), `mota` (mô tả sản phẩm), `gia` (giá bán), `madanhmuc` (liên kết với danh mục), `makhuyenmai` (liên kết với khuyến mãi), `chatlieu` (chất liệu), `thuonghieu` (thương hiệu), `baohanh` (bảo hành).
    - *khuyenmai*: Khuyến mãi, gồm `khuyenmaiid` (ID, khóa chính), `tenkhuyenmai` (tên khuyến mãi), `giatri` (giá trị giảm giá), `ngaybatdau` (ngày bắt đầu), `ngayketthuc` (ngày kết thúc).
    - *danhmuc*: Danh mục sản phẩm, gồm `danhmucid` (ID, khóa chính), `tendanhmuc` (tên danh mục).

        """
        # Tạo yêu cầu đến OpenAI để tạo câu truy vấn MySQL
        response = client.chat.completions.create(
            model="gpt-4o-mini-2024-07-18",
            messages=[{"role": "system", "content": "Bạn là chuyên gia truy vấn dữ liệu MySQL."},
                      {"role": "user", "content": prompt}],
            temperature=0.5,
            max_tokens=2000
        )
        # Lấy câu truy vấn MySQL từ phản hồi
        return response.choices[0].message.content.strip().replace('```sql', '').replace('```', '')  # Xóa các ký tự không cần thiết
    except Exception as e:
        print(f"Lỗi xử lý tư vấn: {e}")
        return "Xin lỗi, tôi gặp khó khăn khi xử lý yêu cầu. Tôi có thể giúp gì về quần áo nam nữ?"

def handle_product_search_query():
    """Hàm xử lý tìm kiếm sản phẩm"""
    print("Đã gọi vào hàm tư vấn sản phẩm. Tư vấn về sản phẩm.")
    return "Tư vấn"
@app.route('/api/chat', methods=['POST'])
def chat():
    try:
        # Lấy dữ liệu từ client
        data = request.json
        message_text = data.get("message", "").strip()

        if not message_text:
            return jsonify({"status": "error", "message": "Vui lòng nhập tin nhắn"}), 400

        # Phân loại yêu cầu
        classification = classify_user_request(message_text)
        print(f"Phân loại yêu cầu: {classification}")

        # Xử lý theo phân loại yêu cầu
        if classification["request_type"] == "consultation":
            # Gọi hàm tư vấn chung
            response = handle_consultation_query(message_text)
            print(f"Trả về câu hỏi từ hàm tư vấn: {response}")
        elif classification["request_type"] == "product_search":
            # Gọi hàm tư vấn sản phẩm
            response = handle_product_search_query()
        else:  # need_more_info
            response = classification["additional_info_needed"]

        # Trả về kết quả
        return jsonify({"status": "success", "response": response}), 200

    except Exception as e:
        print(f"Lỗi xử lý: {e}")
        return jsonify({"status": "error", "message": "Đã xảy ra lỗi. Vui lòng thử lại."}), 500



if __name__ == "__main__":
    app.run(debug=True)
