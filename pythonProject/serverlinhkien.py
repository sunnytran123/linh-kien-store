from flask import Flask, request, jsonify
from openai import OpenAI
import mysql.connector
import json
from flask_cors import CORS
import traceback

app = Flask(__name__)
CORS(app)

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


# Hàm phân loại yêu cầu người dùng
def classify_user_request(query):
    try:
        prompt = f"""Câu yêu cầu của người dùng là: '{query}'
               Bạn là bot hỗ trợ tư vấn mua sắm quần áo và linh kiện. Hãy phân tích yêu cầu của người dùng và xác định yêu cầu ví dụ: các danh mục sản phẩm, thông tin về shop), trả về request_type='consultation'.
               - Nếu người dùng muốn tìm kiếm sản phẩm (ví dụ: tìm váy nữ, giá dưới 500k, tìm kiếm quần áo màu đỏ), trả về request_type='product_search'.

               Trả về kết quả phân loại theo định dạng JSON của công cụ classify_user_request."""

        response = client.chat.completions.create(
            model="gpt-4o-mini-2024-07-18",
            messages=[{"role": "system", "content": "Bạn là một trợ lý phân loại yêu cầu người dùng."},
                      {"role": "user", "content": prompt}],
            tools=tools,
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


# Hàm xử lý câu hỏi tư vấn chung
def handle_consultation_query(query):
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

        response = client.chat.completions.create(
            model="gpt-4o-mini-2024-07-18",
            messages=[{"role": "system", "content": "Bạn là chuyên gia truy vấn dữ liệu MySQL."},
                      {"role": "user", "content": prompt}],
            temperature=0.5,
            max_tokens=2000
        )

        query_sql = response.choices[0].message.content.strip().replace('```sql', '').replace('```', '')
        return query_sql
    except Exception as e:
        print(f"Lỗi xử lý tư vấn: {e}")
        return "Xin lỗi, tôi gặp khó khăn khi xử lý yêu cầu."


# Hàm xử lý tìm kiếm sản phẩm
def handle_product_search_query(query):
    try:
        # Tạo câu truy vấn SQL theo câu hỏi của người dùng
        prompt = f"""Bạn là một chuyên viên tư vấn bán quần áo túi nam và nữ. Khách hàng hỏi: {query}
#Hãy tạo câu truy vấn MySQL tìm kiếm sản phẩm, luôn join với bảng hinhanhsanpham để lấy 1 ảnh đại diện (MIN(ha.duongdan) as duongdan) cho mỗi sản phẩm. Kết quả phải GROUP BY sp.sanphamid để mỗi sản phẩm chỉ xuất hiện 1 lần.
    SELECT sp.*, MIN(ha.duongdan) as duongdan
    FROM sanpham sp
    LEFT JOIN hinhanhsanpham ha ON sp.sanphamid = ha.masanpham
    WHERE ...
    GROUP BY sp.sanphamid
    LIMIT 5
# #Khi người dùng hỏi về chất liệu, tên hoặc mô tả sản phẩm, hãy dùng điều kiện:
# Ví dụ: # WHERE sp.chatlieu LIKE '%lụa%' AND (sp.tensanpham LIKE '%lụa%' OR sp.mota LIKE '%lụa%')
#Lưu ý: Luôn phải có trường duongdan trong kết quả.
#Chú ý: không cần trả lời câu hỏi của khách hàng mà chỉ cần tạo ra câu truy vấn mysql select tìm kiếm các thông tin theo yêu cầu của khách hàng.
#Lưu ý: 
- bỏ các ký tự đặt biệt
- Câu truy vấn cần giới hạn tối đa 5 kết quả.
- Đảm bảo không có ký tự đặc biệt trong câu truy vấn.
# Trả về câu truy vấn MySQL, không cần giải thích gì thêm.
           Cơ sở dữ liệu `shoplinhkien` bao gồm các bảng sau:

        - *sanpham*: Sản phẩm quần áo túi của nam và nữ, gồm `sanphamid` (ID, khóa chính), `tensanpham` (tên sản phẩm), `mota` (mô tả sản phẩm), `gia` (giá bán), `madanhmuc` (liên kết với danh mục), `makhuyenmai` (liên kết với khuyến mãi), `chatlieu` (chất liệu), `thuonghieu` (thương hiệu), `baohanh` (bảo hành).
        - *khuyenmai*: Khuyến mãi, gồm `khuyenmaiid` (ID, khóa chính), `tenkhuyenmai` (tên khuyến mãi), `giatri` (giá trị giảm giá), `ngaybatdau` (ngày bắt đầu), `ngayketthuc` (ngày kết thúc).
        - *danhmuc*: Danh mục sản phẩm, gồm `danhmucid` (ID, khóa chính), `tendanhmuc` (tên danh mục).
        - *mausac*: Màu sắc sản phẩm, gồm `mausacid` (ID, khóa chính), `tenmau` (tên màu), `mamau` (mã màu hex).
        - *size*: Kích cỡ sản phẩm, gồm `sizeid` (ID, khóa chính), `kichco` (kích cỡ sản phẩm).

"""

        response = client.chat.completions.create(
            model="gpt-4o-mini-2024-07-18",
            messages=[{"role": "system", "content": "Bạn là chuyên gia truy vấn dữ liệu MySQL."},
                      {"role": "user", "content": prompt}],
            temperature=0.5,
            max_tokens=2000
        )

        query_sql = response.choices[0].message.content.strip().replace('```sql', '').replace('```', '')
        return query_sql
    except Exception as e:
        print(f"Lỗi xử lý tìm kiếm sản phẩm: {e}")
        return "Xin lỗi, tôi gặp khó khăn khi xử lý yêu cầu tìm kiếm sản phẩm."


# Hàm thực thi câu truy vấn MySQL
def execute_query(query):
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="shoplinhkien"
        )
        cursor = conn.cursor(dictionary=True)
        cursor.execute(query)
        results = cursor.fetchall()
        cursor.close()
        conn.close()
        return results
    except mysql.connector.Error as e:
        print(f"Lỗi thực thi truy vấn MySQL: {e}")
        return []
    except Exception as e:
        print(f"Lỗi khác: {e}")
        return []


# Hàm tạo câu trả lời văn bản
def generate_answer(data, query):
    """Tạo câu trả lời văn bản dựa trên yêu cầu tìm kiếm sản phẩm"""
    try:
        prompt = f"""Người dùng yêu cầu là: '{query}'.
        Bạn dựa vào yêu cầu và dữ liệu tìm được để tạo ra câu trả lời cho người dùng theo dạng văn bản thông thường.
        Nếu thiếu thông tin thì trả về không có thông tin về yêu cầu của người dùng.
        đây là dữ liệu tìm được: '{data}' 
        """

        response = client.chat.completions.create(
            model="gpt-4o-mini-2024-07-18",
            messages=[
                {"role": "system", "content": "Bạn là một trợ lý tư vấn trả lời câu hỏi về shop bán quần áo và túi nam nữ."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.5,
            max_tokens=4000
        )

        result = response.choices[0].message.content.strip()
        return result
    except Exception as e:
        print(f"Lỗi tạo câu trả lời: {e}")
        return "Xin lỗi, hệ thống đang gặp sự cố. Vui lòng thử lại sau."


# Hàm tạo HTML cho sản phẩm
def generate_product_card(data, query):
    """Tạo mã HTML cho sản phẩm dựa trên yêu cầu tìm kiếm của người dùng"""
    try:
        prompt = f"""yêu cầu của người dùng là: '{query}'.
        bạn dựa vào yêu cầu và dữ liệu tôi cung cấp để tạo ra câu trả lời về các sản phẩm cần tìm kiếm.
        dữ liệu tìm kiếm được là: '{data}'
        # CẤU TRÚC CÂU TRẢ LỜI
        Phần 1. Nếu tìm thấy sản phẩm, hãy tạo 1 câu dẫn phản hồi các yêu cầu của người dùng hoặc nếu không có thông tin thì trả lời là không có sản phẩm phù hợp.
        Phần 2. Đoạn mã HTML để hiển thị sản phẩm: 
        - Mỗi sản phẩm là 1 <div class="product-card">.
        - Trong mỗi product-card chỉ có ảnh, tên sản phẩm, giá (hoặc giá khuyến mãi nếu có).
        - Khi nhấn vào toàn bộ thẻ product-card thì chuyển hướng đến ProductDetail.php?id=... (dùng thuộc tính onclick cho div).

        Ví dụ trả về:
        'Tìm thấy 2 sản phẩm phù hợp: <div class="product-list">
            <div class="product-card" onclick="window.location.href='ProductDetail.php?id=31'">
                <img src="picture/1744571319_278381rbr-0006.jpg" class="product-image">
                <div class="product-name">Mạch Thu Phát RF Lora SX1278 433Mhz Ra-02 - có ra chân</div>
                <div class="product-price">đ89.500 - đ93.599</div>
            </div>
            <div class="product-card" onclick="window.location.href='ProductDetail.php?id=37'">
                <img src="picture/1744573300_avr-3.jpg" class="product-image">
                <div class="product-name">Tên sản phẩm khác</div>
                <div class="product-price">đ120.000</div>
            </div>
        </div>'
        """
        response = client.chat.completions.create(
            model="gpt-4o-mini-2024-07-18",
            messages=[
                {"role": "system", "content": "Bạn là một trợ lý tạo truy vấn MySQL chính xác dựa trên yêu cầu tìm kiếm sản phẩm."},
                {"role": "user", "content": prompt}
            ],
            temperature=0.5,
            max_tokens=4000
        )

        result = response.choices[0].message.content.strip()
        return result
    except Exception as e:
        print(f"Lỗi tạo thẻ sản phẩm: {e}")
        return "Xin lỗi, hệ thống gặp sự cố khi hiển thị sản phẩm."



@app.route('/api/chat', methods=['POST'])
def chat():
    try:
        data = request.json
        message_text = data.get("message", "").strip()

        if not message_text:
            return jsonify({"status": "error", "message": "Vui lòng nhập tin nhắn"}), 400

        # Phân loại yêu cầu người dùng
        classification = classify_user_request(message_text)
        print(f"Phân loại yêu cầu: {classification}")

        if classification["request_type"] == "consultation":
            # Xử lý câu hỏi tư vấn
            query = handle_consultation_query(message_text)
            print(f"Câu truy vấn cho tư vấn: {query}")
            data = execute_query(query)
            print(f"Dữ liệu tìm được: {data}")
            response = generate_answer(data, message_text)
            print(f"Trả về câu trả lời: {response}")
            return jsonify({"status": "success", "response": response}), 200

        elif classification["request_type"] == "product_search":
            # Xử lý tìm kiếm sản phẩm
            query = handle_product_search_query(message_text)
            print(f"Câu truy vấn cho tìm kiếm sản phẩm: {query}")
            data = execute_query(query)
            print(f"Dữ liệu tìm được: {data}")
            # Sửa đường dẫn ảnh cho đúng thư mục picture/ và gán ảnh mặc định nếu thiếu
            for item in data:
                if 'duongdan' not in item or not item['duongdan']:
                    item['duongdan'] = 'picture/no-image.png'
                elif isinstance(item['duongdan'], str) and not item['duongdan'].startswith('picture/'):
                    item['duongdan'] = f"picture/{item['duongdan']}"
            response = generate_product_card(data, message_text)
            print(f"Trả về thẻ sản phẩm: {response}")
            return jsonify({"status": "success", "response": response}), 200

        else:  # need_more_info
            response_text = classification["additional_info_needed"]
            return jsonify({"status": "success", "response": response_text}), 200

    except Exception as e:
        import traceback
        print(f"Lỗi xử lý: {e}")
        traceback.print_exc()
        return jsonify({"status": "error", "message": f"Đã xảy ra lỗi: {e}"}), 500


if __name__ == "__main__":
    app.run(debug=True)
