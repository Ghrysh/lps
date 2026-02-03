from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import numpy as np
import mediapipe as mp
import os
import base64

app = Flask(__name__)
CORS(app)

# Path sesuai kode anda
ASSETS_PATH = "/shared/assets/baju"

# Konfigurasi MediaPipe
mp_pose = mp.solutions.pose
pose = mp_pose.Pose(
    static_image_mode=True,
    model_complexity=1, # 1 cukup akurat dan ringan untuk AMD Ryzen
    min_detection_confidence=0.5,
    enable_segmentation=False
)

def load_clothing_image(baju_theme, gender_mode):
    """
    Load gambar baju berdasarkan tema dan gender.
    Mencoba mencari file spesifik, jika tidak ada cari file umum.
    """
    # Logic penentuan nama file
    target_gender = "pria"
    if "wanita" in gender_mode:
        target_gender = "wanita"
    
    filename = f"{baju_theme}_{target_gender}.png"
    path = os.path.join(ASSETS_PATH, filename)

    # Cek file spesifik (misal: jawa_pria.png)
    if not os.path.exists(path):
        # Fallback ke umum (misal: jawa.png)
        path = os.path.join(ASSETS_PATH, f"{baju_theme}.png")
        if not os.path.exists(path):
            return None
            
    return cv2.imread(path, cv2.IMREAD_UNCHANGED)

def warp_clothing_to_body(frame, clothing_img, landmarks):
    """
    Inti dari Konsep 2.5D Warping:
    Menarik 4 sudut gambar baju agar pas dengan 4 titik badan user.
    """
    h_frame, w_frame, _ = frame.shape
    
    # Ambil titik Landmark Tubuh (11-12 Bahu, 23-24 Pinggul)
    lm_11 = landmarks[11] # Bahu Kiri
    lm_12 = landmarks[12] # Bahu Kanan
    lm_23 = landmarks[23] # Pinggul Kiri
    lm_24 = landmarks[24] # Pinggul Kanan

    # Cek visibilitas (agar tidak error jika badan terpotong kamera)
    if lm_11.visibility < 0.5 or lm_12.visibility < 0.5:
        return None

    # Konversi ke Pixel
    pt_shoulder_L = np.array([lm_11.x * w_frame, lm_11.y * h_frame])
    pt_shoulder_R = np.array([lm_12.x * w_frame, lm_12.y * h_frame])
    pt_hip_L = np.array([lm_23.x * w_frame, lm_23.y * h_frame])
    pt_hip_R = np.array([lm_24.x * w_frame, lm_24.y * h_frame])

    # Hitung lebar bahu untuk referensi padding
    shoulder_width = np.linalg.norm(pt_shoulder_L - pt_shoulder_R)

    # -- PENGATURAN PADDING (Agar baju tidak terlalu ketat) --
    # Silakan ubah angka ini jika baju terlihat terlalu kecil/besar
    pad_x = shoulder_width * 0.25      # Melebarkan ke samping (25%)
    pad_y_top = shoulder_width * 0.45  # Naikkan area leher (45%)
    pad_y_bot = shoulder_width * 0.1   # Panjangkan ke bawah sedikit

    # Tentukan 4 Titik Tujuan di Frame (Destination Points)
    # Urutan array harus konsisten: [KiriAtas, KananAtas, KiriBawah, KananBawah]
    # Note: Kanan user adalah Kiri di layar (Mirroring)
    dst_pts = np.float32([
        pt_shoulder_R + [-pad_x, -pad_y_top], # Kiri Atas
        pt_shoulder_L + [pad_x, -pad_y_top],  # Kanan Atas
        pt_hip_R + [-pad_x, pad_y_bot],       # Kiri Bawah
        pt_hip_L + [pad_x, pad_y_bot]         # Kanan Bawah
    ])

    # Tentukan 4 Titik Sumber dari Gambar Baju (Source Points)
    h_cloth, w_cloth = clothing_img.shape[:2]
    src_pts = np.float32([
        [0, 0],             # Kiri Atas
        [w_cloth, 0],       # Kanan Atas
        [0, h_cloth],       # Kiri Bawah
        [w_cloth, h_cloth]  # Kanan Bawah
    ])

    # Hitung Matrix Perspektif & Lakukan Warping
    try:
        matrix = cv2.getPerspectiveTransform(src_pts, dst_pts)
        warped_cloth = cv2.warpPerspective(clothing_img, matrix, (w_frame, h_frame))
        return warped_cloth
    except Exception as e:
        print(f"Warp Error: {e}")
        return None

def overlay_final(background, overlay):
    """
    Menempelkan hasil warping (overlay) ke background.
    """
    if overlay is None: return background

    # Ambil channel alpha
    alpha_mask = overlay[:, :, 3] / 255.0
    overlay_rgb = overlay[:, :, :3]
    
    # Operasi blending per channel
    for c in range(0, 3):
        background[:, :, c] = background[:, :, c] * (1.0 - alpha_mask) + overlay_rgb[:, :, c] * alpha_mask
        
    return background

@app.route("/process_ar", methods=["POST"])
def process_ar():
    try:
        # 1. Baca File
        file = request.files['image'].read()
        baju_theme = request.form.get('baju')
        gender_mode = request.form.get('gender_mode', 'pria')

        nparr = np.frombuffer(file, np.uint8)
        frame = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        
        # 2. Deteksi Pose
        frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        results = pose.process(frame_rgb)

        if results.pose_landmarks:
            # 3. Load Aset Baju
            clothing_img = load_clothing_image(baju_theme, gender_mode)
            
            if clothing_img is not None:
                # 4. PROSES WARPING (Melengkungkan baju)
                warped_cloth = warp_clothing_to_body(frame, clothing_img, results.pose_landmarks.landmark)
                
                # 5. TEMPEL HASIL
                if warped_cloth is not None:
                    frame = overlay_final(frame, warped_cloth)
            else:
                print(f"Baju tidak ditemukan: {baju_theme}")

        # 6. Encode Hasil
        _, buffer = cv2.imencode(".jpg", frame)
        img_str = base64.b64encode(buffer).decode("utf-8")
        
        return jsonify({"status": "success", "image": img_str})

    except Exception as e:
        print(f"Critical Error: {e}")
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, threaded=True)