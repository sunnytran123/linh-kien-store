# app.py
import os
import faiss
import numpy as np
import mysql.connector
from flask import Flask, request, jsonify
from flask_cors import CORS
from docx import Document
import pytesseract
from pdf2image import convert_from_path
from openai import OpenAI
from datetime import datetime

# ========== CONFIG ==========
client = OpenAI(api_key="...")
app = Flask(__name__)
CORS(app)
pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"
poppler_path = r"C:\poppler\bin"

chunk_size, chunk_overlap = 300, 50
index_path = r"C:\xampp\htdocs\shoplinhkien\pythonProject\faiss_index\index_all_in_one.index"

# ========== FAISS ==========
embedding_dim = 1536
index = faiss.read_index(index_path) if os.path.exists(index_path) else faiss.IndexIDMap(faiss.IndexFlatL2(embedding_dim))

# ========== MYSQL ==========
conn = mysql.connector.connect(host="localhost", user="root", password="", database="shoplinhkien")
cursor = conn.cursor()

cursor.execute("""
CREATE TABLE IF NOT EXISTS documents (
    id INT PRIMARY KEY,
    content TEXT,
    vector BLOB
)
""")
conn.commit()

# ========== FUNCTIONS ==========
def get_embedding(text):
    response = client.embeddings.create(input=text, model="text-embedding-3-small")
    return np.array(response.data[0].embedding, dtype=np.float32)

def save_chunk_to_db(idx, chunk, vector):
    vector_blob = vector.tobytes()
    cursor.execute("INSERT INTO documents (id, content, vector) VALUES (%s, %s, %s)", (idx, chunk, vector_blob))

def ocr_pdf(path):
    images = convert_from_path(path, poppler_path=poppler_path)
    return "\n".join(pytesseract.image_to_string(img, lang="vie") for img in images)

# ========== API ==========
@app.route("/add_file", methods=["POST"])
def add_file():
    data = request.get_json()
    filepath = data.get("path")

    if not filepath or not os.path.exists(filepath):
        return jsonify({"message": "File không tồn tại."}), 400

    if filepath.endswith(".pdf"):
        text = ocr_pdf(filepath)
    elif filepath.endswith(".docx"):
        text = "\n".join(p.text for p in Document(filepath).paragraphs)
    else:
        return jsonify({"message": "Chỉ hỗ trợ PDF hoặc DOCX."}), 400

    cursor.execute("SELECT MAX(id) FROM documents")
    id_count = (cursor.fetchone()[0] or 0) + 1

    for i in range(0, len(text), chunk_size - chunk_overlap):
        chunk = text[i:i + chunk_size].strip()
        if chunk:
            vector = get_embedding(chunk)
            index.add_with_ids(np.array([vector]), np.array([id_count]))
            save_chunk_to_db(id_count, chunk, vector)
            id_count += 1

    faiss.write_index(index, index_path)
    conn.commit()
    return jsonify({"message": "Tải dữ liệu thành công."})

@app.route("/ask", methods=["POST"])
def ask():
    data = request.get_json()
    question = data.get("question")
    if not question:
        return jsonify({"answer": "Thiếu câu hỏi."}), 400

    question_vector = np.array([get_embedding(question)], dtype=np.float32)
    _, ids = index.search(question_vector, 5)

    context = ""
    for idx in ids[0]:
        if idx != -1:
            cursor.execute("SELECT content FROM documents WHERE id=%s", (int(idx),))
            row = cursor.fetchone()
            if row:
                context += row[0] + "\n"

    if not context:
        return jsonify({"answer": "Không tìm thấy thông tin phù hợp."})

    prompt = f"Dựa trên thông tin sau, hãy trả lời câu hỏi:\n{context}\nCâu hỏi: {question}"
    res = client.chat.completions.create(
        model="gpt-4o-mini-2024-07-18",
        messages=[{"role": "user", "content": prompt}]
    )
    answer = res.choices[0].message.content.strip()
    return jsonify({"answer": answer})

if __name__ == "__main__":
    app.run(debug=True)
