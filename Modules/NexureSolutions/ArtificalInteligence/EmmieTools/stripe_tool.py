from langchain.agents import Tool
import requests

BASE_URL = "https://us-east-1.nexure-cloud-compute-130-12-30-4.nexuresolutions.com/Modules/Stripe/Payments/Backend/index.php"

def call_php_backend(action: str, payload: dict):
    try:
        payload["action"] = action
        response = requests.post(BASE_URL, json=payload)
        response.raise_for_status()
        return response.json()
    except Exception as e:
        return {"error": f"Error calling Stripe PHP backend: {e}"}

def process_payment(customer_id: str, amount: float, payment_method_id: str, currency: str = "usd"):
    return call_php_backend("processPayment", {
        "customer_id": customer_id,
        "amount": int(amount * 100),
        "payment_method_id": payment_method_id,
        "currency": currency
    })

def get_credit_balance(customer_id: str):
    return call_php_backend("getCreditBalance", {"customer_id": customer_id})

def create_subscription(customer_id: str, price_id: str):
    return call_php_backend("createSubscription", {"customer_id": customer_id, "price_id": price_id})

def add_customer(name: str, email: str, phone: str, account_number: str):
    return call_php_backend("addCustomer", {
        "name": name,
        "email": email,
        "phone": phone,
        "account_number": account_number
    })

TOOLS = [
    Tool(name="process_payment", func=process_payment, description="Charge a customer via Stripe PHP backend"),
    Tool(name="get_credit_balance", func=get_credit_balance, description="Get a customer's credit balance"),
    Tool(name="create_subscription", func=create_subscription, description="Create a subscription"),
    Tool(name="add_customer", func=add_customer, description="Add a new customer via Stripe PHP backend"),
]