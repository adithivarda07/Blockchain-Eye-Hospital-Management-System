from web3 import Web3

# CONNECT GANACHE
w3 = Web3(Web3.HTTPProvider("http://127.0.0.1:7545"))

if not w3.is_connected():
    print("Blockchain not connected")
    exit()

print("Blockchain connected")

# CONTRACT ADDRESS
contract_address = w3.to_checksum_address("0xbc1e326Eb63D06eF2C5f1aA65ea6E7C364bA88c3")

# ABI
abi = [
    {
        "inputs": [
            {"internalType": "string","name": "_patientId","type": "string"},
            {"internalType": "string","name": "_dataHash","type": "string"}
        ],
        "name": "addRecord",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "inputs": [{"internalType": "uint256","name": "index","type": "uint256"}],
        "name": "getRecord",
        "outputs": [
            {"internalType": "string","name": "","type": "string"},
            {"internalType": "string","name": "","type": "string"},
            {"internalType": "uint256","name": "","type": "uint256"}
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [],
        "name": "getTotalRecords",
        "outputs": [{"internalType": "uint256","name": "","type": "uint256"}],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [{"internalType": "uint256","name": "","type": "uint256"}],
        "name": "records",
        "outputs": [
            {"internalType": "string","name": "patientId","type": "string"},
            {"internalType": "string","name": "dataHash","type": "string"},
            {"internalType": "uint256","name": "timestamp","type": "uint256"}
        ],
        "stateMutability": "view",
        "type": "function"
    }
]

contract = w3.eth.contract(address=contract_address, abi=abi)
account = w3.eth.accounts[0]

def store_hash(patient_id, hash_value):
    tx = contract.functions.addRecord(
        str(patient_id), hash_value
    ).transact({'from': account})

    w3.eth.wait_for_transaction_receipt(tx)
    print("Stored on blockchain")

def get_all_records():
    total = contract.functions.getTotalRecords().call()
    data = {}

    for i in range(total):
        record = contract.functions.getRecord(i).call()
        patient_id = record[0]
        hash_value = record[1]
        data[patient_id] = hash_value

    return data