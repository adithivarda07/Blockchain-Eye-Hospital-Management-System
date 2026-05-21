import mysql.connector
import time
from hashing import generate_hash
from blockchain import store_hash

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="eye_hospital",
    port=3307   # 🔥 add this line
)

cursor = conn.cursor(dictionary=True)

BATCH_SIZE = 10   # you can change (5 if slow, 20 if fast)

print("🚀 Starting batch blockchain push...")

while True:
    cursor.execute(f"""
        SELECT * FROM patients
        WHERE hash IS NULL
        LIMIT {BATCH_SIZE}
    """)

    patients = cursor.fetchall()

    if not patients:
        print("🎉 All patients synced!")
        break

    for p in patients:
        try:
            patient_id = p['patient_id']

            data = str(p['name']) + str(p['age']) + str(p['disease']) + str(p['treatment'])

            hash_value = generate_hash(data)

            store_hash(patient_id, hash_value)

            cursor.execute(
                "UPDATE patients SET hash=%s WHERE patient_id=%s",
                (hash_value, patient_id)
            )
            conn.commit()

            print(f"✅ Patient {patient_id} stored")

            time.sleep(0.5)  # 🔥 prevents Ganache from freezing

        except Exception as e:
            print(f"❌ Error for {patient_id}: {e}")