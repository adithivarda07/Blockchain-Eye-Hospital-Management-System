import mysql.connector
from blockchain import get_all_records
from hashing import generate_hash

# 🔗 Connect DB
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="eye_hospital",
    port=3307
)

cursor = conn.cursor(dictionary=True)

# 🔗 Get blockchain data
blockchain_data = get_all_records()

# 📊 Get DB data
cursor.execute("SELECT * FROM patients")
patients = cursor.fetchall()

tampered = []

for p in patients:
    patient_id = str(p['patient_id'])

    # recreate hash (same logic as before)
    data = str(p['name']) + str(p['age']) + str(p['disease']) + str(p['treatment'])
    new_hash = generate_hash(data)

    bc_hash = blockchain_data.get(patient_id)

    if bc_hash != new_hash:
        tampered.append({
            "id": patient_id,
            "name": p['name']
        })

# 🔥 RESULT
# 🔥 RESULT
if len(tampered) == 0:
    print("No Tampering")
else:
    print("Tampering Detected")
    for t in tampered:
        print(f"{t['id']}::{t['name']}")