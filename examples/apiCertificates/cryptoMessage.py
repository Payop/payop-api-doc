# pip3 install import libnacl
import libnacl
import base64

message = '[{"method": 7, "amount": 0.2, "currency": "USD", "type": "1", "additionalData": {"walletNumber": "41001560683733", "direction": "Test payout"}},{"method": 7, "amount": 0.2, "currency": "USD", "type": "1", "additionalData": {"walletNumber": "51001560683733", "direction": "Test payout 2"}}]'
publicKeyAkaCertificate = 'TDlp2n8REswWij2WywDSzxF494psFKHsYdVKU6kvwBI='

box = libnacl.crypto_box_seal(message.encode(), base64.b64decode(publicKeyAkaCertificate))
print(base64.b64encode(box))
