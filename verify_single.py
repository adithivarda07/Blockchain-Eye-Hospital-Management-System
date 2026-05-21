import sys
import mysql.connector
from hashing import generate_hash
from blockchain import get_all_records

patient_id = sys.argv[1]

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="eye_hospital",
    port=3307
)

cursor = conn.cursor(dictionary=True)

cursor.execute("SELECT * FROM patients WHERE patient_id=%s", (patient_id,))
p = cursor.fetchone()

if not p:
    print("❌ Patient not found")
    exit()

data = str(p['name']) + str(p['age']) + str(p['disease']) + str(p['treatment'])
new_hash = generate_hash(data)

blockchain_data = get_all_records()
bc_hash = blockchain_data.get(str(patient_id))

if bc_hash == new_hash:
    print("✅ Data is VALID (No Tampering)")
else:
    print("🚨 Data TAMPERED!")