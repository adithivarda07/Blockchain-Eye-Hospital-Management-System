  
import sys
import mysql.connector
from hashing import generate_hash
from blockchain import store_hash

print(" Script started")

# 🔥 SAFETY CHECK (important)
if len(sys.argv) < 3:
    print("❌ Missing arguments")
    exit()

patient_id = sys.argv[1]
data = sys.argv[2]

# ✅ CONNECT DB (your port is correct)
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="eye_hospital",
    port=3307
)

cursor = conn.cursor()

# 🔥 GENERATE HASH
hash_value = generate_hash(data)

# 🔥 STORE IN BLOCKCHAIN
store_hash(patient_id, hash_value)

# 🔥 UPDATE DB
cursor.execute(
    "UPDATE patients SET hash=%s WHERE patient_id=%s",
    (hash_value, patient_id)
)

conn.commit()

print("Blockchain + DB updated")