import pandas as pd
import mysql.connector
import os

# ================== DATABASE CONNECTION ==================
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="eye_hospital",
    port=3307
)

# ================== LOAD DATA ==================
df = pd.read_sql("SELECT * FROM patients", conn)

print("✅ Connected successfully")
print(df.head())


# ================== ANALYTICS START ==================

# 1. Disease Distribution
print("\n--- Disease Distribution ---")
print(df['disease'].value_counts())


# 2. Cure Rate
print("\n--- Cure Rate (%) ---")
cure_rate = df.groupby('disease')['status'].apply(
    lambda x: (x == 'Recovered').sum() / len(x) * 100
)
print(cure_rate)


# 3. Revenue Analysis
print("\n--- Revenue Trend ---")

df['treatment_cost'] = pd.to_numeric(df['treatment_cost'], errors='coerce')
df['followup'] = pd.to_datetime(df['followup'], errors='coerce')

revenue = df.groupby('followup')['treatment_cost'].sum()
print(revenue)


# 4. Treatment Effectiveness
print("\n--- Treatment Effectiveness ---")
print(df.groupby(['treatment','status']).size())


# 5. Basic Insights
print("\n--- Basic Insights ---")
print("Total Patients:", len(df))
print("Average Treatment Cost:", df['treatment_cost'].mean())


# ================== EXPORT CSV ==================

file_path = os.path.abspath("patients.csv")
df.to_csv(file_path, index=False)

print("\n✅ CSV created successfully!")
print("📁 Location:", file_path)

print("\n🚀 STEP 1 COMPLETED — Ready for Power BI")